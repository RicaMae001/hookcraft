<!-- Navbar -->
<nav class="navbar navbar-dark sticky-top navbar-delivery flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('delivery.dashboard') }}">
        <i class="fas fa-truck me-2"></i>Delivery Portal
    </a>
    <div class="navbar-nav ms-auto d-flex flex-row align-items-center">
        <!-- Notification Bell -->
        <div class="position-relative me-3">
            <div class="notification-bell" onclick="toggleNotifications()">
                <i class="fas fa-bell fa-lg text-white"></i>
                @if(session('delivery_notifications') && count(array_filter(session('delivery_notifications', []), function($n) { return !$n['is_read']; })) > 0)
                <span class="notification-badge">
                    {{ count(array_filter(session('delivery_notifications', []), function($n) { return !$n['is_read']; })) }}
                </span>
                @endif
            </div>
            
            <!-- Notification Dropdown -->
            <div class="notification-dropdown" id="notificationDropdown">
                <div class="notification-header">
                    <h6 class="mb-0"><strong>Notifications</strong></h6>
                    @if(session('delivery_notifications') && count(array_filter(session('delivery_notifications', []), function($n) { return !$n['is_read']; })) > 0)
                    <a href="#" onclick="markAllAsRead(); return false;" class="btn btn-sm btn-link text-decoration-none">
                        Mark all read
                    </a>
                    @endif
                </div>
                
                <div class="notification-list">
                    @php
                        $notifications = session('delivery_notifications', []);
                    @endphp
                    
                    @forelse($notifications as $notification)
                    <div class="notification-item {{ $notification['is_read'] ? '' : 'unread' }}">
                        <div class="d-flex">
                            <div class="notification-icon admin">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1"><strong>{{ $notification['title'] }}</strong></h6>
                                <p class="mb-1 small">{{ $notification['message'] }}</p>
                                <span class="notification-time">
                                    <i class="far fa-clock"></i> 
                                    {{ \Carbon\Carbon::parse($notification['timestamp'])->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted">
                        <i class="far fa-bell-slash fa-3x mb-3"></i>
                        <p>No notifications yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
        
        <!-- Logout -->
        <div class="nav-item text-nowrap">
            <form action="{{ route('delivery.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="nav-link px-3 btn btn-link text-white text-decoration-none">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </button>
            </form>
        </div>
    </div>
</nav>