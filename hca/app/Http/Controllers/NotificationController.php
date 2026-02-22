<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Get current user's recipient type and ID based on REQUEST CONTEXT
     */
    private function getCurrentRecipient(Request $request = null)
    {
        if (!$request) {
            $request = request();
        }

        Log::debug('Getting current recipient', [
            'url' => $request->fullUrl(),
            'route_name' => $request->route() ? $request->route()->getName() : null,
            'path' => $request->path(),
            'session_id' => session()->getId(),
            'has_admin_id' => session()->has('admin_id'),
            'admin_id' => session('admin_id'),
            'has_coordinator_id' => session()->has('coordinator_id'),
            'coordinator_id' => session('coordinator_id'),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
        ]);

        // **CONTEXT-BASED DETECTION** - Check what part of the application is being accessed
        $path = $request->path();
        $referer = $request->header('referer', '');
        
        // ===== USER CONTEXT =====
        // If accessing user routes/pages, ONLY check for user authentication
        $isUserContext = 
            str_starts_with($path, 'api/user/') ||
            str_starts_with($path, 'user/') ||
            str_starts_with($path, 'profile/') ||
            str_contains($path, 'user-notifications') ||
            str_contains($referer, '/profile/') ||
            str_contains($referer, '/user/') ||
            str_contains($referer, '/user-notifications');
        
        if ($isUserContext) {
            if (Auth::check()) {
                $userId = Auth::id();
                Log::debug('User context - Auth user detected', ['user_id' => $userId]);
                return ['type' => 'user', 'id' => $userId];
            }
            
            Log::warning('User context but no authenticated user', [
                'path' => $path,
                'referer' => $referer
            ]);
            return null;
        }
        
        // ===== DELIVERY CONTEXT =====
        // If accessing delivery routes, check for delivery coordinator
        $isDeliveryContext = 
            str_starts_with($path, 'api/delivery/') ||
            str_starts_with($path, 'delivery/') ||
            str_contains($referer, '/delivery/');
        
        if ($isDeliveryContext) {
            if (session()->has('coordinator_id') && session('coordinator_id')) {
                Log::debug('Delivery context - Coordinator detected', ['coordinator_id' => session('coordinator_id')]);
                return ['type' => 'delivery', 'id' => session('coordinator_id')];
            }
            
            Log::warning('Delivery context but no coordinator session');
            return null;
        }
        
        // ===== ADMIN CONTEXT =====
        // If accessing admin routes, check for admin session
        $isAdminContext = 
            str_starts_with($path, 'api/admin/') ||
            str_starts_with($path, 'admin/') ||
            str_starts_with($path, 'staff/') ||
            str_contains($referer, '/admin/') ||
            str_contains($referer, '/staff/');
        
        if ($isAdminContext) {
            if (session()->has('admin_id') && session('admin_id')) {
                Log::debug('Admin context - Admin session detected', ['admin_id' => session('admin_id')]);
                return ['type' => 'admin', 'id' => session('admin_id')];
            }
            
            Log::warning('Admin context but no admin session');
            return null;
        }
        
        // ===== FALLBACK FOR GENERIC NOTIFICATION API =====
        // For generic /api/notifications route, use priority order
        // Priority 1: Check if user is authenticated (most common case)
        if (Auth::check()) {
            $userId = Auth::id();
            Log::debug('Fallback - Auth user detected', ['user_id' => $userId]);
            return ['type' => 'user', 'id' => $userId];
        }
        
        // Priority 2: Check for delivery coordinator session
        if (session()->has('coordinator_id') && session('coordinator_id')) {
            Log::debug('Fallback - Delivery coordinator detected', ['coordinator_id' => session('coordinator_id')]);
            return ['type' => 'delivery', 'id' => session('coordinator_id')];
        }
        
        // Priority 3: Check for admin session
        if (session()->has('admin_id') && session('admin_id')) {
            Log::debug('Fallback - Admin session detected', ['admin_id' => session('admin_id')]);
            return ['type' => 'admin', 'id' => session('admin_id')];
        }
        
        Log::warning('No authenticated user found', [
            'session_id' => session()->getId(),
            'path' => $path
        ]);
        return null;
    }
    
    /**
     * Get notifications for user directly from database
     * This ensures users only see notifications for THEIR orders
     */
    private function getUserNotifications($userId, $limit = 50, $unreadOnly = false)
    {
        try {
            $query = DB::table('notifications')
                ->where('recipient_type', 'user')
                ->where('recipient_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit($limit);
            
            if ($unreadOnly) {
                $query->where('is_read', 0);
            }
            
            $notifications = $query->get();
            
            // Convert to array format
            $result = [];
            foreach ($notifications as $notification) {
                $result[] = [
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
                    'metadata' => $notification->metadata,
                    'created_at' => $notification->created_at,
                    'updated_at' => $notification->updated_at,
                ];
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Error fetching user notifications from DB', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }
    
    /**
     * Get unread count for user directly from database
     */
    private function getUserUnreadCount($userId)
    {
        try {
            return DB::table('notifications')
                ->where('recipient_type', 'user')
                ->where('recipient_id', $userId)
                ->where('is_read', 0)
                ->count();
        } catch (\Exception $e) {
            Log::error('Error counting user unread notifications', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            return 0;
        }
    }
    
    /**
     * Get all notifications for current user (API)
     */
    public function index(Request $request)
    {
        Log::debug('Notification API called', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'path' => $request->path()
        ]);
        
        $recipient = $this->getCurrentRecipient($request);
        
        if (!$recipient) {
            Log::warning('Unauthorized notification access attempt', [
                'ip' => $request->ip(),
                'user_agent' => $request->header('User-Agent')
            ]);
            return response()->json([
                'success' => false,
                'error' => 'Not authenticated',
                'notifications' => [],
                'unread_count' => 0,
                'debug' => [
                    'session_id' => session()->getId(),
                    'has_admin_id' => session()->has('admin_id'),
                    'has_coordinator_id' => session()->has('coordinator_id'),
                    'auth_check' => Auth::check(),
                    'path' => $request->path()
                ]
            ], 401);
        }

        $unreadOnly = filter_var($request->get('unread_only', false), FILTER_VALIDATE_BOOLEAN);
        $limit = (int) $request->get('limit', 50);

        try {
            // ====== USE DATABASE QUERY FOR USERS ======
            if ($recipient['type'] === 'user') {
                $notifications = $this->getUserNotifications($recipient['id'], $limit, $unreadOnly);
                $unreadCount = $this->getUserUnreadCount($recipient['id']);
                
                Log::info('User notifications fetched from database', [
                    'user_id' => $recipient['id'],
                    'count' => count($notifications),
                    'unread_count' => $unreadCount
                ]);
            } else {
                // For admin and delivery, use the service as before
                $notifications = $this->notificationService->getNotifications(
                    $recipient['type'],
                    $recipient['id'],
                    $limit,
                    $unreadOnly
                );
                
                $unreadCount = $this->notificationService->getUnreadCount($recipient['type'], $recipient['id']);
                
                Log::info('Notifications fetched successfully', [
                    'recipient_type' => $recipient['type'],
                    'recipient_id' => $recipient['id'],
                    'count' => count($notifications),
                    'unread_count' => $unreadCount
                ]);
            }

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
                'recipient_type' => $recipient['type'],
                'recipient_id' => $recipient['id']
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching notifications', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error fetching notifications: ' . $e->getMessage(),
                'notifications' => [],
                'unread_count' => 0
            ], 500);
        }
    }
    
    /**
     * Get unread count (API)
     */
    public function unreadCount(Request $request)
    {
        $recipient = $this->getCurrentRecipient($request);
        
        if (!$recipient) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'error' => 'Not authenticated'
            ], 401);
        }

        try {
            // Use database query for users
            if ($recipient['type'] === 'user') {
                $count = $this->getUserUnreadCount($recipient['id']);
            } else {
                $count = $this->notificationService->getUnreadCount($recipient['type'], $recipient['id']);
            }

            Log::debug('Unread count retrieved', [
                'recipient_type' => $recipient['type'],
                'recipient_id' => $recipient['id'],
                'count' => $count
            ]);

            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting unread count', [
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'count' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
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

        try {
            // Verify the notification belongs to the current user
            if ($recipient['type'] === 'user') {
                $notification = DB::table('notifications')
                    ->where('id', $id)
                    ->where('recipient_type', 'user')
                    ->where('recipient_id', $recipient['id'])
                    ->first();
                
                if (!$notification) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Notification not found or unauthorized'
                    ], 404);
                }
                
                DB::table('notifications')
                    ->where('id', $id)
                    ->update([
                        'is_read' => 1,
                        'read_at' => now(),
                        'updated_at' => now()
                    ]);
                
                $success = true;
            } else {
                $success = $this->notificationService->markAsRead($id);
            }

            if ($success) {
                Log::info('Notification marked as read', [
                    'notification_id' => $id,
                    'recipient_type' => $recipient['type'],
                    'recipient_id' => $recipient['id']
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Notification marked as read'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error marking notification as read', [
                'notification_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read (API)
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

        try {
            if ($recipient['type'] === 'user') {
                // Mark all user's unread notifications as read
                $count = DB::table('notifications')
                    ->where('recipient_type', 'user')
                    ->where('recipient_id', $recipient['id'])
                    ->where('is_read', 0)
                    ->update([
                        'is_read' => 1,
                        'read_at' => now(),
                        'updated_at' => now()
                    ]);
                
                Log::info('User notifications marked as read', [
                    'count' => $count,
                    'user_id' => $recipient['id']
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => "{$count} notifications marked as read",
                    'count' => $count
                ]);
            } else {
                $count = $this->notificationService->markAllAsRead($recipient['type'], $recipient['id']);

                Log::info('All notifications marked as read', [
                    'count' => $count,
                    'recipient_type' => $recipient['type'],
                    'recipient_id' => $recipient['id']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => "{$count} notifications marked as read",
                    'count' => $count
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error marking all as read', [
                'error' => $e->getMessage(),
                'recipient_type' => $recipient['type'] ?? 'unknown'
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error marking notifications as read'
            ], 500);
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

        try {
            if ($recipient['type'] === 'user') {
                // Verify the notification belongs to the current user before deleting
                $deleted = DB::table('notifications')
                    ->where('id', $id)
                    ->where('recipient_type', 'user')
                    ->where('recipient_id', $recipient['id'])
                    ->delete();
                
                $success = $deleted > 0;
            } else {
                $success = $this->notificationService->delete($id);
            }

            if ($success) {
                Log::info('Notification deleted', [
                    'notification_id' => $id,
                    'recipient_type' => $recipient['type'],
                    'recipient_id' => $recipient['id']
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Notification deleted'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting notification', [
                'notification_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show all notifications page for admin
     */
    public function adminNotifications()
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login as admin');
        }

        try {
            $notifications = $this->notificationService->getNotifications('admin', session('admin_id'), 100);
            $unreadCount = $this->notificationService->getUnreadCount('admin', session('admin_id'));

            return view('admin.notifications', compact('notifications', 'unreadCount'));
        } catch (\Exception $e) {
            Log::error('Error loading admin notifications page', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error loading notifications');
        }
    }

    /**
     * Show all notifications page for delivery
     */
    public function deliveryNotifications()
    {
        if (!session('coordinator_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login as delivery coordinator');
        }

        try {
            $notifications = $this->notificationService->getNotifications('delivery', session('coordinator_id'), 100);
            $unreadCount = $this->notificationService->getUnreadCount('delivery', session('coordinator_id'));

            return view('admin.delivery.notifications', compact('notifications', 'unreadCount'));
        } catch (\Exception $e) {
            Log::error('Error loading delivery notifications page', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Error loading notifications');
        }
    }

    /**
     * Show all notifications page for users
     */
    public function userNotifications()
    {
        if (!Auth::check()) {
            return redirect()->route('home')->with('error', 'Please login to view notifications');
        }

        // JavaScript will fetch notifications via API
        return view('pages.user-notifications');
    }
}