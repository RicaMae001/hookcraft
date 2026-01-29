<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;

class OrderController extends Controller
{
    protected $notificationService;

    // Only allow authenticated users
    public function __construct(NotificationService $notificationService)
    {
        $this->middleware('auth');
        $this->notificationService = $notificationService;
    }

    // Cancel order (for customer)
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

        DB::table('orders')->where('id', $id)->update([
            'delivery_status' => 'Cancelled'
        ]);

        // Optionally restore stock if paid
        if ($order->payment_status === 'Paid') {
            $orderItems = DB::table('order_item')->where('order_id', $id)->get();
            foreach ($orderItems as $item) {
                DB::table('products')->where('id', $item->product_id)->increment('stock', $item->quantity);
            }
        }

        return back()->with('success', 'Order has been cancelled.');
    }

    /**
     * Update order status (for admin/staff)
     * This method should be called when admin updates order details
     */
    public function updateStatus(Request $request, $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found'], 404);
        }

        $oldPaymentStatus = $order->payment_status;
        $oldDeliveryStatus = $order->delivery_status;

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

            // Send notification to the customer if order belongs to a user
            if ($order->user_id) {
                $this->notifyUserOfOrderUpdate(
                    $order,
                    $oldPaymentStatus,
                    $oldDeliveryStatus,
                    $request->payment_status ?? $oldPaymentStatus,
                    $request->delivery_status ?? $oldDeliveryStatus
                );
            }
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully']);
    }

    /**
     * Update delivery status (for delivery coordinator)
     */
    public function updateDeliveryStatus(Request $request, $id)
    {
        $order = DB::table('orders')->where('id', $id)->first();

        if (!$order) {
            return back()->with('error', 'Order not found');
        }

        $oldStatus = $order->delivery_status;
        $newStatus = $request->delivery_status;

        DB::table('orders')->where('id', $id)->update([
            'delivery_status' => $newStatus
        ]);

        // Send notification to the customer if order belongs to a user
        if ($order->user_id) {
            $this->notifyUserOfDeliveryUpdate($order, $oldStatus, $newStatus);
        }

        return back()->with('success', 'Delivery status updated successfully');
    }

    /**
     * Notify user when their order is updated by admin
     */
    private function notifyUserOfOrderUpdate($order, $oldPaymentStatus, $oldDeliveryStatus, $newPaymentStatus, $newDeliveryStatus)
    {
        try {
            $changes = [];
            
            if ($oldPaymentStatus !== $newPaymentStatus) {
                $changes[] = "Payment: {$oldPaymentStatus} → {$newPaymentStatus}";
            }
            
            if ($oldDeliveryStatus !== $newDeliveryStatus) {
                $changes[] = "Delivery: {$oldDeliveryStatus} → {$newDeliveryStatus}";
            }

            if (empty($changes)) {
                return; // No changes to notify
            }

            $changesText = implode(', ', $changes);
            
            // Determine notification title and priority based on status
            $title = 'Order Update';
            $priority = 'normal';
            
            if ($newDeliveryStatus === 'Out for Delivery') {
                $title = 'Order is Out for Delivery! 🚚';
                $priority = 'high';
            } elseif ($newDeliveryStatus === 'Delivered') {
                $title = 'Order Delivered Successfully! ✓';
                $priority = 'high';
            } elseif ($newDeliveryStatus === 'Cancelled') {
                $title = 'Order Cancelled';
                $priority = 'high';
            } elseif ($newPaymentStatus === 'Paid') {
                $title = 'Payment Confirmed ✓';
                $priority = 'high';
            }

            $message = "Your order #{$order->id} has been updated: {$changesText}";

            $metadata = json_encode([
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'old_payment_status' => $oldPaymentStatus,
                'new_payment_status' => $newPaymentStatus,
                'old_delivery_status' => $oldDeliveryStatus,
                'new_delivery_status' => $newDeliveryStatus,
                'updated_by' => session('admin_id') ? 'admin' : 'system'
            ]);

            // Create notification for the user
            DB::table('notifications')->insert([
                'recipient_type' => 'user',
                'recipient_id' => $order->user_id,
                'sender_type' => 'admin',
                'sender_id' => session('admin_id'),
                'type' => 'order_updated',
                'title' => $title,
                'message' => $message,
                'entity_type' => 'order',
                'entity_id' => $order->id,
                'action_url' => '/profile/track-order',
                'is_read' => 0,
                'priority' => $priority,
                'metadata' => $metadata,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info('User notified of order update', [
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'changes' => $changesText
            ]);

        } catch (\Exception $e) {
            Log::error('Error notifying user of order update', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Notify user when delivery status is updated by delivery coordinator
     */
    private function notifyUserOfDeliveryUpdate($order, $oldStatus, $newStatus)
    {
        try {
            if ($oldStatus === $newStatus) {
                return; // No change
            }

            // Determine notification details based on status
            $title = 'Delivery Status Update';
            $message = "Your order #{$order->id} delivery status: {$oldStatus} → {$newStatus}";
            $priority = 'normal';

            if ($newStatus === 'Out for Delivery') {
                $title = 'Order is Out for Delivery! 🚚';
                $message = "Great news! Your order #{$order->id} is now out for delivery and will arrive soon.";
                $priority = 'high';
            } elseif ($newStatus === 'Delivered') {
                $title = 'Order Delivered Successfully! ✓';
                $message = "Your order #{$order->id} has been delivered. Thank you for your purchase!";
                $priority = 'high';
            } elseif ($newStatus === 'Cancelled') {
                $title = 'Delivery Cancelled';
                $message = "The delivery for order #{$order->id} has been cancelled.";
                $priority = 'high';
            }

            $metadata = json_encode([
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'coordinator_id' => session('coordinator_id'),
                'coordinator_name' => session('coordinator_name', 'delivery')
            ]);

            // Create notification for the user
            DB::table('notifications')->insert([
                'recipient_type' => 'user',
                'recipient_id' => $order->user_id,
                'sender_type' => 'delivery',
                'sender_id' => session('coordinator_id'),
                'type' => 'delivery_status_changed',
                'title' => $title,
                'message' => $message,
                'entity_type' => 'order',
                'entity_id' => $order->id,
                'action_url' => '/profile/track-order',
                'is_read' => 0,
                'priority' => $priority,
                'metadata' => $metadata,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            Log::info('User notified of delivery update', [
                'user_id' => $order->user_id,
                'order_id' => $order->id,
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
}