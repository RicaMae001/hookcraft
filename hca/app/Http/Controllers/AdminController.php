<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\GalleryImage;
use App\Models\Category;
use App\Models\User;
use App\Models\Product;
use App\Services\NotificationService;

class AdminController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    // ============================================
    // ROLE-BASED AUTHORIZATION HELPERS
    // ============================================
    
    /**
     * Check if user is logged in as admin
     */
    private function checkAdminAuth()
    {
        if (!session('admin_id')) {
            return redirect()->route('staff.login')->with('error', 'Please login first');
        }

        $admin = DB::table('admin')->where('id', session('admin_id'))->first();
        
        if (!$admin) {
            session()->forget(['admin_id', 'admin_name', 'admin_role', 'user_type']);
            return redirect()->route('staff.login')->with('error', 'Session expired. Please login again.');
        }

        return null;
    }

    /**
     * Check if admin is Admin (full access)
     */
    private function isAdmin()
    {
        return session('admin_role') === 'Admin';
    }

    /**
     * Check if admin is Staff (limited access)
     */
    private function isStaff()
    {
        return session('admin_role') === 'Staff';
    }

    /**
     * Require Admin role - Block Staff from accessing
     */
    private function requireAdmin()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        if (!$this->isAdmin()) {
            Log::warning('Staff attempted to access Admin-only resource', [
                'admin_id' => session('admin_id'),
                'role' => session('admin_role'),
                'url' => request()->url()
            ]);
            
            // Return JSON for AJAX requests
            if (request()->ajax()) {
                return response()->json([
                    'error' => true,
                    'title' => 'Access Denied',
                    'message' => 'Only Admin can access this section.'
                ], 403);
            }
            
            return redirect()->route('admin.dashboard')
                ->with('error_modal', [
                    'title' => 'Access Denied',
                    'message' => 'Only Admin can access this section.'
                ]);
        }

        return null;
    }

    // ============================================
    // NOTIFICATION METHODS (Updated for database-based service)
    // ============================================
    
    private function getAllNotifications()
    {
        // Using NotificationService with database storage
        $adminId = session('admin_id');
        if (!$adminId) {
            return collect(); // Return empty collection if not logged in
        }
        
        return $this->notificationService->getNotifications('admin', $adminId, 50, false);
    }

    private function getUnreadCount()
    {
        // Using NotificationService with database storage
        $adminId = session('admin_id');
        if (!$adminId) {
            return 0; // Return 0 if not logged in
        }
        
        return $this->notificationService->getUnreadCount('admin', $adminId);
    }

    private function markAsRead($notificationId)
    {
        // Using NotificationService with database storage
        $this->notificationService->markAsRead($notificationId);
    }

    private function markAllRead()
    {
        // Using NotificationService with database storage
        $adminId = session('admin_id');
        if ($adminId) {
            $this->notificationService->markAllAsRead('admin', $adminId);
        }
    }

    // ============================================
    // STOCK MANAGEMENT METHODS
    // ============================================
    
    private function deductStockFromOrder($orderId)
    {
        $orderItems = DB::table('order_item')->where('order_id', $orderId)->get();

        foreach ($orderItems as $item) {
            $product = Product::find($item->product_id);

            if ($product) {
                $newStock = $product->stock - $item->quantity;
                
                if ($newStock < 0) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $product->update(['stock' => $newStock]);

                // Check for low stock notification
                if ($newStock <= 5 && $newStock > 0) {
                    $this->notificationService->productLowStock(
                        $product->id, 
                        $product->name, 
                        $newStock
                    );
                }
                
                // Check for out of stock notification
                if ($newStock <= 0) {
                    $this->notificationService->productOutOfStock(
                        $product->id, 
                        $product->name
                    );
                }
            }
        }
    }

    private function restoreStockFromOrder($orderId)
    {
        $orderItems = DB::table('order_item')->where('order_id', $orderId)->get();

        foreach ($orderItems as $item) {
            $product = Product::find($item->product_id);
            if ($product) {
                $product->increment('stock', $item->quantity);
            }
        }
    }

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

    // ============================================
    // LOGIN & AUTHENTICATION
    // ============================================
    
    public function showLogin()
    {
        return view('admin.login');
    }

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

            Log::info('Admin logged in', [
                'admin_id' => $admin->id,
                'role' => $admin->role
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Welcome back, ' . $admin->name);
        }

        // Try Delivery Coordinator Login
        $coordinator = DB::table('delivery_coordinator')->where('email', $credentials['email'])->first();

        if ($coordinator && Hash::check($credentials['password'], $coordinator->password)) {
            if ($coordinator->status !== 'Active') {
                return back()->withErrors(['email' => 'Your account has been deactivated. Please contact admin.']);
            }

            session([
                'coordinator_id' => $coordinator->coordinator_id,
                'coordinator_name' => $coordinator->name,
                'coordinator_email' => $coordinator->email,
                'user_type' => 'delivery'
            ]);

            Log::info('Delivery coordinator logged in', [
                'coordinator_id' => $coordinator->coordinator_id
            ]);

            return redirect()->route('delivery.dashboard')->with('success', 'Welcome back, ' . $coordinator->name);
        }

        Log::warning('Failed login attempt', ['email' => $credentials['email']]);
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Log::info('Admin logging out', [
            'admin_id' => session('admin_id'),
            'role' => session('admin_role')
        ]);

        session()->forget(['admin_id', 'admin_name', 'admin_role', 'user_type']);
        session()->regenerate();
        
        return redirect()->route('staff.login')->with('success', 'Logged out successfully');
    }

    // ============================================
    // DASHBOARD - ACCESSIBLE TO ALL
    // ============================================
    
    public function dashboard()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $notifications = $this->getAllNotifications();
        $unreadCount = $this->getUnreadCount();

        // Sales Analytics
        $totalSales = DB::table('orders')->where('payment_status', 'Paid')->sum('total');
        $totalOrders = DB::table('orders')->count();
        $pendingOrders = DB::table('orders')->where('delivery_status', 'Pending')->count();
        $totalProducts = Product::count();
        $totalUsers = User::count();

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

        $lowStockProducts = Product::where('stock', '<=', 5)
            ->where('stock', '>', 0)
            ->orderBy('stock', 'asc')
            ->limit(10)
            ->get();

        $outOfStockProducts = Product::where('stock', '<=', 0)
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        // Add role information for view
        $isAdmin = $this->isAdmin();
        $isStaff = $this->isStaff();

        return view('admin.dashboard', compact(
            'totalSales', 'totalOrders', 'pendingOrders', 'totalProducts', 
            'totalUsers', 'monthlySales', 'recentOrders', 'topProducts',
            'notifications', 'unreadCount', 'lowStockProducts', 'outOfStockProducts',
            'isAdmin', 'isStaff'
        ));
    }

    // ============================================
    // NOTIFICATION ACTIONS (Updated for database)
    // ============================================
    
    public function markNotificationAsRead($notificationId)
    {
        $success = $this->notificationService->markAsRead($notificationId);
        return response()->json(['success' => $success]);
    }

    public function markAllNotificationsAsRead()
    {
        $adminId = session('admin_id');
        if ($adminId) {
            $this->notificationService->markAllAsRead('admin', $adminId);
        }
        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    public function deleteNotification($notificationId)
    {
        $success = $this->notificationService->delete($notificationId);
        return response()->json(['success' => $success]);
    }

    // ============================================
    // USER MANAGEMENT - ADMIN HAS FULL ACCESS
    // ============================================
    
    public function users()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $users = User::orderBy('created_at', 'desc')->get();
        $isAdmin = $this->isAdmin();
        $isStaff = $this->isStaff();
        
        return view('admin.users', compact('users', 'isAdmin', 'isStaff'));
    }

    public function deleteUser($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $user = User::findOrFail($id);
        $user->delete();

        Log::info('User deleted', [
            'user_id' => $id,
            'admin_id' => session('admin_id'),
            'admin_role' => session('admin_role')
        ]);

        return redirect()->back()->with('success', 'User deleted successfully');
    }

    public function updateUser(Request $request, $id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $user = User::findOrFail($id);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        Log::info('User updated', [
            'user_id' => $id,
            'admin_id' => session('admin_id'),
            'admin_role' => session('admin_role')
        ]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    // ============================================
    // PRODUCT MANAGEMENT - ADMIN HAS FULL ACCESS
    // ============================================
    
    public function products()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $products = Product::with('category')->orderBy('id', 'desc')->get();
        $categories = Category::orderBy('name')->get();
        
        $isAdmin = $this->isAdmin();
        $isStaff = $this->isStaff();
        
        return view('admin.products', compact('products', 'categories', 'isAdmin', 'isStaff'));
    }

    public function storeProduct(Request $request)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

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

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
            'image' => $imageName,
            'admin_id' => session('admin_id'),
        ]);

        // Send product created notification
        $this->notificationService->productCreated(
            $product->id,
            $validated['name'],
            session('admin_name')
        );

        Log::info('Product created', [
            'product_name' => $validated['name'],
            'admin_id' => session('admin_id')
        ]);

        cache()->forget('products');
        cache()->forget('categories');

        return redirect()->back()->with('success', 'Product added successfully');
    }

    public function updateProduct(Request $request, $id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::findOrFail($id);

        $updateData = [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
        ];

        if ($request->hasFile('image')) {
            if ($product->image && file_exists(public_path('asset/images/' . $product->image))) {
                unlink(public_path('asset/images/' . $product->image));
            }

            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('asset/images'), $imageName);
            $updateData['image'] = $imageName;
        }

        $product->update($updateData);

        // Check stock levels for notifications
        if ($product->stock <= 5 && $product->stock > 0) {
            $this->notificationService->productLowStock(
                $product->id, 
                $product->name, 
                $product->stock
            );
        } elseif ($product->stock <= 0) {
            $this->notificationService->productOutOfStock(
                $product->id, 
                $product->name
            );
        }

        Log::info('Product updated', [
            'product_id' => $id,
            'admin_id' => session('admin_id')
        ]);

        cache()->forget('products');
        cache()->forget('categories');

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function deleteProduct($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $product = Product::findOrFail($id);
        
        if ($product->image && file_exists(public_path('asset/images/' . $product->image))) {
            unlink(public_path('asset/images/' . $product->image));
        }

        $product->delete();

        Log::info('Product deleted', [
            'product_id' => $id,
            'admin_id' => session('admin_id')
        ]);

        cache()->forget('products');
        cache()->forget('categories');

        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    // ============================================
    // CATEGORY MANAGEMENT - ADMIN HAS FULL ACCESS
    // ============================================
    
    public function storeCategory(Request $request)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        Category::create([
            'name' => $validated['name'],
            'limited_edition' => $request->has('limited_edition') ? 1 : 0,
        ]);

        Log::info('Category created', [
            'category_name' => $validated['name'],
            'admin_id' => session('admin_id')
        ]);

        cache()->forget('categories');
        cache()->forget('products');

        return redirect()->back()->with('success', 'Category added successfully');
    }

    public function updateCategory(Request $request, $id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
        ]);

        $category = Category::findOrFail($id);
        
        $category->update([
            'name' => $validated['name'],
            'limited_edition' => $request->has('limited_edition') ? 1 : 0,
        ]);

        Log::info('Category updated', [
            'category_id' => $id,
            'admin_id' => session('admin_id')
        ]);

        cache()->forget('categories');
        cache()->forget('products');

        return redirect()->route('admin.products')->with('success', 'Category updated successfully.');
    }

    public function deleteCategory($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $category = Category::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return redirect()->back()->withErrors([
                'error' => 'Cannot delete category with existing products. Please reassign or delete the products first.'
            ]);
        }
        
        $category->delete();

        Log::info('Category deleted', [
            'category_id' => $id,
            'admin_id' => session('admin_id')
        ]);

        cache()->forget('categories');
        cache()->forget('products');

        return redirect()->back()->with('success', 'Category deleted successfully');
    }

    // ============================================
    // ORDER MANAGEMENT - ADMIN HAS FULL ACCESS
    // ============================================
    
   public function orders()
{
    $authCheck = $this->checkAdminAuth();
    if ($authCheck) return $authCheck;

    // Paginated orders - 15 per page
    $orders = DB::table('orders')
        ->orderBy('created_at', 'desc')
        ->paginate(15);
    
    // Get all orders for stats (not paginated)
    $allOrders = DB::table('orders')->get();
    
    $coordinators = DB::table('delivery_coordinator')
        ->where('status', 'Active')
        ->orderBy('name', 'asc')
        ->get();
    
    $isAdmin = $this->isAdmin();
    $isStaff = $this->isStaff();
    
    return view('admin.orders', compact('orders', 'allOrders', 'coordinators', 'isAdmin', 'isStaff'));
}

