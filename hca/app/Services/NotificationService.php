<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotificationService
{
    /**
     * Create a new notification
     */
    public function create($data)
    {
        return DB::table('notifications')->insertGetId([
            'recipient_type' => $data['recipient_type'],
            'recipient_id' => $data['recipient_id'] ?? null,
            'sender_type' => $data['sender_type'] ?? 'system',
            'sender_id' => $data['sender_id'] ?? null,
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'entity_type' => $data['entity_type'] ?? null,
            'entity_id' => $data['entity_id'] ?? null,
            'action_url' => $data['action_url'] ?? null,
            'priority' => $data['priority'] ?? 'normal',
            'metadata' => isset($data['metadata']) ? json_encode($data['metadata']) : null,
            'is_read' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Get notifications for a specific recipient
     */
    public function getNotifications($recipientType, $recipientId, $limit = 50, $unreadOnly = false)
    {
        $query = DB::table('notifications')
            ->where('recipient_type', $recipientType);
        
        if ($recipientId !== null) {
            $query->where(function($q) use ($recipientId) {
                $q->where('recipient_id', $recipientId)
                  ->orWhereNull('recipient_id'); // Include broadcast notifications
            });
        }
        
        if ($unreadOnly) {
            $query->where('is_read', 0);
        }
        
        $notifications = $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        // Format notifications
        return $notifications->map(function($notification) {
            return $this->formatNotification($notification);
        })->toArray();
    }

    /**
     * Get unread count
     */
    public function getUnreadCount($recipientType, $recipientId)
    {
        $query = DB::table('notifications')
            ->where('recipient_type', $recipientType)
            ->where('is_read', 0);
        
        if ($recipientId !== null) {
            $query->where(function($q) use ($recipientId) {
                $q->where('recipient_id', $recipientId)
                  ->orWhereNull('recipient_id');
            });
        }
        
        return $query->count();
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        return DB::table('notifications')
            ->where('id', $notificationId)
            ->update([
                'is_read' => 1,
                'read_at' => now(),
                'updated_at' => now(),
            ]);
    }

    /**
     * Mark all as read for a recipient
     */
    public function markAllAsRead($recipientType, $recipientId)
    {
        $query = DB::table('notifications')
            ->where('recipient_type', $recipientType)
            ->where('is_read', 0);
        
        if ($recipientId !== null) {
            $query->where(function($q) use ($recipientId) {
                $q->where('recipient_id', $recipientId)
                  ->orWhereNull('recipient_id');
            });
        }
        
        return $query->update([
            'is_read' => 1,
            'read_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Delete a notification
     */
    public function delete($notificationId)
    {
        return DB::table('notifications')
            ->where('id', $notificationId)
            ->delete();
    }

    /**
     * Format notification with additional data
     */
    private function formatNotification($notification)
    {
        // Convert to array for easier manipulation
        $notificationArray = (array) $notification;
        
        $notificationArray['time_ago'] = $this->getTimeAgo($notification->created_at);
        $notificationArray['icon'] = $this->getIcon($notification->type);
        $notificationArray['color_class'] = $this->getColorClass($notification->type);
        
        if ($notification->metadata) {
            $notificationArray['metadata'] = json_decode($notification->metadata, true);
        }
        
        // Convert is_read to boolean
        $notificationArray['is_read'] = (bool) $notification->is_read;
        
        return (object) $notificationArray;
    }

    /**
     * Get icon for notification type
     */
    private function getIcon($type)
    {
        $icons = [
            'order_created' => 'fa-shopping-cart',
            'order_updated' => 'fa-edit',
            'order_cancelled' => 'fa-times-circle',
            'payment_received' => 'fa-credit-card',
            'payment_proof_uploaded' => 'fa-file-upload',
            'delivery_assigned' => 'fa-truck',
            'delivery_status_changed' => 'fa-shipping-fast',
            'product_low_stock' => 'fa-exclamation-triangle',
            'product_out_of_stock' => 'fa-ban',
            'product_created' => 'fa-plus-circle',
            'product_updated' => 'fa-sync',
            'chat_message' => 'fa-comment',
            'system_alert' => 'fa-bell',
        ];
        
        return $icons[$type] ?? 'fa-bell';
    }

    /**
     * Get color class for notification type
     */
    private function getColorClass($type)
    {
        $colors = [
            'order_created' => 'success',
            'order_updated' => 'info',
            'order_cancelled' => 'danger',
            'payment_received' => 'success',
            'payment_proof_uploaded' => 'info',
            'delivery_assigned' => 'primary',
            'delivery_status_changed' => 'info',
            'product_low_stock' => 'warning',
            'product_out_of_stock' => 'danger',
            'product_created' => 'success',
            'product_updated' => 'info',
            'chat_message' => 'primary',
            'system_alert' => 'warning',
        ];
        
        return $colors[$type] ?? 'info';
    }

    /**
     * Get human-readable time ago
     */
    private function getTimeAgo($datetime)
    {
        if (!$datetime) {
            return 'Unknown';
        }

        try {
            $timestamp = strtotime($datetime);
            if ($timestamp === false) {
                return 'Unknown';
            }
            
            $difference = time() - $timestamp;
            
            if ($difference < 60) {
                return 'Just now';
            } elseif ($difference < 3600) {
                $mins = floor($difference / 60);
                return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
            } elseif ($difference < 86400) {
                $hours = floor($difference / 3600);
                return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
            } elseif ($difference < 604800) {
                $days = floor($difference / 86400);
                return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
            } else {
                return date('M j, Y', $timestamp);
            }
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    // Helper methods for creating specific notification types

    public function notifyOrderCreated($orderId, $customerName, $total)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'order_created',
            'title' => 'New Order Received',
            'message' => "{$customerName} placed a new order worth ₱" . number_format($total, 2),
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'high',
            'metadata' => [
                'order_id' => $orderId,
                'customer_name' => $customerName,
                'total' => $total,
            ],
        ]);
    }

    public function notifyPaymentReceived($orderId, $orderNumber, $amount)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'payment_received',
            'title' => 'Payment Received',
            'message' => "Payment of ₱" . number_format($amount, 2) . " received for order {$orderNumber}",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'normal',
        ]);
    }

    public function notifyDeliveryStatusChanged($orderId, $orderNumber, $newStatus, $userId = null)
    {
        // Notify admin/delivery
        $this->create([
            'recipient_type' => 'admin',
            'type' => 'delivery_status_changed',
            'title' => 'Delivery Status Updated',
            'message' => "Order {$orderNumber} status changed to {$newStatus}",
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
                'type' => 'delivery_status_changed',
                'title' => 'Order Status Updated',
                'message' => "Your order {$orderNumber} is now {$newStatus}",
                'entity_type' => 'order',
                'entity_id' => $orderId,
                'action_url' => "/orders/{$orderId}",
                'priority' => 'high',
            ]);
        }
    }

    public function notifyLowStock($productId, $productName, $currentStock)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'product_low_stock',
            'title' => 'Low Stock Alert',
            'message' => "{$productName} is running low on stock ({$currentStock} remaining)",
            'entity_type' => 'product',
            'entity_id' => $productId,
            'action_url' => "/admin/products/{$productId}/edit",
            'priority' => 'high',
        ]);
    }

    public function notifyChatMessage($sessionId, $senderName, $messagePreview)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'chat_message',
            'title' => 'New Chat Message',
            'message' => "{$senderName}: {$messagePreview}",
            'entity_type' => 'chat_session',
            'entity_id' => $sessionId,
            'action_url' => "/admin/chat/{$sessionId}",
            'priority' => 'normal',
        ]);
    }

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
                'updated_by' => $updatedBy,
            ],
        ]);
    }

    public function orderCancelled($orderId, $customerName, $reason = null, $userId = null)
    {
        // Notify admin
        $this->create([
            'recipient_type' => 'admin',
            'type' => 'order_cancelled',
            'title' => 'Order Cancelled',
            'message' => "Order for {$customerName} has been cancelled" . ($reason ? ": {$reason}" : ""),
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'high',
        ]);

        // Notify customer if userId provided
        if ($userId) {
            $this->create([
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'type' => 'order_cancelled',
                'title' => 'Order Cancelled',
                'message' => "Your order has been cancelled" . ($reason ? ": {$reason}" : ""),
                'entity_type' => 'order',
                'entity_id' => $orderId,
                'action_url' => "/profile/purchase-history",
                'priority' => 'high',
            ]);
        }
    }

    public function productCreated($productId, $productName, $price, $adminName)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'product_created',
            'title' => 'New Product Added',
            'message' => "{$adminName} added new product: {$productName} (₱" . number_format($price, 2) . ")",
            'entity_type' => 'product',
            'entity_id' => $productId,
            'action_url' => "/admin/products/{$productId}",
            'priority' => 'normal',
        ]);
    }

    public function productUpdated($productId, $productName, $updatedBy)
    {
        return $this->create([
            'recipient_type' => 'admin',
            'type' => 'product_updated',
            'title' => 'Product Updated',
            'message' => "{$updatedBy} updated product: {$productName}",
            'entity_type' => 'product',
            'entity_id' => $productId,
            'action_url' => "/admin/products/{$productId}",
            'priority' => 'normal',
        ]);
    }

    public function deliveryAssigned($orderId, $coordinatorId, $coordinatorName, $customerName)
    {
        $orderNumber = 'ORD-' . str_pad($orderId, 5, '0', STR_PAD_LEFT);
        
        // Notify the delivery coordinator
        return $this->create([
            'recipient_type' => 'delivery',
            'recipient_id' => $coordinatorId,
            'type' => 'delivery_assigned',
            'title' => 'New Delivery Assignment',
            'message' => "Order {$orderNumber} for {$customerName} has been assigned to you for delivery.",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/delivery/deliveries",
            'priority' => 'high',
            'metadata' => [
                'order_id' => $orderId,
                'order_number' => $orderNumber,
                'customer_name' => $customerName,
                'coordinator_id' => $coordinatorId,
                'coordinator_name' => $coordinatorName,
            ],
        ]);
    }
}