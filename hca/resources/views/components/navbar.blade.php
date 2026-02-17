@php
    $isLoggedIn = Auth::check();
    $user = Auth::user();
    // $cartCount is already available from AppServiceProvider
    
    // Check for active chat session
    $hasActiveChat = false;
    $unreadMessages = 0;
    if ($isLoggedIn) {
        $activeChat = DB::table('chat_sessions')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['waiting', 'active'])
            ->first();
        
        if ($activeChat) {
            $hasActiveChat = true;
            // Count unread messages from staff
            $unreadMessages = DB::table('chat_messages')
                ->where('chat_session_id', $activeChat->id)
                ->where('sender_type', '!=', 'customer')
                ->where('is_read', false)
                ->count();
        }
    }
@endphp

<nav class="navbar navbar-expand-lg navbar-light sticky-top modern-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <img src="{{ asset('asset/images/logo.jpg') }}" alt="Logo" width="45" height="45" class="rounded-circle me-2 logo-img">
            <span class="brand-text">HookcraftAvenue</span>
        </a>
        <button class="navbar-toggler border-0 hamburger-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="hamburger-icon">
                <span></span>
                <span></span>
                <span></span>
            </span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <!-- Close button for mobile -->
            <button class="mobile-close-btn d-lg-none" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <i class="bi bi-x-lg"></i>
            </button>

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('customization.create') }}">Customization</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>

            <ul class="navbar-nav flex-row align-items-center">
                <!-- Chatbot Icon with Live Chat Notification -->
                <li class="nav-item me-3">
                    <a class="nav-link position-relative icon-link" 
                       href="{{ route('chatbot') }}" 
                       title="{{ $hasActiveChat ? 'Active Live Chat - Click to continue' : 'AI Assistant' }}"
                       id="chatbotNavLink">
                        <i class="bi bi-chat fs-5"></i>
                        
                        @if($hasActiveChat)
                            <span class="position-absolute top-0 start-100 translate-middle">
                                <span class="badge bg-danger rounded-circle chat-notification-badge" 
                                      style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                                    !
                                </span>
                            </span>
                            
                            @if($unreadMessages > 0)
                                <span class="position-absolute" style="top: -8px; left: -8px;">
                                    <span class="badge rounded-pill bg-danger" 
                                          style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                                        {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                                    </span>
                                </span>
                            @endif
                        @else
                            <span class="position-absolute top-0 start-100 translate-middle">
                                <span class="badge bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
                            </span>
                        @endif
                    </a>
                </li>

                @if($isLoggedIn)
                    <!-- Notification Bell -->
                    <li class="nav-item me-3">
                        <div class="notification-dropdown">
                            <button class="nav-link position-relative icon-link notification-bell" id="notificationTrigger" type="button">
                                <i class="bi bi-bell fs-5"></i>
                                <span class="notification-badge" id="notificationCount" style="display: none;">0</span>
                            </button>
                            
                            <!-- Notification Menu -->
                            <div class="notification-menu" id="notificationDropdown" style="display: none;">
                                <div class="notification-header">
                                    <h6 class="m-0">
                                        <i class="bi bi-bell-fill me-2" style="color: #FF69B4;"></i>
                                        Notifications
                                    </h6>
                                    <div>
                                        <button class="btn btn-sm btn-link text-primary p-0 me-2" onclick="markAllAsRead()" title="Mark all as read" id="markAllReadBtn" style="display: none;" type="button">
                                            <i class="bi bi-check2-all"></i>
                                        </button>
                                        <a href="{{ route('user.notifications') }}" class="btn btn-sm btn-link p-0" title="View all">
                                            <i class="bi bi-arrow-up-right-square"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="notification-list" id="notificationsList">
                                    <!-- Loading state -->
                                    <div class="text-center py-5" id="notificationsLoading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="notification-footer">
                                    <a href="{{ route('user.notifications') }}" class="view-all-btn">View all notifications</a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif

                <!-- Cart -->
                <li class="nav-item me-3">
                    <a class="nav-link position-relative icon-link" href="{{ $isLoggedIn ? route('cart.index') : 'javascript:void(0)' }}">
                        <i class="bi bi-cart fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                            {{ $cartCount }}
                        </span>
                    </a>
                </li>

                @if($isLoggedIn)
                    <li class="nav-item dropdown user-dropdown">
                        <a class="nav-link dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center" 
                           href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('asset/images/default-profile.png') }}" alt="Profile" 
                                 width="40" height="40" class="rounded-circle profile-img">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow modern-dropdown">
                            <li class="px-3 py-3 border-bottom user-info-header">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('asset/images/default-profile.png') }}" alt="Profile" 
                                         width="50" height="50" class="rounded-circle me-3">
                                    <div>
                                        <div class="fw-bold user-name">{{ $user->name }}</div>
                                        <small class="text-muted user-email">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </li>
                            <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}">
                                <i class="bi bi-person-circle me-2"></i>My Account</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('profile.purchase-history') }}">
                                <i class="bi bi-clock-history me-2"></i>Purchase History</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('profile.track-order') }}">
                                <i class="bi bi-truck me-2"></i>Track Order</a></li>
                        
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button class="dropdown-item text-danger py-2" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link login-btn" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-person-circle me-1"></i>Login
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<style>
/* ============================================
   MODERN NAVBAR DESIGN - DESKTOP & MOBILE
   ============================================ */

