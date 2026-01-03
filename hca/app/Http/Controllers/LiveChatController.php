<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class LiveChatController extends Controller
{
    /**
     * Customer requests a live chat session
     */
    public function request(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'nullable|email|max:255',
        ]);

        try {
            // Get current queue position
            $queuePosition = DB::table('chat_sessions')
                ->where('status', 'waiting')
                ->max('queue_position') + 1;

            // Create new chat session
            $sessionId = Str::uuid()->toString();
            
            DB::table('chat_sessions')->insert([
                'user_id' => auth()->id(),
                'session_id' => $sessionId,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'status' => 'waiting',
                'queue_position' => $queuePosition,
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
                'customer_name' => $request->customer_name,
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
     * Customer sends a message in live chat
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string',
            'message' => 'required|string|max:2000',
        ]);

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $request->session_id)
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            if ($session->status === 'closed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Chat session has been closed'
                ], 400);
            }

            // Insert message
            DB::table('chat_messages')->insert([
                'chat_session_id' => $session->id,
                'sender_type' => 'customer',
                'sender_id' => auth()->id(),
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
        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $sessionId)
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }

            // Get new unread messages
            $newMessages = DB::table('chat_messages')
                ->where('chat_session_id', $session->id)
                ->where('sender_type', '!=', 'customer')
                ->where('is_read', false)
                ->orderBy('created_at', 'asc')
                ->get();

            // Ensure $newMessages is never null
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
        $request->validate([
            'session_id' => 'required|string',
        ]);

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $request->session_id)
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

        // FIXED: Ensure we always get collections, never null
        $waitingSessions = DB::table('chat_sessions')
            ->where('status', 'waiting')
            ->orderBy('queue_position', 'asc')
            ->get() ?? collect([]);

        $activeSessions = DB::table('chat_sessions')
            ->where('status', 'active')
            ->where('admin_id', session('admin_id'))
            ->orderBy('started_at', 'desc')
            ->get() ?? collect([]);

        $allActiveSessions = DB::table('chat_sessions')
            ->leftJoin('admin', 'chat_sessions.admin_id', '=', 'admin.id')
            ->where('chat_sessions.status', 'active')
            ->select('chat_sessions.*', 'admin.name as admin_name')
            ->orderBy('chat_sessions.started_at', 'desc')
            ->get() ?? collect([]);

        $closedSessions = DB::table('chat_sessions')
            ->where('status', 'closed')
            ->orderBy('closed_at', 'desc')
            ->limit(20)
            ->get() ?? collect([]);

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
            ->where('id', $sessionId)
            ->first();

        if (!$session) {
            return redirect()->route('admin.livechat.index')
                ->with('error', 'Chat session not found');
        }

        // Check if admin owns this chat or is viewing
        if ($session->status === 'active' && $session->admin_id != session('admin_id')) {
            return redirect()->route('admin.livechat.index')
                ->with('error', 'This chat is being handled by another staff member');
        }

        // FIXED: Ensure messages is always a collection
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
            ->where('id', $sessionId)
            ->first();

        if (!$session) {
            return redirect()->route('admin.livechat.index')
                ->with('error', 'Chat session not found');
        }

        // FIXED: Ensure messages is always a collection
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
            // Delete messages first
            DB::table('chat_messages')
                ->where('chat_session_id', $sessionId)
                ->delete();

            // Delete session
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
     * Bulk delete old chat sessions (older than X days)
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

            // Get old closed sessions
            $oldSessions = DB::table('chat_sessions')
                ->where('status', 'closed')
                ->where('closed_at', '<', $cutoffDate)
                ->pluck('id');

            if ($oldSessions->isEmpty()) {
                return redirect()->back()
                    ->with('info', 'No old chat sessions found to delete');
            }

            // Delete messages
            DB::table('chat_messages')
                ->whereIn('chat_session_id', $oldSessions)
                ->delete();

            // Delete sessions
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
            // Get all closed sessions
            $closedSessions = DB::table('chat_sessions')
                ->where('status', 'closed')
                ->pluck('id');

            if ($closedSessions->isEmpty()) {
                return redirect()->back()
                    ->with('info', 'No closed chat sessions to delete');
            }

            // Delete messages
            DB::table('chat_messages')
                ->whereIn('chat_session_id', $closedSessions)
                ->delete();

            // Delete sessions
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
     * Admin polls for new messages
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

            // Get new unread messages from customer
            $newMessages = DB::table('chat_messages')
                ->where('chat_session_id', $sessionId)
                ->where('sender_type', 'customer')
                ->where('is_read', false)
                ->orderBy('created_at', 'asc')
                ->get();

            // FIXED: Ensure $newMessages is never null
            if ($newMessages === null) {
                $newMessages = collect([]);
            }

            // Mark as read
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

            // Update session
            DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->update([
                    'status' => 'closed',
                    'closed_at' => now(),
                    'updated_at' => now(),
                ]);

            // Add system message
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