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

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <img src="{{ asset('asset/images/logo.jpg') }}" alt="Logo" width="40" height="40" class="rounded-circle me-2">
            HookcraftAvenue
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('shop') }}">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('gallery') }}">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
            </ul>

            <ul class="navbar-nav flex-row align-items-center">
                <!-- Chatbot Icon with Live Chat Notification -->
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" 
                       href="{{ route('chatbot') }}" 
                       title="{{ $hasActiveChat ? 'Active Live Chat - Click to continue' : 'AI Assistant' }}"
                       id="chatbotNavLink">
                        <i class="bi bi-robot fs-5" style="color: #FF69B4;"></i>
                        
                        @if($hasActiveChat)
                            <!-- Red exclamation badge for active chat -->
                            <span class="position-absolute top-0 start-100 translate-middle">
                                <span class="badge bg-danger rounded-circle chat-notification-badge" 
                                      style="width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold;">
                                    !
                                </span>
                            </span>
                            
                            <!-- Unread message count (if any) -->
                            @if($unreadMessages > 0)
                                <span class="position-absolute" 
                                      style="top: -8px; left: -8px;">
                                    <span class="badge rounded-pill bg-danger" 
                                          style="font-size: 0.65rem; padding: 0.25em 0.5em;">
                                        {{ $unreadMessages > 9 ? '9+' : $unreadMessages }}
                                    </span>
                                </span>
                            @endif
                        @else
                            <!-- Green dot for AI online -->
                            <span class="position-absolute top-0 start-100 translate-middle">
                                <span class="badge bg-success rounded-circle" style="width: 8px; height: 8px;"></span>
                            </span>
                        @endif
                    </a>
                </li>

                <!-- Cart -->
                <li class="nav-item me-3">
                    <a class="nav-link position-relative" href="{{ $isLoggedIn ? route('cart.index') : 'javascript:void(0)' }}">
                        <i class="bi bi-cart fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                            {{ $cartCount }}
                        </span>
                    </a>
                </li>

                @if($isLoggedIn)
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle p-0 border-0 bg-transparent d-flex align-items-center" 
                           href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" 
                                 width="40" height="40" class="rounded-circle" style="object-fit: cover; border: 2px solid #FFB6C1;">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 shadow" style="min-width: 250px;">
                            <li class="px-3 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('asset/images/default-profile.png') }}" alt="Profile" 
                                         width="50" height="50" class="rounded-circle me-3" style="border: 2px solid #FFB6C1;">
                                    <div>
                                        <div class="fw-bold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
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
                    <li class="nav-item me-2">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">Login</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<style>
/* Pulse animation for chat notification */
@keyframes chat-pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.15);
        opacity: 0.8;
    }
}

.chat-notification-badge {
    animation: chat-pulse 2s infinite;
    box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
}

