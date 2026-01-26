<?php

namespace App\Helpers;

use App\Services\NotificationService;

class NotificationHelper
{
    private static $service;

    private static function getService()
    {
        if (!self::$service) {
            self::$service = app(NotificationService::class);
        }
        return self::$service;
    }

    /**
     * Example: When a new order is created
     * Call this in your order creation logic
     */
    public static function orderCreated($orderId, $customerName, $total, $userId = null)
    {
        self::getService()->notifyOrderCreated($orderId, $customerName, $total);
        
        // Also notify the customer if userId is provided
        if ($userId) {
            self::getService()->create([
                'recipient_type' => 'user',
                'recipient_id' => $userId,
                'type' => 'order_created',
                'title' => 'Order Confirmed',
                'message' => 'Your order has been received and is being processed.',
                'entity_type' => 'order',
                'entity_id' => $orderId,
                'action_url' => "/user/orders/{$orderId}",
                'priority' => 'normal',
            ]);
        }
    }

    /**
     * Example: When payment proof is uploaded
     */
    public static function paymentProofUploaded($orderId, $orderNumber, $amount)
    {
        self::getService()->create([
            'recipient_type' => 'admin',
            'type' => 'payment_proof_uploaded',
            'title' => 'Payment Proof Uploaded',
            'message' => "Customer uploaded payment proof for order {$orderNumber} (₱" . number_format($amount, 2) . ")",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'high',
        ]);
    }

    /**
     * Example: When payment is verified
     */
    public static function paymentVerified($orderId, $orderNumber, $amount, $userId)
    {
        // Notify admins
        self::getService()->notifyPaymentReceived($orderId, $orderNumber, $amount);
        
        // Notify customer
        self::getService()->create([
            'recipient_type' => 'user',
            'recipient_id' => $userId,
            'type' => 'payment_received',
            'title' => 'Payment Confirmed',
            'message' => 'Your payment has been verified. Your order will be processed shortly.',
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/user/orders/{$orderId}",
            'priority' => 'normal',
        ]);
    }

    /**
     * Example: When delivery status changes
     */
    public static function deliveryStatusChanged($orderId, $orderNumber, $oldStatus, $newStatus, $userId = null)
    {
        self::getService()->notifyDeliveryStatusChanged($orderId, $orderNumber, $newStatus, $userId);
    }

    /**
     * Example: When order is assigned to delivery coordinator
     */
    public static function deliveryAssigned($orderId, $coordinatorId, $coordinatorName, $customerName)
    {
        self::getService()->deliveryAssigned($orderId, $coordinatorId, $coordinatorName, $customerName);
    }

    /**
     * Example: When product stock is low
     */
    public static function checkProductStock($productId, $productName, $currentStock, $lowStockThreshold = 5)
    {
        if ($currentStock <= $lowStockThreshold && $currentStock > 0) {
            self::getService()->notifyLowStock($productId, $productName, $currentStock);
        } elseif ($currentStock == 0) {
            self::getService()->create([
                'recipient_type' => 'admin',
                'type' => 'product_out_of_stock',
                'title' => 'Product Out of Stock',
                'message' => "{$productName} is now out of stock!",
                'entity_type' => 'product',
                'entity_id' => $productId,
                'action_url' => "/admin/products/{$productId}/edit",
                'priority' => 'urgent',
            ]);
        }
    }

    /**
     * Example: When a new chat message is received
     */
    public static function newChatMessage($sessionId, $senderName, $message, $recipientType, $recipientId = null)
    {
        $messagePreview = strlen($message) > 50 ? substr($message, 0, 50) . '...' : $message;
        
        self::getService()->create([
            'recipient_type' => $recipientType,
            'recipient_id' => $recipientId,
            'type' => 'chat_message',
            'title' => 'New Message from ' . $senderName,
            'message' => $messagePreview,
            'entity_type' => 'chat_session',
            'entity_id' => $sessionId,
            'action_url' => $recipientType === 'admin' ? "/admin/chat/{$sessionId}" : "/chat",
            'priority' => 'normal',
        ]);
    }

    /**
     * Example: System alert notification
     */
    public static function systemAlert($title, $message, $recipientType = 'admin', $recipientId = null, $priority = 'normal')
    {
        self::getService()->create([
            'recipient_type' => $recipientType,
            'recipient_id' => $recipientId,
            'type' => 'system_alert',
            'title' => $title,
            'message' => $message,
            'priority' => $priority,
        ]);
    }

    /**
     * Example: When order is updated
     */
    public static function orderUpdated($orderId, $customerName, $oldPaymentStatus, $newPaymentStatus, $updatedBy)
    {
        self::getService()->orderUpdated($orderId, $customerName, $oldPaymentStatus, $newPaymentStatus, $updatedBy);
    }

    /**
     * Example: When order is cancelled
     */
    public static function orderCancelled($orderId, $customerName, $reason = null, $userId = null)
    {
        self::getService()->orderCancelled($orderId, $customerName, $reason, $userId);
    }

    /**
     * Example: When product is created
     */
    public static function productCreated($productId, $productName, $price, $adminName)
    {
        self::getService()->productCreated($productId, $productName, $price, $adminName);
    }

    /**
     * Example: When product is updated
     */
    public static function productUpdated($productId, $productName, $updatedBy)
    {
        self::getService()->productUpdated($productId, $productName, $updatedBy);
    }
}