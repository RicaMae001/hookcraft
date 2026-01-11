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
                    <a class="nav-link position-relative" href="{{ $isLoggedIn ? route('cart.index') : '#' }}">
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