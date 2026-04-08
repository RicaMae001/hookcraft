<?php

namespace App\Helpers;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

    // ==================== ORDER NOTIFICATIONS ====================

    public static function orderCreated($orderId, $customerName, $total, $userId = null, $grandTotal = null)
    {
        try {
            $displayTotal = $grandTotal ?? $total;
            self::getService()->notifyOrderCreated($orderId, $customerName, $displayTotal);

            if ($userId) {
                self::getService()->create([
                    'recipient_type' => 'user',
                    'recipient_id'   => $userId,
                    'type'           => 'order_created',
                    'title'          => 'Order Confirmed ✓',
                    'message'        => "Your order #{$orderId} has been received and is being processed. Total: ₱" . number_format($displayTotal, 2) . ". Thank you for your purchase!",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => "/user/orders/{$orderId}",
                    'priority'       => 'high',
                    'metadata'       => [
                        'order_id'      => $orderId,
                        'customer_name' => $customerName,
                        'subtotal'      => $total,
                        'grand_total'   => $displayTotal,
                        'action'        => 'order_created',
                    ],
                ]);

                Log::info('Order created notification sent', [
                    'order_id'    => $orderId,
                    'user_id'     => $userId,
                    'grand_total' => $displayTotal,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Failed to send order created notification', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    public static function paymentProofUploaded($orderId, $orderNumber, $amount, $userId = null)
    {
        try {
            self::getService()->create([
                'recipient_type' => 'admin',
                'type'           => 'payment_proof_uploaded',
                'title'          => 'Payment Proof Uploaded',
                'message'        => "Customer uploaded payment proof for order {$orderNumber} (₱" . number_format($amount, 2) . ")",
                'entity_type'    => 'order',
                'entity_id'      => $orderId,
                'action_url'     => "/admin/orders/{$orderId}",
                'priority'       => 'high',
                'metadata'       => [
                    'order_id'      => $orderId,
                    'order_number'  => $orderNumber,
                    'amount'        => $amount,
                    'action'        => 'payment_proof_uploaded',
                ],
            ]);

            if ($userId) {
                self::getService()->create([
                    'recipient_type' => 'user',
                    'recipient_id'   => $userId,
                    'type'           => 'payment_proof_received',
                    'title'          => 'Payment Proof Received',
                    'message'        => "We've received your payment proof for order {$orderNumber}. Our team will verify it shortly.",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => "/user/orders/{$orderId}",
                    'priority'       => 'normal',
                    'metadata'       => [
                        'order_id'     => $orderId,
                        'order_number' => $orderNumber,
                        'action'       => 'proof_received_confirmation',
                    ],
                ]);
            }

            Log::info('Payment proof uploaded notification sent', [
                'order_id'     => $orderId,
                'order_number' => $orderNumber,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment proof notification', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    public static function paymentVerified($orderId, $orderNumber, $amount, $userId)
    {
        try {
            self::getService()->notifyPaymentReceived($orderId, $orderNumber, $amount);

            self::getService()->create([
                'recipient_type' => 'user',
                'recipient_id'   => $userId,
                'type'           => 'payment_received',
                'title'          => 'Payment Confirmed ✓',
                'message'        => "Your payment of ₱" . number_format($amount, 2) . " has been verified. Your order {$orderNumber} will be processed shortly.",
                'entity_type'    => 'order',
                'entity_id'      => $orderId,
                'action_url'     => "/user/orders/{$orderId}",
                'priority'       => 'high',
                'metadata'       => [
                    'order_id'     => $orderId,
                    'order_number' => $orderNumber,
                    'amount'       => $amount,
                    'action'       => 'payment_verified',
                ],
            ]);

            Log::info('Payment verified notification sent', [
                'order_id' => $orderId,
                'user_id'  => $userId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send payment verified notification', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    public static function deliveryStatusChanged(
        $orderId,
        $orderNumber,
        $oldStatus,
        $newStatus,
        $userId = null,
        $coordinatorId = null
    ) {
        try {
            if ($userId) {
                self::getService()->create([
                    'recipient_type' => 'user',
                    'recipient_id'   => $userId,
                    'type'           => 'delivery_status_changed',
                    'title'          => 'Delivery Status Updated',
                    'message'        => "Order {$orderNumber}: {$oldStatus} → {$newStatus}",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => "/user/orders/{$orderId}",
                    'priority'       => 'normal',
                ]);
            }

            self::getService()->create([
                'recipient_type' => 'admin',
                'type'           => 'delivery_status_changed',
                'title'          => 'Delivery Status Updated',
                'message'        => "Order {$orderNumber}: {$oldStatus} → {$newStatus}",
                'entity_type'    => 'order',
                'entity_id'      => $orderId,
                'action_url'     => "/admin/orders/{$orderId}",
                'priority'       => 'normal',
            ]);

            if (!empty($coordinatorId) && in_array($newStatus, ['Pending', 'Out for Delivery', 'Delivered', 'Cancelled', 'Failed'])) {
                self::getService()->create([
                    'recipient_type' => 'delivery',
                    'recipient_id'   => $coordinatorId,
                    'type'           => 'delivery_status_changed',
                    'title'          => 'Order Status Updated',
                    'message'        => "Order {$orderNumber} status: {$newStatus}",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => "/delivery/deliveries",
                    'priority'       => 'normal',
                ]);
            }

            Log::info('Delivery status changed notification sent', [
                'order_id'       => $orderId,
                'old_status'     => $oldStatus,
                'new_status'     => $newStatus,
                'user_id'        => $userId,
                'coordinator_id' => $coordinatorId,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to send delivery status notification', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);
        }
    }

    /**
     * When a delivery attempt fails.
     * Coordinator marks the order as Failed after being unable to reach the customer.
     * Notifies admin (broadcast) and customer with a high-priority alert.
     * A retry button will reset the order back to Pending.
     */
    public static function deliveryFailed(
        $orderId,
        $customerName,
        $userId = null,
        $coordinatorId = null,
        $coordinatorName = null
    ) {
        try {
            // ── Admin — broadcast to all admins ────────────────
            self::getService()->create([
                'recipient_type' => 'admin',
                'recipient_id'   => null,
                'sender_type'    => 'delivery',
                'sender_id'      => $coordinatorId,
                'type'           => 'delivery_failed',
                'title'          => "Delivery Failed – Order #{$orderId}",
                'message'        => ($coordinatorName ?? 'A coordinator')
                                  . " could not complete the delivery for Order #{$orderId}"
                                  . " (Customer: {$customerName}). Order has been queued for retry.",
                'entity_type'    => 'order',
                'entity_id'      => $orderId,
                'action_url'     => "/admin/orders/{$orderId}",
                'priority'       => 'high',
                'metadata'       => [
                    'order_id'         => $orderId,
                    'customer_name'    => $customerName,
                    'coordinator_id'   => $coordinatorId,
                    'coordinator_name' => $coordinatorName,
                    'old_status'       => 'Out for Delivery',
                    'new_status'       => 'Failed',
                ],
            ]);

            // ── Customer ───────────────────────────────────────
            if ($userId) {
                self::getService()->create([
                    'recipient_type' => 'user',
                    'recipient_id'   => $userId,
                    'sender_type'    => 'delivery',
                    'sender_id'      => $coordinatorId,
                    'type'           => 'delivery_failed',
                    'title'          => 'Delivery Attempt Failed ❌',
                    'message'        => "We could not complete the delivery for your order #{$orderId}. "
                                      . "Our team will reschedule a new attempt. "
                                      . "Please ensure someone is available to receive the package, "
                                      . "or contact us to update your delivery details.",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => '/profile/track-order',
                    'priority'       => 'high',
                    'metadata'       => [
                        'order_id'      => $orderId,
                        'customer_name' => $customerName,
                        'old_status'    => 'Out for Delivery',
                        'new_status'    => 'Failed',
                    ],
                ]);

                Log::info('Customer notified of delivery failure', [
                    'user_id'  => $userId,
                    'order_id' => $orderId,
                ]);
            }

            Log::info('Delivery failed notifications sent successfully', [
                'order_id'         => $orderId,
                'coordinator_id'   => $coordinatorId,
                'coordinator_name' => $coordinatorName,
                'user_notified'    => !empty($userId),
            ]);

        } catch (\Throwable $e) {
            Log::error('Failed to send delivery-failed notifications', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);
        }
    }

    public static function deliveryAssigned($orderId, $coordinatorId, $coordinatorName, $customerName, $userId = null)
    {
        try {
            self::getService()->deliveryAssigned($orderId, $coordinatorId, $coordinatorName, $customerName);

            self::getService()->create([
                'recipient_type' => 'admin',
                'type'           => 'delivery_assigned',
                'title'          => 'Delivery Assigned',
                'message'        => "Order #{$orderId} for {$customerName} has been assigned to {$coordinatorName}",
                'entity_type'    => 'order',
                'entity_id'      => $orderId,
                'action_url'     => "/admin/orders/{$orderId}",
                'priority'       => 'normal',
                'metadata'       => [
                    'order_id'         => $orderId,
                    'coordinator_id'   => $coordinatorId,
                    'coordinator_name' => $coordinatorName,
                    'customer_name'    => $customerName,
                ],
            ]);

            if ($userId) {
                self::getService()->create([
                    'recipient_type' => 'user',
                    'recipient_id'   => $userId,
                    'type'           => 'delivery_assigned',
                    'title'          => 'Delivery Coordinator Assigned',
                    'message'        => "Your order #{$orderId} has been assigned to a delivery coordinator for faster processing.",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => "/user/orders/{$orderId}",
                    'priority'       => 'normal',
                    'metadata'       => [
                        'order_id'         => $orderId,
                        'coordinator_name' => $coordinatorName,
                    ],
                ]);
            }

            Log::info('Delivery assignment notification sent', [
                'order_id'         => $orderId,
                'coordinator_id'   => $coordinatorId,
                'coordinator_name' => $coordinatorName,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send delivery assignment notification', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    public static function orderCancelled(
        $orderId,
        $customerName,
        $reason = 'Order cancelled',
        $userId = null,
        $coordinatorId = null
    ) {
        try {
            Log::info('Starting order cancellation notification process', [
                'order_id'       => $orderId,
                'customer_name'  => $customerName,
                'reason'         => $reason,
                'user_id'        => $userId,
                'coordinator_id' => $coordinatorId,
            ]);

            if ($coordinatorId === null || $userId === null) {
                $order = DB::table('orders')->where('id', $orderId)->first();
                if ($order) {
                    $userId        = $userId        ?? $order->user_id;
                    $coordinatorId = $coordinatorId ?? $order->coordinator_id;
                }
            }

            // Admin — broadcast
            $adminNotificationId = self::getService()->create([
                'recipient_type' => 'admin',
                'recipient_id'   => null,
                'type'           => 'order_cancelled',
                'title'          => 'Order Cancelled',
                'message'        => "Order #{$orderId} ({$customerName}) was cancelled. Reason: {$reason}",
                'entity_type'    => 'order',
                'entity_id'      => $orderId,
                'action_url'     => "/admin/orders/{$orderId}",
                'priority'       => 'high',
                'metadata'       => [
                    'order_id'      => $orderId,
                    'customer_name' => $customerName,
                    'reason'        => $reason,
                ],
            ]);

            Log::info('Admin notification created for order cancellation', [
                'notification_id' => $adminNotificationId,
                'order_id'        => $orderId,
            ]);

            // Customer
            if ($userId) {
                self::getService()->create([
                    'recipient_type' => 'user',
                    'recipient_id'   => $userId,
                    'type'           => 'order_cancelled',
                    'title'          => 'Order Cancelled',
                    'message'        => "Your order #{$orderId} has been cancelled. Reason: {$reason}",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => "/user/orders/{$orderId}",
                    'priority'       => 'high',
                    'metadata'       => [
                        'order_id' => $orderId,
                        'reason'   => $reason,
                    ],
                ]);
            }

            // Delivery coordinator — only if assigned
            if (!empty($coordinatorId)) {
                self::getService()->create([
                    'recipient_type' => 'delivery',
                    'recipient_id'   => $coordinatorId,
                    'type'           => 'order_cancelled',
                    'title'          => 'Assigned Order Cancelled',
                    'message'        => "Order #{$orderId} ({$customerName}) has been cancelled.",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => "/delivery/deliveries",
                    'priority'       => 'high',
                    'metadata'       => [
                        'order_id' => $orderId,
                        'reason'   => $reason,
                    ],
                ]);
            }

            Log::info('All order cancellation notifications sent successfully', [
                'order_id'             => $orderId,
                'user_notified'        => !empty($userId),
                'coordinator_notified' => !empty($coordinatorId),
            ]);

        } catch (\Throwable $e) {
            Log::error('Order cancelled notification failed', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
                'trace'    => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    public static function orderUpdated($orderId, $customerName, $changes, $userId = null, $coordinatorId = null)
    {
        try {
            $changesText = is_array($changes) ? implode(', ', $changes) : $changes;

            self::getService()->create([
                'recipient_type' => 'admin',
                'type'           => 'order_updated',
                'title'          => 'Order Updated',
                'message'        => "Order #{$orderId} for {$customerName} was updated. Changes: {$changesText}",
                'entity_type'    => 'order',
                'entity_id'      => $orderId,
                'action_url'     => "/admin/orders/{$orderId}",
                'priority'       => 'normal',
                'metadata'       => [
                    'order_id'      => $orderId,
                    'customer_name' => $customerName,
                    'changes'       => $changesText,
                ],
            ]);

            if ($userId) {
                self::getService()->create([
                    'recipient_type' => 'user',
                    'recipient_id'   => $userId,
                    'type'           => 'order_updated',
                    'title'          => 'Order Updated',
                    'message'        => "Your order #{$orderId} has been updated. Changes: {$changesText}",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => "/user/orders/{$orderId}",
                    'priority'       => 'normal',
                    'metadata'       => [
                        'order_id' => $orderId,
                        'changes'  => $changesText,
                    ],
                ]);
            }

            if ($coordinatorId) {
                self::getService()->create([
                    'recipient_type' => 'delivery',
                    'recipient_id'   => $coordinatorId,
                    'type'           => 'order_updated',
                    'title'          => 'Assigned Order Updated',
                    'message'        => "Order #{$orderId} for {$customerName} has been updated. Changes: {$changesText}",
                    'entity_type'    => 'order',
                    'entity_id'      => $orderId,
                    'action_url'     => "/delivery/deliveries",
                    'priority'       => 'normal',
                    'metadata'       => [
                        'order_id'      => $orderId,
                        'customer_name' => $customerName,
                        'changes'       => $changesText,
                    ],
                ]);
            }

            Log::info('Order updated notification sent', [
                'order_id'       => $orderId,
                'user_id'        => $userId,
                'coordinator_id' => $coordinatorId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order updated notification', [
                'order_id' => $orderId,
                'error'    => $e->getMessage(),
            ]);
        }
    }

    // ==================== CUSTOMIZATION NOTIFICATIONS ====================

    public static function customizationCreated($customizationId, $productName, $userName, $userId)
    {
        try {
            self::getService()->create([
                'recipient_type' => 'admin',
                'type'           => 'customization_created',
                'title'          => 'New Customization Request',
                'message'        => "{$userName} submitted a customization request for {$productName}",
                'entity_type'    => 'customization',
                'entity_id'      => $customizationId,
                'action_url'     => "/admin/customizations/{$customizationId}",
                'priority'       => 'high',
                'metadata'       => [
                    'customization_id' => $customizationId,
                    'product_name'     => $productName,
                    'user_name'        => $userName,
                    'user_id'          => $userId,
                ],
            ]);

            self::getService()->create([
                'recipient_type' => 'user',
                'recipient_id'   => $userId,
                'type'           => 'customization_created',
                'title'          => 'Customization Request Received',
                'message'        => "Your customization request for {$productName} has been received. We'll review it and get back to you with pricing soon!",
                'entity_type'    => 'customization',
                'entity_id'      => $customizationId,
                'action_url'     => "/customization/my-customizations",
                'priority'       => 'normal',
                'metadata'       => [
                    'customization_id' => $customizationId,
                    'product_name'     => $productName,
                ],
            ]);

            Log::info('Customization created notification sent', [
                'customization_id' => $customizationId,
                'user_id'          => $userId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send customization created notification', [
                'customization_id' => $customizationId,
                'error'            => $e->getMessage(),
            ]);
        }
    }

    public static function customizationStatusChanged($customizationId, $productName, $oldStatus, $newStatus, $userId, $adminPrice = null, $adminNotes = null)
    {
        try {
            $title    = 'Customization Update';
            $message  = '';
            $priority = 'normal';

            if ($newStatus === 'Approved') {
                $title   = 'Customization Approved ✓';
                $message = "Great news! Your customization for {$productName} has been approved";
                if ($adminPrice) $message .= " at ₱" . number_format($adminPrice, 2);
                $message .= ". You can now proceed to checkout!";
                $priority = 'high';
            } elseif ($newStatus === 'Rejected') {
                $title   = 'Customization Update';
                $message = "Your customization request for {$productName} requires revision";
                if ($adminNotes) $message .= ". Note: {$adminNotes}";
                $priority = 'high';
            } elseif ($newStatus === 'Completed') {
                $title    = 'Customization Completed ✓';
                $message  = "Your customization for {$productName} has been completed and is ready!";
                $priority = 'high';
            }

            self::getService()->create([
                'recipient_type' => 'user',
                'recipient_id'   => $userId,
                'type'           => 'customization_status_changed',
                'title'          => $title,
                'message'        => $message,
                'entity_type'    => 'customization',
                'entity_id'      => $customizationId,
                'action_url'     => "/customization/my-customizations",
                'priority'       => $priority,
                'metadata'       => [
                    'customization_id' => $customizationId,
                    'product_name'     => $productName,
                    'old_status'       => $oldStatus,
                    'new_status'       => $newStatus,
                    'admin_price'      => $adminPrice,
                    'admin_notes'      => $adminNotes,
                ],
            ]);

            self::getService()->create([
                'recipient_type' => 'admin',
                'type'           => 'customization_status_changed',
                'title'          => 'Customization Status Updated',
                'message'        => "Customization #{$customizationId} for {$productName} status changed: {$oldStatus} → {$newStatus}",
                'entity_type'    => 'customization',
                'entity_id'      => $customizationId,
                'action_url'     => "/admin/customizations/{$customizationId}",
                'priority'       => 'normal',
                'metadata'       => [
                    'customization_id' => $customizationId,
                    'product_name'     => $productName,
                    'old_status'       => $oldStatus,
                    'new_status'       => $newStatus,
                ],
            ]);

            Log::info('Customization status changed notification sent', [
                'customization_id' => $customizationId,
                'old_status'       => $oldStatus,
                'new_status'       => $newStatus,
                'user_id'          => $userId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send customization status notification', [
                'customization_id' => $customizationId,
                'error'            => $e->getMessage(),
            ]);
        }
    }

    public static function customizationPriceUpdated($customizationId, $productName, $newPrice, $userId, $adminNotes = null)
    {
        try {
            $message = "The price for your {$productName} customization has been updated to ₱" . number_format($newPrice, 2);
            if ($adminNotes) $message .= ". Note: {$adminNotes}";

            self::getService()->create([
                'recipient_type' => 'user',
                'recipient_id'   => $userId,
                'type'           => 'customization_price_updated',
                'title'          => 'Customization Price Updated',
                'message'        => $message,
                'entity_type'    => 'customization',
                'entity_id'      => $customizationId,
                'action_url'     => "/customization/my-customizations",
                'priority'       => 'high',
                'metadata'       => [
                    'customization_id' => $customizationId,
                    'product_name'     => $productName,
                    'new_price'        => $newPrice,
                    'admin_notes'      => $adminNotes,
                ],
            ]);

            Log::info('Customization price updated notification sent', [
                'customization_id' => $customizationId,
                'new_price'        => $newPrice,
                'user_id'          => $userId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send customization price notification', [
                'customization_id' => $customizationId,
                'error'            => $e->getMessage(),
            ]);
        }
    }

    public static function customizationAddedToCart($customizationId, $productName, $userId)
    {
        try {
            self::getService()->create([
                'recipient_type' => 'user',
                'recipient_id'   => $userId,
                'type'           => 'customization_cart_added',
                'title'          => 'Added to Cart',
                'message'        => "Your {$productName} customization has been added to your cart. Ready to checkout!",
                'entity_type'    => 'customization',
                'entity_id'      => $customizationId,
                'action_url'     => "/cart",
                'priority'       => 'normal',
                'metadata'       => [
                    'customization_id' => $customizationId,
                    'product_name'     => $productName,
                ],
            ]);

            Log::info('Customization added to cart notification sent', [
                'customization_id' => $customizationId,
                'user_id'          => $userId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send customization cart notification', [
                'customization_id' => $customizationId,
                'error'            => $e->getMessage(),
            ]);
        }
    }

    public static function customizationOrdered($customizationId, $orderId, $productName, $userId, $price)
    {
        try {
            self::getService()->create([
                'recipient_type' => 'admin',
                'type'           => 'customization_ordered',
                'title'          => 'Customization Order Placed',
                'message'        => "Customization #{$customizationId} for {$productName} has been ordered (Order #{$orderId})",
                'entity_type'    => 'customization',
                'entity_id'      => $customizationId,
                'action_url'     => "/admin/customizations/{$customizationId}",
                'priority'       => 'high',
                'metadata'       => [
                    'customization_id' => $customizationId,
                    'order_id'         => $orderId,
                    'product_name'     => $productName,
                    'price'            => $price,
                ],
            ]);

            self::getService()->create([
                'recipient_type' => 'user',
                'recipient_id'   => $userId,
                'type'           => 'customization_ordered',
                'title'          => 'Customization Order Placed ✓',
                'message'        => "Your {$productName} customization order has been placed (Order #{$orderId}). We'll start working on it soon!",
                'entity_type'    => 'order',
                'entity_id'      => $orderId,
                'action_url'     => "/user/orders/{$orderId}",
                'priority'       => 'high',
                'metadata'       => [
                    'customization_id' => $customizationId,
                    'order_id'         => $orderId,
                    'product_name'     => $productName,
                    'price'            => $price,
                ],
            ]);

            Log::info('Customization ordered notification sent', [
                'customization_id' => $customizationId,
                'order_id'         => $orderId,
                'user_id'          => $userId,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send customization ordered notification', [
                'customization_id' => $customizationId,
                'error'            => $e->getMessage(),
            ]);
        }
    }

    // ==================== PRODUCT NOTIFICATIONS ====================

    public static function checkProductStock($productId, $productName, $currentStock, $lowStockThreshold = 5)
    {
        try {
            if ($currentStock <= $lowStockThreshold) {
                $priority = $currentStock == 0 ? 'urgent' : 'high';
                $message  = $currentStock == 0
                    ? "{$productName} is out of stock! Please restock immediately."
                    : "{$productName} stock is low ({$currentStock} remaining).";

                self::getService()->create([
                    'recipient_type' => 'admin',
                    'type'           => $currentStock == 0 ? 'product_out_of_stock' : 'low_stock',
                    'title'          => $currentStock == 0 ? 'Product Out of Stock' : 'Low Stock Alert',
                    'message'        => $message,
                    'entity_type'    => 'product',
                    'entity_id'      => $productId,
                    'action_url'     => "/admin/products/{$productId}/edit",
                    'priority'       => $priority,
                    'metadata'       => [
                        'product_id'     => $productId,
                        'product_name'   => $productName,
                        'current_stock'  => $currentStock,
                        'action'         => 'restock_needed',
                    ],
                ]);

                Log::info('Stock notification sent', [
                    'product_id'    => $productId,
                    'product_name'  => $productName,
                    'current_stock' => $currentStock,
                    'threshold'     => $lowStockThreshold,
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('Failed to send stock notification', [
                'product_id' => $productId,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);
        }
    }

    public static function productCreated($productId, $productName, $adminName, $price = null)
    {
        try {
            self::getService()->productCreated($productId, $productName, $adminName, $price);
            Log::info('Product created notification sent', [
                'product_id'   => $productId,
                'product_name' => $productName,
                'price'        => $price,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send product created notification', [
                'product_id' => $productId,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    public static function productUpdated($productId, $productName, $updatedBy)
    {
        try {
            self::getService()->productUpdated($productId, $productName, $updatedBy);
            Log::info('Product updated notification sent', [
                'product_id'   => $productId,
                'product_name' => $productName,
                'updated_by'   => $updatedBy,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send product updated notification', [
                'product_id' => $productId,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    // ==================== UTILITY NOTIFICATIONS ====================

    public static function notifyMultipleUsers(array $userIds, $type, $title, $message, $entityType = null, $entityId = null, $actionUrl = null, $priority = 'normal')
    {
        try {
            $successCount = 0;
            $failCount    = 0;

            foreach ($userIds as $userId) {
                try {
                    self::getService()->create([
                        'recipient_type' => 'user',
                        'recipient_id'   => $userId,
                        'type'           => $type,
                        'title'          => $title,
                        'message'        => $message,
                        'entity_type'    => $entityType,
                        'entity_id'      => $entityId,
                        'action_url'     => $actionUrl,
                        'priority'       => $priority,
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    $failCount++;
                    Log::error('Failed to send batch notification to user', [
                        'user_id' => $userId,
                        'error'   => $e->getMessage(),
                    ]);
                }
            }

            Log::info('Batch notifications sent', [
                'success' => $successCount,
                'failed'  => $failCount,
                'total'   => count($userIds),
            ]);

            return ['success' => $successCount, 'failed' => $failCount, 'total' => count($userIds)];
        } catch (\Exception $e) {
            Log::error('Failed to send batch notifications', ['error' => $e->getMessage()]);
            return ['success' => 0, 'failed' => count($userIds), 'total' => count($userIds)];
        }
    }

    public static function broadcastToAllUsers($type, $title, $message, $priority = 'normal')
    {
        try {
            self::getService()->create([
                'recipient_type' => 'user',
                'recipient_id'   => null,
                'type'           => $type,
                'title'          => $title,
                'message'        => $message,
                'priority'       => $priority,
                'metadata'       => ['broadcast' => true, 'timestamp' => now()->toDateTimeString()],
            ]);
            Log::info('Broadcast notification sent to all users', ['type' => $type, 'title' => $title]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send broadcast notification', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public static function broadcastToAllAdmins($type, $title, $message, $priority = 'normal')
    {
        try {
            self::getService()->create([
                'recipient_type' => 'admin',
                'recipient_id'   => null,
                'type'           => $type,
                'title'          => $title,
                'message'        => $message,
                'priority'       => $priority,
                'metadata'       => ['broadcast' => true, 'timestamp' => now()->toDateTimeString()],
            ]);
            Log::info('Broadcast notification sent to all admins', ['type' => $type, 'title' => $title]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to send admin broadcast notification', ['error' => $e->getMessage()]);
            return false;
        }
    }
}