/* MOBILE RESPONSIVE - ONLY CSS ADDITIONS */
@media (max-width: 991.98px) {
    /* Make navbar-collapse use flexbox for reordering */
    .navbar-collapse {
        position: fixed;
        top: 0;
        right: -100%;
        width: 85%;
        max-width: 350px;
        height: 100vh;
        background: linear-gradient(135deg, #fff 0%, #fff5f8 100%);
        box-shadow: -8px 0 30px rgba(255, 105, 180, 0.2);
        transition: right 0.3s ease;
        overflow-y: auto;
        z-index: 1050;
        padding: 0;
        display: flex !important;
        flex-direction: column !important;
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
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: -1;
    }
    
    /* User info section FIRST - Enhanced Design */
    .navbar-nav.flex-row {
        order: 1;
        flex-direction: column !important;
        align-items: flex-start !important; /* Keep left aligned */
        padding: 2rem 1.5rem 1.5rem;
        background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 100%);
        margin: 0 !important;
        border-bottom: none;
        box-shadow: 0 4px 15px rgba(255, 105, 180, 0.2);
    }
    
    /* Show profile picture ONLY - bigger, stay on left */
    .navbar-nav.flex-row .dropdown {
        width: auto;
        margin-bottom: 1.5rem;
        order: -1; /* Show first before icons */
    }
    
    .navbar-nav.flex-row .dropdown > a {
        display: flex !important;
        align-items: center;
        background: transparent !important;
        padding: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        gap: 0;
        text-decoration: none;
        border: none !important;
    }
    
    .navbar-nav.flex-row .dropdown > a img {
        width: 80px !important;
        height: 80px !important;
        border: 4px solid white !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3) !important;
    }
    
    .navbar-nav.flex-row .dropdown > a::after {
        display: none !important; /* Hide dropdown arrow */
    }
    
    .navbar-nav.flex-row > .nav-item:not(.dropdown) {
        width: 100%;
        margin: 0 !important;
        padding: 0.75rem 0;
    }
    
    /* Style icons in user section */
    .navbar-nav.flex-row .nav-link {
        background: rgba(255, 255, 255, 0.9) !important;
        border-radius: 12px !important;
        padding: 0.75rem 1rem !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
        color: white !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    }
    
    .navbar-nav.flex-row .nav-link:hover {
        background: white !important;
        transform: translateX(5px) !important;
        box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3) !important;
    }
    
    .navbar-nav.flex-row .nav-link i {
        font-size: 1.3rem;
        color: #FF69B4 !important;
    }
    
    /* Fix AI Assistant icon - replace with robot emoji */
    .navbar-nav.flex-row > .nav-item:not(.dropdown):first-of-type .nav-link i.bi-robot::before {
        content: '🤖' !important;
        font-style: normal;
        font-family: inherit !important;
    }
    
    .navbar-nav.flex-row > .nav-item:not(.dropdown):first-of-type .nav-link i.bi-robot {
        font-family: inherit !important;
    }
    
    /* Keep cart icon as Bootstrap icon */
    .navbar-nav.flex-row .nav-link i.bi-cart {
        color: #FF69B4 !important;
        font-size: 1.3rem !important;
    }
    
    /* Add text labels to icons in mobile - AI Assistant */
    .navbar-nav.flex-row .nav-item:not(.dropdown):first-of-type .nav-link::after {
        content: 'AI Assistant';
        font-size: 0.9rem;
        color: #FF69B4;
    }
    
    /* Add text labels to icons in mobile - Shopping Cart */
    .navbar-nav.flex-row .nav-link .bi-cart::after {
        content: 'Shopping Cart';
        font-size: 0.9rem;
        color: #FF69B4;
        margin-left: 0.75rem;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    .navbar-nav.flex-row .nav-item:not(.dropdown):last-of-type .nav-link::after {
        content: 'Shopping Cart';
        font-size: 0.9rem;
        color: #FF69B4;
    }
    
    .navbar-nav.flex-row .nav-link:hover i,
    .navbar-nav.flex-row .nav-link:hover::after {
        color: #FF1493 !important;
    }
    
    /* Navigation links SECOND - Modern Style with Pink Background */
    .navbar-nav.mx-auto {
        order: 2;
        margin: 0 !important;
        padding: 1.5rem 1.5rem 1rem;
        width: 100%;
        background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 100%);
    }
    
    .navbar-nav.mx-auto .nav-item {
        width: 100%;
        margin-bottom: 0.5rem;
        border: none !important;
    }
    
    .navbar-nav.mx-auto .nav-link {
        padding: 1rem 1.25rem !important;
        background: rgba(255, 255, 255, 0.95) !important;
        border-radius: 12px !important;
        color: #FF69B4 !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        border-left: 4px solid transparent !important;
    }
    
    .navbar-nav.mx-auto .nav-link:hover {
        background: white !important;
        color: #FF1493 !important;
        transform: translateX(8px) !important;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.4) !important;
        border-left-color: white !important;
    }
    
    /* Add icons to navigation links */
    .navbar-nav.mx-auto .nav-item:nth-child(1) .nav-link::before {
        content: '🏠';
        font-size: 1.2rem;
    }
    
    .navbar-nav.mx-auto .nav-item:nth-child(2) .nav-link::before {
        content: '🛍️';
        font-size: 1.2rem;
    }
    
    .navbar-nav.mx-auto .nav-item:nth-child(3) .nav-link::before {
        content: 'ℹ️';
        font-size: 1.2rem;
    }
    
    .navbar-nav.mx-auto .nav-item:nth-child(4) .nav-link::before {
        content: '🖼️';
        font-size: 1.2rem;
    }
    
    .navbar-nav.mx-auto .nav-item:nth-child(5) .nav-link::before {
        content: '✉️';
        font-size: 1.2rem;
    }
    
    /* Dropdown menu appears THIRD - Matching Style with Pink Background */
    .navbar-nav.flex-row .dropdown {
        order: 3 !important;
        width: 100% !important;
        padding: 0 1.5rem 1.5rem;
        background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 100%);
        margin: 0 !important;
    }
    
    .navbar-nav.flex-row .dropdown-menu {
        position: static !important;
        display: block !important;
        transform: none !important;
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
    
    .navbar-nav.flex-row .dropdown-menu li {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item {
        padding: 1rem 1.25rem !important;
        background: rgba(255, 255, 255, 0.95) !important;
        border-radius: 12px !important;
        color: #FF69B4 !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
        display: flex !important;
        align-items: center !important;
        gap: 0.75rem !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
        border-left: 4px solid transparent !important;
        border-bottom: none !important;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item:hover {
        background: white !important;
        color: #FF1493 !important;
        transform: translateX(8px) !important;
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.4) !important;
        border-left-color: white !important;
    }
    
    /* Style existing icons in dropdown */
    .navbar-nav.flex-row .dropdown-menu .dropdown-item i {
        width: 24px;
        font-size: 1.2rem;
        color: #FF69B4 !important;
        transition: color 0.3s ease;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item:hover i {
        color: #FF1493 !important;
    }
    
    /* Hide user header */
    .navbar-nav.flex-row .dropdown-menu > li:first-child {
        display: none;
    }
    
    /* Hide divider */
    .navbar-nav.flex-row .dropdown-menu .dropdown-divider {
        display: none;
    }
    
    /* Logout styling - Special red accent on pink background */
    .navbar-nav.flex-row .dropdown-menu .dropdown-item.text-danger {
        color: #dc3545 !important;
        background: rgba(255, 255, 255, 0.95) !important;
        border-left-color: transparent !important;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item.text-danger:hover {
        background: white !important;
        color: #c82333 !important;
        border-left-color: white !important;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item.text-danger i {
        color: #dc3545 !important;
    }
    
    .navbar-nav.flex-row .dropdown-menu .dropdown-item.text-danger:hover i {
        color: #c82333 !important;
    }
    
    /* Smooth scrollbar */
    .navbar-collapse::-webkit-scrollbar {
        width: 6px;
    }
    
    .navbar-collapse::-webkit-scrollbar-track {
        background: rgba(255, 182, 193, 0.1);
    }
    
    .navbar-collapse::-webkit-scrollbar-thumb {
        background: #FFB6C1;
        border-radius: 10px;
    }
    
    .navbar-collapse::-webkit-scrollbar-thumb:hover {
        background: #FF69B4;
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
                // Update badge if it exists
                const badge = document.querySelector('.chat-notification-badge');
                if (badge) {
                    badge.textContent = '!';
                }
                
                // Show browser notification if allowed
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
    }, 30000); // Every 30 seconds
}

// Request notification permission
if ("Notification" in window && Notification.permission === "default") {
    Notification.requestPermission();
}
</script>
@endif