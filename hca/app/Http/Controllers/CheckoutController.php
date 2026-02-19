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
use App\Helpers\NotificationHelper;
use App\Services\NotificationService;

class CheckoutController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display the checkout page (handles both regular products and customizations)
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first to checkout.');
        }

        $isBuyNow = session('is_buy_now_checkout', false);
        
        $cart = Cart::where('user_id', Auth::id())
            ->where('is_buy_now', $isBuyNow ? 1 : 0)
            ->latest()
            ->first();

        if (!$cart) {
            return view('pages.checkout', [
                'cartItems' => collect(),
                'total' => 0,
                'cartCount' => 0,
                'isBuyNow' => $isBuyNow
            ]);
        }

        // Load cart items with product AND customization relationships
        $cartItems = CartItem::with(['product', 'customization', 'customization.product'])
            ->where('cart_id', $cart->id)
            ->get();

        // Calculate total considering both regular products and customizations
        $total = $cartItems->sum(function($item) {
            if ($item->is_customization && $item->customization) {
                return $item->quantity * $item->customization->admin_price;
            }
            return $item->quantity * $item->product->price;
        });

        $regularCart = Cart::where('user_id', Auth::id())
            ->where('is_buy_now', 0)
            ->first();
        
        $cartCount = $regularCart 
            ? CartItem::where('cart_id', $regularCart->id)->sum('quantity')
            : 0;

        return view('pages.checkout', compact('cartItems', 'total', 'cartCount', 'isBuyNow'));
    }

    /**
     * Process the checkout and create order (handles both regular and customization items)
     */
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
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
            'delivery_fee' => 'nullable|numeric|min:0',
            'delivery_distance_km' => 'nullable|numeric|min:0',
            'grand_total' => 'nullable|numeric|min:0',
            'payment_method' => 'required|string|in:GCash,COD',
        ]);

        $isBuyNow = session('is_buy_now_checkout', false);
        
        $cart = Cart::where('user_id', Auth::id())
            ->where('is_buy_now', $isBuyNow ? 1 : 0)
            ->latest()
            ->first();

        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $cartItems = CartItem::with(['product', 'customization'])->where('cart_id', $cart->id)->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Validate stock availability (only for regular products, not customizations)
        foreach ($cartItems as $item) {
            if (!$item->is_customization) {
                if ($item->quantity > $item->product->stock) {
                    return redirect()->back()->with('error', "Insufficient stock for {$item->product->name}");
                }
            }
        }

        // Calculate subtotal (without delivery fee)
        $subtotal = $cartItems->sum(function($item) {
            if ($item->is_customization && $item->customization) {
                return $item->quantity * $item->customization->admin_price;
            }
            return $item->quantity * $item->product->price;
        });

        // Get delivery fee from request or default to 0
        $deliveryFee = $request->has('delivery_fee') && is_numeric($request->delivery_fee) 
            ? (float) $request->delivery_fee 
            : 0;
        
        $deliveryDistanceKm = $request->has('delivery_distance_km') && is_numeric($request->delivery_distance_km) 
            ? (float) $request->delivery_distance_km 
            : 0;
        
        // Calculate grand total
        $grandTotal = $subtotal + $deliveryFee;

        // Get location details
        $region = DB::table('regions')->where('id', $request->region_id)->value('region_name');
        $province = DB::table('provinces')->where('id', $request->province_id)->value('province_name');
        $city = DB::table('cities')->where('id', $request->city_id)->value('city_name');
        $barangay = DB::table('barangays')->where('id', $request->barangay_id)->value('barangay_name');

        $fullAddress = sprintf(
            '%s, %s, %s, %s, %s',
            $request->street,
            $barangay,
            $city,
            $province,
            $region
        );

        DB::beginTransaction();

        try {
            // Create order WITH ALL FIELDS including delivery fee and grand total
            $order = Order::create([
                'user_id'        => Auth::id(),
                'customer_name'  => $request->name,
                'email'          => Auth::user()->email,
                'address'        => $fullAddress,
                'phone'          => $request->phone,
                'city_id'        => $request->city_id,
                'barangay_id'    => $request->barangay_id,
                'latitude'       => $request->latitude,
                'longitude'      => $request->longitude,
                'total'          => $subtotal,  // This is subtotal without delivery
                'delivery_fee'   => $deliveryFee, // FIXED: Added delivery fee
                'delivery_distance_km' => $deliveryDistanceKm, // FIXED: Added distance
                'grand_total'    => $grandTotal, // FIXED: Added grand total
                'payment_status' => 'Pending', // Make sure it's "Pending" not "Bending"
                'payment_method' => $request->payment_method,
                'delivery_status'=> 'Pending',
            ]);

            // Create order items and handle both regular products and customizations
            foreach ($cartItems as $item) {
                $price = $item->is_customization && $item->customization 
                    ? $item->customization->admin_price 
                    : $item->product->price;

                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id'  => $item->product_id,
                    'category_id' => $item->product->category_id ?? null,
                    'quantity'    => $item->quantity,
                    'price'       => $price,
                    'is_customization' => $item->is_customization ?? 0,
                    'customization_id' => $item->customization_id ?? null,
                ]);

                // Only reduce stock for regular products, not customizations
                if (!$item->is_customization) {
                    DB::table('products')
                        ->where('id', $item->product_id)
                        ->decrement('stock', $item->quantity);
                }

                // Link customization to order
                if ($item->is_customization && $item->customization) {
                    $item->customization->update([
                        'order_id' => $order->id,
                        'status' => 'Completed'
                    ]);

                    // ===== NOTIFY ABOUT CUSTOMIZATION ORDER =====
                    try {
                        NotificationHelper::customizationOrdered(
                            $item->customization_id,
                            $order->id,
                            $item->product->name,
                            Auth::id(),
                            $price
                        );
                        Log::info('Customization order notification sent', [
                            'customization_id' => $item->customization_id,
                            'order_id' => $order->id
                        ]);
                    } catch (\Exception $e) {
                        Log::error('Failed to send customization order notification', [
                            'customization_id' => $item->customization_id,
                            'order_id' => $order->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            // ===== NOTIFY ADMIN OF NEW ORDER =====
            try {
               NotificationHelper::orderCreated($order->id, $order->customer_name, $order->total, Auth::id(), $order->grand_total);
                Log::info('Order created notification sent', ['order_id' => $order->id]);
            } catch (\Exception $e) {
                Log::error('Failed to send order created notification', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            }

            // Delete the cart
            $cart->delete();
            
            session()->forget('is_buy_now_checkout');
            
            // Update cart count
            $regularCart = Cart::where('user_id', Auth::id())
                ->where('is_buy_now', 0)
                ->first();
            
            $cartCount = $regularCart 
                ? CartItem::where('cart_id', $regularCart->id)->sum('quantity')
                : 0;
                
            session(['cart_count' => $cartCount]);

            DB::commit();

            // Log the successful order with delivery details
            Log::info('Order completed with delivery', [
                'order_id' => $order->id,
                'delivery_fee' => $deliveryFee,
                'distance_km' => $deliveryDistanceKm,
                'grand_total' => $grandTotal
            ]);

            // Redirect based on payment method
            if ($request->payment_method === 'GCash') {
                return redirect()->route('checkout.gcash', $order->id)
                    ->with('success', 'Order created! Please complete your GCash payment.');
            }

            return redirect()->route('thankyou', ['order_id' => $order->id])
                ->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Checkout error', [
                'message' => $e->getMessage(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->with('error', 'Failed to process order: ' . $e->getMessage());
        }
    }

    /**
     * Show GCash payment page
     */
    public function showGCashPayment($orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment_method !== 'GCash') {
            return redirect()->route('thankyou', ['order_id' => $order->id]);
        }

        if ($order->payment_proof) {
            return redirect()->route('thankyou', ['order_id' => $order->id])
                ->with('info', 'Payment proof already submitted.');
        }

        return view('pages.gcash-payment', compact('order'));
    }

    /**
     * Submit GCash payment proof
     */
    public function submitGCashPayment(Request $request, $orderId)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->payment_proof) {
            return redirect()->route('thankyou', ['order_id' => $order->id])
                ->with('info', 'Payment proof already submitted.');
        }

        DB::beginTransaction();

        try {
            // Create uploads/payments directory if it doesn't exist
            if (!file_exists(public_path('uploads/payments'))) {
                mkdir(public_path('uploads/payments'), 0755, true);
            }

            // Handle file upload
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = 'payment_' . $order->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/payments'), $filename);

                // Update order with payment proof
                $order->update([
                    'payment_proof' => $filename,
                    'payment_status' => 'Pending',
                ]);

                // ===== NOTIFY ADMIN OF PAYMENT PROOF =====
                try {
                    NotificationHelper::paymentProofUploaded($order->id, "#{$order->id}", $order->total, Auth::id());
                    Log::info('Payment proof notification sent', ['order_id' => $order->id]);
                } catch (\Exception $e) {
                    Log::error('Failed to notify admin of payment proof', ['order_id' => $order->id, 'error' => $e->getMessage()]);
                }
            }

            DB::commit();

            return redirect()->route('thankyou', ['order_id' => $order->id])
                ->with('success', 'Payment proof submitted successfully! We will verify and process your order.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('GCash payment proof upload error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to upload payment proof. Please try again.');
        }
    }
}