public function updateOrderStatus(Request $request, $id)
{
    $authCheck = $this->checkAdminAuth();
    if ($authCheck) return $authCheck;

    $validated = $request->validate([
        'payment_status' => 'required|in:Pending,Paid,Unsuccessful,Refunded',
        'delivery_status' => 'required|in:Pending,Out for Delivery,Delivered,Cancelled',
    ]);

    $currentOrder = DB::table('orders')->where('id', $id)->first();
    
    DB::beginTransaction();

    try {
        DB::table('orders')->where('id', $id)->update([
            'payment_status' => $validated['payment_status'],
            'delivery_status' => $validated['delivery_status'],
        ]);

        if ($validated['payment_status'] === 'Paid' && $currentOrder->payment_status !== 'Paid') {
            $this->deductStockFromOrder($id);
            
            // Send payment received notification
            if ($currentOrder->customer_name && $currentOrder->total) {
                $this->notificationService->paymentReceived(
                    $id,
                    $currentOrder->customer_name,
                    $currentOrder->total
                );
            }
        }

        if ($currentOrder->payment_status === 'Paid' && $validated['payment_status'] !== 'Paid') {
            $this->restoreStockFromOrder($id);
        }

        // Send order updated notification
        if ($currentOrder->customer_name) {
            $this->notificationService->orderUpdated(
                $id,
                $currentOrder->customer_name,
                $currentOrder->payment_status,
                $validated['payment_status'],
                session('admin_name')
            );
        }

        DB::commit();

        Log::info('Order status updated', [
            'order_id' => $id,
            'admin_id' => session('admin_id')
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Order update failed', [
            'order_id' => $id,
            'error' => $e->getMessage()
        ]);
        return redirect()->back()->withErrors(['error' => 'Failed to update order status: ' . $e->getMessage()]);
    }
}

