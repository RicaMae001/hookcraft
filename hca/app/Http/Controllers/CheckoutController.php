<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first to checkout.');
        }

        $cart = Cart::where('user_id', Auth::id())->latest()->first();

        if (!$cart) {
            return view('pages.checkout', [
                'cartItems' => collect(),
                'total' => 0,
                'cartCount' => 0
            ]);
        }

        $cartItems = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        // Get cart count for navbar
        $cartCount = $cartItems->sum('quantity');

        return view('pages.checkout', compact('cartItems', 'total', 'cartCount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'region_id'   => 'required|integer|exists:regions,id',
            'province_id' => 'required|integer|exists:provinces,id',
            'city_id'     => 'required|integer|exists:cities,id',
            'barangay_id' => 'required|integer|exists:barangays,id',
            'street'      => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'payment_method' => 'required|string|in:GCash,COD',
        ]);

        $cart = Cart::where('user_id', Auth::id())->latest()->first();

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate stock availability
        foreach ($cartItems as $item) {
            if ($item->quantity > $item->product->stock) {
                return redirect()->back()->with('error', "Insufficient stock for {$item->product->name}");
            }
        }

        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        // Get location details
        $region = DB::table('regions')->where('id', $request->region_id)->value('region_name');
        $province = DB::table('provinces')->where('id', $request->province_id)->value('province_name');
        $city = DB::table('cities')->where('id', $request->city_id)->value('city_name');
        $barangay = DB::table('barangays')->where('id', $request->barangay_id)->value('barangay_name');

        // Format complete address
        $fullAddress = sprintf(
            '%s, %s, %s, %s, %s',
            $region,
            $province,
            $city,
            $barangay,
            $request->street
        );

        // Create order
        $order = Order::create([
            'user_id'        => Auth::id(),
            'customer_name'  => $request->name,
            'address'        => $fullAddress,
            'phone'          => $request->phone,
            'total'          => $total,
            'payment_status' => 'Pending',
            'payment_method' => $request->payment_method,
            'delivery_status'=> 'Pending',
        ]);

        // Create order items and reduce stock
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'    => $order->id,
                'product_id'  => $item->product_id,
                'category_id' => $item->product->category_id ?? null,
                'quantity'    => $item->quantity,
                'price'       => $item->product->price,
            ]);

            // Reduce product stock
            $item->product->decrement('stock', $item->quantity);
        }

        // Clear cart
        $cart->delete();

        // Redirect to thank you page
        return redirect()->route('thankyou', ['order_id' => $order->id])
            ->with('success', 'Order placed successfully!');
    }
}