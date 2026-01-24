<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get current user's recipient type and ID
     */
    private function getCurrentRecipient()
    {
        if (session('admin_id')) {
            return ['type' => 'admin', 'id' => session('admin_id')];
        } elseif (session('coordinator_id')) {
            return ['type' => 'delivery', 'id' => session('coordinator_id')];
        } elseif (session('user_id')) {
            return ['type' => 'user', 'id' => session('user_id')];
        }
        
        return null;
    }

    /**
     * Get all notifications for current user (API)
     */
    public function index(Request $request)
    {
        $recipient = $this->getCurrentRecipient();
        
        if (!$recipient) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $unreadOnly = $request->get('unread_only', false);
        $limit = $request->get('limit', 50);

        $notifications = $this->notificationService->getNotifications(
            $recipient['type'],
            $recipient['id'],
            $limit,
            $unreadOnly
        );

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $this->notificationService->getUnreadCount($recipient['type'], $recipient['id'])
        ]);
    }

    /**
     * Get unread count (API)
     */
    public function unreadCount()
    {
        $recipient = $this->getCurrentRecipient();
        
        if (!$recipient) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $count = $this->notificationService->getUnreadCount($recipient['type'], $recipient['id']);

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Mark notification as read (API)
     */
    public function markAsRead($id)
    {
        $recipient = $this->getCurrentRecipient();
        
        if (!$recipient) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $success = $this->notificationService->markAsRead($id);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ], 404);
    }

    /**
     * Mark all notifications as read (API)
     */
    public function markAllAsRead()
    {
        $recipient = $this->getCurrentRecipient();
        
        if (!$recipient) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $count = $this->notificationService->markAllAsRead($recipient['type'], $recipient['id']);

        return response()->json([
            'success' => true,
            'message' => "$count notifications marked as read",
            'count' => $count
        ]);
    }

    /**
     * Delete notification (API)
     */
    public function destroy($id)
    {
        $recipient = $this->getCurrentRecipient();
        
        if (!$recipient) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $success = $this->notificationService->delete($id);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Notification deleted'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Notification not found'
        ], 404);
    }

    /**
     * Show all notifications page for admin
     */
    public function adminNotifications()
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login');
        }

        $notifications = $this->notificationService->getNotifications('admin', session('admin_id'), 100);
        $unreadCount = $this->notificationService->getUnreadCount('admin', session('admin_id'));

        return view('admin.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * Show all notifications page for delivery
     */
    public function deliveryNotifications()
    {
        if (!session('coordinator_id')) {
            return redirect()->route('staff.login');
        }

        $notifications = $this->notificationService->getNotifications('delivery', session('coordinator_id'), 100);
        $unreadCount = $this->notificationService->getUnreadCount('delivery', session('coordinator_id'));

        return view('admin.delivery.notifications', compact('notifications', 'unreadCount'));
    }
}