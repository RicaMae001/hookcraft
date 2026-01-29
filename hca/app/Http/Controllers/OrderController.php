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

    // Only allow authenticated users
    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    /**
     * Cancel order (for customer)
     * Enhanced to notify ALL parties properly
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

        // Only allow cancel if status is exactly "Pending"
        if ($order->delivery_status !== 'Pending') {
            return back()->with('error', 'Order can only be cancelled while it is still being processed.');
        }

        DB::beginTransaction();

        try {
            // Update order status
            DB::table('orders')->where('id', $id)->update([
                'delivery_status' => 'Cancelled'
            ]);

            // Restore stock if paid
            if ($order->payment_status === 'Paid') {
                $orderItems = DB::table('order_item')->where('order_id', $id)->get();
                foreach ($orderItems as $item) {
                    // Only restore stock for non-customization items
                    if (!$item->is_customization) {
                        DB::table('products')->where('id', $item->product_id)->increment('stock', $item->quantity);
                    }
                }
            }

            // ===== NOTIFY ALL PARTIES =====
            // Pass coordinator ID to ensure delivery coordinator is notified
            NotificationHelper::orderCancelled(
                $id, 
                $order->customer_name, 
                'Cancelled by customer', 
                auth()->id(),  // User ID
                $order->delivery_coordinator_id  // Coordinator ID (if assigned)
            );

            DB::commit();

            Log::info('Order cancelled by customer', [
                'order_id' => $id,
                'user_id' => auth()->id(),
                'coordinator_id' => $order->delivery_coordinator_id
            ]);

            return back()->with('success', 'Order has been cancelled. All relevant parties have been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error cancelling order', [
                'order_id' => $id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to cancel order. Please try again.');
        }
    }

    /**
     * Update order status (for admin/staff)
     * Enhanced with better notification handling
     */
    public function updateStatus(Request $request, $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $oldPaymentStatus = $order->payment_status;
        $oldDeliveryStatus = $order->delivery_status;

        DB::beginTransaction();

        try {
            // Update order
            $updateData = [];
            
            if ($request->has('payment_status')) {
                $updateData['payment_status'] = $request->payment_status;
            }
            
            if ($request->has('delivery_status')) {
                $updateData['delivery_status'] = $request->delivery_status;
            }

            if (!empty($updateData)) {
                DB::table('orders')->where('id', $id)->update($updateData);
                
                $newPaymentStatus = $request->payment_status ?? $oldPaymentStatus;
                $newDeliveryStatus = $request->delivery_status ?? $oldDeliveryStatus;

                // Handle stock management for payment status changes
                if ($oldPaymentStatus !== 'Paid' && $newPaymentStatus === 'Paid') {
                    // Deduct stock when payment is confirmed
                    $this->deductStockFromOrder($id);
                } elseif ($oldPaymentStatus === 'Paid' && $newPaymentStatus !== 'Paid') {
                    // Restore stock when payment is reversed
                    $this->restoreStockFromOrder($id);
                }

                // ===== HANDLE CANCELLATION BY ADMIN =====
                if ($newDeliveryStatus === 'Cancelled' && $oldDeliveryStatus !== 'Cancelled') {
                    $reason = $request->reason ?? 'Order cancelled by admin';
                    
                    // Restore stock if order was paid
                    if ($newPaymentStatus === 'Paid') {
                        $this->restoreStockFromOrder($id);
                    }
                    
                    // Notify ALL parties about cancellation
                    NotificationHelper::orderCancelled(
                        $id, 
                        $order->customer_name, 
                        $reason, 
                        $order->user_id,  // Customer user ID
                        $order->delivery_coordinator_id  // Coordinator ID (if assigned)
                    );
                    
                } else {
                    // ===== HANDLE OTHER STATUS CHANGES =====
                    // Send notification to the customer if order belongs to a user
                    if ($order->user_id) {
                        $this->notifyUserOfOrderUpdate(
                            $order,
                            $oldPaymentStatus,
                            $oldDeliveryStatus,
                            $newPaymentStatus,
                            $newDeliveryStatus
                        );
                    }
                }
            }

            DB::commit();

            Log::info('Order status updated', [
                'order_id' => $id,
                'old_payment' => $oldPaymentStatus,
                'new_payment' => $newPaymentStatus,
                'old_delivery' => $oldDeliveryStatus,
                'new_delivery' => $newDeliveryStatus,
                'admin_id' => session('admin_id')
            ]);

            return response()->json(['success' => true, 'message' => 'Order updated successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating order status', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json(['success' => false, 'message' => 'Failed to update order: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Update delivery status (for delivery coordinator)
     * Enhanced with better notification handling
     */
    public function updateDeliveryStatus(Request $request, $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        $oldStatus = $order->delivery_status;
        $newStatus = $request->delivery_status;

        DB::beginTransaction();

        try {
            DB::table('orders')->where('id', $id)->update([
                'delivery_status' => $newStatus
            ]);

            // ===== HANDLE CANCELLATION BY DELIVERY COORDINATOR =====
            if ($newStatus === 'Cancelled' && $oldStatus !== 'Cancelled') {
                $reason = $request->reason ?? 'Delivery cancelled by coordinator';
                
                // Restore stock if order was paid
                if ($order->payment_status === 'Paid') {
                    $this->restoreStockFromOrder($id);
                }
                
                // Notify ALL parties about cancellation
                NotificationHelper::orderCancelled(
                    $id, 
                    $order->customer_name, 
                    $reason, 
                    $order->user_id,  // Customer user ID
                    session('coordinator_id')  // Current coordinator ID
                );
                
            } else {
                // ===== HANDLE OTHER DELIVERY STATUS CHANGES =====
                // Send notification to the customer if order belongs to a user
                if ($order->user_id) {
                    $this->notifyUserOfDeliveryUpdate($order, $oldStatus, $newStatus);
                }
            }

            DB::commit();

            Log::info('Delivery status updated', [
                'order_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'coordinator_id' => session('coordinator_id')
            ]);

            return back()->with('success', 'Delivery status updated successfully. All relevant parties have been notified.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error updating delivery status', [
                'order_id' => $id,
                'error' => $e->getMessage()
            ]);

            return back()->with('error', 'Failed to update delivery status.');
        }
    }

    /**
     * Notify user when their order is updated by admin
     * Updated to use NotificationHelper
     */
    private function notifyUserOfOrderUpdate($order, $oldPaymentStatus, $oldDeliveryStatus, $newPaymentStatus, $newDeliveryStatus)
    {
        try {
            // Use NotificationHelper for status changes
            if ($oldDeliveryStatus !== $newDeliveryStatus) {
                NotificationHelper::deliveryStatusChanged(
                    $order->id,
                    "#{$order->id}",
                    $oldDeliveryStatus,
                    $newDeliveryStatus,
                    $order->user_id
                );
            }

            // Additional notification for payment status change
            if ($oldPaymentStatus !== $newPaymentStatus && $newPaymentStatus === 'Paid') {
                NotificationHelper::paymentVerified(
                    $order->id,
                    "#{$order->id}",
                    $order->total,
                    $order->user_id
                );
            }

            // If there are significant changes that need custom messaging
            if ($oldPaymentStatus !== $newPaymentStatus || $oldDeliveryStatus !== $newDeliveryStatus) {
                $changes = [];
                
                if ($oldPaymentStatus !== $newPaymentStatus) {
                    $changes[] = "Payment: {$oldPaymentStatus} → {$newPaymentStatus}";
                }
                
                if ($oldDeliveryStatus !== $newDeliveryStatus) {
                    $changes[] = "Delivery: {$oldDeliveryStatus} → {$newDeliveryStatus}";
                }

                Log::info('Order status updated', [
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'changes' => implode(', ', $changes)
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Error notifying user of order update', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Notify user when delivery status is updated by delivery coordinator
     * Updated to use NotificationHelper
     */
    private function notifyUserOfDeliveryUpdate($order, $oldStatus, $newStatus)
    {
        try {
            // Use NotificationHelper for delivery status changes
            NotificationHelper::deliveryStatusChanged(
                $order->id,
                "#{$order->id}",
                $oldStatus,
                $newStatus,
                $order->user_id
            );

            Log::info('Delivery status change notified', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

        } catch (\Exception $e) {
            Log::error('Error notifying user of delivery update', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Deduct stock from products when payment is confirmed
     */
    private function deductStockFromOrder($orderId)
    {
        try {
            $orderItems = DB::table('order_item')->where('order_id', $orderId)->get();

            foreach ($orderItems as $item) {
                // Skip customization items as they don't affect stock
                if ($item->is_customization) {
                    continue;
                }
                
                DB::table('products')
                    ->where('id', $item->product_id)
                    ->decrement('stock', $item->quantity);

                // Check stock levels after deduction
                $product = DB::table('products')->where('id', $item->product_id)->first();
                if ($product) {
                    // Send low stock notification if applicable
                    NotificationHelper::checkProductStock(
                        $product->id,
                        $product->name,
                        $product->stock
                    );
                }
            }

            Log::info('Stock deducted for paid order', ['order_id' => $orderId]);

        } catch (\Exception $e) {
            Log::error('Error deducting stock', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Restore stock when payment is reversed or order is cancelled
     */
    private function restoreStockFromOrder($orderId)
    {
        try {
            $orderItems = DB::table('order_item')->where('order_id', $orderId)->get();

            foreach ($orderItems as $item) {
                // Skip customization items as they don't affect stock
                if ($item->is_customization) {
                    continue;
                }
                
                DB::table('products')
                    ->where('id', $item->product_id)
                    ->increment('stock', $item->quantity);
            }

            Log::info('Stock restored for order', ['order_id' => $orderId]);

        } catch (\Exception $e) {
            Log::error('Error restoring stock', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}