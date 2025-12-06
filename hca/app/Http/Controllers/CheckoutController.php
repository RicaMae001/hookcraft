<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                'total' => 0
            ]);
        }

        $cartItems = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        return view('pages.checkout', compact('cartItems', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'region'  => 'required|string|max:255',
            'province'=> 'required|string|max:255',
            'city'    => 'required|string|max:255',
            'barangay'=> 'required|string|max:255',
            'street'  => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        $cart = Cart::where('user_id', Auth::id())->latest()->first();

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        // Create order
        $order = Order::create([
            'user_id'       => Auth::id(),
            'customer_name' => $request->name,
            'address'       => $request->region . ', ' . $request->province . ', ' . $request->city . ', ' . $request->barangay . ', ' . $request->street,
            'phone'         => $request->phone,
            'total'         => $total,
            'payment_status'=> 'Pending',
            'payment_method'=> $request->payment_method, // <-- Add this line
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'    => $order->id,
                'product_id'  => $item->product_id,
                'category_id' => $item->product->category_id ?? null,
                'quantity'    => $item->quantity,
                'price'       => $item->product->price,
            ]);
        }

        // Clear cart
        $cart->delete();

        // ✅ Redirect to thankyou page
        return redirect()->route('thankyou', ['order_id' => $order->id]);
    }
}
