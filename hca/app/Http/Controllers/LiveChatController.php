<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LiveChatController extends Controller
{
    // ============================================
    // CUSTOMER METHODS
    // ============================================

    /**
     * Get customer's ongoing orders (for customer view)
     */
    public function getCustomerOngoingOrders()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        try {
            $orders = DB::table('orders')
                ->where('user_id', Auth::id())
                ->whereIn('delivery_status', ['Pending', 'Out for Delivery'])
                ->orderBy('created_at', 'desc')
                ->select('id', 'customer_name', 'delivery_status', 'total', 'created_at', 'address')
                ->get()
                ->map(function ($order) {
                    // Get order items count
                    $order->items_count = DB::table('order_item')
                        ->where('order_id', $order->id)
                        ->count();
                    
                    // Generate order number (e.g., ORD-00048)
                    $order->order_number = 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
                    
                    return $order;
                });

            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);

        } catch (\Exception $e) {
            Log::error('Get customer ongoing orders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get orders'
            ], 500);
        }
    }

    /**
     * Customer activity heartbeat - tracks when customer is active on chat page
     */
    public function heartbeat(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        $sessionId = $request->session_id;
        
        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $sessionId)
                ->where('user_id', Auth::id())
                ->first();
            
            if ($session && $session->status === 'active') {
                DB::table('chat_sessions')
                    ->where('id', $session->id)
                    ->update([
                        'last_activity' => now(),
                        'last_customer_activity' => now()
                    ]);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Heartbeat error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Customer requests STAFF live chat
     */
    public function request(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to use live chat',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $user = Auth::user();
            
            // Check existing staff session
            $existingSession = DB::table('chat_sessions')
                ->where('user_id', $user->id)
                ->whereIn('status', ['waiting', 'active'])
                ->where('chat_type', 'staff')
                ->first();

            if ($existingSession) {
                return response()->json([
                    'success' => true,
                    'session_id' => $existingSession->session_id,
                    'status' => $existingSession->status,
                    'queue_position' => $existingSession->queue_position,
                    'message' => 'You already have an active chat session'
                ]);
            }

            // Get queue position for staff
            $queuePosition = DB::table('chat_sessions')
                ->where('status', 'waiting')
                ->where('chat_type', 'staff')
                ->count() + 1;

            $sessionId = Str::uuid()->toString();
            
            DB::table('chat_sessions')->insert([
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'chat_type' => 'staff',
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'status' => 'waiting',
                'queue_position' => $queuePosition,
                'last_activity' => now(),
                'last_customer_activity' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $chatSessionId = DB::table('chat_sessions')->where('session_id', $sessionId)->value('id');
            
            DB::table('chat_messages')->insert([
                'chat_session_id' => $chatSessionId,
                'sender_type' => 'system',
                'message' => 'Customer joined the queue',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Staff live chat session requested', [
                'session_id' => $sessionId,
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'queue_position' => $queuePosition
            ]);

            return response()->json([
                'success' => true,
                'session_id' => $sessionId,
                'queue_position' => $queuePosition,
                'message' => 'You have been added to the queue'
            ]);

        } catch (\Exception $e) {
            Log::error('Live chat request error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to start live chat'
            ], 500);
        }
    }

    /**
     * Customer requests DELIVERY live chat
     */
    public function requestDeliveryChat(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to use delivery chat',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $user = Auth::user();
            
            // Check existing delivery session
            $existingSession = DB::table('chat_sessions')
                ->where('user_id', $user->id)
                ->whereIn('status', ['waiting', 'active'])
                ->where('chat_type', 'delivery')
                ->first();

            if ($existingSession) {
                return response()->json([
                    'success' => true,
                    'session_id' => $existingSession->session_id,
                    'status' => $existingSession->status,
                    'queue_position' => $existingSession->queue_position,
                    'message' => 'You already have an active delivery chat session'
                ]);
            }

            // Get queue position for delivery
            $queuePosition = DB::table('chat_sessions')
                ->where('status', 'waiting')
                ->where('chat_type', 'delivery')
                ->count() + 1;

            $sessionId = Str::uuid()->toString();
            
            DB::table('chat_sessions')->insert([
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'chat_type' => 'delivery',
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'status' => 'waiting',
                'queue_position' => $queuePosition,
                'last_activity' => now(),
                'last_customer_activity' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $chatSessionId = DB::table('chat_sessions')->where('session_id', $sessionId)->value('id');
            
            DB::table('chat_messages')->insert([
                'chat_session_id' => $chatSessionId,
                'sender_type' => 'system',
                'message' => 'Customer joined the delivery support queue',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Delivery chat session requested', [
                'session_id' => $sessionId,
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'queue_position' => $queuePosition
            ]);

            return response()->json([
                'success' => true,
                'session_id' => $sessionId,
                'queue_position' => $queuePosition,
                'message' => 'You have been added to the delivery support queue'
            ]);

        } catch (\Exception $e) {
            Log::error('Delivery chat request error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to start delivery chat'
            ], 500);
        }
    }

    /**
     * Get user's active chat session
     */
    public function getActiveSession()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['waiting', 'active'])
                ->first();

            if ($session) {
                // Update last activity
                DB::table('chat_sessions')
                    ->where('id', $session->id)
                    ->update([
                        'last_activity' => now(),
                        'last_customer_activity' => now()
                    ]);

                return response()->json([
                    'success' => true,
                    'has_session' => true,
                    'session' => $session
                ]);
            }

            return response()->json([
                'success' => true,
                'has_session' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Get active session error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get session'
            ], 500);
        }
    }

    /**
     * Check for unread messages (for navbar notification)
     */
    public function checkUnread()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['waiting', 'active'])
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => true,
                    'has_unread' => false,
                    'unread_count' => 0
                ]);
            }

            $unreadCount = DB::table('chat_messages')
                ->where('chat_session_id', $session->id)
                ->where('sender_type', '!=', 'customer')
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success' => true,
                'has_unread' => $unreadCount > 0,
                'unread_count' => $unreadCount,
                'status' => $session->status
            ]);

        } catch (\Exception $e) {
            Log::error('Check unread error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to check messages'
            ], 500);
        }
    }

    /**
     * Get chat history for resuming session
     */
    public function getChatHistory($sessionId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $sessionId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            $messages = DB::table('chat_messages')
                ->where('chat_session_id', $session->id)
                ->orderBy('created_at', 'asc')
                ->get();

            // Get sender names for admin/delivery messages
            $messages = $messages->map(function ($msg) {
                if ($msg->sender_type === 'admin' && $msg->sender_id) {
                    $staff = DB::table('admin')->where('id', $msg->sender_id)->first();
                    $msg->sender_name = $staff ? $staff->name : 'Staff';
                } elseif ($msg->sender_type === 'delivery' && $msg->sender_id) {
                    $staff = DB::table('delivery_coordinator')->where('coordinator_id', $msg->sender_id)->first();
                    $msg->sender_name = $staff ? $staff->name : 'Delivery Support';
                }
                return $msg;
            });

            return response()->json([
                'success' => true,
                'messages' => $messages
            ]);

        } catch (\Exception $e) {
            Log::error('Get chat history error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to get history'
            ], 500);
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($sessionId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $sessionId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            // Mark all staff/admin/delivery messages as read
            DB::table('chat_messages')
                ->where('chat_session_id', $session->id)
                ->where('sender_type', '!=', 'customer')
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Messages marked as read'
            ]);

        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as read'
            ], 500);
        }
    }

    /**
     * Customer sends a message in live chat
     */
    public function sendMessage(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $request->session_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found or unauthorized'
                ], 404);
            }

            if ($session->status === 'closed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Chat session has been closed'
                ], 400);
            }

            // Update last activity
            DB::table('chat_sessions')
                ->where('id', $session->id)
                ->update([
                    'last_activity' => now(),
                    'last_customer_activity' => now()
                ]);

            // Insert message
            DB::table('chat_messages')->insert([
                'chat_session_id' => $session->id,
                'sender_type' => 'customer',
                'sender_id' => Auth::id(),
                'message' => $request->message,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent'
            ]);

        } catch (\Exception $e) {
            Log::error('Send message error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message'
            ], 500);
        }
    }

    /**
     * Poll for new messages and status updates
     */
    public function poll($sessionId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $sessionId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            // Update last activity (user is still active)
            DB::table('chat_sessions')
                ->where('id', $session->id)
                ->update([
                    'last_activity' => now(),
                    'last_customer_activity' => now()
                ]);

            // Get new unread messages
            $newMessages = DB::table('chat_messages')
                ->where('chat_session_id', $session->id)
                ->where('sender_type', '!=', 'customer')
                ->where('is_read', false)
                ->orderBy('created_at', 'asc')
                ->get();

            if ($newMessages === null) {
                $newMessages = collect([]);
            }

            // Mark messages as read
            if ($newMessages->count() > 0) {
                DB::table('chat_messages')
                    ->where('chat_session_id', $session->id)
                    ->where('sender_type', '!=', 'customer')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }

            // Get sender names for admin/delivery messages
            $messagesWithNames = $newMessages->map(function ($msg) {
                if ($msg->sender_type === 'admin' && $msg->sender_id) {
                    $staff = DB::table('admin')->where('id', $msg->sender_id)->first();
                    $msg->sender_name = $staff ? $staff->name : 'Staff';
                } elseif ($msg->sender_type === 'delivery' && $msg->sender_id) {
                    $staff = DB::table('delivery_coordinator')->where('coordinator_id', $msg->sender_id)->first();
                    $msg->sender_name = $staff ? $staff->name : 'Delivery Support';
                }
                return $msg;
            });

            // Get staff name based on chat type
            $staffName = null;
            if ($session->chat_type === 'delivery' && $session->delivery_id) {
                $staff = DB::table('delivery_coordinator')->where('coordinator_id', $session->delivery_id)->first();
                $staffName = $staff ? $staff->name : null;
            } elseif ($session->chat_type === 'staff' && $session->admin_id) {
                $staff = DB::table('admin')->where('id', $session->admin_id)->first();
                $staffName = $staff ? $staff->name : null;
            }

            return response()->json([
                'success' => true,
                'status' => $session->status,
                'queue_position' => $session->queue_position,
                'staff_name' => $staffName,
                'chat_type' => $session->chat_type,
                'new_messages' => $messagesWithNames
            ]);

        } catch (\Exception $e) {
            Log::error('Poll error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Polling failed'
            ], 500);
        }
    }

    /**
     * Customer ends the chat session
     */
    public function endSession(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $request->session_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            // Update session status
            DB::table('chat_sessions')
                ->where('id', $session->id)
                ->update([
                    'status' => 'closed',
                    'closed_at' => now(),
                    'updated_at' => now(),
                ]);

            // Add system message
            DB::table('chat_messages')->insert([
                'chat_session_id' => $session->id,
                'sender_type' => 'system',
                'message' => 'Customer ended the chat',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Chat session ended'
            ]);

        } catch (\Exception $e) {
            Log::error('End session error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to end session'
            ], 500);
        }
    }

    // ============================================
    // ADMIN/STAFF METHODS
    // ============================================

    /**
     * Admin dashboard for live chat
     */
    public function adminIndex()
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        $adminId = session('admin_id');

        // Get waiting sessions
        $waitingSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'waiting')
            ->where('chat_sessions.chat_type', 'staff')
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->orderBy('chat_sessions.created_at', 'asc')
            ->get() ?? collect([]);

        // Get my active sessions (assigned to current admin)
        $activeSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'active')
            ->where('chat_sessions.chat_type', 'staff')
            ->where('chat_sessions.admin_id', $adminId)
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->orderBy('chat_sessions.last_activity', 'desc')
            ->get() ?? collect([]);

        // Get all active sessions (for all admins) with additional info
        $allActiveSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->leftJoin('admin', 'chat_sessions.admin_id', '=', 'admin.id')
            ->where('chat_sessions.status', 'active')
            ->where('chat_sessions.chat_type', 'staff')
            ->select(
                'chat_sessions.*',
                'users.name as user_name',
                'users.email as user_email',
                'admin.name as admin_name'
            )
            ->orderBy('chat_sessions.last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                // Calculate inactivity time
                $session->minutes_inactive = 0;
                $session->is_inactive_warning = false;
                $session->is_inactive_critical = false;
                
                if ($session->last_activity) {
                    $lastActivity = Carbon::parse($session->last_activity);
                    $session->minutes_inactive = now()->diffInMinutes($lastActivity);
                    
                    // Mark as warning if inactive for 5+ minutes
                    $session->is_inactive_warning = $session->minutes_inactive >= 5;
                    
                    // Mark as critical if inactive for 10+ minutes
                    $session->is_inactive_critical = $session->minutes_inactive >= 10;
                }
                
                return $session;
            }) ?? collect([]);

        // Get closed sessions (last 50)
        $closedSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'closed')
            ->where('chat_sessions.chat_type', 'staff')
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->orderBy('chat_sessions.closed_at', 'desc')
            ->take(50)
            ->get() ?? collect([]);

        // Use your existing view name and pass the correct variables
        return view('admin.livechat.livechat_index', compact(
            'waitingSessions', 
            'activeSessions', 
            'allActiveSessions', 
            'closedSessions'
        ));
    }

    /**
     * Admin accepts a chat session
     */
    public function acceptChat(Request $request, $sessionId)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return redirect()->route('admin.livechat.index')
                    ->with('error', 'Chat session not found');
            }

            if ($session->status !== 'waiting') {
                return redirect()->route('admin.livechat.index')
                    ->with('error', 'Chat session is not in waiting status');
            }

            // Update session to active and assign admin
            DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->update([
                    'status' => 'active',
                    'admin_id' => session('admin_id'),
                    'started_at' => now(), // FIXED: Changed from 'accepted_at' to 'started_at'
                    'last_activity' => now(),
                    'updated_at' => now(),
                ]);

            // Add system message
            $admin = DB::table('admin')->where('id', session('admin_id'))->first();
            $adminName = $admin ? $admin->name : 'Staff';

            DB::table('chat_messages')->insert([
                'chat_session_id' => $sessionId,
                'sender_type' => 'system',
                'message' => $adminName . ' joined the chat',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Admin accepted chat session', [
                'session_id' => $sessionId,
                'admin_id' => session('admin_id'),
                'customer_name' => $session->customer_name
            ]);

            return redirect()->route('admin.livechat.chat', ['sessionId' => $sessionId])
                ->with('success', 'Chat session accepted');

        } catch (\Exception $e) {
            Log::error('Accept chat error: ' . $e->getMessage());
            return redirect()->route('admin.livechat.index')
                ->with('error', 'Failed to accept chat session: ' . $e->getMessage());
        }
    }

    /**
     * Admin chat interface
     */
    public function adminChat($sessionId)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        $session = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.id', $sessionId)
            ->select('chat_sessions.*', 'users.name as customer_name', 'users.email as customer_email')
            ->first();

        if (!$session) {
            return redirect()->route('admin.livechat.index')
                ->with('error', 'Chat session not found');
        }

        // Check if admin is assigned to this chat
        if ($session->status === 'active' && $session->admin_id !== session('admin_id')) {
            return redirect()->route('admin.livechat.index')
                ->with('error', 'You are not assigned to this chat session');
        }

        $messages = DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get() ?? collect([]);

        // Use your existing view name
        return view('admin.livechat.livechat_chat', compact('session', 'messages'));
    }

    /**
     * Admin sends a message
     */
    public function adminSendMessage(Request $request)
    {
        if (!session('admin_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $request->validate([
            'session_id' => 'required|integer',
            'message' => 'required|string|max:2000',
        ]);

        try {
            $session = DB::table('chat_sessions')
                ->where('id', $request->session_id)
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            if ($session->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Chat session is not active'
                ], 400);
            }

            if ($session->admin_id !== session('admin_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this chat'
                ], 403);
            }

            // Update last activity
            DB::table('chat_sessions')
                ->where('id', $session->id)
                ->update([
                    'last_activity' => now(),
                    'updated_at' => now()
                ]);

            // Insert message
            DB::table('chat_messages')->insert([
                'chat_session_id' => $session->id,
                'sender_type' => 'admin',
                'sender_id' => session('admin_id'),
                'message' => $request->message,
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message sent'
            ]);

        } catch (\Exception $e) {
            Log::error('Admin send message error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send message'
            ], 500);
        }
    }

    /**
     * Admin polls for new messages - FIXED VERSION
     */
    public function adminPoll($sessionId)
    {
        if (!session('admin_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            // Check if admin is assigned to this chat
            if ($session->status === 'active' && $session->admin_id !== session('admin_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this chat'
                ], 403);
            }

            // Update last activity
            DB::table('chat_sessions')
                ->where('id', $session->id)
                ->update([
                    'last_activity' => now(),
                    'updated_at' => now()
                ]);

            // Get ALL unread messages (customer messages)
            $newMessages = DB::table('chat_messages')
                ->where('chat_session_id', $sessionId)
                ->where('is_read', false)
                ->where('sender_type', 'customer') // Only get customer messages
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark ALL customer messages as read (not just the ones we fetched)
            if ($newMessages->count() > 0) {
                DB::table('chat_messages')
                    ->where('chat_session_id', $sessionId)
                    ->where('sender_type', 'customer')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }

            return response()->json([
                'success' => true,
                'status' => $session->status,
                'new_messages' => $newMessages
            ]);

        } catch (\Exception $e) {
            Log::error('Admin poll error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Polling failed'
            ], 500);
        }
    }

    /**
     * Admin ends a chat session
     */
    public function adminEndChat(Request $request, $sessionId)
    {
        if (!session('admin_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
        }

        $request->validate([
            'session_id' => 'required|integer',
        ]);

        try {
            $session = DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            if ($session->admin_id !== session('admin_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not assigned to this chat'
                ], 403);
            }

            // Update session status
            DB::table('chat_sessions')
                ->where('id', $session->id)
                ->update([
                    'status' => 'closed',
                    'closed_at' => now(),
                    'updated_at' => now(),
                ]);

            // Add system message
            $admin = DB::table('admin')->where('id', session('admin_id'))->first();
            $adminName = $admin ? $admin->name : 'Staff';

            DB::table('chat_messages')->insert([
                'chat_session_id' => $session->id,
                'sender_type' => 'system',
                'message' => $adminName . ' ended the chat',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Admin ended chat session', [
                'session_id' => $sessionId,
                'admin_id' => session('admin_id'),
                'customer_name' => $session->customer_name
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Chat session ended'
            ]);

        } catch (\Exception $e) {
            Log::error('Admin end chat error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to end session'
            ], 500);
        }
    }

    /**
     * View closed chat history (Read-only)
     */
    public function adminViewHistory($sessionId)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        $session = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.id', $sessionId)
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->first();

        if (!$session) {
            return redirect()->route('admin.livechat.index')
                ->with('error', 'Chat session not found');
        }

        $messages = DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get() ?? collect([]);

        // Use your existing view name
        return view('admin.livechat.livechat_view', compact('session', 'messages'));
    }

    /**
     * Delete a single chat session and its messages
     */
    public function adminDeleteSession(Request $request, $sessionId)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        try {
            DB::table('chat_messages')
                ->where('chat_session_id', $sessionId)
                ->delete();

            DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->delete();

            Log::info('Chat session deleted', [
                'session_id' => $sessionId,
                'deleted_by' => session('admin_id')
            ]);

            return redirect()->route('admin.livechat.index')
                ->with('success', 'Chat history deleted successfully');

        } catch (\Exception $e) {
            Log::error('Delete session error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete chat history');
        }
    }

    /**
     * Bulk delete old chat sessions
     */
    public function adminBulkDelete(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        $request->validate([
            'days' => 'required|integer|min:1|max:365'
        ]);

        try {
            $days = $request->days;
            $cutoffDate = now()->subDays($days);

            $oldSessions = DB::table('chat_sessions')
                ->where('status', 'closed')
                ->where('closed_at', '<', $cutoffDate)
                ->pluck('id');

            if ($oldSessions->isEmpty()) {
                return redirect()->back()
                    ->with('info', 'No old chat sessions found to delete');
            }

            DB::table('chat_messages')
                ->whereIn('chat_session_id', $oldSessions)
                ->delete();

            $deletedCount = DB::table('chat_sessions')
                ->whereIn('id', $oldSessions)
                ->delete();

            Log::info('Bulk chat sessions deleted', [
                'days' => $days,
                'count' => $deletedCount,
                'deleted_by' => session('admin_id')
            ]);

            return redirect()->route('admin.livechat.index')
                ->with('success', "Successfully deleted {$deletedCount} old chat sessions");

        } catch (\Exception $e) {
            Log::error('Bulk delete error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete old chat sessions');
        }
    }

    /**
     * Delete all closed chat sessions
     */
    public function adminDeleteAllClosed(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        try {
            $closedSessions = DB::table('chat_sessions')
                ->where('status', 'closed')
                ->pluck('id');

            if ($closedSessions->isEmpty()) {
                return redirect()->back()
                    ->with('info', 'No closed chat sessions to delete');
            }

            DB::table('chat_messages')
                ->whereIn('chat_session_id', $closedSessions)
                ->delete();

            $deletedCount = DB::table('chat_sessions')
                ->whereIn('id', $closedSessions)
                ->delete();

            Log::info('All closed chat sessions deleted', [
                'count' => $deletedCount,
                'deleted_by' => session('admin_id')
            ]);

            return redirect()->route('admin.livechat.index')
                ->with('success', "Successfully deleted all {$deletedCount} closed chat sessions");

        } catch (\Exception $e) {
            Log::error('Delete all closed error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete closed chat sessions');
        }
    }
}