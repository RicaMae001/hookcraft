<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    private function getCurrentRecipient(Request $request = null)
    {
        // If called from API and referer contains /delivery/, prioritize coordinator session
        if ($request) {
            $referer = $request->header('referer', '');
            if (str_contains($referer, '/delivery/') && session('coordinator_id')) {
                return ['type' => 'delivery', 'id' => session('coordinator_id')];
            }
        }
        
        // Check for admin first
        if (session('admin_id')) {
            return ['type' => 'admin', 'id' => session('admin_id')];
        } 
        // Then check for delivery coordinator
        elseif (session('coordinator_id')) {
            return ['type' => 'delivery', 'id' => session('coordinator_id')];
        } 
        // Finally check for regular user using Auth facade (UPDATED)
        elseif (Auth::check()) {
            return ['type' => 'user', 'id' => Auth::id()];
        }
        
        return null;
    }
    
    /**
     * Get all notifications for current user (API) - UPDATED WITH FILTERING
     */
    public function index(Request $request)
    {
        $recipient = $this->getCurrentRecipient($request);
        
        if (!$recipient) {
            return response()->json([
                'success' => false,
                'error' => 'Not authenticated',
                'notifications' => [],
                'unread_count' => 0
            ], 401);
        }

        $unreadOnly = $request->get('unread_only', false);
        $limit = $request->get('limit', 50);

        try {
            $notifications = $this->notificationService->getNotifications(
                $recipient['type'],
                $recipient['id'],
                $limit,
                $unreadOnly
            );

            // ====== FILTER OUT RIDER NOTIFICATIONS FOR REGULAR USERS ======
            if ($recipient['type'] === 'user') {
                $notifications = collect($notifications)->filter(function ($notification) {
                    // Get notification type and title
                    $type = strtolower($notification['type'] ?? '');
                    $title = strtolower($notification['title'] ?? '');
                    
                    // Exclude delivery assignment notifications
                    $isDeliveryAssignment = 
                        str_contains($type, 'delivery_assignment') ||
                        str_contains($type, 'rider') ||
                        str_contains($title, 'delivery assignment') ||
                        str_contains($title, 'assigned to you for delivery') ||
                        str_contains($title, 'new delivery');
                    
                    // Only include notifications that are NOT delivery assignments
                    return !$isDeliveryAssignment;
                })->values()->all();
            }
            // ============================================================

            // Recalculate unread count after filtering
            $unreadCount = $recipient['type'] === 'user' 
                ? collect($notifications)->where('is_read', false)->count()
                : $this->notificationService->getUnreadCount($recipient['type'], $recipient['id']);

            // Log for debugging
            \Log::info('Notifications fetched', [
                'recipient_type' => $recipient['type'],
                'recipient_id' => $recipient['id'],
                'count' => count($notifications),
                'unread_count' => $unreadCount,
                'referer' => $request->header('referer', 'none'),
                'filtered' => $recipient['type'] === 'user' ? 'yes' : 'no'
            ]);

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'recipient_type' => $recipient['type'],
                'recipient_id' => $recipient['id']
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error fetching notifications',
                'notifications' => [],
                'unread_count' => 0
            ], 500);
        }
    }
    
    /**
     * Get unread count (API) - UPDATED WITH FILTERING
     */
    public function unreadCount(Request $request)
    {
        $recipient = $this->getCurrentRecipient($request);
        
        if (!$recipient) {
            return response()->json([
                'success' => false,
                'count' => 0
            ], 401);
        }

        // For users, get notifications and filter them before counting
        if ($recipient['type'] === 'user') {
            try {
                $notifications = $this->notificationService->getNotifications(
                    $recipient['type'],
                    $recipient['id'],
                    100, // Get more to ensure accurate count
                    true // unread only
                );
                
                // Filter out delivery assignments
                $filteredNotifications = collect($notifications)->filter(function ($notification) {
                    $type = strtolower($notification['type'] ?? '');
                    $title = strtolower($notification['title'] ?? '');
                    
                    $isDeliveryAssignment = 
                        str_contains($type, 'delivery_assignment') ||
                        str_contains($type, 'rider') ||
                        str_contains($title, 'delivery assignment') ||
                        str_contains($title, 'assigned to you for delivery') ||
                        str_contains($title, 'new delivery');
                    
                    return !$isDeliveryAssignment;
                });
                
                $count = $filteredNotifications->count();
            } catch (\Exception $e) {
                \Log::error('Error getting filtered unread count: ' . $e->getMessage());
                $count = 0;
            }
        } else {
            $count = $this->notificationService->getUnreadCount($recipient['type'], $recipient['id']);
        }

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    /**
     * Mark notification as read (API)
     */
    public function markAsRead($id, Request $request)
    {
        $recipient = $this->getCurrentRecipient($request);
        
        if (!$recipient) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 401);
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
     * Mark all notifications as read (API) - UPDATED WITH FILTERING
     */
    public function markAllAsRead(Request $request)
    {
        $recipient = $this->getCurrentRecipient($request);
        
        if (!$recipient) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 401);
        }

        // For users, only mark non-delivery notifications as read
        if ($recipient['type'] === 'user') {
            try {
                $notifications = $this->notificationService->getNotifications(
                    $recipient['type'],
                    $recipient['id'],
                    100,
                    true // unread only
                );
                
                // Filter and mark as read
                $count = 0;
                foreach ($notifications as $notification) {
                    $type = strtolower($notification['type'] ?? '');
                    $title = strtolower($notification['title'] ?? '');
                    
                    $isDeliveryAssignment = 
                        str_contains($type, 'delivery_assignment') ||
                        str_contains($type, 'rider') ||
                        str_contains($title, 'delivery assignment') ||
                        str_contains($title, 'assigned to you for delivery') ||
                        str_contains($title, 'new delivery');
                    
                    // Only mark non-delivery notifications
                    if (!$isDeliveryAssignment) {
                        $this->notificationService->markAsRead($notification['id']);
                        $count++;
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => "{$count} notifications marked as read",
                    'count' => $count
                ]);
            } catch (\Exception $e) {
                \Log::error('Error marking all as read: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => 'Error marking notifications as read'
                ], 500);
            }
        } else {
            $count = $this->notificationService->markAllAsRead($recipient['type'], $recipient['id']);

            return response()->json([
                'success' => true,
                'message' => "{$count} notifications marked as read",
                'count' => $count
            ]);
        }
    }

    /**
     * Delete notification (API)
     */
    public function destroy($id, Request $request)
    {
        $recipient = $this->getCurrentRecipient($request);
        
        if (!$recipient) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 401);
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

    /**
     * Show all notifications page for users
     */
    public function userNotifications()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Please login to view notifications');
        }

        // Just return the view - JavaScript will fetch via API (same as navbar)
        return view('pages.user-notifications');
    }
}