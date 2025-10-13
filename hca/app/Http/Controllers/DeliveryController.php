<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
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
            'completedDeliveries', 'recentDeliveries', 'dailyDeliveries'
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
        
        // Get old status for logging
        $order = DB::table('orders')->where('id', $id)->first();
        
        // Update order status
        DB::table('orders')->where('id', $id)->update([
            'delivery_status' => $validated['delivery_status'],
        ]);

        // Log the status change
        DB::table('delivery_logs')->insert([
            'order_id' => $id,
            'coordinator_id' => $coordinatorId,
            'old_status' => $order->delivery_status,
            'new_status' => $validated['delivery_status'],
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Delivery status updated successfully');
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