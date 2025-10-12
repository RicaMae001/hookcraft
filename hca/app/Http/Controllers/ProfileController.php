<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    // Show user profile/settings page
    public function index()
    {
        $user = Auth::user();
        
        // Get cart count
        $cartCount = DB::table('cart_item')
            ->join('cart', 'cart_item.cart_id', '=', 'cart.id')
            ->where('cart.user_id', $user->id)
            ->count();

        return view('pages.profile', compact('user', 'cartCount'));
    }

    // Update profile
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'password' => Hash::make($request->new_password),
            ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }

    // Show purchase history
    public function purchaseHistory()
    {
        $user = Auth::user();
        
        // Get orders with items
        $orders = DB::table('orders')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get cart count
        $cartCount = DB::table('cart_item')
            ->join('cart', 'cart_item.cart_id', '=', 'cart.id')
            ->where('cart.user_id', $user->id)
            ->count();

        return view('pages.purchase_history', compact('orders', 'cartCount'));
    }

    // Show track order page
    public function trackOrder()
    {
        $user = Auth::user();
        
        $orders = DB::table('orders')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get cart count
        $cartCount = DB::table('cart_item')
            ->join('cart', 'cart_item.cart_id', '=', 'cart.id')
            ->where('cart.user_id', $user->id)
            ->count();

        return view('pages.track_order', compact('orders', 'cartCount'));
    }
}