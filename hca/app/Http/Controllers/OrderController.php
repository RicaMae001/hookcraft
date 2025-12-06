<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Only allow authenticated users
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Cancel order (for customer)
    public function cancel(Request $request, $id)
    {
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Order not found.');
        }

        // Only allow cancel if status is exactly "Pending"
        if ($order->delivery_status !== 'Pending') {
            return back()->with('error', 'Order can only be cancelled while it is still being processed.');
        }

        DB::table('orders')->where('id', $id)->update([
            'delivery_status' => 'Cancelled'
        ]);

        // Optionally restore stock if paid
        if ($order->payment_status === 'Paid') {
            $orderItems = DB::table('order_item')->where('order_id', $id)->get();
            foreach ($orderItems as $item) {
                DB::table('products')->where('id', $item->product_id)->increment('stock', $item->quantity);
            }
        }

        return back()->with('success', 'Order has been cancelled.');
    }
}