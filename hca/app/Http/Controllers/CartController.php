<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $cartItems = $cart->items()->with(['product.category'])->get();

        return view('pages.cart', compact('cartItems'));
    }

    /**
     * Add product to cart - FIXED VERSION
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

        // Get product with category - IMPORTANT: Load category relationship
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

        // Get or create cart for user
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
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
            // Create new cart item - FIXED: Include category_id
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $validated['product_id'],
                'category_id' => $product->category_id, // ✅ ADDED THIS LINE
                'quantity' => $validated['quantity'],
                'price' => $product->price,
                'subtotal' => $validated['quantity'] * $product->price
            ]);
            
            Log::info('New cart item created', ['item_id' => $cartItem->id]);
        }

        // Calculate total cart count
        $cartCount = CartItem::whereHas('cart', function($query) {
            $query->where('user_id', Auth::id());
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
    public function update(Request $request, $id)
    {
        try {
            $item = CartItem::findOrFail($id);
            
            // Verify ownership
            if ($item->cart->user_id !== Auth::id()) {
                return back()->with('error', 'Unauthorized action.');
            }
            
            $quantity = max(1, (int) $request->quantity);

            // Check stock availability
            if ($item->product->stock < $quantity) {
                return back()->with('error', 'Insufficient stock available. Only ' . $item->product->stock . ' items left.');
            }

            $item->update([
                'quantity' => $quantity,
                'subtotal' => $item->price * $quantity
            ]);

            // Update session cart count
            $cartCount = CartItem::whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })->sum('quantity');
            session(['cart_count' => $cartCount]);

            return back()->with('success', 'Cart updated successfully.');
            
        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update cart.');
        }
    }

    public function remove($id)
    {
        try {
            $item = CartItem::findOrFail($id);
            
            // Verify ownership
            if ($item->cart->user_id !== Auth::id()) {
                return back()->with('error', 'Unauthorized action.');
            }
            
            $item->delete();

            // Update session cart count
            $cartCount = CartItem::whereHas('cart', function($query) {
                $query->where('user_id', Auth::id());
            })->sum('quantity');
            session(['cart_count' => $cartCount]);

            return back()->with('success', 'Item removed from cart.');
            
        } catch (\Exception $e) {
            Log::error('Cart remove error: ' . $e->getMessage());
            return back()->with('error', 'Failed to remove item from cart.');
        }
    }
}