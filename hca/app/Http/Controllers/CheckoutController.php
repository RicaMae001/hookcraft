<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Check if this is a "Buy Now" checkout or regular cart checkout
        $isBuyNow = session('is_buy_now_checkout', false);
        
        Log::info('Checkout Index', [
            'user_id' => Auth::id(),
            'is_buy_now' => $isBuyNow,
            'session_id' => session()->getId()
        ]);
        
        // Get appropriate cart based on checkout type
        $cart = Cart::where('user_id', Auth::id())
            ->where('is_buy_now', $isBuyNow ? 1 : 0)
            ->latest()
            ->first();

        Log::info('Cart retrieved', [
            'cart_found' => $cart ? true : false,
            'cart_id' => $cart ? $cart->id : null,
            'is_buy_now' => $cart ? $cart->is_buy_now : null
        ]);

        if (!$cart) {
            Log::warning('No cart found for checkout', [
                'user_id' => Auth::id(),
                'is_buy_now' => $isBuyNow
            ]);
            
            return view('pages.checkout', [
                'cartItems' => collect(),
                'total' => 0,
                'cartCount' => 0,
                'isBuyNow' => $isBuyNow
            ]);
        }

        $cartItems = CartItem::with('product')
            ->where('cart_id', $cart->id)
            ->get();

        Log::info('Cart items retrieved', [
            'items_count' => $cartItems->count()
        ]);

        $total = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);

        // Get cart count for navbar (from regular cart only)
        $regularCart = Cart::where('user_id', Auth::id())
            ->where('is_buy_now', 0)
            ->first();
        
        $cartCount = $regularCart 
            ? CartItem::where('cart_id', $regularCart->id)->sum('quantity')
            : 0;

        return view('pages.checkout', compact('cartItems', 'total', 'cartCount', 'isBuyNow'));
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

        // Check if this is a buy-now checkout
        $isBuyNow = session('is_buy_now_checkout', false);
        
        Log::info('Checkout Store', [
            'user_id' => Auth::id(),
            'is_buy_now' => $isBuyNow
        ]);
        
        // Get appropriate cart
        $cart = Cart::where('user_id', Auth::id())
            ->where('is_buy_now', $isBuyNow ? 1 : 0)
            ->latest()
            ->first();

        if (!$cart) {
            Log::warning('No cart found during checkout store');
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = CartItem::with('product')->where('cart_id', $cart->id)->get();

        if ($cartItems->isEmpty()) {
            Log::warning('Cart items empty during checkout store');
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

        DB::beginTransaction();

        try {
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

            Log::info('Order created', ['order_id' => $order->id]);

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

            // Delete the cart (buy-now or regular cart that was used)
            $cart->delete();
            
            Log::info('Cart deleted after order', [
                'cart_id' => $cart->id,
                'was_buy_now' => $isBuyNow
            ]);
            
            // Clear buy-now session flag
            session()->forget('is_buy_now_checkout');
            
            // Update cart count in session (from regular cart)
            $regularCart = Cart::where('user_id', Auth::id())
                ->where('is_buy_now', 0)
                ->first();
            
            $cartCount = $regularCart 
                ? CartItem::where('cart_id', $regularCart->id)->sum('quantity')
                : 0;
                
            session(['cart_count' => $cartCount]);

            DB::commit();

            Log::info('Checkout completed successfully', ['order_id' => $order->id]);

            // Redirect to thank you page
            return redirect()->route('thankyou', ['order_id' => $order->id])
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Checkout error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to process order. Please try again.');
        }
    }

    /**
     * Display the thank you page with order details
     */
    public function thankYou($order_id)
    {
        try {
            // Load order with relationships
            $order = Order::with(['orderItems.product', 'orderItems.category'])
                ->findOrFail($order_id);
            
            // Optional: Check if the order belongs to the logged-in user
            if (Auth::check() && $order->user_id !== Auth::id()) {
                Log::warning('Unauthorized access to order', [
                    'order_id' => $order_id,
                    'user_id' => Auth::id(),
                    'order_user_id' => $order->user_id
                ]);
                abort(403, 'Unauthorized access to this order.');
            }
            
            Log::info('Thank you page accessed', [
                'order_id' => $order_id,
                'user_id' => Auth::id()
            ]);
            
            // Get cart count for navbar
            $cartCount = 0;
            if (Auth::check()) {
                $regularCart = Cart::where('user_id', Auth::id())
                    ->where('is_buy_now', 0)
                    ->first();
                
                $cartCount = $regularCart 
                    ? CartItem::where('cart_id', $regularCart->id)->sum('quantity')
                    : 0;
            }
            
            return view('pages.thankyou', [
                'order' => $order,
                'order_id' => $order_id,
                'cartCount' => $cartCount
            ]);
            
        } catch (\Exception $e) {
            Log::error('Thank you page error', [
                'order_id' => $order_id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('shop')
                ->with('error', 'Order not found or an error occurred.');
        }
    }
}