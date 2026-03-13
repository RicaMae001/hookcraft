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
        
        $waitingSessions = DB::table('chat_sessions')
            ->where('status', 'waiting')
            ->where('chat_type', 'delivery')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($session, $index) {
                $session->queue_position = $index + 1;
                $session->ongoing_orders = $this->getCustomerOngoingOrders($session->user_id);
                return $session;
            });
        
        $activeSessions = DB::table('chat_sessions')
            ->where('status', 'active')
            ->where('chat_type', 'delivery')
            ->where('delivery_id', $coordinatorId)
            ->orderBy('started_at', 'desc')
            ->get()
            ->map(function ($session) {
                $session->ongoing_orders = $this->getCustomerOngoingOrders($session->user_id);
                return $session;
            });
        
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
                
                $session->ongoing_orders = $this->getCustomerOngoingOrders($session->user_id);
                return $session;
            });
        
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
    
    /**
     * Get customer's ongoing orders WITH product images.
     * Uses asset('asset/images/') — same as the rest of the app.
     */
    private function getCustomerOngoingOrders($userId)
    {
        if (!$userId) return collect([]);
        
        return DB::table('orders')
            ->where('user_id', $userId)
            ->whereIn('delivery_status', ['Pending', 'Out for Delivery'])
            ->orderBy('created_at', 'desc')
            ->select('id', 'customer_name', 'delivery_status', 'total', 'created_at', 'address')
            ->get()
            ->map(function ($order) {
                $order->items_count = DB::table('order_item')
                    ->where('order_id', $order->id)
                    ->count();

                $order->order_number = 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);

                // ✅ Fetch product images — matches LiveChatController & checkout page
                $order->product_images = DB::table('order_item')
                    ->join('products', 'order_item.product_id', '=', 'products.id')
                    ->where('order_item.order_id', $order->id)
                    ->select('products.id', 'products.name', 'products.image', 'products.price', 'order_item.quantity')
                    ->get()
                    ->map(function ($item) {
                        $item->image_url = asset('asset/images/' . $item->image);
                        return $item;
                    });

                return $order;
            });
    }
    
    public function acceptChat($sessionId)
    {
        $coordinatorId   = session('coordinator_id');
        $coordinatorName = session('coordinator_name');
        
        $session = DB::table('chat_sessions')->where('id', $sessionId)->first();
        
        if (!$session || $session->status !== 'waiting' || $session->chat_type !== 'delivery') {
            return redirect()->back()->with('error', 'Invalid chat session');
        }
        
        DB::table('chat_sessions')
            ->where('id', $sessionId)
            ->update([
                'status'                 => 'active',
                'delivery_id'            => $coordinatorId,
                'started_at'             => now(),
                'last_activity'          => now(),
                'last_customer_activity' => now(),
                'updated_at'             => now()
            ]);
        
        DB::table('chat_messages')->insert([
            'chat_session_id' => $sessionId,
            'sender_type'     => 'system',
            'message'         => $coordinatorName . ' joined the chat',
            'created_at'      => now()
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
                if ($msg->sender_type === 'delivery' && $msg->sender_id) {
                    $staff = DB::table('delivery_coordinator')
                        ->where('coordinator_id', $msg->sender_id)
                        ->first();
                    $msg->sender_name = $staff ? $staff->name : 'Delivery Support';
                }
                return $msg;
            });
        
        DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->where('sender_type', 'customer')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
        
        // getCustomerOngoingOrders now includes product_images
        $ongoingOrders = $this->getCustomerOngoingOrders($session->user_id);
        
        return view('admin.delivery.livechat.chat', compact('session', 'messages', 'ongoingOrders'));
    }
    
    /**
     * Get orders for customer (API endpoint)
     */
    public function getCustomerOrders($sessionId)
    {
        $coordinatorId = session('coordinator_id');
        
        $session = DB::table('chat_sessions')->where('id', $sessionId)->first();
        
        if (!$session || ($session->status === 'active' && $session->delivery_id != $coordinatorId)) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $orders = $this->getCustomerOngoingOrders($session->user_id);
        
        return response()->json(['success' => true, 'orders' => $orders]);
    }
    
    public function sendMessage(Request $request)
    {
        $coordinatorId = session('coordinator_id');
        
        if (!$coordinatorId) {
            Log::error('Delivery not authenticated');
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated. Please login again.'
            ], 401);
        }
        
        try {
            $request->validate([
                'session_id'    => 'required|integer',
                'message'       => 'required|string|max:2000',
                // ✅ Accept product fields for sharing product cards
                'product_name'  => 'nullable|string|max:255',
                'product_price' => 'nullable|numeric',
                'product_image' => 'nullable|string|max:500',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $e->errors()
            ], 422);
        }
        
        try {
            $session = DB::table('chat_sessions')->where('id', $request->session_id)->first();
            
            if (!$session) {
                return response()->json(['success' => false, 'message' => 'Session not found'], 404);
            }
            
            if ($session->status !== 'active') {
                return response()->json(['success' => false, 'message' => 'Chat session is not active'], 400);
            }
            
            if ($session->delivery_id != $coordinatorId) {
                return response()->json(['success' => false, 'message' => 'Unauthorized access to this chat'], 403);
            }
            
            // ✅ Store product fields alongside the message
            $messageId = DB::table('chat_messages')->insertGetId([
                'chat_session_id' => $request->session_id,
                'sender_type'     => 'delivery',
                'sender_id'       => $coordinatorId,
                'message'         => $request->message,
                'product_name'    => $request->product_name,
                'product_price'   => $request->product_price,
                'product_image'   => $request->product_image,
                'is_read'         => 0,
                'created_at'      => now(),
                'updated_at'      => now()
            ]);
            
            DB::table('chat_sessions')
                ->where('id', $request->session_id)
                ->update(['last_activity' => now(), 'updated_at' => now()]);
            
            return response()->json([
                'success'    => true,
                'message'    => 'Message sent successfully',
                'message_id' => $messageId
            ]);
            
        } catch (\Exception $e) {
            Log::error('Delivery send message error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Server error: ' . $e->getMessage()], 500);
        }
    }
    
    public function endChat($sessionId)
    {
        $coordinatorId   = session('coordinator_id');
        $coordinatorName = session('coordinator_name');
        
        $session = DB::table('chat_sessions')->where('id', $sessionId)->first();
        
        if (!$session || $session->delivery_id != $coordinatorId) {
            return response()->json(['success' => false], 400);
        }
        
        DB::table('chat_sessions')
            ->where('id', $sessionId)
            ->update(['status' => 'closed', 'closed_at' => now(), 'updated_at' => now()]);
        
        DB::table('chat_messages')->insert([
            'chat_session_id' => $sessionId,
            'sender_type'     => 'system',
            'message'         => $coordinatorName . ' ended the chat',
            'created_at'      => now()
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
        
        if ($unreadMessages->count() > 0) {
            DB::table('chat_messages')
                ->where('chat_session_id', $sessionId)
                ->where('is_read', 0)
                ->where('sender_type', '!=', 'delivery')
                ->update(['is_read' => 1]);
        }
        
        $customerActive  = null;
        $autoEndWarning  = false;
        
        if ($session->last_customer_activity) {
            $minutesInactive = Carbon::parse($session->last_customer_activity)->diffInMinutes(now());
            $customerActive  = $minutesInactive < 1;
            $autoEndWarning  = $minutesInactive >= 13 && $minutesInactive < 15;
            
            if ($minutesInactive >= 15 && $session->status === 'active') {
                DB::table('chat_sessions')
                    ->where('id', $sessionId)
                    ->update(['status' => 'closed', 'closed_at' => now(), 'closed_reason' => 'auto_inactive']);
                
                DB::table('chat_messages')->insert([
                    'chat_session_id' => $sessionId,
                    'sender_type'     => 'system',
                    'message'         => 'Chat ended due to customer inactivity (15 minutes)',
                    'created_at'      => now()
                ]);
                
                return response()->json([
                    'success'      => true,
                    'status'       => 'closed',
                    'reason'       => 'customer_inactive',
                    'new_messages' => []
                ]);
            }
        }
        
        return response()->json([
            'success'          => true,
            'status'           => $session->status,
            'new_messages'     => $unreadMessages,
            'customer_active'  => $customerActive,
            'auto_end_warning' => $autoEndWarning
        ]);
    }
}