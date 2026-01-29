<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a new notification
     */
    public function create(array $data)
    {
        try {
            // Set defaults
            $defaults = [
                'sender_type' => 'system',
                'sender_id' => null,
                'entity_type' => null,
                'entity_id' => null,
                'action_url' => null,
                'priority' => 'normal',
                'metadata' => null,
                'is_read' => false,
            ];

            $notificationData = array_merge($defaults, $data);

            // Convert metadata to JSON if it's an array
            if (isset($notificationData['metadata']) && is_array($notificationData['metadata'])) {
                $notificationData['metadata'] = json_encode($notificationData['metadata']);
            }

            $id = DB::table('notifications')->insertGetId($notificationData);

            Log::info('Notification created', [
                'id' => $id,
                'recipient_type' => $notificationData['recipient_type'],
                'recipient_id' => $notificationData['recipient_id'] ?? 'all',
                'type' => $notificationData['type']
            ]);

            return $id;
        } catch (\Exception $e) {
            Log::error('Error creating notification: ' . $e->getMessage(), [
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Get notifications for a recipient with improved query
     */
    public function getNotifications($recipientType, $recipientId = null, $limit = 50, $unreadOnly = false)
    {
        try {
            $query = DB::table('notifications')
                ->where('recipient_type', $recipientType);

            // Handle both specific recipient and broadcast notifications
            if ($recipientId !== null) {
                $query->where(function($q) use ($recipientId) {
                    $q->where('recipient_id', $recipientId)
                      ->orWhereNull('recipient_id'); // Include broadcast notifications
                });
            } else {
                $query->whereNull('recipient_id'); // Only broadcast notifications
            }

            if ($unreadOnly) {
                $query->where('is_read', false);
            }

            $notifications = $query
                ->orderBy('priority', 'desc') // Urgent first
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            // Convert to array and format
            return $notifications->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'recipient_type' => $notification->recipient_type,
                    'recipient_id' => $notification->recipient_id,
                    'sender_type' => $notification->sender_type,
                    'sender_id' => $notification->sender_id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'entity_type' => $notification->entity_type,
                    'entity_id' => $notification->entity_id,
                    'action_url' => $notification->action_url,
                    'is_read' => (bool) $notification->is_read,
                    'read_at' => $notification->read_at,
                    'priority' => $notification->priority,
                    'metadata' => $notification->metadata ? json_decode($notification->metadata, true) : null,
                    'created_at' => $notification->created_at,
                    'updated_at' => $notification->updated_at,
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('Error fetching notifications: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get unread count with improved query
     */
    public function getUnreadCount($recipientType, $recipientId = null)
    {
        try {
            $query = DB::table('notifications')
                ->where('recipient_type', $recipientType)
                ->where('is_read', false);

            if ($recipientId !== null) {
                $query->where(function($q) use ($recipientId) {
                    $q->where('recipient_id', $recipientId)
                      ->orWhereNull('recipient_id');
                });
            } else {
                $query->whereNull('recipient_id');
            }

            return $query->count();
        } catch (\Exception $e) {
            Log::error('Error getting unread count: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        try {
            $updated = DB::table('notifications')
                ->where('id', $id)
                ->update([
                    'is_read' => true,
                    'read_at' => now(),
                    'updated_at' => now()
                ]);

            return $updated > 0;
        } catch (\Exception $e) {
            Log::error('Error marking notification as read: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead($recipientType, $recipientId = null)
    {
        try {
            $query = DB::table('notifications')
                ->where('recipient_type', $recipientType)
                ->where('is_read', false);

            if ($recipientId !== null) {
                $query->where(function($q) use ($recipientId) {
                    $q->where('recipient_id', $recipientId)
                      ->orWhereNull('recipient_id');
                });
            } else {
                $query->whereNull('recipient_id');
            }

            return $query->update([
                'is_read' => true,
                'read_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Error marking all as read: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Delete notification
     */
    public function delete($id)
    {
        try {
            return DB::table('notifications')->where('id', $id)->delete() > 0;
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete old read notifications (cleanup)
     */
    public function deleteOldNotifications($days = 30)
    {
        try {
            return DB::table('notifications')
                ->where('is_read', true)
                ->where('created_at', '<', now()->subDays($days))
                ->delete();
        } catch (\Exception $e) {
            Log::error('Error deleting old notifications: ' . $e->getMessage());
            return 0;
        }
    }

    // ==================== PRESET NOTIFICATION METHODS ====================

    /**
     * Notify when order is created
     */
    public function notifyOrderCreated($orderId, $customerName, $total)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'order_created',
            'title' => 'New Order Received',
            'message' => "New order from {$customerName} (₱" . number_format($total, 2) . ")",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'high',
            'metadata' => [
                'order_id' => $orderId,
                'customer_name' => $customerName,
                'total' => $total
            ]
        ]);
    }

    /**
     * Notify when payment is received
     */
    public function notifyPaymentReceived($orderId, $orderNumber, $amount)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'payment_received',
            'title' => 'Payment Received',
            'message' => "Payment verified for order {$orderNumber} (₱" . number_format($amount, 2) . ")",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'high',
            'metadata' => [
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'amount' => $amount
            ]
        ]);
    }

    /**
     * Notify when delivery status changes
     */
    public function notifyDeliveryStatusChanged($orderId, $orderNumber, $newStatus, $userId = null)
    {
        // Notify admin
        $this->create([
            'recipient_type' => 'admin',
            'type' => 'delivery_status_changed',
            'title' => 'Delivery Status Updated',
            'message' => "Order {$orderNumber} status changed to: {$newStatus}",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'normal',
        ]);

        // Notify customer if userId is provided
        if ($userId) {
            $statusMessages = [
                'Pending' => 'Your order is being prepared.',
                'Out for Delivery' => 'Your order is out for delivery!',
                'Delivered' => 'Your order has been delivered. Thank you for your purchase!',
                'Cancelled' => 'Your order delivery has been cancelled.',
            ];

            $this->create([
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'type' => 'delivery_status_changed',
                'title' => 'Order Status Update',
                'message' => $statusMessages[$newStatus] ?? "Your order status has been updated to: {$newStatus}",
                'entity_type' => 'order',
                'entity_id' => $orderId,
                'action_url' => "/user/orders/{$orderId}",
                'priority' => 'normal',
            ]);
        }
    }

    /**
     * Notify when delivery is assigned
     */
    public function deliveryAssigned($orderId, $coordinatorId, $coordinatorName, $customerName)
    {
        return $this->create([
            'recipient_type' => 'delivery',
            'recipient_id' => $coordinatorId,
            'type' => 'delivery_assigned',
            'title' => 'New Delivery Assignment',
            'message' => "Order for {$customerName} has been assigned to you for delivery.",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/delivery/deliveries",
            'priority' => 'high',
            'metadata' => [
                'order_id' => $orderId,
                'coordinator_id' => $coordinatorId,
                'coordinator_name' => $coordinatorName,
                'customer_name' => $customerName
            ]
        ]);
    }

    /**
     * Notify when product stock is low
     */
    public function notifyLowStock($productId, $productName, $currentStock)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'product_low_stock',
            'title' => 'Low Stock Alert',
            'message' => "{$productName} is running low on stock (Only {$currentStock} left)",
            'entity_type' => 'product',
            'entity_id' => $productId,
            'action_url' => "/admin/products/{$productId}/edit",
            'priority' => 'high',
        ]);
    }

    /**
     * Notify when order is updated
     */
    public function orderUpdated($orderId, $customerName, $oldPaymentStatus, $newPaymentStatus, $updatedBy)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'order_updated',
            'title' => 'Order Updated',
            'message' => "Order for {$customerName} updated by {$updatedBy}. Payment status: {$oldPaymentStatus} → {$newPaymentStatus}",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'normal',
            'metadata' => [
                'order_id' => $orderId,
                'customer_name' => $customerName,
                'old_payment_status' => $oldPaymentStatus,
                'new_payment_status' => $newPaymentStatus,
                'updated_by' => $updatedBy
            ]
        ]);
    }

    /**
     * Notify when order is cancelled
     */
    public function orderCancelled($orderId, $customerName, $reason = null, $userId = null)
    {
        // Notify admin
        $this->create([
            'recipient_type' => 'admin',
            'type' => 'order_cancelled',
            'title' => 'Order Cancelled',
            'message' => "Order for {$customerName} has been cancelled." . ($reason ? " Reason: {$reason}" : ""),
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'normal',
        ]);

        // Notify customer if userId provided
        if ($userId) {
            $this->create([
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'type' => 'order_cancelled',
                'title' => 'Order Cancelled',
                'message' => "Your order has been cancelled." . ($reason ? " Reason: {$reason}" : ""),
                'entity_type' => 'order',
                'entity_id' => $orderId,
                'action_url' => "/user/orders/{$orderId}",
                'priority' => 'normal',
            ]);
        }
    }

    /**
     * Notify when product is created
     */
    public function productCreated($productId, $productName, $price, $adminName)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'product_created',
            'title' => 'New Product Added',
            'message' => "{$adminName} added a new product: {$productName} (₱" . number_format($price, 2) . ")",
            'entity_type' => 'product',
            'entity_id' => $productId,
            'action_url' => "/admin/products/{$productId}",
            'priority' => 'normal',
        ]);
    }

    /**
     * Notify when product is updated
     */
    public function productUpdated($productId, $productName, $updatedBy)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'product_updated',
            'title' => 'Product Updated',
            'message' => "{$updatedBy} updated the product: {$productName}",
            'entity_type' => 'product',
            'entity_id' => $productId,
            'action_url' => "/admin/products/{$productId}",
            'priority' => 'normal',
        ]);
    }
}