public function deleteOrder($id)
{
    $authCheck = $this->checkAdminAuth();
    if ($authCheck) return $authCheck;

    DB::beginTransaction();

    try {
        $order = DB::table('orders')->where('id', $id)->first();
        
        if ($order && $order->payment_status === 'Paid') {
            $this->restoreStockFromOrder($id);
        }

        // Send order cancelled notification if applicable
        if ($order && $order->customer_name && $order->delivery_status !== 'Cancelled') {
            $this->notificationService->orderCancelled(
                $id,
                $order->customer_name,
                'Order deleted by admin'
            );
        }

        DB::table('orders')->where('id', $id)->delete();

        DB::commit();

        Log::info('Order deleted', [
            'order_id' => $id,
            'admin_id' => session('admin_id')
        ]);

        return redirect()->back()->with('success', 'Order deleted successfully');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Order deletion failed', [
            'order_id' => $id,
            'error' => $e->getMessage()
        ]);
        return redirect()->back()->withErrors(['error' => 'Failed to delete order: ' . $e->getMessage()]);
    }
}

public function assignCoordinator(Request $request, $id)
{
    $authCheck = $this->checkAdminAuth();
    if ($authCheck) return $authCheck;

    $validated = $request->validate([
        'coordinator_id' => 'required|exists:delivery_coordinator,coordinator_id',
    ]);

    $order = DB::table('orders')->where('id', $id)->first();
    $coordinator = DB::table('delivery_coordinator')->where('coordinator_id', $validated['coordinator_id'])->first();

    DB::table('orders')
        ->where('id', $id)
        ->update([
            'coordinator_id' => $validated['coordinator_id'],
            'admin_id' => session('admin_id')
        ]);

    // Send delivery assignment notification
    if ($order && $coordinator) {
        $this->notificationService->deliveryAssigned(
            $id,
            $coordinator->coordinator_id,
            $coordinator->name,
            $order->customer_name ?? 'Customer'
        );
    }

    Log::info('Coordinator assigned', [
        'order_id' => $id,
        'coordinator_id' => $validated['coordinator_id'],
        'admin_id' => session('admin_id')
    ]);

    return redirect()->back()->with('success', 'Delivery coordinator assigned successfully!');
}
    // ============================================
    // STAFF MANAGEMENT - ADMIN ONLY
    // ============================================
    
    public function staffAdmins()
    {
        // Only Admin can access staff management
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $admins = DB::table('admin')->orderBy('id', 'desc')->get();
        return view('admin.staff-admins', compact('admins'));
    }

    public function storeAdmin(Request $request)
    {
        // Only Admin can create admin accounts
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|min:6',
            'role' => 'required|in:Admin,Staff',
        ]);

        DB::table('admin')->insert([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        Log::info('Admin account created', [
            'created_email' => $validated['email'],
            'created_role' => $validated['role'],
            'by_admin_id' => session('admin_id')
        ]);

        return redirect()->back()->with('success', 'Admin account created successfully');
    }

    public function updateAdmin(Request $request, $id)
    {
        // Only Admin can update admin accounts
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin,email,' . $id,
            'role' => 'required|in:Admin,Staff',
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

        Log::info('Admin account updated', [
            'updated_admin_id' => $id,
            'by_admin_id' => session('admin_id')
        ]);

        return redirect()->back()->with('success', 'Admin account updated successfully');
    }

    public function deleteAdmin($id)
    {
        // Only Admin can delete admin accounts
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        if ($id == session('admin_id')) {
            return redirect()->back()->withErrors(['error' => 'You cannot delete your own account']);
        }

        DB::table('admin')->where('id', $id)->delete();

        Log::info('Admin account deleted', [
            'deleted_admin_id' => $id,
            'by_admin_id' => session('admin_id')
        ]);

        return redirect()->back()->with('success', 'Admin account deleted successfully');
    }

    // ============================================
    // DELIVERY COORDINATOR MANAGEMENT - ADMIN ONLY
    // ============================================
    
    public function staffDelivery()
    {
        // Only Admin can access delivery coordinator management
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        $coordinators = DB::table('delivery_coordinator')->orderBy('created_at', 'desc')->get();
        return view('admin.staff-delivery', compact('coordinators'));
    }

    public function storeDelivery(Request $request)
    {
        // Only Admin can create delivery accounts
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

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
            'role' => 'Delivery',
            'created_at' => now(),
        ]);

        Log::info('Delivery coordinator created', [
            'created_email' => $validated['email'],
            'by_admin_id' => session('admin_id')
        ]);

        return redirect()->back()->with('success', 'Delivery coordinator created successfully');
    }

    public function updateDelivery(Request $request, $id)
    {
        // Only Admin can update delivery accounts
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

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

        Log::info('Delivery coordinator updated', [
            'updated_coordinator_id' => $id,
            'by_admin_id' => session('admin_id')
        ]);

        return redirect()->back()->with('success', 'Delivery coordinator updated successfully');
    }

    public function deleteDelivery($id)
    {
        // Only Admin can delete delivery accounts
        $roleCheck = $this->requireAdmin();
        if ($roleCheck) return $roleCheck;

        DB::table('delivery_coordinator')->where('coordinator_id', $id)->delete();

        Log::info('Delivery coordinator deleted', [
            'deleted_coordinator_id' => $id,
            'by_admin_id' => session('admin_id')
        ]);

        return redirect()->back()->with('success', 'Delivery coordinator deleted successfully');
    }

    // ============================================
    // GALLERY MANAGEMENT - ADMIN HAS FULL ACCESS
    // ============================================
    
    public function galleryIndex()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $galleries = GalleryImage::orderBy('display_order', 'asc')->paginate(12);
        
        $isAdmin = $this->isAdmin();
        $isStaff = $this->isStaff();
        
        return view('admin.gallery.index', compact('galleries', 'isAdmin', 'isStaff'));
    }

    public function galleryCreate()
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        return view('admin.gallery.create');
    }

    public function galleryStore(Request $request)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|in:birthday,casual,tiny,wedding,custom',
            'display_order' => 'nullable|integer',
            'is_active' => 'boolean'
        ]);

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

            Log::info('Gallery image created', [
                'title' => $request->title,
                'admin_id' => session('admin_id')
            ]);

            return redirect()->route('admin.gallery.index')
                ->with('success', 'Gallery image added successfully!');
        }

        return back()->with('error', 'Failed to upload image.');
    }

    public function galleryEdit($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $gallery = GalleryImage::findOrFail($id);
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function galleryUpdate(Request $request, $id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

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

        if ($request->hasFile('image')) {
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

        Log::info('Gallery image updated', [
            'gallery_id' => $id,
            'admin_id' => session('admin_id')
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image updated successfully!');
    }

    public function galleryDestroy($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $gallery = GalleryImage::findOrFail($id);

        if (file_exists(public_path('asset/images/' . $gallery->image_path))) {
            unlink(public_path('asset/images/' . $gallery->image_path));
        }

        $gallery->delete();

        Log::info('Gallery image deleted', [
            'gallery_id' => $id,
            'admin_id' => session('admin_id')
        ]);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery image deleted successfully!');
    }

    public function galleryToggleStatus($id)
    {
        $authCheck = $this->checkAdminAuth();
        if ($authCheck) return $authCheck;

        $gallery = GalleryImage::findOrFail($id);
        $gallery->is_active = !$gallery->is_active;
        $gallery->save();

        Log::info('Gallery status toggled', [
            'gallery_id' => $id,
            'new_status' => $gallery->is_active,
            'admin_id' => session('admin_id')
        ]);

        return back()->with('success', 'Gallery status updated successfully!');
    }
}