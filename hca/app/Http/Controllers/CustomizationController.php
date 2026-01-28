<?php

namespace App\Http\Controllers;

use App\Models\ProductCustomization;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomizationController extends Controller
{
    // ==================== LANDING PAGE ====================
    
    /**
     * Show landing page with all customizable products
     */
    public function landing()
    {
        $products = Product::where('is_available', 1)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('customization.landing', compact('products'));
    }

    // ==================== CUSTOMER METHODS ====================
    
    /**
     * Show customization creation form
     */
    public function create(Request $request)
    {
        $product = null;
        $products = Product::where('is_available', 1)->get();
        
        if ($request->has('product_id')) {
            $product = Product::find($request->product_id);
        }

        return view('customization.create', compact('product', 'products'));
    }

    /**
     * Store new customization request
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'customization_name' => 'required|string|max:255',
            'customization_details' => 'required|string',
            'special_instructions' => 'nullable|string',
            'custom_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to submit customization requests.');
        }

        DB::beginTransaction();
        
        try {
            $imagePath = null;
            if ($request->hasFile('custom_image')) {
                $image = $request->file('custom_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/customizations'), $imageName);
                $imagePath = $imageName;
            }

            $customization = ProductCustomization::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'customization_name' => $request->customization_name,
                'customization_details' => $request->customization_details,
                'special_instructions' => $request->special_instructions,
                'custom_image' => $imagePath,
                'total_price' => 0,
                'status' => 'Pending',
                'admin_price' => null,
                'admin_notes' => null,
                'admin_id' => null,
            ]);

            Log::info('Customization request submitted', [
                'user_id' => Auth::id(),
                'customization_id' => $customization->id,
                'product_id' => $request->product_id,
            ]);

            DB::commit();

            return redirect()->route('customization.my-customizations')
                ->with('success', 'Customization request submitted! Our team will review it and provide pricing within 24 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customization creation failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'product_id' => $request->product_id ?? 'not provided',
            ]);
            
            return redirect()->back()
                ->with('error', 'Error submitting customization request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show all user's customizations
     */
    public function myCustomizations()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view customizations.');
        }

        $customizations = ProductCustomization::where('user_id', Auth::id())
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customization.my-customizations', compact('customizations'));
    }

    /**
     * Show single customization details
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $customization = ProductCustomization::where('user_id', Auth::id())
            ->with('product', 'options')
            ->findOrFail($id);

        return view('customization.show', compact('customization'));
    }

    /**
     * Show edit form for customization
     */
    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $customization = ProductCustomization::where('user_id', Auth::id())
            ->where('status', 'Pending')
            ->with('product')
            ->findOrFail($id);

        $products = Product::where('is_available', 1)->get();

        return view('customization.edit', compact('customization', 'products'));
    }

    /**
     * Update customization
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $customization = ProductCustomization::where('user_id', Auth::id())
            ->where('status', 'Pending')
            ->findOrFail($id);

        $request->validate([
            'customization_name' => 'required|string|max:255',
            'customization_details' => 'required|string',
            'special_instructions' => 'nullable|string',
            'custom_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        DB::beginTransaction();
        
        try {
            $updateData = [
                'customization_name' => $request->customization_name,
                'customization_details' => $request->customization_details,
                'special_instructions' => $request->special_instructions,
            ];

            if ($request->hasFile('custom_image')) {
                // Delete old image
                if ($customization->custom_image) {
                    $oldImagePath = public_path('uploads/customizations/' . $customization->custom_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Upload new image
                $image = $request->file('custom_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/customizations'), $imageName);
                $updateData['custom_image'] = $imageName;
            }

            $customization->update($updateData);

            DB::commit();

            return redirect()->route('customization.show', $customization->id)
                ->with('success', 'Customization updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customization update failed', ['error' => $e->getMessage()]);
            
            return redirect()->back()
                ->with('error', 'Error updating customization: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete customization
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $customization = ProductCustomization::where('user_id', Auth::id())
            ->where('status', 'Pending')
            ->findOrFail($id);

        if ($customization->custom_image) {
            $imagePath = public_path('uploads/customizations/' . $customization->custom_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $customization->delete();

        return redirect()->route('customization.my-customizations')
            ->with('success', 'Customization deleted successfully!');
    }

    /**
     * Add approved customization to cart (without checkout)
     */
    public function addToCart(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $customization = ProductCustomization::where('user_id', Auth::id())
            ->with('product')
            ->findOrFail($id);

        // Validate customization can be added to cart
        if (!$customization->isApproved()) {
            return back()->with('error', 'Customization must be approved before adding to cart.');
        }

        if ($customization->order_id) {
            return back()->with('error', 'This customization has already been ordered.');
        }

        if (!$customization->admin_price) {
            return back()->with('error', 'No price has been set for this customization.');
        }

        DB::beginTransaction();
        
        try {
            // Get or create regular cart
            $cart = \App\Models\Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'is_buy_now' => 0]
            );

            // Check if customization is already in cart
            $existingItem = \App\Models\CartItem::where('cart_id', $cart->id)
                ->where('product_id', $customization->product_id)
                ->where('is_customization', 1)
                ->where('customization_id', $customization->id)
                ->first();

            if ($existingItem) {
                return back()->with('info', 'This customization is already in your cart.');
            }

            // Create cart item for customization
            \App\Models\CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $customization->product_id,
                'category_id' => $customization->product->category_id ?? null,
                'quantity' => 1,
                'price' => $customization->admin_price,
                'subtotal' => $customization->admin_price,
                'is_customization' => 1,
                'customization_id' => $customization->id,
            ]);

            // Update session cart count
            $cartCount = \App\Models\CartItem::whereHas('cart', function($query) {
                $query->where('user_id', Auth::id())
                      ->where('is_buy_now', 0);
            })->sum('quantity');
            
            session(['cart_count' => $cartCount]);

            DB::commit();

            return redirect()->route('cart.index')
                ->with('success', 'Customization added to cart successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add customization to cart', [
                'error' => $e->getMessage(),
                'customization_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return back()->with('error', 'Failed to add customization to cart.');
        }
    }

    /**
     * Add customization to cart and redirect to checkout (alias method)
     */
    public function addToCartAndCheckout(Request $request, $id)
    {
        // This is just an alias that calls proceedToCheckout
        return $this->proceedToCheckout($request, $id);
    }

    /**
     * Add customization to cart and redirect to main checkout page
     * This method is now simplified - it just adds to cart and redirects to your existing checkout
     */
    public function proceedToCheckout(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        try {
            $customization = ProductCustomization::where('user_id', Auth::id())
                ->with('product')
                ->findOrFail($id);

            // Validate customization status
            if ($customization->status !== 'Approved') {
                Log::warning('Customization not approved', [
                    'customization_id' => $id,
                    'status' => $customization->status
                ]);
                return redirect()->route('customization.my-customizations')
                    ->with('error', 'This customization must be approved before checkout.');
            }

            if ($customization->order_id) {
                Log::warning('Customization already ordered', [
                    'customization_id' => $id,
                    'order_id' => $customization->order_id
                ]);
                return redirect()->route('customization.my-customizations')
                    ->with('info', 'This customization has already been ordered.');
            }

            if (!$customization->admin_price || $customization->admin_price <= 0) {
                Log::warning('Customization has no price', [
                    'customization_id' => $id,
                    'admin_price' => $customization->admin_price
                ]);
                return redirect()->route('customization.my-customizations')
                    ->with('error', 'No price has been set for this customization yet.');
            }

            DB::beginTransaction();
            
            // Get or create regular cart
            $cart = \App\Models\Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'is_buy_now' => 0]
            );

            Log::info('Cart retrieved/created', ['cart_id' => $cart->id]);

            // Check if customization is already in cart
            $existingItem = \App\Models\CartItem::where('cart_id', $cart->id)
                ->where('is_customization', 1)
                ->where('customization_id', $customization->id)
                ->first();

            if (!$existingItem) {
                // Add customization to cart
                $cartItem = \App\Models\CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $customization->product_id,
                    'category_id' => $customization->product->category_id ?? null,
                    'quantity' => 1,
                    'price' => $customization->admin_price,
                    'subtotal' => $customization->admin_price,
                    'is_customization' => 1,
                    'customization_id' => $customization->id,
                ]);

                Log::info('Customization added to cart', [
                    'cart_item_id' => $cartItem->id,
                    'customization_id' => $customization->id
                ]);

                // Update cart count
                $cartCount = \App\Models\CartItem::whereHas('cart', function($query) {
                    $query->where('user_id', Auth::id())
                          ->where('is_buy_now', 0);
                })->sum('quantity');
                
                session(['cart_count' => $cartCount]);
            } else {
                Log::info('Customization already in cart', [
                    'cart_item_id' => $existingItem->id
                ]);
            }

            DB::commit();

            Log::info('Redirecting to checkout', [
                'customization_id' => $id,
                'user_id' => Auth::id()
            ]);

            // Redirect to checkout.index route
            return redirect()->route('checkout.index')
                ->with('success', 'Customization added to cart! Complete your order below.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to proceed to checkout with customization', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'customization_id' => $id,
                'user_id' => Auth::id()
            ]);
            
            return redirect()->route('customization.my-customizations')
                ->with('error', 'Failed to proceed to checkout: ' . $e->getMessage());
        }
    }

    // ==================== ADMIN METHODS ====================
    
    /**
     * Check if user is admin
     */
    private function requireAdmin()
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        $admin = DB::table('admin')->where('id', session('admin_id'))->first();
        
        if (!$admin) {
            session()->forget(['admin_id', 'admin_name', 'admin_role', 'user_type']);
            return redirect()->route('staff.login')->with('error', 'Session expired. Please login again.');
        }

        if ($admin->role !== 'Admin') {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Access denied. Admin role required.');
        }

        return null;
    }

    /**
     * Admin: View all customizations
     */
    public function adminIndex(Request $request)
    {
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $status = $request->get('status');
        
        $query = ProductCustomization::with(['user', 'product']);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $customizations = $query->orderByRaw("FIELD(status, 'Pending', 'Approved', 'Rejected', 'Completed')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.customizations.index', compact('customizations'));
    }

    /**
     * Admin: View single customization
     */
    public function adminShow($id)
    {
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $customization = ProductCustomization::with(['user', 'product', 'options'])
            ->findOrFail($id);

        return view('admin.customizations.show', compact('customization'));
    }

    /**
     * Admin: Update customization status and price
     */
    public function adminUpdate(Request $request, $id)
    {
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,Completed',
            'admin_price' => 'required_if:status,Approved|nullable|numeric|min:0',
            'admin_notes' => 'nullable|string',
        ]);

        $customization = ProductCustomization::findOrFail($id);

        DB::beginTransaction();
        try {
            $updateData = [
                'status' => $request->status,
                'admin_notes' => $request->admin_notes,
                'admin_id' => session('admin_id'),
            ];

            // Only update price if status is Approved
            if ($request->status === 'Approved' && $request->has('admin_price')) {
                $updateData['admin_price'] = $request->admin_price;
                $updateData['total_price'] = $request->admin_price;
            } elseif ($request->status !== 'Approved') {
                $updateData['admin_price'] = null;
            }

            $customization->update($updateData);

            Log::info('Customization status updated by admin', [
                'admin_id' => session('admin_id'),
                'customization_id' => $customization->id,
                'status' => $request->status,
                'price' => $request->admin_price ?? 'N/A'
            ]);

            DB::commit();

            return redirect()->route('admin.customizations.index')
                ->with('success', 'Customization updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customization update failed', [
                'error' => $e->getMessage(),
                'admin_id' => session('admin_id')
            ]);
            
            return redirect()->back()
                ->with('error', 'Error updating customization: ' . $e->getMessage());
        }
    }

    /**
     * Admin: Delete customization
     */
    public function adminDestroy($id)
    {
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $customization = ProductCustomization::findOrFail($id);
        
        // Delete image if exists
        if ($customization->custom_image) {
            $imagePath = public_path('uploads/customizations/' . $customization->custom_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $customization->delete();

        Log::info('Customization deleted by admin', [
            'customization_id' => $id,
            'admin_id' => session('admin_id')
        ]);

        return redirect()->route('admin.customizations.index')
            ->with('success', 'Customization deleted successfully');
    }
}