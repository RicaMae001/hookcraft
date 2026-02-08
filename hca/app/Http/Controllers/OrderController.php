<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;
use App\Helpers\NotificationHelper;

class OrderController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * ===============================
     * CUSTOMER: Cancel Order
     * ===============================
     */
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
            // Update order status
            DB::table('orders')->where('id', $id)->update([
                'delivery_status' => 'Cancelled',
                'payment_status'  => 'Unsuccessful',
            ]);

            // Restore stock only if already paid
            if ($order->payment_status === 'Paid') {
                $this->restoreStockFromOrder($id);
            }

            /**
             * ===============================
             * NOTIFICATIONS - FIXED
             * ===============================
             * Send notification to ALL parties:
             * - Admin/Staff (always)
             * - Customer (always) 
             * - Delivery Coordinator (only if assigned)
             */

            NotificationHelper::orderCancelled(
                $id,                      // orderId
                $order->customer_name,    // customerName
                'Cancelled by customer',  // reason
                auth()->id(),             // userId (customer)
                $order->coordinator_id    // coordinatorId (FIXED: was delivery_coordinator_id)
            );

            DB::commit();

            Log::info('Order cancelled by customer', [
                'order_id'       => $id,
                'user_id'        => auth()->id(),
                'coordinator_id' => $order->coordinator_id,
                'customer_name'  => $order->customer_name
            ]);

            return back()->with(
                'success',
                'Order cancelled successfully.'
            );

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error cancelling order', [
                'order_id' => $id,
                'user_id'  => auth()->id(),
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString()
            ]);

            return back()->with(
                'error',
                'Failed to cancel order. Please try again.'
            );
        }
    }

    /**
     * ===============================
     * ADMIN: Update Order Status
     * ===============================
     */
    public function updateStatus(Request $request, $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
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

            $newPaymentStatus  = $request->payment_status ?? $oldPaymentStatus;
            $newDeliveryStatus = $request->delivery_status ?? $oldDeliveryStatus;

            // Stock handling
            if ($oldPaymentStatus !== 'Paid' && $newPaymentStatus === 'Paid') {
                $this->deductStockFromOrder($id);
            }

            if ($oldPaymentStatus === 'Paid' && $newPaymentStatus !== 'Paid') {
                $this->restoreStockFromOrder($id);
            }

            // Handle cancellation
            if ($newDeliveryStatus === 'Cancelled' && $oldDeliveryStatus !== 'Cancelled') {

                if ($order->payment_status === 'Paid') {
                    $this->restoreStockFromOrder($id);
                }

                // Send notifications to all parties (FIXED: coordinator_id)
                NotificationHelper::orderCancelled(
                    $id,
                    $order->customer_name,
                    $request->reason ?? 'Cancelled by admin',
                    $order->user_id,
                    $order->coordinator_id
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating order', [
                'order_id' => $id,
                'error'    => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order'
            ], 500);
        }
    }

    /**
     * ===============================
     * STOCK HELPERS
     * ===============================
     */
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