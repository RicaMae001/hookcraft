<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\GalleryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LiveChatController;

// Home
Route::get('/', [ProductController::class, 'index'])->name('home');

// Shop, About, Gallery, Contact
Route::get('/shop', [ProductController::class, 'shop'])->name('shop');
Route::get('/about', [ProductController::class, 'about'])->name('about');
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
Route::get('/contact', [ProductController::class, 'contact'])->name('contact');

// Product Details
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Authentication
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// ===================================
// CUSTOMER LIVE CHAT ROUTES (Requires Authentication)
// ===================================
Route::middleware(['auth'])->group(function () {
    // Get active session
    Route::get('/livechat/active-session', [LiveChatController::class, 'getActiveSession'])->name('livechat.active-session');
    
    // Check unread messages (for navbar notification)
    Route::get('/livechat/check-unread', [LiveChatController::class, 'checkUnread'])->name('livechat.check-unread');
    
    // Get chat history
    Route::get('/livechat/history/{sessionId}', [LiveChatController::class, 'getChatHistory'])->name('livechat.history');
    
    // Mark messages as read
    Route::post('/livechat/mark-read/{sessionId}', [LiveChatController::class, 'markAsRead'])->name('livechat.mark-read');
    
    // Start chat request
    Route::post('/livechat/request', [LiveChatController::class, 'request'])->name('livechat.request');
    
    // Send message
    Route::post('/livechat/send', [LiveChatController::class, 'sendMessage'])->name('livechat.send');
    
    // Poll for updates
    Route::get('/livechat/poll/{sessionId}', [LiveChatController::class, 'poll'])->name('livechat.poll');
    
    // End session
    Route::post('/livechat/end', [LiveChatController::class, 'endSession'])->name('livechat.end');
    
    // Customer activity heartbeat (NEW)
    Route::post('/livechat/heartbeat', [LiveChatController::class, 'heartbeat'])->name('livechat.heartbeat');
});

// Cart + Checkout + Profile (protected by auth)
Route::middleware('auth')->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buy-now');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Thank You Page
    Route::get('/thankyou/{order_id}', function ($order_id) {
        return view('pages.thankyou', [
            'order_id' => $order_id,
            'cartCount' => CartItem::whereHas('cart', function ($q) {
                $q->where('user_id', Auth::id());
            })->count()
        ]);
    })->name('thankyou');

    // Profile/Settings Routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/profile/purchase-history', [ProfileController::class, 'purchaseHistory'])->name('profile.purchase-history');
    Route::get('/profile/track-order', [ProfileController::class, 'trackOrder'])->name('profile.track-order');
    
    // Customization Routes
    Route::get('/product/{id}/customize', [CustomizationController::class, 'create'])->name('customization.create');
    Route::post('/product/{id}/customize', [CustomizationController::class, 'store'])->name('customization.store');
    Route::get('/my-customizations', [CustomizationController::class, 'myCustomizations'])->name('customization.my-customizations');
    Route::get('/customization/{id}', [CustomizationController::class, 'show'])->name('customization.show');
    Route::get('/customization/{id}/edit', [CustomizationController::class, 'edit'])->name('customization.edit');
    Route::put('/customization/{id}', [CustomizationController::class, 'update'])->name('customization.update');
    Route::delete('/customization/{id}', [CustomizationController::class, 'destroy'])->name('customization.destroy');
    
    // Order Management
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Unified Staff Login (Admin & Delivery)
Route::get('/staff/login', [AdminController::class, 'showLogin'])->name('staff.login');
Route::post('/staff/login', [AdminController::class, 'login'])->name('staff.login.submit');

