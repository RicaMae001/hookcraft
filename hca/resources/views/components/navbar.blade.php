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
                
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>

            <ul class="navbar-nav flex-row align-items-center">
                <!-- Chatbot Icon with Live Chat Notification -->
                <li class="nav-item me-3">
                    <a class="nav-link position-relative icon-link" 
                       href="{{ route('chatbot') }}" 
                       title="{{ $hasActiveChat ? 'Active Live Chat - Click to continue' : 'AI Assistant' }}"
                       id="chatbotNavLink">
                        <i class="bi bi-robot fs-5"></i>
                        
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
                            <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" 
                                 width="40" height="40" class="rounded-circle profile-img">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow modern-dropdown">
                            <li class="px-3 py-3 border-bottom user-info-header">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" 
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
                            <li><hr class="dropdown-divider my-2"></li>
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

/* ============================================
   MOBILE RESPONSIVE DESIGN
   ============================================ */
@media (max-width: 991.98px) {
    /* Hide desktop layout, show mobile */
    .modern-navbar {
        padding: 0.5rem 0;
    }

    .modern-navbar .container {
        padding: 0 1rem;
    }

    /* Adjust brand for mobile */
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

    /* Mobile Sidebar Navigation */
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
    
    /* Dark overlay */
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
    
    /* Close Button */
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
    
    /* User Profile Section - TOP */
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
    
    /* Profile Picture & Info */
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
    
    /* Icon Cards (AI Assistant & Cart) */
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
    
    /* Add text labels */
    .navbar-nav.flex-row > .nav-item:nth-child(1) .icon-link::after {
        content: 'AI Assistant';
        font-weight: 600;
        color: #FF69B4;
        font-size: 0.95rem;
        white-space: nowrap;
    }
    
    .navbar-nav.flex-row > .nav-item:nth-child(2) .icon-link::after {
        content: 'Shopping Cart';
        font-weight: 600;
        color: #FF69B4;
        font-size: 0.95rem;
        white-space: nowrap;
    }
    
    .navbar-nav.flex-row .icon-link:hover::after {
        color: #FF1493;
    }
    
    /* Navigation Links - MIDDLE */
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
    
    /* Add emoji icons to nav links */
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
        content: '🖼️'; 
        font-size: 1.1rem;
    }
    .navbar-nav.mx-auto .nav-item:nth-child(5) .nav-link::before {
        content: '✉️'; 
        font-size: 1.1rem;
    }
    
    /* User Dropdown Menu - BOTTOM */
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
        display: none; /* Hide duplicate user info */
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
    
    /* Logout button special styling */
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
    
    /* Login button mobile */
    .login-btn {
        width: 100%;
        text-align: center;
        display: block !important;
        padding: 0.85rem 1.5rem !important;
        font-size: 0.95rem;
    }
    
    /* Custom scrollbar */
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
    
    /* Ensure proper spacing */
    .navbar-nav.flex-row > * {
        flex-shrink: 0;
    }
}

/* Tablet adjustments */
@media (min-width: 768px) and (max-width: 991.98px) {
    .navbar-collapse {
        max-width: 380px;
        width: 80%;
    }
    
    .navbar-nav.flex-row {
        padding: 4rem 1.5rem 1.75rem;
    }
    
    .navbar-nav.flex-row .user-dropdown > a img {
        width: 80px !important;
        height: 80px !important;
    }
    
    .navbar-nav.mx-auto {
        padding: 1.5rem;
    }
    
    .navbar-nav.mx-auto .nav-link,
    .navbar-nav.flex-row .dropdown-menu .dropdown-item,
    .navbar-nav.flex-row .icon-link {
        padding: 1rem 1.25rem !important;
        font-size: 1rem;
    }
}

/* Small mobile devices */
@media (max-width: 576px) {
    .modern-navbar .brand-text {
        font-size: 1rem;
    }
    
    .modern-navbar .logo-img {
        width: 35px;
        height: 35px;
    }
    
    .navbar-collapse {
        width: 90%;
        max-width: 320px;
    }
    
    .mobile-close-btn {
        width: 36px;
        height: 36px;
        font-size: 1.1rem;
    }
    
    .navbar-nav.flex-row {
        padding: 3rem 1rem 1.25rem;
    }
    
    .navbar-nav.flex-row .user-dropdown > a img {
        width: 65px !important;
        height: 65px !important;
    }
    
    .navbar-nav.mx-auto {
        padding: 1rem;
    }
    
    .navbar-nav.mx-auto .nav-link,
    .navbar-nav.flex-row .dropdown-menu .dropdown-item {
        padding: 0.75rem 0.85rem !important;
        font-size: 0.9rem;
    }
    
    .navbar-nav.flex-row .icon-link {
        padding: 0.75rem 0.85rem !important;
    }
    
    .navbar-nav.flex-row .icon-link::after,
    .navbar-nav.mx-auto .nav-link::before {
        font-size: 1rem;
    }
    
    .navbar-nav.flex-row .dropdown-menu {
        padding: 0 1rem 1rem;
    }
}

/* Extra small devices */
@media (max-width: 375px) {
    .navbar-collapse {
        width: 95%;
    }
    
    .modern-navbar .brand-text {
        font-size: 0.9rem;
    }
    
    .navbar-nav.mx-auto .nav-link,
    .navbar-nav.flex-row .dropdown-menu .dropdown-item,
    .navbar-nav.flex-row .icon-link {
        font-size: 0.85rem;
        padding: 0.7rem 0.8rem !important;
    }
}
</style>

@if($hasActiveChat)
<script>
// Check for new messages every 30 seconds when not on chatbot page
if (window.location.pathname !== '/chatbot') {
    setInterval(async function() {
        try {
            const response = await fetch('{{ route("livechat.check-unread") }}', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
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

// Auto-close mobile menu when clicking outside
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
@endif