/* Base Navbar Styles */
.modern-navbar {
    background: linear-gradient(135deg, #ffffff 0%, #fff5f8 100%);
    box-shadow: 0 2px 20px rgba(255, 105, 180, 0.1);
    backdrop-filter: blur(10px);
    padding: 0.75rem 0;
    transition: all 0.3s ease;
}

.modern-navbar .navbar-brand {
    display: flex;
    align-items: center;
    color: #FF69B4;
    font-size: 1.5rem;
    transition: transform 0.3s ease;
}

.modern-navbar .navbar-brand:hover {
    transform: scale(1.05);
}

.modern-navbar .logo-img {
    border: 2px solid #FFB6C1;
    box-shadow: 0 2px 10px rgba(255, 105, 180, 0.2);
    transition: all 0.3s ease;
}

.modern-navbar .navbar-brand:hover .logo-img {
    border-color: #FF69B4;
    box-shadow: 0 4px 15px rgba(255, 105, 180, 0.4);
}

.brand-text {
    background: linear-gradient(135deg, #FF69B4 0%, #f1677c 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Navigation Links - Desktop */
.navbar-nav .nav-link {
    color: #555;
    font-weight: 500;
    padding: 0.5rem 1rem !important;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
}

.navbar-nav .nav-link::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 2px;
    background: linear-gradient(90deg, #FF69B4, #FFB6C1);
    transition: width 0.3s ease;
}

.navbar-nav .nav-link:hover {
    color: #FF69B4;
}

.navbar-nav .nav-link:hover::before {
    width: 80%;
}

/* Icon Links */
.icon-link {
    background: linear-gradient(135deg, #fff 0%, #fff5f8 100%);
    padding: 0.5rem !important;
    border-radius: 50%;
    width: 45px;
    height: 45px;
    display: flex !important;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(255, 105, 180, 0.1);
    border: none;
}

.icon-link:hover {
    background: linear-gradient(135deg, #FF69B4, #FFB6C1);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 105, 180, 0.3);
}

.icon-link:hover i {
    color: white !important;
}

.icon-link i {
    color: #FF69B4;
}

/* Cart Badge */
.cart-badge {
    font-size: 0.7rem;
    padding: 0.25em 0.5em;
    min-width: 20px;
}

/* Pulse animation for chat notification */
@keyframes chat-pulse {
    0%, 100% { transform: scale(1); opacity: 1; }
    50% { transform: scale(1.15); opacity: 0.8; }
}

.chat-notification-badge {
    animation: chat-pulse 2s infinite;
    box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
}

/* ============================================
   NOTIFICATION DROPDOWN STYLES
   ============================================ */
.notification-dropdown {
    position: relative;
}

.notification-bell {
    position: relative;
    cursor: pointer;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 0.7rem;
    font-weight: 700;
    min-width: 18px;
    text-align: center;
    border: 2px solid white;
    line-height: 1;
}

.notification-menu {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 380px;
    background: white;
    border-radius: 16px;
    border: 1px solid #E2E8F0;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    z-index: 1100;
}

.notification-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h6 {
    margin: 0;
    font-weight: 700;
    color: #1A202C;
    font-size: 1rem;
}

.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

.notification-item {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #E2E8F0;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background: rgba(255, 105, 180, 0.05);
}

.notification-item.unread {
    background: rgba(255, 105, 180, 0.05);
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: #FF69B4;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    flex-shrink: 0;
}

.notification-icon.success { background: rgba(72, 187, 120, 0.15); color: #48BB78; }
.notification-icon.info { background: rgba(99, 179, 237, 0.15); color: #63B3ED; }
.notification-icon.warning { background: rgba(246, 173, 85, 0.15); color: #F6AD55; }
.notification-icon.danger { background: rgba(252, 129, 129, 0.15); color: #FC8181; }
.notification-icon.primary { background: rgba(255, 105, 180, 0.15); color: #FF69B4; }

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-weight: 600;
    color: #1A202C;
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.notification-message {
    color: #718096;
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    word-wrap: break-word;
}

.notification-time {
    color: #718096;
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.notification-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #E2E8F0;
    text-align: center;
}

.view-all-btn {
    color: #FF69B4;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
}

.view-all-btn:hover {
    text-decoration: underline;
}

.notification-priority-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
}

.notification-priority-badge.urgent {
    background: rgba(252, 129, 129, 0.2);
    color: #FC8181;
}

.notification-priority-badge.high {
    background: rgba(246, 173, 85, 0.2);
    color: #F6AD55;
}

/* Profile Image */
.profile-img {
    border: 2px solid #FFB6C1;
    transition: all 0.3s ease;
    cursor: pointer;
}

.profile-img:hover {
    border-color: #FF69B4;
    box-shadow: 0 0 15px rgba(255, 105, 180, 0.4);
}

/* Modern Dropdown */
.modern-dropdown {
    border: none;
    border-radius: 15px;
    overflow: hidden;
    min-width: 280px;
    background: white;
}

.user-info-header {
    background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 100%);
    color: white;
}

.user-info-header .user-name {
    color: white;
}

.user-info-header .user-email {
    color: rgba(255, 255, 255, 0.9);
}

.modern-dropdown .dropdown-item {
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.modern-dropdown .dropdown-item:hover {
    background: linear-gradient(90deg, #fff5f8 0%, #ffffff 100%);
    border-left-color: #FF69B4;
    transform: translateX(5px);
}

.modern-dropdown .dropdown-item i {
    color: #FF69B4;
    width: 20px;
}

/* Login Button */
.login-btn {
    background: linear-gradient(135deg, #FF69B4, #FFB6C1);
    color: white !important;
    padding: 0.5rem 1.5rem !important;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(255, 105, 180, 0.3);
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(255, 105, 180, 0.5);
}

/* Custom Hamburger Button */
.hamburger-btn {
    padding: 0;
    width: 40px;
    height: 40px;
    position: relative;
    background: linear-gradient(135deg, #FF69B4, #FFB6C1);
    border-radius: 8px;
}

.hamburger-icon {
    display: block;
    position: relative;
    width: 24px;
    height: 18px;
    margin: auto;
}

.hamburger-icon span {
    display: block;
    position: absolute;
    height: 3px;
    width: 100%;
    background: white;
    border-radius: 3px;
    transition: all 0.3s ease;
}

.hamburger-icon span:nth-child(1) { top: 0; }
.hamburger-icon span:nth-child(2) { top: 50%; transform: translateY(-50%); }
.hamburger-icon span:nth-child(3) { bottom: 0; }

.hamburger-btn:not(.collapsed) .hamburger-icon span:nth-child(1) {
    top: 50%;
    transform: translateY(-50%) rotate(45deg);
}

.hamburger-btn:not(.collapsed) .hamburger-icon span:nth-child(2) {
    opacity: 0;
}

.hamburger-btn:not(.collapsed) .hamburger-icon span:nth-child(3) {
    bottom: 50%;
    transform: translateY(50%) rotate(-45deg);
}

/* Mobile Close Button */
.mobile-close-btn {
    display: none;
}

/* Utility classes */
.flex-grow-1 {
    flex-grow: 1;
}

.d-flex {
    display: flex;
}

.gap-3 {
    gap: 0.75rem;
}

/* ============================================
   MOBILE RESPONSIVE DESIGN
   ============================================ */
@media (max-width: 991.98px) {
    /* Mobile styles - keeping existing mobile code */
    .modern-navbar {
        padding: 0.5rem 0;
    }

    .modern-navbar .container {
        padding: 0 1rem;
    }

    .modern-navbar .navbar-brand {
        font-size: 1.2rem;
    }

    .modern-navbar .logo-img {
        width: 38px;
        height: 38px;
    }

    .brand-text {
        font-size: 1rem;
    }

    .navbar-collapse {
        position: fixed;
        top: 0;
        right: -100%;
        width: 85%;
        max-width: 350px;
        height: 100vh;
        background: linear-gradient(180deg, #ffffff 0%, #fff5f8 100%);
        box-shadow: -8px 0 30px rgba(255, 105, 180, 0.3);
        transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 1050;
        display: flex !important;
        flex-direction: column;
    }
    
    .navbar-collapse.show {
        right: 0;
    }
    
    .navbar-collapse.show::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(5px);
        z-index: -1;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .mobile-close-btn {
        display: block;
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, #FF69B4, #FFB6C1);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: white;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(255, 105, 180, 0.3);
        transition: all 0.3s ease;
        z-index: 10;
    }
    
    .mobile-close-btn:hover {
        transform: rotate(90deg);
        box-shadow: 0 4px 15px rgba(255, 105, 180, 0.5);
    }
    
    .navbar-nav.flex-row {
        order: 1;
        flex-direction: column !important;
        align-items: stretch !important;
        padding: 3.5rem 1.25rem 1.5rem;
        background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 100%);
        margin: 0 !important;
        box-shadow: 0 4px 20px rgba(255, 105, 180, 0.2);
        width: 100%;
    }
    
    .navbar-nav.flex-row .user-dropdown {
        width: 100%;
        margin-bottom: 1.25rem;
        order: -1;
    }
    
    .navbar-nav.flex-row .user-dropdown > a {
        justify-content: center;
        background: white;
        padding: 1rem !important;
        border-radius: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: flex !important;
    }
    
    .navbar-nav.flex-row .user-dropdown > a img {
        width: 70px !important;
        height: 70px !important;
        border: 4px solid white !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2) !important;
    }
    
    .navbar-nav.flex-row .user-dropdown > a::after {
        display: none !important;
    }
    
    .navbar-nav.flex-row > .nav-item:not(.dropdown):not(.user-dropdown) {
        width: 100%;
        margin: 0 0 0.65rem 0 !important;
        padding: 0 !important;
    }
    
    .navbar-nav.flex-row .icon-link {
        width: 100%;
        height: auto;
        border-radius: 12px !important;
        padding: 0.85rem 1rem !important;
        background: rgba(255, 255, 255, 0.95);
        display: flex !important;
        align-items: center;
        justify-content: flex-start;
        gap: 0.85rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .navbar-nav.flex-row .notification-dropdown {
        width: 100%;
    }
    
    .navbar-nav.flex-row .notification-dropdown .notification-bell {
        width: 100%;
        border-radius: 12px !important;
    }
    
    .navbar-nav.flex-row .icon-link:hover {
        background: white;
        transform: translateX(5px);
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.12);
    }
    
    .navbar-nav.flex-row .icon-link i {
        font-size: 1.3rem !important;
        color: #FF69B4 !important;
        flex-shrink: 0;
    }
    
    .navbar-nav.flex-row > .nav-item:nth-child(1) .icon-link::after {
        content: 'AI Assistant';
        font-weight: 600;
        color: #FF69B4;
        font-size: 0.95rem;
        white-space: nowrap;
    }
    
    .navbar-nav.flex-row > .nav-item:nth-child(2) .notification-bell::after {
        content: 'Notifications';
        font-weight: 600;
        color: #FF69B4;
        font-size: 0.95rem;
        white-space: nowrap;
    }
    
    .navbar-nav.flex-row > .nav-item:nth-child(3) .icon-link::after {
        content: 'Shopping Cart';
        font-weight: 600;
        color: #FF69B4;
        font-size: 0.95rem;
        white-space: nowrap;
    }
    
    .navbar-nav.flex-row .icon-link:hover::after,
    .navbar-nav.flex-row .notification-bell:hover::after {
        color: #FF1493;
    }
    
    .notification-menu {
        position: fixed !important;
        top: auto !important;
        bottom: 0 !important;
        left: 0 !important;
        right: 0 !important;
        width: 100% !important;
        max-height: 70vh;
        border-radius: 20px 20px 0 0 !important;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    
    .notification-menu[style*="display: block"] {
        transform: translateY(0);
    }
    
    .navbar-nav.mx-auto {
        order: 2;
        padding: 1.25rem;
        margin: 0 !important;
        width: 100%;
        background: white;
    }
    
    .navbar-nav.mx-auto .nav-item {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .navbar-nav.mx-auto .nav-link {
        padding: 0.85rem 1rem !important;
        background: linear-gradient(135deg, #fff5f8 0%, #ffffff 100%);
        border-radius: 12px !important;
        color: #FF69B4 !important;
        font-weight: 600 !important;
        border-left: 3px solid transparent;
        box-shadow: 0 2px 8px rgba(255, 105, 180, 0.1);
        display: flex !important;
        align-items: center;
        gap: 0.75rem;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .navbar-nav.mx-auto .nav-link::before {
        display: inline-block !important;
        position: static !important;
        width: auto !important;
        height: auto !important;
        background: none !important;
        flex-shrink: 0;
    }
    
    .navbar-nav.mx-auto .nav-link:hover {
        background: linear-gradient(135deg, #FF69B4, #FFB6C1);
        color: white !important;
        transform: translateX(6px);
        border-left-color: #FF1493;
        box-shadow: 0 4px 15px rgba(255, 105, 180, 0.3);
    }
    
    .navbar-nav.mx-auto .nav-item:nth-child(1) .nav-link::before {
        content: '🏠'; 
        font-size: 1.1rem;
    }
    .navbar-nav.mx-auto .nav-item:nth-child(2) .nav-link::before {
        content: '🛍️'; 
        font-size: 1.1rem;
    }
    .navbar-nav.mx-auto .nav-item:nth-child(3) .nav-link::before {
        content: 'ℹ️'; 
        font-size: 1.1rem;
    }
    .navbar-nav.mx-auto .nav-item:nth-child(4) .nav-link::before {
        content: '✉️'; 
        font-size: 1.1rem;
    }
    
    .navbar-nav.flex-row .user-dropdown .dropdown-menu {
        position: static !important;
        display: block !important;
        background: transparent;
        border: none;
        box-shadow: none;
        padding: 0 1.25rem 1.25rem;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100%;
    }
    
    .navbar-nav.flex-row .dropdown-menu > li:first-child {
        display: none;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-divider {
        display: none;
    }
    
    .navbar-nav.flex-row .dropdown-menu li {
        margin-bottom: 0.5rem;
        width: 100%;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item {
        padding: 0.85rem 1rem !important;
        background: linear-gradient(135deg, #fff5f8 0%, #ffffff 100%) !important;
        border-radius: 12px !important;
        color: #FF69B4 !important;
        font-weight: 600 !important;
        border-left: 3px solid transparent;
        box-shadow: 0 2px 8px rgba(255, 105, 180, 0.1);
        border-bottom: none !important;
        transition: all 0.3s ease;
        display: flex !important;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        width: 100%;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item:hover {
        background: linear-gradient(135deg, #FF69B4, #FFB6C1) !important;
        color: white !important;
        transform: translateX(6px);
        border-left-color: #FF1493;
        box-shadow: 0 4px 15px rgba(255, 105, 180, 0.3);
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item i {
        color: #FF69B4;
        font-size: 1.1rem;
        width: 20px;
        flex-shrink: 0;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item:hover i {
        color: white !important;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item.text-danger {
        color: #dc3545 !important;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item.text-danger:hover {
        background: linear-gradient(135deg, #dc3545, #c82333) !important;
        color: white !important;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item.text-danger i {
        color: #dc3545;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item.text-danger:hover i {
        color: white !important;
    }
    
    .login-btn {
        width: 100%;
        text-align: center;
        display: block !important;
        padding: 0.85rem 1.5rem !important;
        font-size: 0.95rem;
    }
    
    .navbar-collapse::-webkit-scrollbar {
        width: 5px;
    }
    
    .navbar-collapse::-webkit-scrollbar-track {
        background: rgba(255, 182, 193, 0.1);
    }
    
    .navbar-collapse::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #FF69B4, #FFB6C1);
        border-radius: 10px;
    }
    
    .navbar-collapse::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #FF1493, #FF69B4);
    }
    
    .navbar-nav.flex-row > * {
        flex-shrink: 0;
    }
}
</style>

@if($isLoggedIn)
<script>
let notificationUpdateInterval;

// Get CSRF token safely
function getCsrfToken() {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    return csrfMeta ? csrfMeta.content : null;
}

// Fetch and display notifications
async function fetchNotifications() {
    try {
        // console.log('✅ Fetching user notifications from API...');
        
        const csrfToken = getCsrfToken();
        const headers = {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        const response = await fetch('/api/notifications?limit=10', {
            headers: headers
        });
        
        // console.log('📊 Response status:', response.status);
        
        // Handle non-OK responses gracefully
        if (!response.ok) {
            // console.warn('⚠️ Failed to fetch notifications:', response.status);
            hideLoading();
            showEmpty();
            updateNotificationBadge(0);
            return;
        }
        
        const data = await response.json();
        // console.log('📦 API Response:', data);
        
        // Check if user is a guest
        if (data.guest === true) {
            // console.log('👤 User is guest - hiding notifications');
            hideLoading();
            showEmpty();
            updateNotificationBadge(0);
            return;
        }
        
        // Get notifications array
        let notifications = [];
        if (data && data.success) {
            notifications = Array.isArray(data.notifications) ? data.notifications : [];
        } else if (Array.isArray(data)) {
            notifications = data;
        }
        
        // console.log(`📬 Processing ${notifications.length} notifications`);
        
        // Filter out delivery rider notifications (client-side safety check)
        notifications = notifications.filter(notif => {
            const title = String(notif.title || '').toLowerCase();
            const type = String(notif.type || '').toLowerCase();
            
            const isDeliveryAssignment = 
                title.includes('delivery assignment') || 
                title.includes('assigned to you for delivery') ||
                title.includes('new delivery') ||
                type.includes('delivery_assignment') ||
                type.includes('rider');
            
            return !isDeliveryAssignment;
        });
        
        // console.log(`✅ After filtering: ${notifications.length} user notifications`);
        
        // Get unread count
        const unreadCount = data.unread_count !== undefined ? 
            data.unread_count : 
            notifications.filter(n => !n.is_read).length;
        
        // console.log(`🔔 Unread count: ${unreadCount}`);
        
        updateNotificationBadge(unreadCount);
        displayNotifications(notifications);
        
    } catch (error) {
        // console.error('❌ Error fetching notifications:', error);
        hideLoading();
        showEmpty();
        updateNotificationBadge(0);
    }
}

function hideLoading() {
    const loading = document.getElementById('notificationsLoading');
    if (loading) loading.style.display = 'none';
}

function showEmpty() {
    const container = document.getElementById('notificationsList');
    if (!container) return;
    
    container.innerHTML = `
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="color: #E2E8F0; font-size: 3rem;"></i>
            <p style="color: #718096; margin: 0; margin-top: 1rem;">No notifications yet</p>
        </div>
    `;
}

// Update notification badge
function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationCount');
    const markAllBtn = document.getElementById('markAllReadBtn');
    
    // console.log('🎯 Updating badge with count:', count);
    
    if (badge) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'flex';
            if (markAllBtn) markAllBtn.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
            if (markAllBtn) markAllBtn.style.display = 'none';
        }
    }
}

function displayNotifications(notifications) {
    const container = document.getElementById('notificationsList');
    hideLoading();
    
    if (!container) {
        // console.warn('⚠️ Notification container not found');
        return;
    }
    
    if (!notifications || !Array.isArray(notifications) || notifications.length === 0) {
        // console.log('📭 No notifications to display');
        showEmpty();
        return;
    }
    
    // console.log(`📋 Displaying ${notifications.length} notifications`);
    
    const html = notifications.map(notification => {
        if (!notification || typeof notification !== 'object') return '';
        
        // Determine icon and color based on notification type
        let icon = 'bi-bell-fill';
        let colorClass = 'primary';
        let priorityBadge = '';
        
        const notifType = String(notification.type || '').toLowerCase();
        const notifTitle = String(notification.title || '').toLowerCase();
        
        // Order notifications
        if (notifType.includes('order') || notifTitle.includes('order')) {
            if (notifType.includes('confirm') || notifTitle.includes('confirm') || notifTitle.includes('placed')) {
                icon = 'bi-check-circle-fill';
                colorClass = 'success';
            } else if (notifType.includes('ship') || notifTitle.includes('ship') || notifTitle.includes('out for delivery')) {
                icon = 'bi-truck';
                colorClass = 'info';
                priorityBadge = '<span class="notification-priority-badge high">SHIPPING</span>';
            } else if (notifType.includes('deliver') || notifTitle.includes('deliver')) {
                icon = 'bi-box-seam';
                colorClass = 'success';
                priorityBadge = '<span class="notification-priority-badge high">DELIVERED</span>';
            } else if (notifType.includes('cancel') || notifTitle.includes('cancel')) {
                icon = 'bi-x-circle-fill';
                colorClass = 'danger';
            } else if (notifType.includes('processing') || notifTitle.includes('processing')) {
                icon = 'bi-gear-fill';
                colorClass = 'info';
            }
        } else if (notifType.includes('payment')) {
            icon = 'bi-credit-card-fill';
            colorClass = 'success';
        } else if (notifType.includes('promo') || notifType.includes('discount')) {
            icon = 'bi-tag-fill';
            colorClass = 'warning';
        } else if (notifType.includes('chat') || notifTitle.includes('chat')) {
            icon = 'bi-chat-dots-fill';
            colorClass = 'primary';
        }
        
        // Check if unread
        const isUnread = !notification.is_read || 
                        notification.is_read === 0 || 
                        notification.is_read === false || 
                        notification.read_at === null ||
                        notification.read_at === undefined;
        
        // Format message
        let message = String(notification.message || notification.data?.message || '');
        
        // If it's an order update, format it nicely
        if (notifType.includes('order') && message) {
            const orderMatch = message.match(/Order\s*#?(\w+)/i);
            if (orderMatch) {
                message = message.replace(/Order\s*#?(\w+)/i, `<strong>Order #${orderMatch[1]}</strong>`);
            }
        }
        
        const actionUrl = notification.action_url && 
                         String(notification.action_url) !== 'null' && 
                         String(notification.action_url) !== '#' &&
                         String(notification.action_url) !== '' ? 
                         notification.action_url : '#';
        
        return `
            <div class="notification-item ${isUnread ? 'unread' : ''}" 
                 onclick="handleNotificationClick(${notification.id}, '${escapeHtml(actionUrl)}')">
                <div class="d-flex gap-3">
                    <div class="notification-icon ${colorClass}">
                        <i class="${icon}"></i>
                    </div>
                    <div class="notification-content flex-grow-1">
                        <div class="notification-title">
                            ${escapeHtml(String(notification.title || 'Notification'))}
                            ${priorityBadge}
                        </div>
                        <div class="notification-message">${message}</div>
                        <div class="notification-time">
                            <i class="bi bi-clock"></i>
                            <span>${notification.time_ago || formatTimeAgo(notification.created_at)}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).filter(html => html !== '').join('');
    
    if (html) {
        container.innerHTML = html;
    } else {
        showEmpty();
    }
}

// Format time ago
function formatTimeAgo(dateString) {
    if (!dateString) return 'Just now';
    
    try {
        const date = new Date(dateString);
        const now = new Date();
        const seconds = Math.floor((now - date) / 1000);
        
        if (seconds < 60) return 'Just now';
        if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
        if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
        if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
        return Math.floor(seconds / 604800) + ' weeks ago';
    } catch (e) {
        return 'Just now';
    }
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = String(text);
    return div.innerHTML;
}

// Handle notification click
async function handleNotificationClick(notificationId, actionUrl) {
    try {
        const csrfToken = getCsrfToken();
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        await fetch(`/api/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: headers
        });
        
        await fetchNotifications();
        
        if (actionUrl && actionUrl !== '#' && actionUrl !== 'null' && actionUrl !== '') {
            window.location.href = actionUrl;
        }
    } catch (error) {
        console.error('Error handling notification click:', error);
    }
}

// Mark all as read
async function markAllAsRead() {
    try {
        const csrfToken = getCsrfToken();
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        const response = await fetch('/api/notifications/mark-all-read', {
            method: 'POST',
            headers: headers
        });
        
        if (response.ok) {
            await fetchNotifications();
        }
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const notificationBell = document.getElementById('notificationTrigger');
    const notificationMenu = document.getElementById('notificationDropdown');
    
    if (notificationBell && notificationMenu) {
        notificationBell.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = notificationMenu.style.display === 'block';
            
            notificationMenu.style.display = isVisible ? 'none' : 'block';
            
            if (!isVisible) {
                fetchNotifications();
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (notificationMenu && notificationBell && 
            !notificationMenu.contains(e.target) && !notificationBell.contains(e.target)) {
            notificationMenu.style.display = 'none';
        }
    });

    if (notificationMenu) {
        notificationMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // console.log('🚀 Initializing user notifications...');
    fetchNotifications();
    
    // Poll for new notifications every 30 seconds
    notificationUpdateInterval = setInterval(fetchNotifications, 30000);
});

window.addEventListener('beforeunload', function() {
    if (notificationUpdateInterval) {
        clearInterval(notificationUpdateInterval);
    }
});
</script>
@endif

@if($hasActiveChat)
<script>
if (window.location.pathname !== '/chatbot') {
    setInterval(async function() {
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const headers = {
                'X-Requested-With': 'XMLHttpRequest'
            };
            
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken;
            }
            
            const response = await fetch('{{ route("livechat.check-unread") }}', {
                headers: headers
            });
            
            const data = await response.json();
            
            if (data.has_unread && data.unread_count > 0) {
                const badge = document.querySelector('.chat-notification-badge');
                if (badge) {
                    badge.textContent = '!';
                }
                
                if ("Notification" in window && Notification.permission === "granted") {
                    new Notification("New message from support", {
                        body: `You have ${data.unread_count} new message(s) from staff`,
                        icon: "{{ asset('asset/images/logo.jpg') }}",
                        tag: "live-chat-notification",
                        requireInteraction: true
                    });
                }
            }
        } catch (error) {
            console.error('Error checking messages:', error);
        }
    }, 30000);
}

if ("Notification" in window && Notification.permission === "default") {
    Notification.requestPermission();
}
</script>
@endif

<script>
document.addEventListener('click', function(event) {
    const navbar = document.getElementById('navbarContent');
    const toggler = document.querySelector('.navbar-toggler');
    
    if (navbar && navbar.classList.contains('show')) {
        if (!navbar.contains(event.target) && !toggler.contains(event.target)) {
            const bsCollapse = new bootstrap.Collapse(navbar, {
                toggle: false
            });
            bsCollapse.hide();
        }
    }
});
</script>