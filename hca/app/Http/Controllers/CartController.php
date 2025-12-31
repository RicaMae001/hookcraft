<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        // Only get regular cart items (not buy-now items)
        $cart = Cart::where('user_id', Auth::id())
                    ->where('is_buy_now', 0)
                    ->first();
        
        $cartItems = $cart ? $cart->items()->with(['product.category'])->get() : collect();

        return view('pages.cart', compact('cartItems'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request)
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please login to add items to cart'
                    ], 401);
                }
                return redirect()->route('login')->with('error', 'Please login to add items to cart');
            }

            // Validate request
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:99'
            ]);

            Log::info('Cart add request', $validated);

            // Get product with category
            $product = Product::with('category')->findOrFail($validated['product_id']);
            
            // Check if product is in stock
            if ($product->stock < $validated['quantity']) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock available. Only ' . $product->stock . ' items left.'
                    ], 400);
                }
                
                return back()->with('error', 'Insufficient stock available. Only ' . $product->stock . ' items left.');
            }

            // Get or create REGULAR cart for user (is_buy_now = 0)
            $cart = Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'is_buy_now' => 0]
            );
            
            Log::info('Cart found/created', ['cart_id' => $cart->id, 'user_id' => Auth::id()]);

            // Check if item already exists in cart
            $existingCartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($existingCartItem) {
                // Update quantity if item exists
                $newQuantity = $existingCartItem->quantity + $validated['quantity'];
                
                // Check total quantity against stock
                if ($newQuantity > $product->stock) {
                    if ($request->ajax()) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Cannot add more items. Stock limit reached. (Available: ' . $product->stock . ', In cart: ' . $existingCartItem->quantity . ')'
                        ], 400);
                    }
                    
                    return back()->with('error', 'Cannot add more items. Stock limit reached.');
                }
                
                $existingCartItem->update([
                    'quantity' => $newQuantity,
                    'subtotal' => $newQuantity * $product->price
                ]);
                
                Log::info('Cart item updated', ['item_id' => $existingCartItem->id, 'new_quantity' => $newQuantity]);
            } else {
                // Create new cart item
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $validated['product_id'],
                    'category_id' => $product->category_id,
                    'quantity' => $validated['quantity'],
                    'price' => $product->price,
                    'subtotal' => $validated['quantity'] * $product->price
                ]);
                
                Log::info('New cart item created', ['item_id' => $cartItem->id]);
            }

            // Calculate total cart count (only regular cart items)
            $cartCount = CartItem::whereHas('cart', function($query) {
                $query->where('user_id', Auth::id())
                      ->where('is_buy_now', 0);
            })->sum('quantity');
            
            // Update session cart count
            session(['cart_count' => $cartCount]);
            
            Log::info('Cart count updated', ['cart_count' => $cartCount]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully!',
                    'cart_count' => $cartCount,
                    'product_name' => $product->name
                ]);
            }

            return back()->with('success', 'Product added to cart successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in cart add', ['errors' => $e->errors()]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid input: ' . implode(', ', array_map('implode', $e->errors()))
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Cart add error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'product_id' => $request->product_id ?? 'unknown',
                'quantity' => $request->quantity ?? 'unknown'
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again. Error: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    /**
     * ✨ Buy Now - Debug Version with Extra Logging
     */
    public function buyNow(Request $request)
    {
        Log::info('=== BUY NOW START ===', [
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'is_ajax' => $request->ajax(),
            'wants_json' => $request->wantsJson()
        ]);

        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                Log::warning('Buy Now - User not authenticated');
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please login to continue'
                    ], 401);
                }
                return redirect()->route('login')->with('error', 'Please login to continue');
            }

            // Validate request
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:99'
            ]);

            Log::info('Buy Now - Validation passed', $validated);

            // Get product with category
            $product = Product::with('category')->findOrFail($validated['product_id']);
            
            Log::info('Buy Now - Product found', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'stock' => $product->stock
            ]);
            
            // Check if product is in stock
            if ($product->stock < $validated['quantity']) {
                Log::warning('Buy Now - Insufficient stock', [
                    'requested' => $validated['quantity'],
                    'available' => $product->stock
                ]);
                
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Insufficient stock available. Only ' . $product->stock . ' items left.'
                    ], 400);
                }
                
                return back()->with('error', 'Insufficient stock available.');
            }

            DB::beginTransaction();

            try {
                // Delete any existing "buy now" cart for this user
                $deletedCount = Cart::where('user_id', Auth::id())
                    ->where('is_buy_now', 1)
                    ->delete();
                
                Log::info('Buy Now - Deleted old buy-now carts', ['count' => $deletedCount]);
                
                // Create NEW "buy now" cart (separate from regular cart)
                $buyNowCart = Cart::create([
                    'user_id' => Auth::id(),
                    'is_buy_now' => 1  // ✅ Now this will work because it's in fillable
                ]);
                
                Log::info('Buy Now - Created new cart', [
                    'cart_id' => $buyNowCart->id,
                    'is_buy_now' => $buyNowCart->is_buy_now,
                    'is_buy_now_from_db' => $buyNowCart->fresh()->is_buy_now
                ]);

                // Create cart item in the buy-now cart
                $cartItem = CartItem::create([
                    'cart_id' => $buyNowCart->id,
                    'product_id' => $validated['product_id'],
                    'category_id' => $product->category_id,
                    'quantity' => $validated['quantity'],
                    'price' => $product->price,
                    'subtotal' => $validated['quantity'] * $product->price
                ]);
                
                Log::info('Buy Now - Created cart item', [
                    'item_id' => $cartItem->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity
                ]);

                DB::commit();
                
                Log::info('Buy Now - Transaction committed');

                // Store buy-now flag in session
                session(['is_buy_now_checkout' => true]);
                
                Log::info('Buy Now - Session set', [
                    'is_buy_now_checkout' => session('is_buy_now_checkout'),
                    'session_id' => session()->getId()
                ]);

                $redirectUrl = route('checkout.index');
                
                Log::info('=== BUY NOW SUCCESS ===', [
                    'redirect_url' => $redirectUrl,
                    'will_return_json' => ($request->ajax() || $request->wantsJson())
                ]);

                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Proceeding to checkout...',
                        'redirect_url' => $redirectUrl,
                        'cart_id' => $buyNowCart->id
                    ], 200);
                }

                return redirect()->route('checkout.index');

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Buy Now - Transaction failed', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Buy Now - Validation error', ['errors' => $e->errors()]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid input'
                ], 422);
            }
            
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('=== BUY NOW ERROR ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'product_id' => $request->product_id ?? 'unknown'
            ]);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Something went wrong. Please try again.'
                ], 500);
            }
            
            return back()->with('error', 'Something went wrong. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);
        $maxStock = $cartItem->product->stock;

        $quantity = $request->get('quantity');

        $request->validate([
            'quantity' => "required|integer|min:1|max:$maxStock"
        ]);

        $cartItem->quantity = $quantity;
        $cartItem->subtotal = $quantity * $cartItem->price;
        $cartItem->save();

        return response()->json(['success' => true]);
    }

    public function delete($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return back()->with('success', 'Item removed!');
    }
}