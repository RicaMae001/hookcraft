<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCustomization;
use App\Models\CustomizationOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomizationController extends Controller
{
    // Show customization page
    public function create($productId)
    {
        $product = Product::with('category')->findOrFail($productId);
        
        // Check if product is customizable
        $customizable = DB::table('customizable_products')
            ->where('product_id', $productId)
            ->where('is_customizable', 1)
            ->first();
            
        if (!$customizable) {
            return redirect()->back()->with('error', 'This product is not customizable.');
        }
        
        return view('customization.create', compact('product'));
    }

    // Store customization with canvas data
    public function store(Request $request, $productId)
    {
        $request->validate([
            'customization_name' => 'required|string|max:255',
            'customization_details' => 'required|string',
            'special_instructions' => 'nullable|string',
            'custom_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'canvas_data' => 'nullable|string', // JSON data from canvas
            'canvas_image' => 'nullable|string', // Base64 image
            'options.*.type' => 'nullable|string',
            'options.*.value' => 'nullable|string',
            'options.*.price' => 'nullable|numeric|min:0',
        ]);

        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to customize products.');
        }

        DB::beginTransaction();
        
        try {
            // Handle canvas image (base64)
            $imagePath = null;
            if ($request->filled('canvas_image')) {
                $imageData = $request->canvas_image;
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    
                    $imageData = base64_decode($imageData);
                    $imageName = time() . '_canvas.' . $type;
                    file_put_contents(public_path('uploads/customizations/' . $imageName), $imageData);
                    $imagePath = $imageName;
                }
            }
            // Handle regular image upload
            elseif ($request->hasFile('custom_image')) {
                $image = $request->file('custom_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/customizations'), $imageName);
                $imagePath = $imageName;
            }

            // Calculate total price
            $totalPrice = 50.00; // Base customization price
            if ($request->has('options')) {
                foreach ($request->options as $option) {
                    if (!empty($option['price'])) {
                        $totalPrice += floatval($option['price']);
                    }
                }
            }

            // Create customization
            $customization = ProductCustomization::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'customization_name' => $request->customization_name,
                'customization_details' => $request->customization_details,
                'special_instructions' => $request->special_instructions,
                'custom_image' => $imagePath,
                'total_price' => $totalPrice,
                'status' => $request->status ?? 'Draft',
            ]);

            // Save canvas data as customization option if provided
            if ($request->filled('canvas_data')) {
                CustomizationOption::create([
                    'customization_id' => $customization->id,
                    'option_type' => 'canvas_design',
                    'option_value' => $request->canvas_data,
                    'additional_price' => 50.00,
                ]);
            }

            // Save customization options
            if ($request->has('options')) {
                foreach ($request->options as $option) {
                    if (!empty($option['type']) && !empty($option['value'])) {
                        CustomizationOption::create([
                            'customization_id' => $customization->id,
                            'option_type' => $option['type'],
                            'option_value' => $option['value'],
                            'additional_price' => $option['price'] ?? 0,
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->status == 'Draft') {
                return redirect()->route('customization.my-customizations')
                    ->with('success', 'Customization saved as draft!');
            }

            // Redirect to cart
            return redirect()->route('cart.index')
                ->with('success', 'Product customization created! Add to cart to proceed.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error creating customization: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Show user's customizations
    public function myCustomizations()
    {
        $customizations = ProductCustomization::with(['product', 'options', 'order'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customization.my-customizations', compact('customizations'));
    }

    // Show single customization
    public function show($id)
    {
        $customization = ProductCustomization::with(['product', 'options', 'order', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('customization.show', compact('customization'));
    }

    // Edit customization (redirect to canvas)
    public function edit($id)
    {
        $customization = ProductCustomization::with(['product', 'options'])
            ->where('user_id', Auth::id())
            ->where('order_id', null) // Can only edit if not ordered yet
            ->findOrFail($id);

        // Get canvas data if exists
        $canvasOption = $customization->options()
            ->where('option_type', 'canvas_design')
            ->first();

        return view('customization.edit', compact('customization', 'canvasOption'));
    }

    // Update customization
    public function update(Request $request, $id)
    {
        $customization = ProductCustomization::where('user_id', Auth::id())
            ->where('order_id', null)
            ->findOrFail($id);

        $request->validate([
            'customization_name' => 'required|string|max:255',
            'customization_details' => 'required|string',
            'special_instructions' => 'nullable|string',
            'custom_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'canvas_data' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Handle image upload
            if ($request->hasFile('custom_image')) {
                // Delete old image
                if ($customization->custom_image) {
                    $oldImagePath = public_path('uploads/customizations/' . $customization->custom_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $image = $request->file('custom_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/customizations'), $imageName);
                $customization->custom_image = $imageName;
            }

            // Calculate total price
            $totalPrice = 50.00;
            if ($request->has('options')) {
                foreach ($request->options as $option) {
                    if (!empty($option['price'])) {
                        $totalPrice += floatval($option['price']);
                    }
                }
            }

            // Update customization
            $customization->update([
                'customization_name' => $request->customization_name,
                'customization_details' => $request->customization_details,
                'special_instructions' => $request->special_instructions,
                'total_price' => $totalPrice,
            ]);

            // Update canvas data if provided
            if ($request->filled('canvas_data')) {
                $canvasOption = $customization->options()
                    ->where('option_type', 'canvas_design')
                    ->first();
                    
                if ($canvasOption) {
                    $canvasOption->update(['option_value' => $request->canvas_data]);
                } else {
                    CustomizationOption::create([
                        'customization_id' => $customization->id,
                        'option_type' => 'canvas_design',
                        'option_value' => $request->canvas_data,
                        'additional_price' => 50.00,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('customization.my-customizations')
                ->with('success', 'Customization updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating customization: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Delete customization
    public function destroy($id)
    {
        $customization = ProductCustomization::where('user_id', Auth::id())
            ->where('order_id', null)
            ->findOrFail($id);

        // Delete image if exists
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
}