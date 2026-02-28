<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;

class DeliveryController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    private function addAdminNotification($orderId, $coordinatorName, $customerName, $oldStatus, $newStatus)
    {
        $this->notificationService->create([
            'recipient_type' => 'admin',
            'recipient_id' => null,
            'sender_type' => 'delivery',
            'sender_id' => session('coordinator_id'),
            'type' => 'delivery_status_changed',
            'title' => "Order #{$orderId} Status Updated",
            'message' => "{$coordinatorName} changed delivery status from '{$oldStatus}' to '{$newStatus}' for Order #{$orderId} (Customer: {$customerName})",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'normal',
            'metadata' => [
                'order_id' => $orderId,
                'coordinator_name' => $coordinatorName,
                'customer_name' => $customerName,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]
        ]);
    }

    private function addUserNotification($order, $oldStatus, $newStatus)
    {
        if (!$order->user_id) {
            Log::info('Skipping user notification - no user_id for order', ['order_id' => $order->id]);
            return;
        }

        try {
            $title = 'Delivery Status Update';
            $message = "Your order #{$order->id} delivery status: {$oldStatus} → {$newStatus}";
            $priority = 'normal';

            if ($newStatus === 'Out for Delivery') {
                $title = 'Order is Out for Delivery! 🚚';
                $message = "Great news! Your order #{$order->id} is now out for delivery and will arrive soon.";
                $priority = 'high';
            } elseif ($newStatus === 'Delivered') {
                $title = 'Order Delivered Successfully! ✓';
                $message = "Your order #{$order->id} has been delivered. Thank you for your purchase!";
                $priority = 'high';
            } elseif ($newStatus === 'Cancelled') {
                $title = 'Delivery Cancelled';
                $message = "The delivery for order #{$order->id} has been cancelled.";
                $priority = 'high';
            }

            $this->notificationService->create([
                'recipient_type' => 'user',
                'recipient_id' => $order->user_id,
                'sender_type' => 'delivery',
                'sender_id' => session('coordinator_id'),
                'type' => 'delivery_status_changed',
                'title' => $title,
                'message' => $message,
                'entity_type' => 'order',
                'entity_id' => $order->id,
                'action_url' => '/profile/track-order',
                'priority' => $priority,
                'metadata' => [
                    'order_id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                    'coordinator_id' => session('coordinator_id'),
                    'coordinator_name' => session('coordinator_name'),
                ]
            ]);

            Log::info('User notified of delivery update', [
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

        } catch (\Exception $e) {
            Log::error('Error notifying user of delivery update', [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'error' => $e->getMessage()
            ]);
        }
    }

    // Notify admin of payment proof upload — works for any payment method
    private function addPaymentProofNotification($orderId, $coordinatorName, $customerName, $paymentMethod)
    {
        $this->notificationService->create([
            'recipient_type' => 'admin',
            'recipient_id' => null,
            'sender_type' => 'delivery',
            'sender_id' => session('coordinator_id'),
            'type' => 'payment_proof_uploaded',
            'title' => "Payment Proof Uploaded - Order #{$orderId}",
            'message' => "{$coordinatorName} uploaded payment proof for Order #{$orderId} (Customer: {$customerName}, Method: {$paymentMethod}). Payment status automatically updated to 'Paid'.",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'high',
            'metadata' => [
                'order_id' => $orderId,
                'coordinator_name' => $coordinatorName,
                'customer_name' => $customerName,
                'payment_method' => $paymentMethod,
            ]
        ]);
    }

    private function addUserPaymentNotification($order)
    {
        if (!$order->user_id) {
            return;
        }

        try {
            $this->notificationService->create([
                'recipient_type' => 'user',
                'recipient_id' => $order->user_id,
                'sender_type' => 'delivery',
                'sender_id' => session('coordinator_id'),
                'type' => 'payment_received',
                'title' => 'Payment Confirmed ✓',
                'message' => "Your payment for order #{$order->id} has been confirmed. Thank you!",
                'entity_type' => 'order',
                'entity_id' => $order->id,
                'action_url' => '/profile/track-order',
                'priority' => 'high',
                'metadata' => [
                    'order_id' => $order->id,
                    'payment_method' => $order->payment_method,
                    'customer_name' => $order->customer_name,
                ]
            ]);

            Log::info('User notified of payment confirmation', [
                'user_id' => $order->user_id,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error notifying user of payment confirmation', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $coordinator = DB::table('delivery_coordinator')
            ->where('email', $credentials['email'])
            ->first();
        
        if ($coordinator) {
            if ($coordinator->role !== 'Delivery') {
                Log::warning('Invalid delivery coordinator role attempting login', [
                    'email' => $credentials['email'],
                    'role' => $coordinator->role
                ]);
                return back()->withErrors(['email' => 'Invalid credentials']);
            }

            if ($coordinator->status !== 'Active') {
                Log::warning('Inactive delivery coordinator attempted login', [
                    'email' => $credentials['email'],
                    'coordinator_id' => $coordinator->coordinator_id
                ]);
                return back()->withErrors(['email' => 'Your account is inactive. Please contact the administrator.']);
            }

            if (Hash::check($credentials['password'], $coordinator->password)) {
                session([
                    'coordinator_id' => $coordinator->coordinator_id,
                    'coordinator_name' => $coordinator->name,
                    'coordinator_email' => $coordinator->email,
                    'user_type' => 'Delivery',
                    'coordinator_role' => $coordinator->role
                ]);

                Log::info('Delivery coordinator logged in successfully', [
                    'coordinator_id' => $coordinator->coordinator_id,
                    'role' => 'Delivery'
                ]);

                $request->session()->regenerate();
                return redirect()->route('delivery.dashboard')->with('success', 'Welcome back, ' . $coordinator->name);
            }
        }

        $user = DB::table('users')
            ->where('email', $credentials['email'])
            ->first();
        
        if ($user) {
            Log::warning('Regular user attempted delivery login', ['email' => $credentials['email']]);
            return back()->withErrors(['email' => 'Please use the customer login page.']);
        }

        Log::warning('Failed delivery login attempt', ['email' => $credentials['email']]);
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        $coordinatorId = session('coordinator_id');
        $coordinatorName = session('coordinator_name');
        $userType = session('user_type');

        Log::info('Delivery coordinator logging out', [
            'coordinator_id' => $coordinatorId,
            'coordinator_name' => $coordinatorName,
            'role' => $userType
        ]);

        session()->forget(['coordinator_id', 'coordinator_name', 'coordinator_email', 'user_type', 'coordinator_role']);
        session()->regenerate();
        
        return redirect()->route('staff.login')->with('success', 'Logged out successfully');
    }

    private function checkDeliveryRole()
    {
        if (!session('coordinator_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        $coordinator = DB::table('delivery_coordinator')
            ->where('coordinator_id', session('coordinator_id'))
            ->first();

        if (!$coordinator || $coordinator->role !== 'Delivery') {
            session()->forget(['coordinator_id', 'coordinator_name', 'coordinator_email', 'user_type', 'coordinator_role']);
            return redirect()->route('staff.login')->with('error', 'Unauthorized access');
        }

        if ($coordinator->status !== 'Active') {
            session()->forget(['coordinator_id', 'coordinator_name', 'coordinator_email', 'user_type', 'coordinator_role']);
            return redirect()->route('staff.login')->with('error', 'Your account has been deactivated');
        }

        return null;
    }

    public function dashboard()
    {
        $roleCheck = $this->checkDeliveryRole();
        if ($roleCheck) return $roleCheck;

        $coordinatorId = session('coordinator_id');

        $totalDeliveries = DB::table('orders')->where('coordinator_id', $coordinatorId)->count();
        $pendingDeliveries = DB::table('orders')->where('coordinator_id', $coordinatorId)->where('delivery_status', 'Pending')->count();
        $outForDelivery = DB::table('orders')->where('coordinator_id', $coordinatorId)->where('delivery_status', 'Out for Delivery')->count();
        $completedDeliveries = DB::table('orders')->where('coordinator_id', $coordinatorId)->where('delivery_status', 'Delivered')->count();

        $recentDeliveries = DB::table('orders')->where('coordinator_id', $coordinatorId)->orderBy('created_at', 'desc')->limit(10)->get();
        $deliveries = DB::table('orders')->where('coordinator_id', $coordinatorId)->orderBy('created_at', 'desc')->get();

        $dailyDeliveries = DB::table('orders')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('coordinator_id', $coordinatorId)
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('admin.delivery.dashboard', compact(
            'totalDeliveries', 'pendingDeliveries', 'outForDelivery',
            'completedDeliveries', 'recentDeliveries', 'dailyDeliveries', 'deliveries'
        ));
    }

    public function deliveries()
    {
        $roleCheck = $this->checkDeliveryRole();
        if ($roleCheck) return $roleCheck;

        $coordinatorId = session('coordinator_id');
        
        $deliveries = DB::table('orders')
            ->where('coordinator_id', $coordinatorId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.delivery.deliveries', compact('deliveries'));
    }

    public function updateStatus(Request $request, $id)
    {
        $roleCheck = $this->checkDeliveryRole();
        if ($roleCheck) return $roleCheck;

        $validated = $request->validate([
            'delivery_status' => 'required|in:Pending,Out for Delivery,Delivered,Cancelled',
        ]);

        $coordinatorId = session('coordinator_id');
        $coordinatorName = session('coordinator_name');
        
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('coordinator_id', $coordinatorId)
            ->first();
        
        if (!$order) {
            Log::warning('Unauthorized delivery status update attempt', [
                'coordinator_id' => $coordinatorId,
                'order_id' => $id
            ]);
            return redirect()->back()->with('error', 'Order not found or not assigned to you');
        }

        $oldStatus = $order->delivery_status;
        $newStatus = $validated['delivery_status'];

        if ($oldStatus === $newStatus) {
            return redirect()->back()->with('info', 'Status unchanged');
        }
        
        DB::table('orders')->where('id', $id)->update([
            'delivery_status' => $validated['delivery_status'],
        ]);

        DB::table('delivery_logs')->insert([
            'order_id' => $id,
            'coordinator_id' => $coordinatorId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'updated_at' => now(),
        ]);

        Log::info('Delivery status updated', [
            'order_id' => $id,
            'coordinator_id' => $coordinatorId,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);

        $this->addAdminNotification($id, $coordinatorName, $order->customer_name, $oldStatus, $newStatus);
        $this->addUserNotification($order, $oldStatus, $newStatus);

        return redirect()->back()->with('success', 'Delivery status updated successfully. Customer and admin have been notified.');
    }

    // Upload Payment Proof — works for ALL payment methods (COD, GCash, etc.)
    public function uploadPaymentProof(Request $request, $id)
    {
        $roleCheck = $this->checkDeliveryRole();
        if ($roleCheck) return $roleCheck;

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            $coordinatorId = session('coordinator_id');
            $coordinatorName = session('coordinator_name');
            
            // Check order exists and belongs to this coordinator
            $order = DB::table('orders')
                ->where('id', $id)
                ->where('coordinator_id', $coordinatorId)
                ->first();
            
            if (!$order) {
                Log::warning('Unauthorized payment proof upload attempt', [
                    'coordinator_id' => $coordinatorId,
                    'order_id' => $id
                ]);
                return response()->json(['error' => 'Order not found or not assigned to you'], 403);
            }

            // Handle file upload
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = 'proof_order_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                $uploadPath = public_path('uploads/payments');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Delete old proof if exists
                if ($order->payment_proof) {
                    $oldFilePath = $uploadPath . '/' . $order->payment_proof;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                
                $file->move($uploadPath, $filename);
                
                DB::table('orders')
                    ->where('id', $id)
                    ->update([
                        'payment_proof'  => $filename,
                        'payment_status' => 'Paid',
                    ]);

                Log::info("Payment proof uploaded for Order #{$id}", [
                    'filename'       => $filename,
                    'payment_method' => $order->payment_method,
                    'coordinator_id' => $coordinatorId,
                ]);
                
                $this->addPaymentProofNotification($id, $coordinatorName, $order->customer_name, $order->payment_method);
                $this->addUserPaymentNotification($order);

                // Return JSON for fetch() calls, redirect for regular form posts
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => 'Payment proof uploaded successfully!']);
                }

                return back()->with('success', 'Payment proof uploaded successfully! Payment status updated to Paid.');
            }
            
            return response()->json(['error' => 'No file was uploaded. Please try again.'], 422);
            
        } catch (\Exception $e) {
            Log::error("Payment proof upload error for Order #{$id}: " . $e->getMessage());

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['error' => 'Upload failed: ' . $e->getMessage()], 500);
            }

            return back()->with('error', 'Error uploading payment proof: ' . $e->getMessage());
        }
    }

    public function history()
    {
        $roleCheck = $this->checkDeliveryRole();
        if ($roleCheck) return $roleCheck;

        $coordinatorId = session('coordinator_id');
        
        $logs = DB::table('delivery_logs')
            ->join('orders', 'delivery_logs.order_id', '=', 'orders.id')
            ->where('delivery_logs.coordinator_id', $coordinatorId)
            ->select('delivery_logs.*', 'orders.customer_name', 'orders.address')
            ->orderBy('delivery_logs.updated_at', 'desc')
            ->get();

        return view('admin.delivery.history', compact('logs'));
    }
}