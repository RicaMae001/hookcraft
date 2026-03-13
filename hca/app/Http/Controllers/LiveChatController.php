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
     * Get customer's ongoing orders with product images
     */
    public function getCustomerOngoingOrders()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $orders = DB::table('orders')
                ->where('user_id', Auth::id())
                ->whereIn('delivery_status', ['Pending', 'Out for Delivery'])
                ->orderBy('created_at', 'desc')
                ->select('id', 'customer_name', 'delivery_status', 'total', 'created_at', 'address')
                ->get()
                ->map(function ($order) {
                    $order->items_count = DB::table('order_item')
                        ->where('order_id', $order->id)
                        ->count();

                    $order->product_images = DB::table('order_item')
                        ->join('products', 'order_item.product_id', '=', 'products.id')
                        ->where('order_item.order_id', $order->id)
                        ->select('products.id', 'products.name', 'products.image', 'products.price', 'order_item.quantity')
                        ->get()
                        ->map(function ($item) {
                            $item->image_url = asset('asset/images/' . $item->image);
                            return $item;
                        });

                    $order->order_number = 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
                    return $order;
                });

            return response()->json(['success' => true, 'orders' => $orders]);

        } catch (\Exception $e) {
            Log::error('Get customer ongoing orders error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get orders'], 500);
        }
    }

    /**
     * Get customer's pending/reviewing customization requests
     * Special case: shows customize info + image for unprocessed requests
     */
    public function getCustomerPendingCustomizations()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $customizations = DB::table('product_customizations')
                ->join('products', 'product_customizations.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('orders', 'product_customizations.order_id', '=', 'orders.id')
                ->where('product_customizations.user_id', Auth::id())
                ->orderBy('product_customizations.created_at', 'desc')
                ->take(20)
                ->select(
                    'product_customizations.id',
                    'product_customizations.order_id',
                    'product_customizations.customization_name',
                    'product_customizations.customization_details',
                    'product_customizations.special_instructions',
                    'product_customizations.custom_image',
                    'product_customizations.total_price',
                    'product_customizations.admin_price',
                    'product_customizations.admin_notes',
                    'product_customizations.status',
                    'product_customizations.created_at',
                    'product_customizations.updated_at',
                    'products.name as product_name',
                    'products.image as product_image',
                    'products.price as product_base_price',
                    'categories.name as category_name',
                    'orders.id as linked_order_id'
                )
                ->get()
                ->map(function ($c) {
                    $c->ref = 'CUST-' . str_pad($c->id, 5, '0', STR_PAD_LEFT);
                    $c->order_number = $c->linked_order_id
                        ? 'ORD-' . str_pad($c->linked_order_id, 5, '0', STR_PAD_LEFT)
                        : null;

                    // Resolve custom image URL
                    $c->image_url = $c->custom_image
                        ? asset('uploads/customizations/' . $c->custom_image)
                        : null;

                    // Resolve base product image
                    $c->product_image_url = $c->product_image
                        ? asset('asset/images/' . $c->product_image)
                        : null;

                    // Fetch material options
                    $c->options = DB::table('customization_options')
                        ->where('customization_id', $c->id)
                        ->get()
                        ->map(function ($opt) {
                            $decoded = json_decode($opt->option_value, true);
                            if (is_array($decoded)) {
                                $opt->parsed = $decoded;
                            } else {
                                $opt->parsed = ['label' => $opt->option_value, 'quantity' => 1, 'unit_price' => 0, 'subtotal' => (float)$opt->additional_price];
                            }
                            $opt->additional_price = (float) $opt->additional_price;
                            return $opt;
                        });

                    $c->options_total     = $c->options->sum('additional_price');
                    $c->is_pending_approval = in_array($c->status, ['Pending', 'Reviewing']);
                    $c->formatted_date    = \Carbon\Carbon::parse($c->created_at)->format('M d, Y');

                    return $c;
                });

            return response()->json(['success' => true, 'customizations' => $customizations]);

        } catch (\Exception $e) {
            Log::error('Get customer customizations error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get customizations'], 500);
        }
    }

    /**
     * Customer activity heartbeat
     */
    public function heartbeat(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $request->session_id)
                ->where('user_id', Auth::id())
                ->first();

            if ($session && $session->status === 'active') {
                DB::table('chat_sessions')
                    ->where('id', $session->id)
                    ->update(['last_activity' => now(), 'last_customer_activity' => now()]);
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
                'success'  => false,
                'message'  => 'You must be logged in to use live chat',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $user = Auth::user();

            $existingSession = DB::table('chat_sessions')
                ->where('user_id', $user->id)
                ->whereIn('status', ['waiting', 'active'])
                ->where('chat_type', 'staff')
                ->first();

            if ($existingSession) {
                return response()->json([
                    'success'        => true,
                    'session_id'     => $existingSession->session_id,
                    'status'         => $existingSession->status,
                    'queue_position' => $existingSession->queue_position,
                    'message'        => 'You already have an active chat session'
                ]);
            }

            $queuePosition = DB::table('chat_sessions')
                ->where('status', 'waiting')
                ->where('chat_type', 'staff')
                ->count() + 1;

            $sessionId = Str::uuid()->toString();

            DB::table('chat_sessions')->insert([
                'user_id'                => $user->id,
                'session_id'             => $sessionId,
                'chat_type'              => 'staff',
                'customer_name'          => $user->name,
                'customer_email'         => $user->email,
                'status'                 => 'waiting',
                'queue_position'         => $queuePosition,
                'last_activity'          => now(),
                'last_customer_activity' => now(),
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);

            $chatSessionId = DB::table('chat_sessions')->where('session_id', $sessionId)->value('id');

            DB::table('chat_messages')->insert([
                'chat_session_id' => $chatSessionId,
                'sender_type'     => 'system',
                'message'         => 'Customer joined the queue',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            return response()->json([
                'success'        => true,
                'session_id'     => $sessionId,
                'queue_position' => $queuePosition,
                'message'        => 'You have been added to the queue'
            ]);

        } catch (\Exception $e) {
            Log::error('Live chat request error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to start live chat'], 500);
        }
    }

    /**
     * Customer requests DELIVERY live chat
     */
    public function requestDeliveryChat(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success'  => false,
                'message'  => 'You must be logged in to use delivery chat',
                'redirect' => route('login')
            ], 401);
        }

        try {
            $user = Auth::user();

            $existingSession = DB::table('chat_sessions')
                ->where('user_id', $user->id)
                ->whereIn('status', ['waiting', 'active'])
                ->where('chat_type', 'delivery')
                ->first();

            if ($existingSession) {
                return response()->json([
                    'success'        => true,
                    'session_id'     => $existingSession->session_id,
                    'status'         => $existingSession->status,
                    'queue_position' => $existingSession->queue_position,
                    'message'        => 'You already have an active delivery chat session'
                ]);
            }

            $queuePosition = DB::table('chat_sessions')
                ->where('status', 'waiting')
                ->where('chat_type', 'delivery')
                ->count() + 1;

            $sessionId = Str::uuid()->toString();

            DB::table('chat_sessions')->insert([
                'user_id'                => $user->id,
                'session_id'             => $sessionId,
                'chat_type'              => 'delivery',
                'customer_name'          => $user->name,
                'customer_email'         => $user->email,
                'status'                 => 'waiting',
                'queue_position'         => $queuePosition,
                'last_activity'          => now(),
                'last_customer_activity' => now(),
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);

            $chatSessionId = DB::table('chat_sessions')->where('session_id', $sessionId)->value('id');

            DB::table('chat_messages')->insert([
                'chat_session_id' => $chatSessionId,
                'sender_type'     => 'system',
                'message'         => 'Customer joined the delivery support queue',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            return response()->json([
                'success'        => true,
                'session_id'     => $sessionId,
                'queue_position' => $queuePosition,
                'message'        => 'You have been added to the delivery support queue'
            ]);

        } catch (\Exception $e) {
            Log::error('Delivery chat request error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to start delivery chat'], 500);
        }
    }

    /**
     * Get user's active chat session
     */
    public function getActiveSession()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['waiting', 'active'])
                ->first();

            if ($session) {
                DB::table('chat_sessions')
                    ->where('id', $session->id)
                    ->update(['last_activity' => now(), 'last_customer_activity' => now()]);

                return response()->json(['success' => true, 'has_session' => true, 'session' => $session]);
            }

            return response()->json(['success' => true, 'has_session' => false]);

        } catch (\Exception $e) {
            Log::error('Get active session error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get session'], 500);
        }
    }

    /**
     * Check for unread messages
     */
    public function checkUnread()
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('user_id', Auth::id())
                ->whereIn('status', ['waiting', 'active'])
                ->first();

            if (!$session) {
                return response()->json(['success' => true, 'has_unread' => false, 'unread_count' => 0]);
            }

            $unreadCount = DB::table('chat_messages')
                ->where('chat_session_id', $session->id)
                ->where('sender_type', '!=', 'customer')
                ->where('is_read', false)
                ->count();

            return response()->json([
                'success'      => true,
                'has_unread'   => $unreadCount > 0,
                'unread_count' => $unreadCount,
                'status'       => $session->status
            ]);

        } catch (\Exception $e) {
            Log::error('Check unread error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to check messages'], 500);
        }
    }

    /**
     * Get chat history for resuming session
     */
    public function getChatHistory($sessionId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $sessionId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json(['success' => false, 'message' => 'Session not found'], 404);
            }

            $messages = DB::table('chat_messages')
                ->where('chat_session_id', $session->id)
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(function ($msg) {
                    if ($msg->sender_type === 'admin' && $msg->sender_id) {
                        $staff = DB::table('admin')->where('id', $msg->sender_id)->first();
                        $msg->sender_name = $staff ? $staff->name : 'Staff';
                    } elseif ($msg->sender_type === 'delivery' && $msg->sender_id) {
                        $staff = DB::table('delivery_coordinator')->where('coordinator_id', $msg->sender_id)->first();
                        $msg->sender_name = $staff ? $staff->name : 'Delivery Support';
                    }
                    return $msg;
                });

            return response()->json(['success' => true, 'messages' => $messages]);

        } catch (\Exception $e) {
            Log::error('Get chat history error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get history'], 500);
        }
    }

    /**
     * Mark messages as read
     */
    public function markAsRead($sessionId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $sessionId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json(['success' => false, 'message' => 'Session not found'], 404);
            }

            DB::table('chat_messages')
                ->where('chat_session_id', $session->id)
                ->where('sender_type', '!=', 'customer')
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json(['success' => true, 'message' => 'Messages marked as read']);

        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to mark as read'], 500);
        }
    }

    /**
     * Customer sends a message.
     * Supports: plain text, product share, and customize request share.
     */
    public function sendMessage(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $request->validate([
            'session_id'             => 'required|string',
            'message'                => 'required|string|max:2000',
            // Product fields
            'product_name'           => 'nullable|string|max:255',
            'product_price'          => 'nullable|numeric',
            'product_image'          => 'nullable|string|max:500',
            // Customize fields
            'customize_ref'          => 'nullable|string|max:50',
            'customize_name'         => 'nullable|string|max:255',
            'customize_details'      => 'nullable|string|max:1000',
            'customize_instructions' => 'nullable|string|max:500',
            'customize_status'       => 'nullable|string|max:50',
            'customize_price'        => 'nullable|numeric',
            'customize_image'        => 'nullable|string|max:500',
            'customize_materials'    => 'nullable|string|max:5000',
        ]);

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $request->session_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json(['success' => false, 'message' => 'Session not found or unauthorized'], 404);
            }

            if ($session->status === 'closed') {
                return response()->json(['success' => false, 'message' => 'Chat session has been closed'], 400);
            }

            DB::table('chat_sessions')
                ->where('id', $session->id)
                ->update(['last_activity' => now(), 'last_customer_activity' => now()]);

            DB::table('chat_messages')->insert([
                'chat_session_id'        => $session->id,
                'sender_type'            => 'customer',
                'sender_id'              => Auth::id(),
                'message'                => $request->message,
                // Product
                'product_name'           => $request->product_name,
                'product_price'          => $request->product_price,
                'product_image'          => $request->product_image,
                // Customize
                'customize_ref'          => $request->customize_ref,
                'customize_name'         => $request->customize_name,
                'customize_details'      => $request->customize_details,
                'customize_instructions' => $request->customize_instructions,
                'customize_status'       => $request->customize_status,
                'customize_price'        => $request->customize_price,
                'customize_image'        => $request->customize_image,
                'customize_materials'    => $request->customize_materials,
                'is_read'                => false,
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Message sent']);

        } catch (\Exception $e) {
            Log::error('Send message error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send message'], 500);
        }
    }

    /**
     * Poll for new messages and status updates
     */
    public function poll($sessionId)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $sessionId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json(['success' => false, 'message' => 'Session not found'], 404);
            }

            DB::table('chat_sessions')
                ->where('id', $session->id)
                ->update(['last_activity' => now(), 'last_customer_activity' => now()]);

            $newMessages = DB::table('chat_messages')
                ->where('chat_session_id', $session->id)
                ->where('sender_type', '!=', 'customer')
                ->where('is_read', false)
                ->orderBy('created_at', 'asc')
                ->get();

            if ($newMessages->count() > 0) {
                DB::table('chat_messages')
                    ->where('chat_session_id', $session->id)
                    ->where('sender_type', '!=', 'customer')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }

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

            $staffName = null;
            if ($session->chat_type === 'delivery' && $session->delivery_id) {
                $staff = DB::table('delivery_coordinator')->where('coordinator_id', $session->delivery_id)->first();
                $staffName = $staff ? $staff->name : null;
            } elseif ($session->chat_type === 'staff' && $session->admin_id) {
                $staff = DB::table('admin')->where('id', $session->admin_id)->first();
                $staffName = $staff ? $staff->name : null;
            }

            return response()->json([
                'success'        => true,
                'status'         => $session->status,
                'queue_position' => $session->queue_position,
                'staff_name'     => $staffName,
                'chat_type'      => $session->chat_type,
                'new_messages'   => $messagesWithNames
            ]);

        } catch (\Exception $e) {
            Log::error('Poll error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Polling failed'], 500);
        }
    }

    /**
     * Customer ends the chat session
     */
    public function endSession(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $request->validate(['session_id' => 'required|string']);

        try {
            $session = DB::table('chat_sessions')
                ->where('session_id', $request->session_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$session) {
                return response()->json(['success' => false, 'message' => 'Session not found'], 404);
            }

            DB::table('chat_sessions')->where('id', $session->id)->update([
                'status'     => 'closed',
                'closed_at'  => now(),
                'updated_at' => now(),
            ]);

            DB::table('chat_messages')->insert([
                'chat_session_id' => $session->id,
                'sender_type'     => 'system',
                'message'         => 'Customer ended the chat',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Chat session ended']);

        } catch (\Exception $e) {
            Log::error('End session error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to end session'], 500);
        }
    }

    // ============================================
    // ADMIN/STAFF METHODS
    // ============================================

    public function adminIndex()
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        $adminId = session('admin_id');

        $waitingSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'waiting')
            ->where('chat_sessions.chat_type', 'staff')
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->orderBy('chat_sessions.created_at', 'asc')
            ->get() ?? collect([]);

        $activeSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'active')
            ->where('chat_sessions.chat_type', 'staff')
            ->where('chat_sessions.admin_id', $adminId)
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->orderBy('chat_sessions.last_activity', 'desc')
            ->get() ?? collect([]);

        $allActiveSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->leftJoin('admin', 'chat_sessions.admin_id', '=', 'admin.id')
            ->where('chat_sessions.status', 'active')
            ->where('chat_sessions.chat_type', 'staff')
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email', 'admin.name as admin_name')
            ->orderBy('chat_sessions.last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->minutes_inactive     = 0;
                $session->is_inactive_warning  = false;
                $session->is_inactive_critical = false;
                if ($session->last_activity) {
                    $lastActivity = Carbon::parse($session->last_activity);
                    $session->minutes_inactive     = now()->diffInMinutes($lastActivity);
                    $session->is_inactive_warning  = $session->minutes_inactive >= 5;
                    $session->is_inactive_critical = $session->minutes_inactive >= 10;
                }
                return $session;
            }) ?? collect([]);

        $closedSessions = DB::table('chat_sessions')
            ->leftJoin('users', 'chat_sessions.user_id', '=', 'users.id')
            ->where('chat_sessions.status', 'closed')
            ->where('chat_sessions.chat_type', 'staff')
            ->select('chat_sessions.*', 'users.name as user_name', 'users.email as user_email')
            ->orderBy('chat_sessions.closed_at', 'desc')
            ->take(50)
            ->get() ?? collect([]);

        return view('admin.livechat.livechat_index', compact(
            'waitingSessions', 'activeSessions', 'allActiveSessions', 'closedSessions'
        ));
    }

    public function acceptChat(Request $request, $sessionId)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        try {
            $session = DB::table('chat_sessions')->where('id', $sessionId)->first();

            if (!$session || $session->status !== 'waiting') {
                return redirect()->route('admin.livechat.index')->with('error', 'Session not available');
            }

            DB::table('chat_sessions')->where('id', $sessionId)->update([
                'status'        => 'active',
                'admin_id'      => session('admin_id'),
                'started_at'    => now(),
                'last_activity' => now(),
                'updated_at'    => now(),
            ]);

            $admin     = DB::table('admin')->where('id', session('admin_id'))->first();
            $adminName = $admin ? $admin->name : 'Staff';

            DB::table('chat_messages')->insert([
                'chat_session_id' => $sessionId,
                'sender_type'     => 'system',
                'message'         => $adminName . ' joined the chat',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            return redirect()->route('admin.livechat.chat', ['sessionId' => $sessionId])
                ->with('success', 'Chat session accepted');

        } catch (\Exception $e) {
            Log::error('Accept chat error: ' . $e->getMessage());
            return redirect()->route('admin.livechat.index')->with('error', 'Failed to accept chat session');
        }
    }

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
            return redirect()->route('admin.livechat.index')->with('error', 'Chat session not found');
        }

        if ($session->status === 'active' && $session->admin_id !== session('admin_id')) {
            return redirect()->route('admin.livechat.index')->with('error', 'You are not assigned to this chat session');
        }

        $messages = DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get() ?? collect([]);

        return view('admin.livechat.livechat_chat', compact('session', 'messages'));
    }

    /**
     * Admin/delivery sends a message.
     * Supports: plain text, product share, and customize request share.
     */
    public function adminSendMessage(Request $request)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        $request->validate([
            'session_id'             => 'required|integer',
            'message'                => 'required|string|max:2000',
            // Product
            'product_name'           => 'nullable|string|max:255',
            'product_price'          => 'nullable|numeric',
            'product_image'          => 'nullable|string|max:500',
            // Customize
            'customize_ref'          => 'nullable|string|max:50',
            'customize_name'         => 'nullable|string|max:255',
            'customize_details'      => 'nullable|string|max:1000',
            'customize_instructions' => 'nullable|string|max:500',
            'customize_status'       => 'nullable|string|max:50',
            'customize_price'        => 'nullable|numeric',
            'customize_image'        => 'nullable|string|max:500',
            'customize_materials'    => 'nullable|string|max:5000',
        ]);

        try {
            $session = DB::table('chat_sessions')->where('id', $request->session_id)->first();

            if (!$session) {
                return response()->json(['success' => false, 'message' => 'Session not found'], 404);
            }

            if ($session->status !== 'active') {
                return response()->json(['success' => false, 'message' => 'Chat session is not active'], 400);
            }

            if ($session->admin_id !== session('admin_id')) {
                return response()->json(['success' => false, 'message' => 'You are not assigned to this chat'], 403);
            }

            DB::table('chat_sessions')->where('id', $session->id)->update([
                'last_activity' => now(), 'updated_at' => now()
            ]);

            DB::table('chat_messages')->insert([
                'chat_session_id'        => $session->id,
                'sender_type'            => 'admin',
                'sender_id'              => session('admin_id'),
                'message'                => $request->message,
                // Product
                'product_name'           => $request->product_name,
                'product_price'          => $request->product_price,
                'product_image'          => $request->product_image,
                // Customize
                'customize_ref'          => $request->customize_ref,
                'customize_name'         => $request->customize_name,
                'customize_details'      => $request->customize_details,
                'customize_instructions' => $request->customize_instructions,
                'customize_status'       => $request->customize_status,
                'customize_price'        => $request->customize_price,
                'customize_image'        => $request->customize_image,
                'customize_materials'    => $request->customize_materials,
                'is_read'                => false,
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Message sent']);

        } catch (\Exception $e) {
            Log::error('Admin send message error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to send message'], 500);
        }
    }

    public function adminPoll($sessionId)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $session = DB::table('chat_sessions')->where('id', $sessionId)->first();

            if (!$session) {
                return response()->json(['success' => false, 'message' => 'Session not found'], 404);
            }

            if ($session->status === 'active' && $session->admin_id !== session('admin_id')) {
                return response()->json(['success' => false, 'message' => 'You are not assigned to this chat'], 403);
            }

            DB::table('chat_sessions')->where('id', $session->id)->update([
                'last_activity' => now(), 'updated_at' => now()
            ]);

            $newMessages = DB::table('chat_messages')
                ->where('chat_session_id', $sessionId)
                ->where('is_read', false)
                ->where('sender_type', 'customer')
                ->orderBy('created_at', 'asc')
                ->get();

            if ($newMessages->count() > 0) {
                DB::table('chat_messages')
                    ->where('chat_session_id', $sessionId)
                    ->where('sender_type', 'customer')
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }

            return response()->json([
                'success'      => true,
                'status'       => $session->status,
                'new_messages' => $newMessages
            ]);

        } catch (\Exception $e) {
            Log::error('Admin poll error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Polling failed'], 500);
        }
    }

    public function adminEndChat(Request $request, $sessionId)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $session = DB::table('chat_sessions')->where('id', $sessionId)->first();

            if (!$session || $session->admin_id !== session('admin_id')) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            DB::table('chat_sessions')->where('id', $session->id)->update([
                'status' => 'closed', 'closed_at' => now(), 'updated_at' => now(),
            ]);

            $admin     = DB::table('admin')->where('id', session('admin_id'))->first();
            $adminName = $admin ? $admin->name : 'Staff';

            DB::table('chat_messages')->insert([
                'chat_session_id' => $session->id,
                'sender_type'     => 'system',
                'message'         => $adminName . ' ended the chat',
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Chat session ended']);

        } catch (\Exception $e) {
            Log::error('Admin end chat error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to end session'], 500);
        }
    }

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
            return redirect()->route('admin.livechat.index')->with('error', 'Chat session not found');
        }

        $messages = DB::table('chat_messages')
            ->where('chat_session_id', $sessionId)
            ->orderBy('created_at', 'asc')
            ->get() ?? collect([]);

        return view('admin.livechat.livechat_view', compact('session', 'messages'));
    }

    public function adminDeleteSession(Request $request, $sessionId)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        try {
            DB::table('chat_messages')->where('chat_session_id', $sessionId)->delete();
            DB::table('chat_sessions')->where('id', $sessionId)->delete();

            return redirect()->route('admin.livechat.index')->with('success', 'Chat history deleted successfully');
        } catch (\Exception $e) {
            Log::error('Delete session error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete chat history');
        }
    }

    public function adminBulkDelete(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        $request->validate(['days' => 'required|integer|min:1|max:365']);

        try {
            $cutoffDate  = now()->subDays($request->days);
            $oldSessions = DB::table('chat_sessions')
                ->where('status', 'closed')
                ->where('closed_at', '<', $cutoffDate)
                ->pluck('id');

            if ($oldSessions->isEmpty()) {
                return redirect()->back()->with('info', 'No old chat sessions found to delete');
            }

            DB::table('chat_messages')->whereIn('chat_session_id', $oldSessions)->delete();
            $deletedCount = DB::table('chat_sessions')->whereIn('id', $oldSessions)->delete();

            return redirect()->route('admin.livechat.index')
                ->with('success', "Successfully deleted {$deletedCount} old chat sessions");
        } catch (\Exception $e) {
            Log::error('Bulk delete error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete old chat sessions');
        }
    }

    public function adminDeleteAllClosed(Request $request)
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        try {
            $closedSessions = DB::table('chat_sessions')->where('status', 'closed')->pluck('id');

            if ($closedSessions->isEmpty()) {
                return redirect()->back()->with('info', 'No closed chat sessions to delete');
            }

            DB::table('chat_messages')->whereIn('chat_session_id', $closedSessions)->delete();
            $deletedCount = DB::table('chat_sessions')->whereIn('id', $closedSessions)->delete();

            return redirect()->route('admin.livechat.index')
                ->with('success', "Successfully deleted all {$deletedCount} closed chat sessions");
        } catch (\Exception $e) {
            Log::error('Delete all closed error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete closed chat sessions');
        }
    }

    // ============================================
    // ADMIN SIDEBAR — Customer data for chat panel
    // ============================================

    /**
     * Admin sidebar: Get a specific customer's orders
     * GET /admin/livechat/customer-orders/{userId}
     */
    public function adminGetCustomerOrders($userId)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $orders = DB::table('orders')
                ->where('user_id', $userId)
                ->whereIn('delivery_status', ['Pending', 'Out for Delivery', 'Delivered'])
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->select('id', 'customer_name', 'delivery_status', 'total', 'created_at', 'address')
                ->get()
                ->map(function ($order) {
                    $order->items_count = DB::table('order_item')
                        ->where('order_id', $order->id)
                        ->count();

                    $order->product_images = DB::table('order_item')
                        ->join('products', 'order_item.product_id', '=', 'products.id')
                        ->where('order_item.order_id', $order->id)
                        ->select('products.id', 'products.name', 'products.image', 'products.price', 'order_item.quantity')
                        ->get()
                        ->map(function ($item) {
                            $item->image_url = asset('asset/images/' . $item->image);
                            return $item;
                        });

                    $order->order_number = 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
                    return $order;
                });

            return response()->json(['success' => true, 'orders' => $orders]);

        } catch (\Exception $e) {
            Log::error('Admin get customer orders error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get orders'], 500);
        }
    }

    /**
     * Admin sidebar: Get a specific customer's customizations with materials
     * GET /admin/livechat/customer-customizations/{userId}
     */
    public function adminGetCustomerCustomizations($userId)
    {
        if (!session('admin_id')) {
            return response()->json(['success' => false, 'message' => 'Not authenticated'], 401);
        }

        try {
            $customizations = DB::table('product_customizations')
                ->join('products', 'product_customizations.product_id', '=', 'products.id')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->leftJoin('orders', 'product_customizations.order_id', '=', 'orders.id')
                ->where('product_customizations.user_id', $userId)
                ->orderBy('product_customizations.created_at', 'desc')
                ->take(20)
                ->select(
                    'product_customizations.id',
                    'product_customizations.order_id',
                    'product_customizations.customization_name',
                    'product_customizations.customization_details',
                    'product_customizations.special_instructions',
                    'product_customizations.custom_image',
                    'product_customizations.total_price',
                    'product_customizations.admin_price',
                    'product_customizations.admin_notes',
                    'product_customizations.status',
                    'product_customizations.created_at',
                    'products.name as product_name',
                    'products.image as product_image',
                    'categories.name as category_name'
                )
                ->get()
                ->map(function ($c) {
                    $c->ref = 'CUST-' . str_pad($c->id, 5, '0', STR_PAD_LEFT);
                    $c->order_number = $c->order_id
                        ? 'ORD-' . str_pad($c->order_id, 5, '0', STR_PAD_LEFT)
                        : null;

                    $c->image_url = $c->custom_image
                        ? asset('uploads/customizations/' . $c->custom_image)
                        : null;

                    $c->product_image_url = $c->product_image
                        ? asset('asset/images/' . $c->product_image)
                        : null;

                    // Load material options with JSON parsing
                    $c->options = DB::table('customization_options')
                        ->where('customization_id', $c->id)
                        ->get()
                        ->map(function ($opt) {
                            $decoded = json_decode($opt->option_value, true);
                            if (is_array($decoded)) {
                                $opt->parsed = $decoded;
                            } else {
                                $opt->parsed = [
                                    'label'      => $opt->option_value,
                                    'quantity'   => 1,
                                    'unit_price' => 0,
                                    'subtotal'   => (float) $opt->additional_price,
                                ];
                            }
                            $opt->additional_price = (float) $opt->additional_price;
                            return $opt;
                        });

                    $c->options_total           = $c->options->sum('additional_price');
                    $c->is_pending_approval     = in_array($c->status, ['Pending', 'Reviewing']);
                    $c->formatted_date          = \Carbon\Carbon::parse($c->created_at)->format('M d, Y');

                    return $c;
                });

            return response()->json(['success' => true, 'customizations' => $customizations]);

        } catch (\Exception $e) {
            Log::error('Admin get customer customizations error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to get customizations'], 500);
        }
    }

}