<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeliveryLiveChatController extends Controller
{
    public function index()
    {
        $coordinatorId = session('coordinator_id');
        
        // Get waiting delivery chat sessions
        $waitingSessions = DB::table('chat_sessions')
            ->where('status', 'waiting')
            ->where('chat_type', 'delivery')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($session, $index) {
                $session->queue_position = $index + 1;
                return $session;
            });
        
        // Get active sessions handled by this delivery staff
        $activeSessions = DB::table('chat_sessions')
            ->where('status', 'active')
            ->where('chat_type', 'delivery')
            ->where('delivery_id', $coordinatorId)
            ->orderBy('started_at', 'desc')
            ->get();
        
        // Get all active delivery sessions
        $allActiveSessions = DB::table('chat_sessions')
            ->where('status', 'active')
            ->where('chat_type', 'delivery')
            ->orderBy('started_at', 'desc')
            ->get()
            ->map(function ($session) {
                $deliveryStaff = DB::table('delivery_coordinator')
                    ->where('coordinator_id', $session->delivery_id)
                    ->first();
                $session->delivery_name = $deliveryStaff ? $deliveryStaff->name : 'Unknown';
                
                if ($session->last_customer_activity) {
                    $lastActivity = Carbon::parse($session->last_customer_activity);
                    $minutesInactive = $lastActivity->diffInMinutes(now());
                    $session->minutes_inactive = $minutesInactive;
                    $session->is_inactive_warning = $minutesInactive >= 10;
                    $session->is_inactive_critical = $minutesInactive >= 13;
                }
                
                return $session;
            });
        
        // Get closed sessions for today
        $closedSessions = DB::table('chat_sessions')
            ->where('status', 'closed')
            ->where('chat_type', 'delivery')
            ->whereDate('closed_at', today())
            ->orderBy('closed_at', 'desc')
            ->get();
        
        return view('admin.delivery.livechat.index', compact(
            'waitingSessions',
            'activeSessions',
            'allActiveSessions',
            'closedSessions',
            'coordinatorId'
        ));
    }
    
    public function acceptChat($sessionId)
    {
        $coordinatorId = session('coordinator_id');
        $coordinatorName = session('coordinator_name');
        
        $session = DB::table('chat_sessions')->where('id', $sessionId)->first();
        
        if (!$session || $session->status !== 'waiting' || $session->chat_type !== 'delivery') {
            return redirect()->back()->with('error', 'Invalid chat session');
        }
        
        DB::table('chat_sessions')
            ->where('id', $sessionId)
            ->update([
                'status' => 'active',
                'delivery_id' => $coordinatorId,
                'started_at' => now(),
                'last_activity' => now(),
                'last_customer_activity' => now(),
                'updated_at' => now()
            ]);
        
        // Add system message
        DB::table('chat_messages')->insert([
            'chat_session_id' => $sessionId,
            'sender_type' => 'system',
            'message' => $coordinatorName . ' joined the chat',
            'created_at' => now()
        ]);
        
        return redirect()->route('delivery.livechat.chat', $sessionId);
    }
    
    public function chat($sessionId)
    {
        $coordinatorId = session('coordinator_id');
        
        $session = DB::table('chat_sessions')->where('id', $sessionId)->first();
        
        if (!$session || $session->chat_type !== 'delivery') {
            return redirect()->route('delivery.livechat.index')->with('error', 'Session not found');
        }
        
        if ($session->status === 'active' && $session->delivery_id != $coordinatorId) {
            return redirect()->route('delivery.livechat.index')
                ->with('error', 'This chat is being handled by another delivery coordinator');
        }
        
        $messages = DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($msg) {
                // Add sender names for delivery messages
                if ($msg->sender_type === 'delivery' && $msg->sender_id) {
                    $staff = DB::table('delivery_coordinator')
                        ->where('coordinator_id', $msg->sender_id)
                        ->first();
                    $msg->sender_name = $staff ? $staff->name : 'Delivery Support';
                }
                return $msg;
            });
        
        // Mark messages as read
        DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->where('sender_type', 'customer')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
        
        return view('admin.delivery.livechat.chat', compact('session', 'messages'));
    }
    
    public function sendMessage(Request $request)
    {
        // Debug: Log session data
        Log::info('Delivery sendMessage called', [
            'coordinator_id' => session('coordinator_id'),
            'coordinator_name' => session('coordinator_name'),
            'has_session' => session()->has('coordinator_id')
        ]);
        
        $coordinatorId = session('coordinator_id');
        
        // Check if delivery is authenticated
        if (!$coordinatorId) {
            Log::error('Delivery not authenticated');
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated. Please login again.'
            ], 401);
        }
        
        // Debug: Log request data
        Log::info('Request data:', [
            'session_id' => $request->session_id,
            'message' => $request->message,
            'all_input' => $request->all()
        ]);
        
        // Validate input
        try {
            $validated = $request->validate([
                'session_id' => 'required|integer',
                'message' => 'required|string|max:2000'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        }
        
        $sessionId = $request->session_id;
        $message = $request->message;
        
        try {
            $session = DB::table('chat_sessions')->where('id', $sessionId)->first();
            
            Log::info('Chat session lookup', [
                'session_id' => $sessionId,
                'found' => $session ? 'yes' : 'no',
                'status' => $session ? $session->status : null,
                'delivery_id' => $session ? $session->delivery_id : null
            ]);
            
            if (!$session) {
                Log::error('Session not found', ['session_id' => $sessionId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Session not found'
                ], 404);
            }
            
            if ($session->status !== 'active') {
                Log::error('Session not active', [
                    'status' => $session->status
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Chat session is not active (Status: ' . $session->status . ')'
                ], 400);
            }
            
            if ($session->delivery_id != $coordinatorId) {
                Log::error('Unauthorized access', [
                    'session_delivery_id' => $session->delivery_id,
                    'current_coordinator_id' => $coordinatorId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this chat'
                ], 403);
            }
            
            // Insert message
            $messageId = DB::table('chat_messages')->insertGetId([
                'chat_session_id' => $sessionId,
                'sender_type' => 'delivery',
                'sender_id' => $coordinatorId,
                'message' => $message,
                'is_read' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            Log::info('Message inserted', [
                'message_id' => $messageId,
                'session_id' => $sessionId,
                'coordinator_id' => $coordinatorId
            ]);
            
            // Update session activity
            DB::table('chat_sessions')
                ->where('id', $sessionId)
                ->update([
                    'last_activity' => now(),
                    'updated_at' => now()
                ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'message_id' => $messageId
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delivery send message error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function endChat($sessionId)
    {
        $coordinatorId = session('coordinator_id');
        $coordinatorName = session('coordinator_name');
        
        $session = DB::table('chat_sessions')->where('id', $sessionId)->first();
        
        if (!$session || $session->delivery_id != $coordinatorId) {
            return response()->json(['success' => false], 400);
        }
        
        DB::table('chat_sessions')
            ->where('id', $sessionId)
            ->update([
                'status' => 'closed',
                'closed_at' => now(),
                'updated_at' => now()
            ]);
        
        DB::table('chat_messages')->insert([
            'chat_session_id' => $sessionId,
            'sender_type' => 'system',
            'message' => $coordinatorName . ' ended the chat',
            'created_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }
    
    public function pollMessages($sessionId)
    {
        $coordinatorId = session('coordinator_id');
        
        $session = DB::table('chat_sessions')->where('id', $sessionId)->first();
        
        if (!$session) {
            return response()->json(['success' => false, 'status' => 'closed']);
        }
        
        $unreadMessages = DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->where('is_read', 0)
            ->where('sender_type', '!=', 'delivery')
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark as read
        if ($unreadMessages->count() > 0) {
            DB::table('chat_messages')
                ->where('chat_session_id', $sessionId)
                ->where('is_read', 0)
                ->where('sender_type', '!=', 'delivery')
                ->update(['is_read' => 1]);
        }
        
        // Check customer activity
        $customerActive = null;
        $autoEndWarning = false;
        
        if ($session->last_customer_activity) {
            $minutesInactive = Carbon::parse($session->last_customer_activity)->diffInMinutes(now());
            $customerActive = $minutesInactive < 1;
            $autoEndWarning = $minutesInactive >= 13 && $minutesInactive < 15;
            
            // Auto-end if inactive for 15 minutes
            if ($minutesInactive >= 15 && $session->status === 'active') {
                DB::table('chat_sessions')
                    ->where('id', $sessionId)
                    ->update([
                        'status' => 'closed',
                        'closed_at' => now(),
                        'closed_reason' => 'auto_inactive'
                    ]);
                
                DB::table('chat_messages')->insert([
                    'chat_session_id' => $sessionId,
                    'sender_type' => 'system',
                    'message' => 'Chat ended due to customer inactivity (15 minutes)',
                    'created_at' => now()
                ]);
                
                return response()->json([
                    'success' => true,
                    'status' => 'closed',
                    'reason' => 'customer_inactive',
                    'new_messages' => []
                ]);
            }
        }
        
        return response()->json([
            'success' => true,
            'status' => $session->status,
            'new_messages' => $unreadMessages,
            'customer_active' => $customerActive,
            'auto_end_warning' => $autoEndWarning
        ]);
    }
}