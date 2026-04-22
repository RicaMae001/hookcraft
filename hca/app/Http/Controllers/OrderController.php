<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Helpers\NotificationHelper;
use App\Mail\OrderCancelledMail;

class OrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    // ============================================
    // EMAIL HELPER
    // ============================================

    /**
     * Resolve customer email from user_id or order.email fallback,
     * then send the cancellation email. Wrapped in try/catch so a
     * mail failure never breaks the cancellation flow.
     */
    private function sendCancelledEmail($order, string $reason, string $cancelledBy): void
    {
        try {
            $toEmail = null;

            if (!empty($order->user_id)) {
                $user    = DB::table('users')->where('id', $order->user_id)->first();
                $toEmail = $user->email ?? null;
            }

            if (!$toEmail && !empty($order->email)) {
                $toEmail = $order->email;
            }

            if (!$toEmail) {
                Log::warning('Order cancelled email skipped — no email found', [
                    'order_id' => $order->id,
                ]);
                return;
            }

            Mail::to($toEmail)->send(new OrderCancelledMail($order, $reason, $cancelledBy));

            Log::info('Order cancelled email sent', [
                'order_id'     => $order->id,
                'to'           => $toEmail,
                'cancelled_by' => $cancelledBy,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send order cancelled email', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    // ============================================
    // CUSTOMER: Cancel Order
    // ============================================

    public function cancel(Request $request, $id)
    {
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        // Allow cancel ONLY if still pending
        if ($order->delivery_status !== 'Pending') {
            return back()->with(
                'error',
                'Order can only be cancelled while it is still pending.'
            );
        }

        DB::beginTransaction();

        try {
            DB::table('orders')->where('id', $id)->update([
                'delivery_status' => 'Cancelled',
                'payment_status'  => 'Unsuccessful',
            ]);

            // Restore stock only if already paid
            if ($order->payment_status === 'Paid') {
                $this->restoreStockFromOrder($id);
            }

            // In-app notifications to all parties
            NotificationHelper::orderCancelled(
                $id,
                $order->customer_name,
                'Cancelled by customer',
                auth()->id(),
                $order->coordinator_id
            );

            DB::commit();

            // Send cancellation email to customer
            $this->sendCancelledEmail($order, 'Cancelled by customer', 'customer');

            Log::info('Order cancelled by customer', [
                'order_id'       => $id,
                'user_id'        => auth()->id(),
                'coordinator_id' => $order->coordinator_id,
                'customer_name'  => $order->customer_name,
            ]);

            return back()->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error cancelling order', [
                'order_id' => $id,
                'user_id'  => auth()->id(),
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Failed to cancel order. Please try again.');
        }
    }

    // ============================================
    // ADMIN: Update Order Status
    // ============================================

    public function updateStatus(Request $request, $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }

        $oldPaymentStatus  = $order->payment_status;
        $oldDeliveryStatus = $order->delivery_status;

        DB::beginTransaction();

        try {
            $updateData = [];

            if ($request->filled('payment_status')) {
                $updateData['payment_status'] = $request->payment_status;
            }

            if ($request->filled('delivery_status')) {
                $updateData['delivery_status'] = $request->delivery_status;
            }

            if (!empty($updateData)) {
                DB::table('orders')->where('id', $id)->update($updateData);
            }

            $newPaymentStatus  = $request->payment_status  ?? $oldPaymentStatus;
            $newDeliveryStatus = $request->delivery_status ?? $oldDeliveryStatus;

            // Stock handling
            if ($oldPaymentStatus !== 'Paid' && $newPaymentStatus === 'Paid') {
                $this->deductStockFromOrder($id);
            }

            if ($oldPaymentStatus === 'Paid' && $newPaymentStatus !== 'Paid') {
                $this->restoreStockFromOrder($id);
            }

            // Handle cancellation by admin
            if ($newDeliveryStatus === 'Cancelled' && $oldDeliveryStatus !== 'Cancelled') {

                if ($order->payment_status === 'Paid') {
                    $this->restoreStockFromOrder($id);
                }

                $reason = $request->reason ?? 'Cancelled by admin';

                // In-app notifications
                NotificationHelper::orderCancelled(
                    $id,
                    $order->customer_name,
                    $reason,
                    $order->user_id,
                    $order->coordinator_id
                );

                // Send cancellation email to customer
                $this->sendCancelledEmail($order, $reason, 'admin');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating order', [
                'order_id' => $id,
                'error'    => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order',
            ], 500);
        }
    }

    // ============================================
    // STOCK HELPERS
    // ============================================

    private function deductStockFromOrder($orderId)
    {
        $items = DB::table('order_item')->where('order_id', $orderId)->get();

        foreach ($items as $item) {
            if ($item->is_customization) continue;

            DB::table('products')
                ->where('id', $item->product_id)
                ->decrement('stock', $item->quantity);
        }
    }

    private function restoreStockFromOrder($orderId)
    {
        $items = DB::table('order_item')->where('order_id', $orderId)->get();

        foreach ($items as $item) {
            if ($item->is_customization) continue;

            DB::table('products')
                ->where('id', $item->product_id)
                ->increment('stock', $item->quantity);
        }
    }
}