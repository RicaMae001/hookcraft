<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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

    // Delivery logout
    public function logout()
    {
        session()->forget(['coordinator_id', 'coordinator_name', 'coordinator_email', 'user_type']);
        return redirect()->route('staff.login');
    }

    // Dashboard
    public function dashboard()
    {
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
        $validated = $request->validate([
            'delivery_status' => 'required|in:Pending,Out for Delivery,Delivered,Cancelled',
        ]);

        $coordinatorId = session('coordinator_id');
        $coordinatorName = session('coordinator_name');
        
        // Get old status for logging
        $order = DB::table('orders')->where('id', $id)->first();
        
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
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

    // ⭐ FIXED METHOD - Upload GCash Payment Proof
    public function uploadPaymentProof(Request $request, $id)
    {
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
                
                // ⭐ CRITICAL FIX: Update order with payment proof AND set payment status to Paid
                $updated = DB::table('orders')
                    ->where('id', $id)
                    ->update([
                        'payment_proof' => $filename,
                        'payment_status' => 'Paid'
                    ]);
                
                // ⭐ DEBUG: Log the update result
                if ($updated) {
                    \Log::info("Payment proof updated for Order #{$id}", [
                        'filename' => $filename,
                        'payment_status' => 'Paid',
                        'coordinator_id' => $coordinatorId
                    ]);
                } else {
                    \Log::error("Failed to update payment proof for Order #{$id}");
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
                
                // ⭐ VERIFY the update was successful
                $verifyOrder = DB::table('orders')->where('id', $id)->first();
                
                if ($verifyOrder->payment_proof === $filename && $verifyOrder->payment_status === 'Paid') {
                    return back()->with('success', 'Payment proof uploaded successfully! Payment status updated to Paid. Admin has been notified.');
                } else {
                    return back()->with('error', 'File uploaded but database update failed. Please contact administrator.');
                }
            }
            
            return back()->with('error', 'No file was uploaded. Please try again.');
            
        } catch (\Exception $e) {
            \Log::error("Payment proof upload error for Order #{$id}: " . $e->getMessage());
            return back()->with('error', 'Error uploading payment proof: ' . $e->getMessage());
        }
    }

    // Delivery History/Logs
    public function history()
    {
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