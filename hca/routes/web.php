<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

// Controllers
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\CustomizationController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LiveChatController;
use App\Http\Controllers\DeliveryLiveChatController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ===================================
// PUBLIC ROUTES
// ===================================

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

// Chatbot Routes
Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot');
Route::post('/chatbot/send', [ChatbotController::class, 'send'])->name('chatbot.send');

// Unified Staff Login (Admin & Delivery)
Route::get('/staff/login', [AdminController::class, 'showLogin'])->name('staff.login');
Route::post('/staff/login', [AdminController::class, 'login'])->name('staff.login.submit');

// Location API Routes
Route::prefix('api/locations')->group(function () {
    Route::get('/regions', [LocationController::class, 'getRegions']);
    Route::get('/provinces/{regionId}', [LocationController::class, 'getProvinces']);
    Route::get('/cities/{provinceId}', [LocationController::class, 'getCities']);
    Route::get('/barangays/{cityId}', [LocationController::class, 'getBarangays']);
    Route::get('/address/{barangayId}', [LocationController::class, 'getCompleteAddress']);
});

// ===================================
// CUSTOMER AUTHENTICATED ROUTES
// ===================================
Route::middleware(['auth'])->group(function () {
    
    // ===================================
    // CUSTOMER LIVE CHAT ROUTES (FIXED)
    // ===================================
    // Request STAFF chat
    Route::post('/livechat/request', [LiveChatController::class, 'request'])->name('livechat.request');
    
    // Request DELIVERY chat
    Route::post('/livechat/request-delivery', [LiveChatController::class, 'requestDeliveryChat'])->name('livechat.request-delivery');
    
    // Send message
    Route::post('/livechat/send', [LiveChatController::class, 'sendMessage'])->name('livechat.send');
    
    // End chat session
    Route::post('/livechat/end', [LiveChatController::class, 'endSession'])->name('livechat.end');
    
    // Poll for new messages
    Route::get('/livechat/poll/{sessionId}', [LiveChatController::class, 'poll'])->name('livechat.poll');
    
    // Get active session
    Route::get('/livechat/active-session', [LiveChatController::class, 'getActiveSession'])->name('livechat.active-session');
    
    // Get chat history
    Route::get('/livechat/history/{sessionId}', [LiveChatController::class, 'getChatHistory'])->name('livechat.history');
    
    // Mark messages as read
    Route::post('/livechat/mark-read/{sessionId}', [LiveChatController::class, 'markAsRead'])->name('livechat.mark-read');
    
    // Activity heartbeat
    Route::post('/livechat/heartbeat', [LiveChatController::class, 'heartbeat'])->name('livechat.heartbeat');
    
    // Check unread messages (for navbar notification)
    Route::get('/livechat/check-unread', [LiveChatController::class, 'checkUnread'])->name('livechat.check-unread');

    // ===================================
    // CUSTOMER ORDERS ENDPOINT (NEW - FOR ORDER DISPLAY IN CHAT)
    // ===================================
    Route::get('/customer/orders/ongoing', [LiveChatController::class, 'getCustomerOngoingOrders'])->name('customer.orders.ongoing');

    // ===================================
    // CART ROUTES
    // ===================================
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/buy-now', [CartController::class, 'buyNow'])->name('cart.buy-now');
    Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');

    // ===================================
    // CHECKOUT ROUTES
    // ===================================
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

    // ===================================
    // PROFILE/SETTINGS ROUTES
    // ===================================
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::get('/profile/purchase-history', [ProfileController::class, 'purchaseHistory'])->name('profile.purchase-history');
    Route::get('/profile/track-order', [ProfileController::class, 'trackOrder'])->name('profile.track-order');
    
    // ===================================
    // CUSTOMIZATION ROUTES (UPDATED)
    // ===================================
    Route::get('/customize', [CustomizationController::class, 'landing'])->name('customization.landing');
    Route::get('/customize/create', [CustomizationController::class, 'create'])->name('customization.create');
    Route::post('/customize/store', [CustomizationController::class, 'store'])->name('customization.store');
    Route::get('/my-customizations', [CustomizationController::class, 'myCustomizations'])->name('customization.my-customizations');
    Route::get('/customization/{id}', [CustomizationController::class, 'show'])->name('customization.show');
    Route::get('/customization/{id}/edit', [CustomizationController::class, 'edit'])->name('customization.edit');
    Route::put('/customization/{id}', [CustomizationController::class, 'update'])->name('customization.update');
    Route::delete('/customization/{id}', [CustomizationController::class, 'destroy'])->name('customization.destroy');
    
    // ===================================
    // ORDER MANAGEMENT
    // ===================================
    Route::patch('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// ===================================
// ADMIN ROUTES
// ===================================
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AdminController::class, 'logout'])->name('logout');
    
    // ===================================
    // NOTIFICATION ROUTES
    // ===================================
    Route::post('/notifications/{id}/read', [AdminController::class, 'markNotificationAsRead'])->name('notifications.markRead');
    Route::get('/notifications/mark-all-read', [AdminController::class, 'markAllNotificationsAsRead'])->name('notifications.markAllRead');
    Route::get('/notifications/clear-all', [AdminController::class, 'clearAllNotifications'])->name('notifications.clearAll');
    
    // ===================================
    // USER MANAGEMENT
    // ===================================
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');

    // ===================================
    // PRODUCT MANAGEMENT
    // ===================================
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'deleteProduct'])->name('products.destroy');
    
    // ===================================
    // CATEGORY MANAGEMENT
    // ===================================
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
    
    // ===================================
    // ORDER MANAGEMENT
    // ===================================
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::put('/orders/{id}', [AdminController::class, 'updateOrderStatus'])->name('orders.update');
    Route::put('/orders/{id}/assign-coordinator', [AdminController::class, 'assignCoordinator'])->name('orders.assign-coordinator');
    Route::delete('/orders/{id}', [AdminController::class, 'deleteOrder'])->name('orders.delete');
    
    // ===================================
    // STAFF MANAGEMENT - ADMIN ACCOUNTS
    // ===================================
    Route::get('/staff/admins', [AdminController::class, 'staffAdmins'])->name('staff.admins');
    Route::post('/staff/admins', [AdminController::class, 'storeAdmin'])->name('staff.admins.store');
    Route::put('/staff/admins/{id}', [AdminController::class, 'updateAdmin'])->name('staff.admins.update');
    Route::delete('/staff/admins/{id}', [AdminController::class, 'deleteAdmin'])->name('staff.admins.delete');
    
    // ===================================
    // STAFF MANAGEMENT - DELIVERY COORDINATORS
    // ===================================
    Route::get('/staff/delivery', [AdminController::class, 'staffDelivery'])->name('staff.delivery');
    Route::post('/staff/delivery', [AdminController::class, 'storeDelivery'])->name('staff.delivery.store');
    Route::put('/staff/delivery/{id}', [AdminController::class, 'updateDelivery'])->name('staff.delivery.update');
    Route::delete('/staff/delivery/{id}', [AdminController::class, 'deleteDelivery'])->name('staff.delivery.delete');
    
    // ===================================
    // GALLERY MANAGEMENT ROUTES
    // ===================================
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
    // ADMIN LIVE CHAT ROUTES - FIXED TO USE LiveChatController
    // ===================================
    Route::prefix('livechat')->name('livechat.')->group(function () {
        // Dashboard - You'll need to add an index() method to LiveChatController
        Route::get('/', [LiveChatController::class, 'adminIndex'])->name('index');
        
        // Accept chat - You'll need to add an acceptChat() method to LiveChatController
        Route::post('/accept/{sessionId}', [LiveChatController::class, 'acceptChat'])->name('accept');
        
        // Chat interface - You'll need to add a chat() method to LiveChatController
        Route::get('/chat/{sessionId}', [LiveChatController::class, 'adminChat'])->name('chat');
        
        // View history (already exists)
        Route::get('/view/{sessionId}', [LiveChatController::class, 'adminViewHistory'])->name('view');
        
        // Send message - You'll need to add an adminSendMessage() method to LiveChatController
        Route::post('/send', [LiveChatController::class, 'adminSendMessage'])->name('send');
        
        // Poll for messages - You'll need to add an adminPoll() method to LiveChatController
        Route::get('/poll/{sessionId}', [LiveChatController::class, 'adminPoll'])->name('poll');
        
        // End session - You'll need to add an adminEndChat() method to LiveChatController
        Route::post('/end/{sessionId}', [LiveChatController::class, 'adminEndChat'])->name('end');
        
        // Delete session (already exists)
        Route::delete('/delete/{sessionId}', [LiveChatController::class, 'adminDeleteSession'])->name('delete');
        
        // Bulk delete (already exists)
        Route::post('/bulk-delete', [LiveChatController::class, 'adminBulkDelete'])->name('bulk-delete');
        
        // Delete all closed (already exists)
        Route::post('/delete-all-closed', [LiveChatController::class, 'adminDeleteAllClosed'])->name('delete-all-closed');
    });
});

