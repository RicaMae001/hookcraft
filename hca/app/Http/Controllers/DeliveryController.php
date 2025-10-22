<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session; // ⭐ ADD THIS LINE

class DeliveryController extends Controller
{
    // ⭐ ADD THIS NEW METHOD - Add notification to admin session
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

        // ⭐ ADD THIS - All Deliveries for grid view
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

        // ⭐ ADD 'deliveries' to compact()
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

    // Update Delivery Status - ⭐ UPDATED WITH NOTIFICATION
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'delivery_status' => 'required|in:Pending,Out for Delivery,Delivered,Cancelled',
        ]);

        $coordinatorId = session('coordinator_id');
        $coordinatorName = session('coordinator_name'); // ⭐ ADD THIS LINE
        
        // Get old status for logging
        $order = DB::table('orders')->where('id', $id)->first();
        
        // ⭐ ADD THIS CHECK
        if (!$order) {
            return redirect()->back()->with('error', 'Order not found');
        }

        $oldStatus = $order->delivery_status; // ⭐ STORE OLD STATUS
        $newStatus = $validated['delivery_status']; // ⭐ STORE NEW STATUS

        // ⭐ ADD THIS CHECK - Don't update if status is the same
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
            'old_status' => $oldStatus, // ⭐ CHANGED FROM $order->delivery_status
            'new_status' => $newStatus, // ⭐ CHANGED FROM $validated['delivery_status']
            'updated_at' => now(),
        ]);

        // ⭐ ADD THIS - Create notification for admin
        $this->addAdminNotification(
            $id,
            $coordinatorName,
            $order->customer_name,
            $oldStatus,
            $newStatus
        );

        // ⭐ UPDATED MESSAGE
        return redirect()->back()->with('success', 'Delivery status updated successfully. Admin has been notified.');
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