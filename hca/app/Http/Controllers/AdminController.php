<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Models\GalleryImage;
use App\Models\Category;
use App\Models\User;
use App\Models\Product; // Added this

class AdminController extends Controller
{
    // ⭐ ADD THESE NEW METHODS FOR NOTIFICATIONS
    /**
     * Get all notifications from session
     */
    private function getAllNotifications()
    {
        return Session::get('admin_notifications', []);
    }

    /**
     * Get unread notifications count
     */
    private function getUnreadCount()
    {
        $notifications = $this->getAllNotifications();
        return count(array_filter($notifications, function($n) {
            return !$n['is_read'];
        }));
    }

    /**
     * Mark notification as read
     */
    private function markAsRead($notificationId)
    {
        $notifications = $this->getAllNotifications();
        
        foreach ($notifications as &$notification) {
            if ($notification['id'] === $notificationId) {
                $notification['is_read'] = true;
                break;
            }
        }
        
        Session::put('admin_notifications', $notifications);
    }

    /**
     * Mark all notifications as read
     */
    private function markAllRead()
    {
        $notifications = $this->getAllNotifications();
        
        foreach ($notifications as &$notification) {
            $notification['is_read'] = true;
        }
        
        Session::put('admin_notifications', $notifications);
    }

    // ⭐ ADD THESE NEW PRIVATE METHODS FOR STOCK MANAGEMENT
    /**
     * Deduct stock for all items in an order
     */
    private function deductStockFromOrder($orderId)
    {
        // Get all order items
        $orderItems = DB::table('order_item')
            ->where('order_id', $orderId)
            ->get();

        foreach ($orderItems as $item) {
            // Get current product using Eloquent
            $product = Product::find($item->product_id);

            if ($product) {
                $newStock = $product->stock - $item->quantity;
                
                // Ensure stock doesn't go negative
                if ($newStock < 0) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                // Update product stock
                $product->update(['stock' => $newStock]);
            }
        }
    }

    /**
     * Restore stock for all items in an order (for refunds/cancellations)
     */
    private function restoreStockFromOrder($orderId)
    {
        // Get all order items
        $orderItems = DB::table('order_item')
            ->where('order_id', $orderId)
            ->get();

        foreach ($orderItems as $item) {
            // Restore stock using Eloquent
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }
    }

