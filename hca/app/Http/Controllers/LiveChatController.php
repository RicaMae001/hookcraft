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
    /**
     * Customer activity heartbeat - tracks when customer is active on chat page
     */
    public function heartbeat(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated'
            ], 401);
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
                    ->update(['last_activity' => now()]);
            }
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Heartbeat error: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    /**
     * Customer requests a live chat session (REQUIRES LOGIN)
     */
    public function request(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to use live chat',
                'redirect' => route('login')
            ], 401);
        }

        $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'customer_email' => 'nullable|email|max:255',
        ]);

        try {
            $user = Auth::user();
            
            // Check if user already has an active or waiting session
            $existingSession = DB::table('chat_sessions')
                ->where('user_id', $user->id)
                ->whereIn('status', ['waiting', 'active'])
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

            // Get current queue position
            $queuePosition = DB::table('chat_sessions')
                ->where('status', 'waiting')
                ->max('queue_position') + 1;

            // Create new chat session
            $sessionId = Str::uuid()->toString();
            
            DB::table('chat_sessions')->insert([
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'customer_name' => $request->customer_name ?? $user->name,
                'customer_email' => $request->customer_email ?? $user->email,
                'status' => 'waiting',
                'queue_position' => $queuePosition,
                'last_activity' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add system message
            $chatSessionId = DB::table('chat_sessions')
                ->where('session_id', $sessionId)
                ->value('id');

            DB::table('chat_messages')->insert([
                'chat_session_id' => $chatSessionId,
                'sender_type' => 'system',
                'message' => 'Customer joined the queue',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Live chat session requested', [
                'session_id' => $sessionId,
                'user_id' => $user->id,
                'customer_name' => $request->customer_name ?? $user->name,
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
                    ->update(['last_activity' => now()]);

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

            // Get sender names for admin messages
            $messages = $messages->map(function ($msg) {
                if ($msg->sender_type === 'admin' && $msg->sender_id) {
                    $admin = DB::table('admin')->where('id', $msg->sender_id)->first();
                    $msg->sender_name = $admin ? $admin->name : 'Staff';
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

            // Mark all staff/admin messages as read
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
                ->update(['last_activity' => now()]);

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
                ->update(['last_activity' => now()]);

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

            // Get sender names for admin messages
            $messagesWithNames = $newMessages->map(function ($msg) {
                if ($msg->sender_type === 'admin' && $msg->sender_id) {
                    $admin = DB::table('admin')->where('id', $msg->sender_id)->first();
                    $msg->sender_name = $admin ? $admin->name : 'Staff';
                }
                return $msg;
            });

            return response()->json([
                'success' => true,
                'status' => $session->status,
                'queue_position' => $session->queue_position,
                'admin_name' => $session->admin_id ? DB::table('admin')->where('id', $session->admin_id)->value('name') : null,
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
     * Get all chat sessions for admin dashboard
     */
    public function adminIndex()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $waitingSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'waiting')
            ->orderBy('chat_sessions.queue_position', 'asc')
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->get() ?? collect([]);

        $activeSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'active')
            ->where('chat_sessions.admin_id', session('admin_id'))
            ->orderBy('chat_sessions.started_at', 'desc')
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->get() ?? collect([]);

        $allActiveSessions = DB::table('chat_sessions')
            ->leftJoin('admin', 'chat_sessions.admin_id', '=', 'admin.id')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'active')
            ->select('chat_sessions.*', 'admin.name as admin_name', 'users.name as user_name', 'users.email as user_email')
            ->orderBy('chat_sessions.started_at', 'desc')
            ->get() ?? collect([]);

        $closedSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'closed')
            ->orderBy('chat_sessions.closed_at', 'desc')
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->limit(20)
            ->get() ?? collect([]);

        // Add activity warnings for active sessions
        $now = Carbon::now();
        $allActiveSessions = $allActiveSessions->map(function($session) use ($now) {
            if ($session->last_activity) {
                $lastActivity = Carbon::parse($session->last_activity);
                $minutesInactive = $now->diffInMinutes($lastActivity);
                $session->minutes_inactive = $minutesInactive;
                $session->is_inactive_warning = $minutesInactive >= 2; // Warning at 2 mins (customer not on page)
                $session->is_inactive_critical = $minutesInactive >= 13; // Critical at 13 mins (will auto-close soon)
            }
            return $session;
        });

        return view('admin.livechat.livechat_index', compact(
            'waitingSessions',
            'activeSessions',
            'allActiveSessions',
            'closedSessions'
        ));
    }

    /**
     * Admin accepts a waiting chat
     */
    public function adminAccept(Request $request, $sessionId)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        try {
            $session = DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->where('status', 'waiting')
                ->first();

            if (!$session) {
                return redirect()->back()->with('error', 'Chat session not found or already accepted');
            }

            // Update session
            DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->update([
                    'status' => 'active',
                    'admin_id' => session('admin_id'),
                    'started_at' => now(),
                    'last_activity' => now(),
                    'queue_position' => null,
                    'updated_at' => now(),
                ]);

            // Add system message
            $adminName = session('admin_name');
            DB::table('chat_messages')->insert([
                'chat_session_id' => $sessionId,
                'sender_type' => 'system',
                'message' => "{$adminName} joined the chat",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info('Admin accepted chat', [
                'session_id' => $sessionId,
                'admin_id' => session('admin_id')
            ]);

            return redirect()->route('admin.livechat.chat', $sessionId)
                ->with('success', 'Chat session accepted');

        } catch (\Exception $e) {
            Log::error('Admin accept error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to accept chat');
        }
    }

    /**
     * Admin chat interface
     */
    public function adminChat($sessionId)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $session = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.id', $sessionId)
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->first();

        if (!$session) {
            return redirect()->route('admin.livechat.index')
                ->with('error', 'Chat session not found');
        }

        // Check if admin owns this chat
        if ($session->status === 'active' && $session->admin_id != session('admin_id')) {
            return redirect()->route('admin.livechat.index')
                ->with('error', 'This chat is being handled by another staff member');
        }

        // Calculate inactivity
        $now = Carbon::now();
        $lastActivity = Carbon::parse($session->last_activity);
        $session->minutes_inactive = $now->diffInMinutes($lastActivity);
        $session->is_inactive_warning = $session->minutes_inactive >= 2;

        $messages = DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get() ?? collect([]);

        // Mark customer messages as read
        DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('admin.livechat.livechat_chat', compact('session', 'messages'));
    }

    /**
     * View closed chat history (Read-only)
     */
    public function adminViewHistory($sessionId)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

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

        return view('admin.livechat.livechat_view', compact('session', 'messages'));
    }

    /**
     * Delete a single chat session and its messages
     */
    public function adminDeleteSession(Request $request, $sessionId)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

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
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

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
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

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

    /**
     * Admin sends a message
     */
    public function adminSendMessage(Request $request)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'session_id' => 'required|integer',
            'message' => 'required|string|max:2000',
        ]);

        try {
            DB::table('chat_messages')->insert([
                'chat_session_id' => $request->session_id,
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
     * Admin polls for new messages and customer activity status
     */
    public function adminPoll($sessionId)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

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

            // Check for auto-end due to inactivity (15 minutes)
            $now = Carbon::now();
            $lastActivity = Carbon::parse($session->last_activity);
            $minutesInactive = $now->diffInMinutes($lastActivity);
            
            $customerActive = true;
            $autoEndWarning = false;
            
            if ($session->status === 'active') {
                // Auto-end after 15 minutes of inactivity
                if ($minutesInactive >= 15) {
                    DB::table('chat_sessions')
                        ->where('id', $sessionId)
                        ->update([
                            'status' => 'closed',
                            'closed_at' => now(),
                            'updated_at' => now(),
                        ]);
                    
                    // Add system message
                    DB::table('chat_messages')->insert([
                        'chat_session_id' => $sessionId,
                        'sender_type' => 'system',
                        'message' => 'Chat ended due to customer inactivity (15 minutes)',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    return response()->json([
                        'success' => true,
                        'status' => 'closed',
                        'reason' => 'customer_inactive',
                        'new_messages' => []
                    ]);
                }
                
                // Warning at 13 minutes (2 minutes before auto-end)
                $autoEndWarning = $minutesInactive >= 13;
                
                // Customer is considered "away" if no activity for 2 minutes
                $customerActive = $minutesInactive < 2;
            }

            // Get new messages
            $newMessages = DB::table('chat_messages')
                ->where('chat_session_id', $sessionId)
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->orderBy('created_at', 'asc')
                ->get();

            if ($newMessages === null) {
                $newMessages = collect([]);
            }

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
                'minutes_inactive' => $minutesInactive,
                'customer_active' => $customerActive,
                'auto_end_warning' => $autoEndWarning,
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
     * Admin ends the chat
     */
    public function adminEndSession(Request $request, $sessionId)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

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

            DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->update([
                    'status' => 'closed',
                    'closed_at' => now(),
                    'updated_at' => now(),
                ]);

            $adminName = session('admin_name');
            DB::table('chat_messages')->insert([
                'chat_session_id' => $sessionId,
                'sender_type' => 'system',
                'message' => "{$adminName} ended the chat",
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Chat ended successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Admin end session error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to end session'
            ], 500);
        }
    }

    /**
     * Check admin authentication
     */
    private function checkAdminAuth()
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }
        return null;
    }
}