<?php

namespace App\Http\Controllers;

use App\Models\ProductCustomization;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomizationController extends Controller
{
    // ==================== LANDING PAGE ====================
    
    /**
     * Show landing page with all categories for customization
     */
    public function landing()
    {
        $categories = Category::whereHas('products', function($query) {
            $query->where('is_available', 1);
        })
        ->with(['products' => function($query) {
            $query->where('is_available', 1)
                  ->orderBy('id', 'asc')
                  ->limit(1);
        }])
        ->orderBy('name', 'asc')
        ->get();
        
        return view('customization.landing', compact('categories'));
    }

    // ==================== CUSTOMER METHODS ====================
    
    /**
     * Show customization creation form
     */
    public function create(Request $request)
    {
        $category = null;
        $referenceProduct = null;
        $categories = Category::whereHas('products', function($query) {
            $query->where('is_available', 1);
        })->orderBy('name', 'asc')->get();
        
        if ($request->has('category_id')) {
            $category = Category::with(['products' => function($query) {
                $query->where('is_available', 1)
                      ->orderBy('id', 'asc');
            }])->find($request->category_id);
            
            if ($category && $category->products->count() > 0) {
                $referenceProduct = $category->products->first();
            }
        }

        return view('customization.create', compact('category', 'referenceProduct', 'categories'));
    }

    /**
     * Store new customization request
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'           => 'required|exists:categories,id',
            'product_id'            => 'nullable|exists:products,id',
            'customization_name'    => 'required|string|max:255',
            'customization_details' => 'required|string',
            'special_instructions'  => 'nullable|string',
            'custom_image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to submit customization requests.');
        }

        DB::beginTransaction();
        
        try {
            if ($request->has('product_id') && $request->product_id) {
                $referenceProduct = Product::where('id', $request->product_id)
                    ->where('is_available', 1)
                    ->first();
                
                if (!$referenceProduct) {
                    return redirect()->back()
                        ->with('error', 'The selected product is not available.')
                        ->withInput();
                }
            } else {
                $referenceProduct = Product::where('category_id', $request->category_id)
                    ->where('is_available', 1)
                    ->orderBy('id', 'asc')
                    ->first();
                
                if (!$referenceProduct) {
                    return redirect()->back()
                        ->with('error', 'No available products found in this category.')
                        ->withInput();
                }
            }

            $imagePath = null;
            if ($request->hasFile('custom_image')) {
                $image = $request->file('custom_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/customizations'), $imageName);
                $imagePath = $imageName;
            }

            $customization = ProductCustomization::create([
                'user_id'               => Auth::id(),
                'product_id'            => $referenceProduct->id,
                'category_id'           => $referenceProduct->category_id,
                'customization_name'    => $request->customization_name,
                'customization_details' => $request->customization_details,
                'special_instructions'  => $request->special_instructions,
                'custom_image'          => $imagePath,
                'total_price'           => 0,
                'status'                => 'Pending',
                'admin_price'           => null,
                'admin_notes'           => null,
                'admin_id'              => null,
            ]);

            Log::info('Customization request submitted', [
                'user_id'          => Auth::id(),
                'customization_id' => $customization->id,
                'category_id'      => $request->category_id,
                'product_id'       => $referenceProduct->id,
            ]);

            DB::commit();

            return redirect()->route('customization.my-customizations')
                ->with('success', 'Customization request submitted! Our team will review it and provide pricing within 24 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customization creation failed', [
                'error'       => $e->getMessage(),
                'user_id'     => Auth::id(),
                'category_id' => $request->category_id ?? 'not provided',
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
            ->with('product.category')
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
            ->with('product.category', 'options')
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
            ->with('product.category')
            ->findOrFail($id);

        $categories = Category::whereHas('products', function($query) {
            $query->where('is_available', 1);
        })->orderBy('name', 'asc')->get();

        $products = Product::where('category_id', $customization->product->category_id)
            ->where('is_available', 1)
            ->orderBy('id', 'asc')
            ->get();

        return view('customization.edit', compact('customization', 'categories', 'products'));
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
            'category_id'           => 'required|exists:categories,id',
            'product_id'            => 'nullable|exists:products,id',
            'customization_name'    => 'required|string|max:255',
            'customization_details' => 'required|string',
            'special_instructions'  => 'nullable|string',
            'custom_image'          => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        DB::beginTransaction();
        
        try {
            $updateData = [
                'customization_name'    => $request->customization_name,
                'customization_details' => $request->customization_details,
                'special_instructions'  => $request->special_instructions,
            ];

            if ($request->has('product_id') && $request->product_id) {
                $newReferenceProduct = Product::where('id', $request->product_id)
                    ->where('is_available', 1)
                    ->first();
                
                if (!$newReferenceProduct) {
                    return redirect()->back()
                        ->with('error', 'The selected product is not available.')
                        ->withInput();
                }
                
                $updateData['product_id']  = $newReferenceProduct->id;
                $updateData['category_id'] = $newReferenceProduct->category_id;
            } else {
                $newReferenceProduct = Product::where('category_id', $request->category_id)
                    ->where('is_available', 1)
                    ->orderBy('id', 'asc')
                    ->first();
                
                if (!$newReferenceProduct) {
                    return redirect()->back()
                        ->with('error', 'No available products found in this category.')
                        ->withInput();
                }
                
                $updateData['product_id']  = $newReferenceProduct->id;
                $updateData['category_id'] = $newReferenceProduct->category_id;
            }

            if ($request->hasFile('custom_image')) {
                if ($customization->custom_image) {
                    $oldImagePath = public_path('uploads/customizations/' . $customization->custom_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image     = $request->file('custom_image');
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
            ->whereNull('order_id')
            ->findOrFail($id);
        
        if ($customization->custom_image) {
            $imagePath = public_path('uploads/customizations/' . $customization->custom_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $customization->delete();

        return redirect()->route('customization.my-customizations')
            ->with('success', 'Customization deleted successfully');
    }

    /**
     * Add approved customization to cart
     */
    public function addToCart($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        try {
            $customization = ProductCustomization::where('user_id', Auth::id())
                ->where('status', 'Approved')
                ->whereNull('order_id')
                ->with('product')
                ->findOrFail($id);

            if (!$customization->admin_price) {
                return redirect()->route('customization.my-customizations')
                    ->with('error', 'No price has been set for this customization yet.');
            }

            DB::beginTransaction();
            
            $cart = \App\Models\Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'is_buy_now' => 0]
            );

            $existingItem = \App\Models\CartItem::where('cart_id', $cart->id)
                ->where('is_customization', 1)
                ->where('customization_id', $customization->id)
                ->first();

            if (!$existingItem) {
                \App\Models\CartItem::create([
                    'cart_id'          => $cart->id,
                    'product_id'       => $customization->product_id,
                    'category_id'      => $customization->product->category_id ?? null,
                    'quantity'         => 1,
                    'price'            => $customization->admin_price,
                    'subtotal'         => $customization->admin_price,
                    'is_customization' => 1,
                    'customization_id' => $customization->id,
                ]);

                $cartCount = \App\Models\CartItem::whereHas('cart', function($query) {
                    $query->where('user_id', Auth::id())
                          ->where('is_buy_now', 0);
                })->sum('quantity');
                
                session(['cart_count' => $cartCount]);

                DB::commit();

                return redirect()->route('cart.index')
                    ->with('success', 'Customization added to cart!');
            }

            DB::commit();
            return redirect()->route('cart.index')
                ->with('info', 'This customization is already in your cart.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add customization to cart', [
                'error'            => $e->getMessage(),
                'customization_id' => $id,
            ]);
            
            return redirect()->route('customization.my-customizations')
                ->with('error', 'Failed to add to cart: ' . $e->getMessage());
        }
    }

    /**
     * Proceed directly to checkout with customization
     */
    public function proceedCheckout($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        try {
            $customization = ProductCustomization::where('user_id', Auth::id())
                ->where('status', 'Approved')
                ->whereNull('order_id')
                ->with('product')
                ->findOrFail($id);

            if (!$customization->admin_price) {
                return redirect()->route('customization.my-customizations')
                    ->with('error', 'No price has been set for this customization yet.');
            }

            DB::beginTransaction();
            
            $cart = \App\Models\Cart::firstOrCreate(
                ['user_id' => Auth::id(), 'is_buy_now' => 0]
            );

            $existingItem = \App\Models\CartItem::where('cart_id', $cart->id)
                ->where('is_customization', 1)
                ->where('customization_id', $customization->id)
                ->first();

            if (!$existingItem) {
                \App\Models\CartItem::create([
                    'cart_id'          => $cart->id,
                    'product_id'       => $customization->product_id,
                    'category_id'      => $customization->product->category_id ?? null,
                    'quantity'         => 1,
                    'price'            => $customization->admin_price,
                    'subtotal'         => $customization->admin_price,
                    'is_customization' => 1,
                    'customization_id' => $customization->id,
                ]);

                $cartCount = \App\Models\CartItem::whereHas('cart', function($query) {
                    $query->where('user_id', Auth::id())
                          ->where('is_buy_now', 0);
                })->sum('quantity');
                
                session(['cart_count' => $cartCount]);
            }

            DB::commit();

            return redirect()->route('checkout.index')
                ->with('success', 'Customization added to cart! Complete your order below.');

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to proceed to checkout with customization', [
                'error'            => $e->getMessage(),
                'customization_id' => $id,
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

        if (!in_array($admin->role, ['Admin', 'SuperAdmin'])) {
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
        
        $query = ProductCustomization::with(['user', 'product.category']);
        
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

        $customization = ProductCustomization::with(['user', 'product.category', 'options'])
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
            'status'       => 'required|in:Pending,Approved,Rejected,Completed',
            'admin_price'  => 'required_if:status,Approved|nullable|numeric|min:0',
            'admin_notes'  => 'nullable|string',
        ]);

        $customization = ProductCustomization::findOrFail($id);

        DB::beginTransaction();
        try {
            $updateData = [
                'status'      => $request->status,
                'admin_notes' => $request->admin_notes,
                'admin_id'    => session('admin_id'),
            ];

            if ($request->status === 'Approved' && $request->has('admin_price')) {
                $updateData['admin_price']  = $request->admin_price;
                $updateData['total_price']  = $request->admin_price;
            } elseif ($request->status !== 'Approved') {
                $updateData['admin_price'] = null;
            }

            $customization->update($updateData);

            Log::info('Customization status updated by admin', [
                'admin_id'         => session('admin_id'),
                'customization_id' => $customization->id,
                'status'           => $request->status,
                'price'            => $request->admin_price ?? 'N/A'
            ]);

            DB::commit();

            return redirect()->route('admin.customizations.index')
                ->with('success', 'Customization updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customization update failed', [
                'error'    => $e->getMessage(),
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
        
        if ($customization->custom_image) {
            $imagePath = public_path('uploads/customizations/' . $customization->custom_image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $customization->delete();

        Log::info('Customization deleted by admin', [
            'customization_id' => $id,
            'admin_id'         => session('admin_id')
        ]);

        return redirect()->route('admin.customizations.index')
            ->with('success', 'Customization deleted successfully');
    }

    /**
     * AJAX: Get products by category (for product selection)
     */
    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->where('is_available', 1)
            ->orderBy('id', 'asc')
            ->get(['id', 'name', 'price', 'image', 'description']);

        if ($products->count() > 0) {
            return response()->json([
                'success'  => true,
                'products' => $products
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No available products in this category'
        ], 404);
    }

    /**
     * AJAX: Get first product from category (for auto-selection)
     */
    public function getFirstProductByCategory($categoryId)
    {
        $product = Product::where('category_id', $categoryId)
            ->where('is_available', 1)
            ->orderBy('id', 'asc')
            ->first();

        if ($product) {
            return response()->json([
                'success' => true,
                'product' => [
                    'id'          => $product->id,
                    'name'        => $product->name,
                    'price'       => $product->price,
                    'image'       => $product->image,
                    'description' => $product->description,
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No available products in this category'
        ], 404);
    }
}