// ===================================
// DELIVERY COORDINATOR ROUTES
// ===================================
Route::middleware(['delivery'])->prefix('delivery')->name('delivery.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DeliveryController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [DeliveryController::class, 'logout'])->name('logout');
    
    // ===================================
    // DELIVERY MANAGEMENT
    // ===================================
    Route::get('/deliveries', [DeliveryController::class, 'deliveries'])->name('deliveries');
    Route::put('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus'])->name('update-status');
    Route::post('/deliveries/{id}/status', [DeliveryController::class, 'updateStatus'])->name('updateStatus');
    
    // Payment Proof Upload
    Route::post('/deliveries/{id}/upload-payment-proof', [DeliveryController::class, 'uploadPaymentProof'])->name('upload-payment-proof');
    
    // Delivery History
    Route::get('/history', [DeliveryController::class, 'history'])->name('history');
    
    // ===================================
    // DELIVERY LIVE CHAT ROUTES (FIXED + ENHANCED WITH ORDER DISPLAY)
    // ===================================
    Route::prefix('livechat')->name('livechat.')->group(function () {
        Route::get('/', [DeliveryLiveChatController::class, 'index'])->name('index');
        Route::post('/accept/{sessionId}', [DeliveryLiveChatController::class, 'acceptChat'])->name('accept');
        Route::get('/chat/{sessionId}', [DeliveryLiveChatController::class, 'chat'])->name('chat');
        Route::post('/send', [DeliveryLiveChatController::class, 'sendMessage'])->name('send');
        Route::post('/end/{sessionId}', [DeliveryLiveChatController::class, 'endChat'])->name('end');
        Route::get('/poll/{sessionId}', [DeliveryLiveChatController::class, 'pollMessages'])->name('poll');
        
        // NEW: Get customer orders endpoint for delivery coordinators
        Route::get('/orders/{sessionId}', [DeliveryLiveChatController::class, 'getCustomerOrders'])->name('orders');
    });
});