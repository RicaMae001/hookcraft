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

    // Add notification to admin - FIXED VERSION
    private function addAdminNotification($orderId, $coordinatorName, $customerName, $oldStatus, $newStatus)
    {
        // Use the correct NotificationService method: create()
        $this->notificationService->create([
            'recipient_type' => 'admin',
            'recipient_id' => null, // null = broadcast to all admins
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

    // ===== NEW: Add notification to USER (customer) =====
    private function addUserNotification($order, $oldStatus, $newStatus)
    {
        // Only notify if order has a user_id (registered user)
        if (!$order->user_id) {
            Log::info('Skipping user notification - no user_id for order', ['order_id' => $order->id]);
            return;
        }

        try {
            // Determine notification details based on status
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

            // Create notification using NotificationService
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

    // Add notification for payment proof upload - FIXED VERSION
    private function addPaymentProofNotification($orderId, $coordinatorName, $customerName)
    {
        $this->notificationService->create([
            'recipient_type' => 'admin',
            'recipient_id' => null,
            'sender_type' => 'delivery',
            'sender_id' => session('coordinator_id'),
            'type' => 'payment_proof_uploaded',
            'title' => "GCash Payment Proof Uploaded - Order #{$orderId}",
            'message' => "{$coordinatorName} uploaded GCash payment proof for Order #{$orderId} (Customer: {$customerName}). Payment status automatically updated to 'Paid'.",
            'entity_type' => 'order',
            'entity_id' => $orderId,
            'action_url' => "/admin/orders/{$orderId}",
            'priority' => 'high',
            'metadata' => [
                'order_id' => $orderId,
                'coordinator_name' => $coordinatorName,
                'customer_name' => $customerName,
            ]
        ]);
    }

    // ===== NEW: Notify user when payment is confirmed =====
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
                'message' => "Your GCash payment for order #{$order->id} has been confirmed. Thank you!",
                'entity_type' => 'order',
                'entity_id' => $order->id,
                'action_url' => '/profile/track-order',
                'priority' => 'high',
                'metadata' => [
                    'order_id' => $order->id,
                    'payment_method' => 'GCash',
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

    // Login with role-based verification
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Check if delivery coordinator exists
        $coordinator = DB::table('delivery_coordinator')
            ->where('email', $credentials['email'])
            ->first();
        
        if ($coordinator) {
            // Verify it's a Delivery role
            if ($coordinator->role !== 'Delivery') {
                Log::warning('Invalid delivery coordinator role attempting login', [
                    'email' => $credentials['email'],
                    'role' => $coordinator->role
                ]);
                return back()->withErrors(['email' => 'Invalid credentials']);
            }

            // Check if account is active
            if ($coordinator->status !== 'Active') {
                Log::warning('Inactive delivery coordinator attempted login', [
                    'email' => $credentials['email'],
                    'coordinator_id' => $coordinator->coordinator_id
                ]);
                return back()->withErrors(['email' => 'Your account is inactive. Please contact the administrator.']);
            }

            // Verify password
            if (Hash::check($credentials['password'], $coordinator->password)) {
                // Set session for delivery coordinator
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

        // Check if it's a regular user trying to login here
        $user = DB::table('users')
            ->where('email', $credentials['email'])
            ->first();
        
        if ($user) {
            Log::warning('Regular user attempted delivery login', [
                'email' => $credentials['email']
            ]);
            return back()->withErrors([
                'email' => 'Please use the customer login page.'
            ]);
        }

        Log::warning('Failed delivery login attempt', ['email' => $credentials['email']]);
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Delivery logout with role logging
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

    // Middleware helper: Check if user has 'Delivery' role
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

        // Check if account is still active
        if ($coordinator->status !== 'Active') {
            session()->forget(['coordinator_id', 'coordinator_name', 'coordinator_email', 'user_type', 'coordinator_role']);
            return redirect()->route('staff.login')->with('error', 'Your account has been deactivated');
        }

        return null; // Continue
    }

    // Dashboard
    public function dashboard()
    {
        // Check role authorization
        $roleCheck = $this->checkDeliveryRole();
        if ($roleCheck) return $roleCheck;

        $coordinatorId = session('coordinator_id');

        // Statistics
        $totalDeliveries = DB::table('orders')
            ->where('coordinator_id', $coordinatorId)
            ->count();

        $pendingDeliveries = DB::table('orders')
            ->where('coordinator_id', $coordinatorId)
            ->where('delivery_status', 'Pending')
            ->count();

        $outForDelivery = DB::table('orders')
            ->where('coordinator_id', $coordinatorId)
            ->where('delivery_status', 'Out for Delivery')
            ->count();

        $completedDeliveries = DB::table('orders')
            ->where('coordinator_id', $coordinatorId)
            ->where('delivery_status', 'Delivered')
            ->count();

        // Recent Deliveries
        $recentDeliveries = DB::table('orders')
            ->where('coordinator_id', $coordinatorId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // All Deliveries for grid view
        $deliveries = DB::table('orders')
            ->where('coordinator_id', $coordinatorId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Daily Deliveries Chart (last 7 days)
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

    // All Deliveries
    public function deliveries()
    {
        // Check role authorization
        $roleCheck = $this->checkDeliveryRole();
        if ($roleCheck) return $roleCheck;

        $coordinatorId = session('coordinator_id');
        
        $deliveries = DB::table('orders')
            ->where('coordinator_id', $coordinatorId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.delivery.deliveries', compact('deliveries'));
    }

    // Update Delivery Status - NOW WITH USER NOTIFICATIONS
    public function updateStatus(Request $request, $id)
    {
        // Check role authorization
        $roleCheck = $this->checkDeliveryRole();
        if ($roleCheck) return $roleCheck;

        $validated = $request->validate([
            'delivery_status' => 'required|in:Pending,Out for Delivery,Delivered,Cancelled',
        ]);

        $coordinatorId = session('coordinator_id');
        $coordinatorName = session('coordinator_name');
        
        // Get old status for logging
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('coordinator_id', $coordinatorId) // Verify ownership
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

        // Don't update if status is the same
        if ($oldStatus === $newStatus) {
            return redirect()->back()->with('info', 'Status unchanged');
        }
        
        // Update order status
        DB::table('orders')->where('id', $id)->update([
            'delivery_status' => $validated['delivery_status'],
        ]);

        // Log the status change
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

        // ===== NOTIFY ADMIN =====
        $this->addAdminNotification(
            $id,
            $coordinatorName,
            $order->customer_name,
            $oldStatus,
            $newStatus
        );

        // ===== NOTIFY USER (CUSTOMER) =====
        $this->addUserNotification($order, $oldStatus, $newStatus);

        return redirect()->back()->with('success', 'Delivery status updated successfully. Customer and admin have been notified.');
    }

    // Upload GCash Payment Proof - NOW WITH USER NOTIFICATION
    public function uploadPaymentProof(Request $request, $id)
    {
        // Check role authorization
        $roleCheck = $this->checkDeliveryRole();
        if ($roleCheck) return $roleCheck;

        // Validate the uploaded file
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        try {
            $coordinatorId = session('coordinator_id');
            $coordinatorName = session('coordinator_name');
            
            // Check if order exists and is assigned to this coordinator
            $order = DB::table('orders')
                ->where('id', $id)
                ->where('coordinator_id', $coordinatorId)
                ->first();
            
            if (!$order) {
                Log::warning('Unauthorized payment proof upload attempt', [
                    'coordinator_id' => $coordinatorId,
                    'order_id' => $id
                ]);
                return back()->with('error', 'Order not found or not assigned to you');
            }

            // Verify it's a GCash order
            if ($order->payment_method !== 'GCash') {
                return back()->with('error', 'This order is not a GCash payment');
            }

            // Check if already paid
            if ($order->payment_status === 'Paid' && $order->payment_proof) {
                return back()->with('info', 'Payment proof already uploaded and marked as paid');
            }

            // Handle file upload
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = 'gcash_proof_order_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Create uploads/payments directory if it doesn't exist
                $uploadPath = public_path('uploads/payments');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Delete old payment proof if exists
                if ($order->payment_proof) {
                    $oldFilePath = $uploadPath . '/' . $order->payment_proof;
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
                
                // Move file to public/uploads/payments
                $file->move($uploadPath, $filename);
                
                // Update order with payment proof AND set payment status to Paid
                $updated = DB::table('orders')
                    ->where('id', $id)
                    ->update([
                        'payment_proof' => $filename,
                        'payment_status' => 'Paid'
                    ]);
                
                // Log the update result
                if ($updated) {
                    Log::info("Payment proof updated for Order #{$id}", [
                        'filename' => $filename,
                        'payment_status' => 'Paid',
                        'coordinator_id' => $coordinatorId,
                        'role' => 'Delivery'
                    ]);
                } else {
                    Log::error("Failed to update payment proof for Order #{$id}");
                }
                
                // ===== NOTIFY ADMIN =====
                $this->addPaymentProofNotification($id, $coordinatorName, $order->customer_name);
                
                // ===== NOTIFY USER (CUSTOMER) =====
                $this->addUserPaymentNotification($order);
                
                // Verify the update was successful
                $verifyOrder = DB::table('orders')->where('id', $id)->first();
                
                if ($verifyOrder->payment_proof === $filename && $verifyOrder->payment_status === 'Paid') {
                    return back()->with('success', 'Payment proof uploaded successfully! Payment status updated to Paid. Customer and admin have been notified.');
                } else {
                    return back()->with('error', 'File uploaded but database update failed. Please contact administrator.');
                }
            }
            
            return back()->with('error', 'No file was uploaded. Please try again.');
            
        } catch (\Exception $e) {
            Log::error("Payment proof upload error for Order #{$id}: " . $e->getMessage(), [
                'coordinator_id' => $coordinatorId,
                'role' => 'Delivery'
            ]);
            return back()->with('error', 'Error uploading payment proof: ' . $e->getMessage());
        }
    }

    // Delivery History/Logs
    public function history()
    {
        // Check role authorization
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