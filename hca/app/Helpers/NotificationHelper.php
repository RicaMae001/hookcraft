<?php

namespace App\Helpers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class NotificationHelper
{
    private static $service;

    /**
     * Get or create notification service instance
     */
    private static function getService()
    {
        if (!self::$service) {
            self::$service = app(NotificationService::class);
        }
        return self::$service;
    }

    /**
     * When a new order is created
     * Notifies admin and optionally the customer
     */
    public static function orderCreated($orderId, $customerName, $total, $userId = null)
    {
        try {
            // Notify admins
            self::getService()->notifyOrderCreated($orderId, $customerName, $total);
            
            // Also notify the customer if userId is provided
            if ($userId) {
                self::getService()->create([
                    'recipient_type' => 'user',
                    'recipient_id' => $userId,
                    'type' => 'order_created',
                    'title' => 'Order Confirmed',
                    'message' => 'Your order has been received and is being processed. Thank you for your purchase!',
                    'entity_type' => 'order',
                    'entity_id' => $orderId,
                    'action_url' => "/user/orders/{$orderId}",
                    'priority' => 'normal',
                    'metadata' => [
                        'order_id' => $orderId,
                        'customer_name' => $customerName,
                        'total' => $total,
                        'action' => 'order_created'
                    ]
                ]);
                
                Log::info('Order created notification sent', [
                    'order_id' => $orderId,
                    'user_id' => $userId
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order created notification', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * When payment proof is uploaded
     * Notifies admin to review the payment
     */
    public static function paymentProofUploaded($orderId, $orderNumber, $amount)
    {
        try {
            self::getService()->create([
                'recipient_type' => 'admin',
                'type' => 'payment_proof_uploaded',
                'title' => 'Payment Proof Uploaded',
                'message' => "Customer uploaded payment proof for order {$orderNumber} (₱" . number_format($amount, 2) . ")",
                'entity_type' => 'order',
                'entity_id' => $orderId,
                'action_url' => "/admin/orders/{$orderId}",
                'priority' => 'high',
                'metadata' => [
                    'order_id' => $orderId,
                    'order_number' => $orderNumber,
                    'amount' => $amount,
                    'action' => 'payment_proof_uploaded'
                ]
            ]);
            
            Log::info('Payment proof uploaded notification sent', [
                'order_id' => $orderId,
                'order_number' => $orderNumber
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment proof notification', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * When payment is verified
     * Notifies both admin and customer
     */
    public static function paymentVerified($orderId, $orderNumber, $amount, $userId)
    {
        try {
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
                'priority' => 'high',
                'metadata' => [
                    'order_id' => $orderId,
                    'order_number' => $orderNumber,
                    'amount' => $amount,
                    'action' => 'payment_verified'
                ]
            ]);
            
            Log::info('Payment verified notification sent', [
                'order_id' => $orderId,
                'user_id' => $userId
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment verified notification', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * When delivery status changes
     * Notifies admin and customer
     */
    public static function deliveryStatusChanged($orderId, $orderNumber, $oldStatus, $newStatus, $userId = null)
    {
        try {
            self::getService()->notifyDeliveryStatusChanged($orderId, $orderNumber, $newStatus, $userId);
            
            Log::info('Delivery status changed notification sent', [
                'order_id' => $orderId,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'user_id' => $userId
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send delivery status notification', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * When order is assigned to delivery coordinator
     * Notifies the delivery coordinator only
     */
    public static function deliveryAssigned($orderId, $coordinatorId, $coordinatorName, $customerName)
    {
        try {
            self::getService()->deliveryAssigned($orderId, $coordinatorId, $coordinatorName, $customerName);
            
            Log::info('Delivery assignment notification sent', [
                'order_id' => $orderId,
                'coordinator_id' => $coordinatorId,
                'coordinator_name' => $coordinatorName
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send delivery assignment notification', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Check product stock and notify if low or out of stock
     * This can be called after product updates or order placements
     */
    public static function checkProductStock($productId, $productName, $currentStock, $lowStockThreshold = 5)
    {
        try {
            if ($currentStock <= $lowStockThreshold && $currentStock > 0) {
                // Low stock warning
                self::getService()->notifyLowStock($productId, $productName, $currentStock);
                
                Log::info('Low stock notification sent', [
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'current_stock' => $currentStock
                ]);
            } elseif ($currentStock == 0) {
                // Out of stock alert
                self::getService()->create([
                    'recipient_type' => 'admin',
                    'type' => 'product_out_of_stock',
                    'title' => 'Product Out of Stock',
                    'message' => "{$productName} is now out of stock! Please restock immediately.",
                    'entity_type' => 'product',
                    'entity_id' => $productId,
                    'action_url' => "/admin/products/{$productId}/edit",
                    'priority' => 'urgent',
                    'metadata' => [
                        'product_id' => $productId,
                        'product_name' => $productName,
                        'current_stock' => $currentStock,
                        'action' => 'out_of_stock'
                    ]
                ]);
                
                Log::info('Out of stock notification sent', [
                    'product_id' => $productId,
                    'product_name' => $productName
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send stock notification', [
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Send a generic notification
     * Useful for custom scenarios
     */
    public static function send($recipientType, $recipientId, $type, $title, $message, $options = [])
    {
        try {
            $data = [
                'recipient_type' => $recipientType,
                'recipient_id' => $recipientId,
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'entity_type' => $options['entity_type'] ?? null,
                'entity_id' => $options['entity_id'] ?? null,
                'action_url' => $options['action_url'] ?? null,
                'priority' => $options['priority'] ?? 'normal',
                'metadata' => $options['metadata'] ?? null,
            ];

            self::getService()->create($data);
            
            Log::info('Generic notification sent', [
                'recipient_type' => $recipientType,
                'recipient_id' => $recipientId,
                'type' => $type,
                'title' => $title
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send generic notification', [
                'title' => $title,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * When order is updated
     * Notifies admin about changes
     */
    public static function orderUpdated($orderId, $customerName, $oldPaymentStatus, $newPaymentStatus, $updatedBy)
    {
        try {
            self::getService()->orderUpdated($orderId, $customerName, $oldPaymentStatus, $newPaymentStatus, $updatedBy);
            
            Log::info('Order updated notification sent', [
                'order_id' => $orderId,
                'old_status' => $oldPaymentStatus,
                'new_status' => $newPaymentStatus,
                'updated_by' => $updatedBy
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order updated notification', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * When order is cancelled
     * ENHANCED: Notifies ALL parties: admin, delivery coordinator (if assigned), and customer
     * 
     * @param int $orderId The order ID
     * @param string $customerName Customer's name
     * @param string|null $reason Reason for cancellation
     * @param int|null $userId Customer user ID (if registered user)
     * @param int|null $coordinatorId Delivery coordinator ID (if assigned)
     */
    public static function orderCancelled($orderId, $customerName, $reason = null, $userId = null, $coordinatorId = null)
    {
        try {
            // If coordinator ID is not provided, try to get it from the order
            if ($coordinatorId === null) {
                $order = DB::table('orders')->where('id', $orderId)->first();
                if ($order && $order->delivery_coordinator_id) {
                    $coordinatorId = $order->delivery_coordinator_id;
                }
            }

            // Use the enhanced service method that notifies all parties
            self::getService()->orderCancelled($orderId, $customerName, $reason, $userId, $coordinatorId);
            
            Log::info('Order cancelled notification sent to all parties', [
                'order_id' => $orderId,
                'user_id' => $userId,
                'coordinator_id' => $coordinatorId,
                'reason' => $reason
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order cancelled notification', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * When product is created
     * Notifies admin about new product
     * Price parameter is optional
     */
    public static function productCreated($productId, $productName, $adminName, $price = null)
    {
        try {
            self::getService()->productCreated($productId, $productName, $adminName, $price);
            
            Log::info('Product created notification sent', [
                'product_id' => $productId,
                'product_name' => $productName,
                'price' => $price
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send product created notification', [
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * When product is updated
     * Notifies admin about product changes
     */
    public static function productUpdated($productId, $productName, $updatedBy)
    {
        try {
            self::getService()->productUpdated($productId, $productName, $updatedBy);
            
            Log::info('Product updated notification sent', [
                'product_id' => $productId,
                'product_name' => $productName,
                'updated_by' => $updatedBy
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send product updated notification', [
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Batch notification sending
     * Send the same notification to multiple users
     */
    public static function notifyMultipleUsers(array $userIds, $type, $title, $message, $entityType = null, $entityId = null, $actionUrl = null, $priority = 'normal')
    {
        try {
            $successCount = 0;
            $failCount = 0;

            foreach ($userIds as $userId) {
                try {
                    self::getService()->create([
                        'recipient_type' => 'user',
                        'recipient_id' => $userId,
                        'type' => $type,
                        'title' => $title,
                        'message' => $message,
                        'entity_type' => $entityType,
                        'entity_id' => $entityId,
                        'action_url' => $actionUrl,
                        'priority' => $priority,
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $failCount++;
                    Log::error('Failed to send batch notification to user', [
                        'user_id' => $userId,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Batch notifications sent', [
                'success' => $successCount,
                'failed' => $failCount,
                'total' => count($userIds)
            ]);

            return [
                'success' => $successCount,
                'failed' => $failCount,
                'total' => count($userIds)
            ];
        } catch (\Exception $e) {
            Log::error('Failed to send batch notifications', [
                'error' => $e->getMessage()
            ]);
            return [
                'success' => 0,
                'failed' => count($userIds),
                'total' => count($userIds)
            ];
        }
    }

    /**
     * Broadcast notification to all users
     * Sends to all users without specifying recipient_id
     */
    public static function broadcastToAllUsers($type, $title, $message, $priority = 'normal')
    {
        try {
            self::getService()->create([
                'recipient_type' => 'user',
                'recipient_id' => null, // Broadcast to all users
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'priority' => $priority,
                'metadata' => [
                    'broadcast' => true,
                    'timestamp' => now()->toDateTimeString()
                ]
            ]);

            Log::info('Broadcast notification sent to all users', [
                'type' => $type,
                'title' => $title,
                'priority' => $priority
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send broadcast notification', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Broadcast notification to all admins
     * Sends to all admin users
     */
    public static function broadcastToAllAdmins($type, $title, $message, $priority = 'normal')
    {
        try {
            self::getService()->create([
                'recipient_type' => 'admin',
                'recipient_id' => null, // Broadcast to all admins
                'type' => $type,
                'title' => $title,
                'message' => $message,
                'priority' => $priority,
                'metadata' => [
                    'broadcast' => true,
                    'timestamp' => now()->toDateTimeString()
                ]
            ]);

            Log::info('Broadcast notification sent to all admins', [
                'type' => $type,
                'title' => $title,
                'priority' => $priority
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send admin broadcast notification', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}