// Admin Routes
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // Notification Routes
    Route::post('/notifications/{id}/read', [AdminController::class, 'markNotificationAsRead'])->name('notifications.markRead');
    Route::get('/notifications/mark-all-read', [AdminController::class, 'markAllNotificationsAsRead'])->name('notifications.markAllRead');
    Route::get('/notifications/clear-all', [AdminController::class, 'clearAllNotifications'])->name('notifications.clearAll');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');

    // Product Management
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.destroy');
    
    // Category Management
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
    
    // Order Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::put('/orders/{id}', [AdminController::class, 'updateOrderStatus'])->name('orders.update');
    Route::put('/orders/{id}/assign-coordinator', [AdminController::class, 'assignCoordinator'])->name('orders.assign-coordinator');
    Route::delete('/orders/{id}', [AdminController::class, 'deleteOrder'])->name('orders.delete');
    
    // Staff Management - Admin Accounts
    Route::get('/staff/admins', [AdminController::class, 'staffAdmins'])->name('staff.admins');
    Route::post('/staff/admins', [AdminController::class, 'storeAdmin'])->name('staff.admins.store');
    Route::put('/staff/admins/{id}', [AdminController::class, 'updateAdmin'])->name('staff.admins.update');
    Route::delete('/staff/admins/{id}', [AdminController::class, 'deleteAdmin'])->name('staff.admins.delete');
    
    // Staff Management - Delivery Coordinators
    Route::get('/staff/delivery', [AdminController::class, 'staffDelivery'])->name('staff.delivery');
    Route::post('/staff/delivery', [AdminController::class, 'storeDelivery'])->name('staff.delivery.store');
    Route::put('/staff/delivery/{id}', [AdminController::class, 'updateDelivery'])->name('staff.delivery.update');
    Route::delete('/staff/delivery/{id}', [AdminController::class, 'deleteDelivery'])->name('staff.delivery.delete');
    
    // Gallery Management Routes
    Route::prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', [GalleryController::class, 'adminIndex'])->name('index');
        Route::get('/create', [GalleryController::class, 'create'])->name('create');
        Route::post('/', [GalleryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [GalleryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GalleryController::class, 'update'])->name('update');
        Route::delete('/{id}', [GalleryController::class, 'destroy'])->name('destroy');
        Route::patch('/{id}/toggle-status', [GalleryController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/update-order', [GalleryController::class, 'updateOrder'])->name('update-order');
    });

    // ===================================
    // ADMIN LIVE CHAT ROUTES
    // ===================================
    Route::prefix('livechat')->name('livechat.')->group(function () {
        // Dashboard
        Route::get('/', [LiveChatController::class, 'adminIndex'])->name('index');
        
        // Accept chat
        Route::post('/accept/{sessionId}', [LiveChatController::class, 'adminAccept'])->name('accept');
        
        // Chat interface
        Route::get('/chat/{sessionId}', [LiveChatController::class, 'adminChat'])->name('chat');
        
        // View history
        Route::get('/view/{sessionId}', [LiveChatController::class, 'adminViewHistory'])->name('view');
        
        // Send message
        Route::post('/send', [LiveChatController::class, 'adminSendMessage'])->name('send');
        
        // Poll for messages
        Route::get('/poll/{sessionId}', [LiveChatController::class, 'adminPoll'])->name('poll');
        
        // End session
        Route::post('/end/{sessionId}', [LiveChatController::class, 'adminEndSession'])->name('end');
        
        // Delete session
        Route::delete('/delete/{sessionId}', [LiveChatController::class, 'adminDeleteSession'])->name('delete');
        
        // Bulk delete
        Route::post('/bulk-delete', [LiveChatController::class, 'adminBulkDelete'])->name('bulk-delete');
        
        // Delete all closed
        Route::post('/delete-all-closed', [LiveChatController::class, 'adminDeleteAllClosed'])->name('delete-all-closed');
    });
});

// Delivery Routes
Route::middleware(['delivery'])->prefix('delivery')->group(function () {
    Route::get('/dashboard', [DeliveryController::class, 'dashboard'])->name('delivery.dashboard');
    Route::post('/logout', [DeliveryController::class, 'logout'])->name('delivery.logout');
    
    // Delivery Management
    Route::get('/deliveries', [DeliveryController::class, 'deliveries'])->name('delivery.deliveries');
    Route::put('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus'])->name('delivery.update-status');
    Route::post('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus'])->name('delivery.updateStatus');
    
    // Payment Proof Upload Route
    Route::post('/deliveries/{id}/upload-payment-proof', [DeliveryController::class, 'uploadPaymentProof'])->name('delivery.upload-payment-proof');
    
    // Delivery History
    Route::get('/history', [DeliveryController::class, 'history'])->name('delivery.history');
});

// Chatbot Routes
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');

// Location API Routes
Route::prefix('api/locations')->group(function () {
    Route::get('/regions', [LocationController::class, 'getRegions']);
    Route::get('/provinces/{regionId}', [LocationController::class, 'getProvinces']);
    Route::get('/cities/{provinceId}', [LocationController::class, 'getCities']);
    Route::get('/barangays/{cityId}', [LocationController::class, 'getBarangays']);
    Route::get('/address/{barangayId}', [LocationController::class, 'getCompleteAddress']);
});