    /**
     * Validate stock before order creation
     */
    private function validateOrderStock($orderItems)
    {
        foreach ($orderItems as $item) {
            $product = Product::find($item->product_id);
            
            if (!$product) {
                throw new \Exception("Product not found: {$item->product_id}");
            }
            
            if ($product->stock < $item->quantity) {
                throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$product->stock}, Requested: {$item->quantity}");
            }
        }
        return true;
    }

    // Show unified staff login page
    public function showLogin()
    {
        return view('admin.login');
    }

    // Unified Staff Login (Admin & Delivery)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Try Admin Login First
        $admin = DB::table('admin')->where('email', $credentials['email'])->first();

        if ($admin && Hash::check($credentials['password'], $admin->password)) {
            session([
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_role' => $admin->role,
                'user_type' => 'admin'
            ]);
            return redirect()->route('admin.dashboard');
        }

        // Try Delivery Coordinator Login
        $coordinator = DB::table('delivery_coordinator')->where('email', $credentials['email'])->first();

        if ($coordinator && Hash::check($credentials['password'], $coordinator->password)) {
            // Check if coordinator is active
            if ($coordinator->status !== 'Active') {
                return back()->withErrors(['email' => 'Your account has been deactivated. Please contact admin.']);
            }

            session([
                'coordinator_id' => $coordinator->coordinator_id,
                'coordinator_name' => $coordinator->name,
                'coordinator_email' => $coordinator->email,
                'user_type' => 'delivery'
            ]);
            return redirect()->route('delivery.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // Admin logout
    public function logout()
    {
        session()->forget(['admin_id', 'admin_name', 'admin_role', 'user_type']);
        return redirect()->route('staff.login');
    }

    // Dashboard - ⭐ UPDATED WITH NOTIFICATIONS AND STOCK ALERTS
    public function dashboard()
    {
        // ⭐ ADD THESE TWO LINES - Get notifications from session
        $notifications = $this->getAllNotifications();
        $unreadCount = $this->getUnreadCount();

        // Sales Analytics
        $totalSales = DB::table('orders')->where('payment_status', 'Paid')->sum('total');
        $totalOrders = DB::table('orders')->count();
        $pendingOrders = DB::table('orders')->where('delivery_status', 'Pending')->count();
        $totalProducts = Product::count(); // Changed to Eloquent
        $totalUsers = User::count(); // Changed to Eloquent

        // Monthly Sales (last 6 months)
        $monthlySales = DB::table('orders')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('SUM(total) as total'))
            ->where('payment_status', 'Paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Recent Orders
        $recentOrders = DB::table('orders')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top Selling Products
        $topProducts = DB::table('order_item')
            ->join('products', 'order_item.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_item.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        // ⭐ ADD LOW STOCK ALERTS - Using Eloquent
        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        $outOfStockProducts = Product::where('stock', '<=', 0)
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        // ⭐ ADD 'notifications' and 'unreadCount' to compact()
        return view('admin.dashboard', compact(
            'totalSales', 'totalOrders', 'pendingOrders', 'totalProducts', 
            'totalUsers', 'monthlySales', 'recentOrders', 'topProducts',
            'notifications', 'unreadCount', 'lowStockProducts', 'outOfStockProducts'
        ));
    }

    // ⭐ ADD THESE NEW PUBLIC METHODS FOR NOTIFICATION ACTIONS
    /**
     * Mark a notification as read (AJAX endpoint)
     */
    public function markNotificationAsRead($notificationId)
    {
        $this->markAsRead($notificationId);
        return response()->json(['success' => true]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead()
    {
        $this->markAllRead();
        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Clear all notifications
     */
    public function clearAllNotifications()
    {
        Session::forget('admin_notifications');
        return redirect()->back()->with('success', 'All notifications cleared');
    }

    // User Management
    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->get(); // Changed to Eloquent
        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    // ============================================
    // PRODUCT MANAGEMENT - FIXED WITH ELOQUENT
    // ============================================
    public function products()
    {
        // Use Eloquent instead of DB::table()
        $products = Product::with('category')
            ->orderBy('id', 'desc')
            ->get();
        
        $categories = Category::orderBy('name')->get();
        
        return view('admin.products', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
        $request->file('image')->move(public_path('asset/images'), $imageName);

        // Use Eloquent Model instead of DB::table()
        Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
            'image' => $imageName,
            'admin_id' => session('admin_id'),
        ]);

        // Clear any cache
        cache()->forget('products');
        cache()->forget('categories');

        return redirect()->back()->with('success', 'Product added successfully');
    }

    public function updateProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Use Eloquent Model
        $product = Product::findOrFail($id);

        $updateData = [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image && file_exists(public_path('asset/images/' . $product->image))) {
                unlink(public_path('asset/images/' . $product->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('asset/images'), $imageName);
            $updateData['image'] = $imageName;
        }

        $product->update($updateData);

        // Clear cache
        cache()->forget('products');
        cache()->forget('categories');

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        
        // Delete the product image file if it exists
        if ($product->image && file_exists(public_path('asset/images/' . $product->image))) {
            unlink(public_path('asset/images/' . $product->image));
        }

        $product->delete();

        // Clear cache
        cache()->forget('products');
        cache()->forget('categories');

        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    // ============================================
    // CATEGORY MANAGEMENT - FIXED WITH ELOQUENT
    // ============================================
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        // Use Eloquent Model
        Category::create([
            'name' => $validated['name'],
            'limited_edition' => $request->has('limited_edition') ? 1 : 0,
        ]);

        // Clear cache
        cache()->forget('categories');
        cache()->forget('products');

        return redirect()->back()->with('success', 'Category added successfully');
    }

    public function updateCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
        ]);

        // Use Eloquent Model
        $category = Category::findOrFail($id);
        
        $category->update([
            'name' => $validated['name'],
            'limited_edition' => $request->has('limited_edition') ? 1 : 0,
        ]);

        // Clear cache
        cache()->forget('categories');
        cache()->forget('products');

        return redirect()->route('admin.products')->with('success', 'Category updated successfully.');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        
        // Check if category has products
        if ($category->products()->count() > 0) {
            return redirect()->back()->withErrors([
                'error' => 'Cannot delete category with existing products. Please reassign or delete the products first.'
            ]);
        }
        
        $category->delete();

        // Clear cache
        cache()->forget('categories');
        cache()->forget('products');

        return redirect()->back()->with('success', 'Category deleted successfully');
    }

    // ============================================
    // ORDER MANAGEMENT - UPDATED WITH STOCK MANAGEMENT
    // ============================================
    public function orders()
    {
        $orders = DB::table('orders')->orderBy('created_at', 'desc')->get();
        
        // Fetch all active delivery coordinators
        $coordinators = DB::table('delivery_coordinator')
            ->where('status', 'Active')
            ->orderBy('name', 'asc')
            ->get();
        
        return view('admin.orders', compact('orders', 'coordinators'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:Pending,Paid,Unsuccessful,Refunded',
            'delivery_status' => 'required|in:Pending,Out for Delivery,Delivered,Cancelled',
        ]);

        // Get the current order status before update
        $currentOrder = DB::table('orders')->where('id', $id)->first();
        
        // Start database transaction for data consistency
        DB::beginTransaction();

        try {
            // Update order status
            DB::table('orders')->where('id', $id)->update([
                'payment_status' => $validated['payment_status'],
                'delivery_status' => $validated['delivery_status'],
            ]);

            // ⭐ ADD STOCK DEDUCTION LOGIC HERE
            // If payment status is being changed to "Paid", deduct stock
            if ($validated['payment_status'] === 'Paid' && $currentOrder->payment_status !== 'Paid') {
                $this->deductStockFromOrder($id);
            }

            // ⭐ ADD STOCK RESTORATION LOGIC HERE
            // If payment status is being changed from "Paid" to something else, restore stock
            if ($currentOrder->payment_status === 'Paid' && $validated['payment_status'] !== 'Paid') {
                $this->restoreStockFromOrder($id);
            }

            // Commit transaction
            DB::commit();

            return redirect()->back()->with('success', 'Order status updated successfully');

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to update order status: ' . $e->getMessage()]);
        }
    }

    public function deleteOrder($id)
    {
        // Start transaction for safety
        DB::beginTransaction();

        try {
            // Get order details before deletion
            $order = DB::table('orders')->where('id', $id)->first();
            
            // If order was paid, restore stock before deletion
            if ($order && $order->payment_status === 'Paid') {
                $this->restoreStockFromOrder($id);
            }

            // Delete the order
            DB::table('orders')->where('id', $id)->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Order deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Failed to delete order: ' . $e->getMessage()]);
        }
    }

    public function assignCoordinator(Request $request, $id)
    {
        $validated = $request->validate([
            'coordinator_id' => 'required|exists:delivery_coordinator,coordinator_id',
        ]);

        DB::table('orders')
            ->where('id', $id)
            ->update([
                'coordinator_id' => $validated['coordinator_id'],
                'admin_id' => session('admin_id')
            ]);

        return redirect()->back()->with('success', 'Delivery coordinator assigned successfully!');
    }

    // ============================================
    // STAFF MANAGEMENT - ADMIN ACCOUNTS
    // ============================================
    public function staffAdmins()
    {
        $admins = DB::table('admin')->orderBy('id', 'desc')->get();
        return view('admin.staff-admins', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|min:6',
            'role' => 'required|in:SuperAdmin,Staff',
        ]);

        DB::table('admin')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->back()->with('success', 'Admin account created successfully');
    }

    public function updateAdmin(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email,' . $id,
            'role' => 'required|in:SuperAdmin,Staff',
            'password' => 'nullable|min:6',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table('admin')->where('id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Admin account updated successfully');
    }

    public function deleteAdmin($id)
    {
        if ($id == session('admin_id')) {
            return redirect()->back()->withErrors(['error' => 'You cannot delete your own account']);
        }

        DB::table('admin')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Admin account deleted successfully');
    }

    // ============================================
    // STAFF MANAGEMENT - DELIVERY COORDINATORS
    // ============================================
    public function staffDelivery()
    {
        $coordinators = DB::table('delivery_coordinator')->orderBy('created_at', 'desc')->get();
        return view('admin.staff-delivery', compact('coordinators'));
    }

    public function storeDelivery(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:delivery_coordinator,email',
            'password' => 'required|min:6',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:Active,Inactive',
        ]);

        DB::table('delivery_coordinator')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'status' => $validated['status'],
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Delivery coordinator created successfully');
    }

    public function updateDelivery(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:delivery_coordinator,email,' . $id . ',coordinator_id',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:Active,Inactive',
            'password' => 'nullable|min:6',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table('delivery_coordinator')->where('coordinator_id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Delivery coordinator updated successfully');
    }

    public function deleteDelivery($id)
    {
        DB::table('delivery_coordinator')->where('coordinator_id', $id)->delete();
        return redirect()->back()->with('success', 'Delivery coordinator deleted successfully');
    }

    // ============================================
    // GALLERY MANAGEMENT METHODS
    // ============================================
    
    /**
     * Display gallery images
     */
    public function galleryIndex()
    {
        $galleries = GalleryImage::orderBy('display_order', 'asc')->paginate(12);
        return view('admin.gallery.index', compact('galleries'));
    }

    /**
     * Show gallery creation form
     */
    public function galleryCreate()
    {
        return view('admin.gallery.create');
    }

    /**
     * Store new gallery image
     */
    public function galleryStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|in:birthday,casual,tiny,wedding,custom',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('asset/images'), $imageName);

            GalleryImage::create([
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $imageName,
                'category' => $request->category,
                'display_order' => $request->display_order ?? 0,
                'is_active' => $request->has('is_active') ? 1 : 0,
                'admin_id' => session('admin_id')
            ]);

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Gallery image added successfully!');
        }

        return back()->with('error', 'Failed to upload image.');
    }

    /**
     * Show gallery edit form
     */
    public function galleryEdit($id)
    {
        $gallery = GalleryImage::findOrFail($id);
        return view('admin.gallery.edit', compact('gallery'));
    }

    /**
     * Update gallery image
     */
    public function galleryUpdate(Request $request, $id)
    {
        $gallery = GalleryImage::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|in:birthday,casual,tiny,wedding,custom',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

        $imageName = $gallery->image_path;

        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if (file_exists(public_path('asset/images/' . $gallery->image_path))) {
                unlink(public_path('asset/images/' . $gallery->image_path));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('asset/images'), $imageName);
        }

        $gallery->update([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imageName,
            'category' => $request->category,
            'display_order' => $request->display_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image updated successfully!');
    }

    /**
     * Delete gallery image
     */
    public function galleryDestroy($id)
    {
        $gallery = GalleryImage::findOrFail($id);

        // Delete image file
        if (file_exists(public_path('asset/images/' . $gallery->image_path))) {
            unlink(public_path('asset/images/' . $gallery->image_path));
        }

        $gallery->delete();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image deleted successfully!');
    }

    /**
     * Toggle gallery image status
     */
    public function galleryToggleStatus($id)
    {
        $gallery = GalleryImage::findOrFail($id);
        $gallery->is_active = !$gallery->is_active;
        $gallery->save();

        return back()->with('success', 'Gallery status updated successfully!');
    }
}