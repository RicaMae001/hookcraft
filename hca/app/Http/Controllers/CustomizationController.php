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

    public function create(Request $request)
    {
        $category = null;
        $referenceProduct = null;
        $categories = Category::whereHas('products', function($query) {
            $query->where('is_available', 1);
        })->orderBy('name', 'asc')->get();

        if ($request->has('category_id')) {
            $category = Category::with(['products' => function($query) {
                $query->where('is_available', 1)->orderBy('id', 'asc');
            }])->find($request->category_id);

            if ($category && $category->products->count() > 0) {
                $referenceProduct = $category->products->first();
            }
        }

        return view('customization.create', compact('category', 'referenceProduct', 'categories'));
    }

    /**
     * Store new customization request — saves customer materials as customization_options
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'            => 'required|exists:categories,id',
            'product_id'             => 'nullable|exists:products,id',
            'customization_name'     => 'required|string|max:255',
            'customization_details'  => 'required|string',
            'special_instructions'   => 'nullable|string',
            'custom_image'           => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            // Customer expected materials
            'materials'              => 'nullable|array',
            'materials.*.label'      => 'required_with:materials|string|max:255',
            'materials.*.quantity'   => 'required_with:materials|numeric|min:0',
            'materials.*.unit_price' => 'required_with:materials|numeric|min:0',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to submit customization requests.');
        }

        DB::beginTransaction();

        try {
            // Determine reference product
            if ($request->filled('product_id')) {
                $referenceProduct = Product::where('id', $request->product_id)
                    ->where('is_available', 1)->first();

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

            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('custom_image')) {
                $image     = $request->file('custom_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/customizations'), $imageName);
                $imagePath = $imageName;
            }

            // Calculate customer's estimated total from materials
            $customerEstimate = 0;
            if ($request->filled('materials')) {
                foreach ($request->materials as $row) {
                    if (!empty($row['label'])) {
                        $customerEstimate += (float)($row['quantity'] ?? 1) * (float)($row['unit_price'] ?? 0);
                    }
                }
            }

            // Create the customization
            $customization = ProductCustomization::create([
                'user_id'               => Auth::id(),
                'product_id'            => $referenceProduct->id,
                'category_id'           => $referenceProduct->category_id,
                'customization_name'    => $request->customization_name,
                'customization_details' => $request->customization_details,
                'special_instructions'  => $request->special_instructions,
                'custom_image'          => $imagePath,
                'total_price'           => $customerEstimate,
                'status'                => 'Pending',
                'admin_price'           => null,
                'admin_notes'           => null,
                'admin_id'              => null,
            ]);

            // Save customer material rows as customization_options
            if ($request->filled('materials')) {
                foreach ($request->materials as $row) {
                    if (empty($row['label'])) continue;

                    $subtotal = (float)($row['quantity'] ?? 1) * (float)($row['unit_price'] ?? 0);

                    \App\Models\CustomizationOption::create([
                        'customization_id' => $customization->id,
                        'option_type'      => 'material',
                        'option_value'     => json_encode([
                            'label'      => $row['label'],
                            'quantity'   => (float)($row['quantity'] ?? 1),
                            'unit_price' => (float)($row['unit_price'] ?? 0),
                            'subtotal'   => $subtotal,
                        ]),
                        'additional_price' => $subtotal,
                    ]);
                }
            }

            Log::info('Customization request submitted', [
                'user_id'          => Auth::id(),
                'customization_id' => $customization->id,
                'category_id'      => $request->category_id,
                'product_id'       => $referenceProduct->id,
                'materials_count'  => count($request->materials ?? []),
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

            if ($request->filled('product_id')) {
                $newReferenceProduct = Product::where('id', $request->product_id)
                    ->where('is_available', 1)->first();

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
                    if (file_exists($oldImagePath)) unlink($oldImagePath);
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
            if (file_exists($imagePath)) unlink($imagePath);
        }

        $customization->delete();

        return redirect()->route('customization.my-customizations')
            ->with('success', 'Customization deleted successfully');
    }

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
                    $query->where('user_id', Auth::id())->where('is_buy_now', 0);
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
                    $query->where('user_id', Auth::id())->where('is_buy_now', 0);
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

    public function adminIndex(Request $request)
    {
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $status = $request->get('status');
        $query  = ProductCustomization::with(['user', 'product.category']);

        if ($status) {
            $query->where('status', $status);
        }

        $customizations = $query
            ->orderByRaw("FIELD(status, 'Pending', 'Approved', 'Rejected', 'Completed')")
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.customizations.index', compact('customizations'));
    }

    public function adminShow($id)
    {
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $customization = ProductCustomization::with(['user', 'product.category', 'options'])
            ->findOrFail($id);

        return view('admin.customizations.show', compact('customization'));
    }

    /**
     * Admin: Update customization status, price, and official price breakdown
     */
    public function adminUpdate(Request $request, $id)
    {
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $request->validate([
            'status'                      => 'required|in:Pending,Approved,Rejected,Completed',
            'admin_price'                 => 'required_if:status,Approved|nullable|numeric|min:0',
            'admin_notes'                 => 'nullable|string',
            // Official price breakdown rows
            'breakdown'                   => 'nullable|array',
            'breakdown.*.label'           => 'required_with:breakdown|string|max:255',
            'breakdown.*.quantity'        => 'required_with:breakdown|numeric|min:0',
            'breakdown.*.unit_price'      => 'required_with:breakdown|numeric|min:0',
        ]);

        $customization = ProductCustomization::findOrFail($id);

        DB::beginTransaction();
        try {
            // Build breakdown rows and sum total
            $breakdownRows  = [];
            $breakdownTotal = 0;

            if ($request->filled('breakdown')) {
                foreach ($request->breakdown as $row) {
                    if (empty($row['label'])) continue;

                    $subtotal        = (float)$row['quantity'] * (float)$row['unit_price'];
                    $breakdownTotal += $subtotal;

                    $breakdownRows[] = [
                        'label'      => $row['label'],
                        'quantity'   => (float)$row['quantity'],
                        'unit_price' => (float)$row['unit_price'],
                        'subtotal'   => $subtotal,
                    ];
                }
            }

            $updateData = [
                'status'          => $request->status,
                'admin_notes'     => $request->admin_notes,
                'admin_id'        => session('admin_id'),
                'price_breakdown' => !empty($breakdownRows) ? $breakdownRows : null,
            ];

            if ($request->status === 'Approved') {
                // Use typed price if provided, otherwise use breakdown total
                $finalPrice = $request->filled('admin_price')
                    ? (float)$request->admin_price
                    : $breakdownTotal;

                $updateData['admin_price'] = $finalPrice;
                $updateData['total_price'] = $finalPrice;
            } elseif ($request->status !== 'Completed') {
                // Don't wipe price if marking Completed
                $updateData['admin_price'] = null;
            }

            $customization->update($updateData);

            Log::info('Customization status updated by admin', [
                'admin_id'         => session('admin_id'),
                'customization_id' => $customization->id,
                'status'           => $request->status,
                'price'            => $updateData['admin_price'] ?? 'N/A',
                'breakdown_items'  => count($breakdownRows),
            ]);

            DB::commit();

            return redirect()->route('admin.customizations.index')
                ->with('success', 'Customization updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Customization update failed', [
                'error'    => $e->getMessage(),
                'admin_id' => session('admin_id'),
            ]);

            return redirect()->back()
                ->with('error', 'Error updating customization: ' . $e->getMessage());
        }
    }

    public function adminDestroy($id)
    {
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $customization = ProductCustomization::findOrFail($id);

        if ($customization->custom_image) {
            $imagePath = public_path('uploads/customizations/' . $customization->custom_image);
            if (file_exists($imagePath)) unlink($imagePath);
        }

        $customization->delete();

        Log::info('Customization deleted by admin', [
            'customization_id' => $id,
            'admin_id'         => session('admin_id'),
        ]);

        return redirect()->route('admin.customizations.index')
            ->with('success', 'Customization deleted successfully');
    }

    public function getProductsByCategory($categoryId)
    {
        $products = Product::where('category_id', $categoryId)
            ->where('is_available', 1)
            ->orderBy('id', 'asc')
            ->get(['id', 'name', 'price', 'image', 'description']);

        if ($products->count() > 0) {
            return response()->json(['success' => true, 'products' => $products]);
        }

        return response()->json(['success' => false, 'message' => 'No available products in this category'], 404);
    }

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

        return response()->json(['success' => false, 'message' => 'No available products in this category'], 404);
    }
}