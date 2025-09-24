<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

// Home
Route::get('/', [ProductController::class, 'index'])->name('home');

// Shop, About, Gallery, Contact
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/gallery', fn() => view('gallery'))->name('gallery');
Route::get('/contact', fn() => view('contact'))->name('contact');

// Product Details
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Cart + Checkout (protected by auth)
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // ✅ Thank You Page
    Route::get('/thankyou/{order_id}', function ($order_id) {
        return view('pages.thankyou', [
            'order_id' => $order_id,
            'cartCount' => CartItem::whereHas('cart', function ($q) {
                $q->where('user_id', Auth::id());
            })->count()
        ]);
    })->name('thankyou');
});

// Authentication
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
