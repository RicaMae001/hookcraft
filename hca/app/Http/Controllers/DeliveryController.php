<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class DeliveryController extends Controller
{
    // Add notification to admin session
    private function addAdminNotification($orderId, $coordinatorName, $customerName, $oldStatus, $newStatus)
    {
        $notifications = Session::get('admin_notifications', []);
        
        $notification = [
            'id' => uniqid(),
            'type' => 'delivery_status_change',
            'title' => "Order #{$orderId} Status Updated",
            'message' => "{$coordinatorName} changed delivery status from '{$oldStatus}' to '{$newStatus}' for Order #{$orderId} (Customer: {$customerName})",
            'order_id' => $orderId,
            'coordinator_name' => $coordinatorName,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'is_read' => false
        ];
        
        array_unshift($notifications, $notification);
        $notifications = array_slice($notifications, 0, 50); // Keep last 50
        
        Session::put('admin_notifications', $notifications);
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

    // Update Delivery Status
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

        // Create notification for admin
        $this->addAdminNotification(
            $id,
            $coordinatorName,
            $order->customer_name,
            $oldStatus,
            $newStatus
        );

        return redirect()->back()->with('success', 'Delivery status updated successfully. Admin has been notified.');
    }

    // Upload GCash Payment Proof
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
                
                // Create notification for admin about payment proof upload
                $notifications = Session::get('admin_notifications', []);
                
                $notification = [
                    'id' => uniqid(),
                    'type' => 'payment_proof_uploaded',
                    'title' => "GCash Payment Proof Uploaded - Order #{$id}",
                    'message' => "{$coordinatorName} uploaded GCash payment proof for Order #{$id} (Customer: {$order->customer_name}). Payment status automatically updated to 'Paid'.",
                    'order_id' => $id,
                    'coordinator_name' => $coordinatorName,
                    'timestamp' => now()->format('Y-m-d H:i:s'),
                    'is_read' => false
                ];
                
                array_unshift($notifications, $notification);
                $notifications = array_slice($notifications, 0, 50);
                Session::put('admin_notifications', $notifications);
                
                // Verify the update was successful
                $verifyOrder = DB::table('orders')->where('id', $id)->first();
                
                if ($verifyOrder->payment_proof === $filename && $verifyOrder->payment_status === 'Paid') {
                    return back()->with('success', 'Payment proof uploaded successfully! Payment status updated to Paid. Admin has been notified.');
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