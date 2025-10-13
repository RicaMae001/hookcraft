<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
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

    // Dashboard
    public function dashboard()
    {
        // Sales Analytics
        $totalSales = DB::table('orders')->where('payment_status', 'Paid')->sum('total');
        $totalOrders = DB::table('orders')->count();
        $pendingOrders = DB::table('orders')->where('delivery_status', 'Pending')->count();
        $totalProducts = DB::table('products')->count();
        $totalUsers = DB::table('users')->count();

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

        return view('admin.dashboard', compact(
            'totalSales', 'totalOrders', 'pendingOrders', 'totalProducts', 
            'totalUsers', 'monthlySales', 'recentOrders', 'topProducts'
        ));
    }

    // User Management
    public function users()
    {
        $users = DB::table('users')->orderBy('created_at', 'desc')->get();
        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }

    // Product Management
    public function products()
    {
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->orderBy('products.id', 'desc')
            ->get();
        $categories = DB::table('categories')->get();
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
        $request->file('image')->move(public_path('uploads/products'), $imageName);

        DB::table('products')->insert([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
            'image' => $imageName,
            'admin_id' => session('admin_id'),
        ]);

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

        $updateData = [
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'description' => $validated['description'],
        ];

        if ($request->hasFile('image')) {
            $imageName = time() . '_' . $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path('uploads/products'), $imageName);
            $updateData['image'] = $imageName;
        }

        DB::table('products')->where('id', $id)->update($updateData);

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function deleteProduct($id)
    {
        DB::table('products')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    // Category Management
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);

        DB::table('categories')->insert([
            'name' => $validated['name'],
        ]);

        return redirect()->back()->with('success', 'Category added successfully');
    }

    public function updateCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $id,
        ]);

        DB::table('categories')->where('id', $id)->update([
            'name' => $validated['name'],
        ]);

        return redirect()->back()->with('success', 'Category updated successfully');
    }

    public function deleteCategory($id)
    {
        $productCount = DB::table('products')->where('category_id', $id)->count();
        
        if ($productCount > 0) {
            return redirect()->back()->withErrors(['error' => 'Cannot delete category with existing products.']);
        }

        DB::table('categories')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Category deleted successfully');
    }

    // Order Management
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

        DB::table('orders')->where('id', $id)->update([
            'payment_status' => $validated['payment_status'],
            'delivery_status' => $validated['delivery_status'],
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function deleteOrder($id)
    {
        DB::table('orders')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Order deleted successfully');
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

    // Staff Management - Admin Accounts
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

    // Staff Management - Delivery Coordinators
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
}