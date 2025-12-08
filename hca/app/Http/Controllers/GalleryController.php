<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // Public gallery page
    public function index()
    {
        $galleryItems = GalleryImage::with('category')->active()->ordered()->get();
        $cartCount = 0;
        
        if (auth()->check()) {
            $cart = auth()->user()->cart;
            $cartCount = $cart ? $cart->items()->sum('quantity') : 0;
        }

        return view('pages.gallery', compact('galleryItems', 'cartCount'));
    }

    // Admin gallery index
    public function adminIndex()
    {
        $galleryItems = GalleryImage::with('category')->ordered()->get();
        return view('admin.gallery.index', compact('galleryItems'));
    }

    // Show create form
    public function create()
    {
        $categories = Category::all();
        return view('admin.gallery.create', compact('categories'));
    }

    // Store new gallery item
    public function store(Request $request)
    {
        \Log::info('Gallery Store Method Called', ['request_data' => $request->all()]);

        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_id' => 'required|exists:categories,id',
                'display_order' => 'required|integer|min:0',
            ]);

            \Log::info('Validation passed');

            // Handle image upload - SAVE TO asset/images DIRECTORY (same as products)
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                // Move image to public/asset/images directory (same as products)
                $image->move(public_path('asset/images'), $imageName);
                $imagePath = $imageName;
                \Log::info('Image uploaded to: asset/images/' . $imagePath);
            }

            $galleryData = [
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $imagePath,
                'category_id' => $request->category_id,
                'display_order' => $request->display_order,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'admin_id' => auth()->id(), 
            ];

            \Log::info('Creating gallery item with data:', $galleryData);

            $galleryImage = GalleryImage::create($galleryData);
            \Log::info('Gallery item created successfully with ID: ' . $galleryImage->id);

            // return ($galleryImage );
            return redirect()->route('admin.gallery.index')
                ->with('success', 'Gallery item created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Error creating gallery item: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Error creating gallery item: ' . $e->getMessage())
                        ->withInput();
        }
    }

    // Show edit form
    public function edit($id)
    {
        $galleryItem = GalleryImage::with('category')->findOrFail($id);
        $categories = Category::all();
        return view('admin.gallery.edit', compact('galleryItem', 'categories'));
    }

    // Update gallery item
    public function update(Request $request, $id)
    {
        \Log::info('Gallery Update Method Called', ['id' => $id, 'request_data' => $request->all()]);

        try {
            $galleryItem = GalleryImage::findOrFail($id);

            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_id' => 'required|exists:categories,id',
                'display_order' => 'required|integer|min:0',
            ]);

            \Log::info('Validation passed for update');

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'display_order' => $request->display_order,
                'is_active' => $request->has('is_active') ? 1 : 0,
            ];

            // Handle image update - SAVE TO asset/images DIRECTORY (same as products)
            if ($request->hasFile('image')) {
                \Log::info('New image uploaded');
                // Delete old image from asset/images directory
                if ($galleryItem->image_path && file_exists(public_path('asset/images/' . $galleryItem->image_path))) {
                    unlink(public_path('asset/images/' . $galleryItem->image_path));
                    \Log::info('Old image deleted: asset/images/' . $galleryItem->image_path);
                }
                
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('asset/images'), $imageName);
                $data['image_path'] = $imageName;
                \Log::info('New image stored at: asset/images/' . $data['image_path']);
            }

            \Log::info('Updating gallery item with data:', $data);
            $galleryItem->update($data);
            \Log::info('Gallery item updated successfully');

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Gallery item updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed: ' . json_encode($e->errors()));
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            \Log::error('Error updating gallery item: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Error updating gallery item: ' . $e->getMessage())
                        ->withInput();
        }
    }

    // Delete gallery item
    public function destroy($id)
    {
        \Log::info('Gallery Destroy Method Called', ['id' => $id]);

        try {
            $galleryItem = GalleryImage::findOrFail($id);

            // Delete image file from asset/images directory
            if ($galleryItem->image_path && file_exists(public_path('asset/images/' . $galleryItem->image_path))) {
                unlink(public_path('asset/images/' . $galleryItem->image_path));
                \Log::info('Image file deleted: asset/images/' . $galleryItem->image_path);
            }

            $galleryItem->delete();
            \Log::info('Gallery item deleted successfully');

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Gallery item deleted successfully.');

        } catch (\Exception $e) {
            \Log::error('Error deleting gallery item: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Error deleting gallery item: ' . $e->getMessage());
        }
    }

    // Toggle active status
    public function toggleStatus($id)
    {
        \Log::info('Toggle Status Method Called', ['id' => $id]);

        try {
            $galleryItem = GalleryImage::findOrFail($id);
            $newStatus = !$galleryItem->is_active;
            
            $galleryItem->update([
                'is_active' => $newStatus
            ]);

            \Log::info('Status toggled to: ' . ($newStatus ? 'active' : 'inactive'));

            return back()->with('success', 'Status updated successfully.');

        } catch (\Exception $e) {
            \Log::error('Error toggling status: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Error updating status: ' . $e->getMessage());
        }
    }

    // Update display order
    public function updateOrder(Request $request)
    {
        \Log::info('Update Order Method Called', ['order_data' => $request->order]);

        try {
            foreach ($request->order as $order) {
                GalleryImage::where('id', $order['id'])->update(['display_order' => $order['position']]);
            }

            \Log::info('Display order updated successfully');

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \Log::error('Error updating display order: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}