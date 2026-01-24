{{-- resources/views/admin/layouts/navbar.blade.php --}}

<!-- Modern Navbar -->
<nav class="modern-navbar">
    <div class="d-flex align-items-center gap-3">
        <button class="btn btn-modern-secondary d-lg-none" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
            <i class="fas fa-flower"></i> HookcraftAvenue
        </a>
    </div>

    <div class="navbar-actions">
        <!-- Pending Deliveries Badge -->
        @if(isset($pendingCount) && $pendingCount > 0)
        <div class="notification-bell" onclick="toggleNotificationDropdown()" title="{{ $pendingCount }} pending deliveries" id="notificationTrigger">
            <i class="fas fa-truck"></i>
            <span class="notification-badge">{{ $pendingCount }}</span>
        </div>
        @else
        <div class="notification-bell" onclick="toggleNotificationDropdown()" title="Notifications" id="notificationTrigger">
            <i class="fas fa-bell"></i>
        </div>
        @endif

        <!-- Notification Dropdown -->
        <div class="notification-dropdown-wrapper" id="notificationDropdown">
            @include('admin.partials.notification-dropdown')
        </div>

        <!-- Theme Toggle -->
        <div class="theme-toggle" onclick="toggleTheme()">
            <div class="theme-toggle-slider">
                <i class="fas fa-sun theme-toggle-icon sun"></i>
                <i class="fas fa-moon theme-toggle-icon moon"></i>
            </div>
        </div>

        <!-- Admin Profile -->
        <div class="admin-profile dropdown" onclick="toggleProfileDropdown(event)">
            <div class="admin-avatar">
                {{ strtoupper(substr(session('admin_name') ?? 'A', 0, 1)) }}
            </div>
            <div class="d-none d-md-block">
                <div style="font-weight: 600;">{{ session('admin_name') ?? 'Admin' }}</div>
                <div style="font-size: 0.75rem; color: var(--text-secondary);">
                    {{ session('admin_role') ?? 'Admin' }}
                </div>
            </div>
            <i class="fas fa-chevron-down ms-2" style="font-size: 0.875rem;"></i>
            
            <!-- Profile Dropdown Menu -->
            <div class="dropdown-menu dropdown-menu-end shadow-lg profile-dropdown">
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user me-2"></i> My Profile
                </a>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cog me-2"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
    /* Notification Dropdown Wrapper */
    .notification-dropdown-wrapper {
        position: absolute;
        top: 100%;
        right: 0;
        width: 350px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        display: none;
        margin-top: 10px;
        overflow: hidden;
    }

    .notification-dropdown-wrapper.show {
        display: block;
        animation: slideDown 0.2s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .profile-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 10px;
    }

    .profile-dropdown.show {
        display: block;
        animation: slideDown 0.2s ease;
    }
</style>

<script>
    // Theme Toggle Function
    function toggleTheme() {
        const html = document.documentElement;
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
    }

    // Sidebar Toggle for Mobile
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Toggle Notification Dropdown
    function toggleNotificationDropdown() {
        const dropdown = document.getElementById('notificationDropdown');
        const profileDropdown = document.querySelector('.profile-dropdown');
        
        dropdown.classList.toggle('show');
        if (profileDropdown) profileDropdown.classList.remove('show');
        
        event.stopPropagation();
    }

    // Toggle Profile Dropdown
    function toggleProfileDropdown(event) {
        event.stopPropagation();
        const profileDropdown = document.querySelector('.profile-dropdown');
        const notificationDropdown = document.getElementById('notificationDropdown');
        
        profileDropdown.classList.toggle('show');
        if (notificationDropdown) notificationDropdown.classList.remove('show');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        const notificationDropdown = document.getElementById('notificationDropdown');
        const profileDropdown = document.querySelector('.profile-dropdown');
        
        if (notificationDropdown) notificationDropdown.classList.remove('show');
        if (profileDropdown) profileDropdown.classList.remove('show');
    });

    // Close dropdowns when clicking inside them (prevent immediate closing)
    document.querySelectorAll('.notification-dropdown-wrapper, .profile-dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
</script>