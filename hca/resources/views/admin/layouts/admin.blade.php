<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - HookcraftAvenue Admin</title>
    
    <!-- CRITICAL: Load theme BEFORE any styles to prevent flicker -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    
    @stack('head-scripts')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* Light Mode Colors */
        :root {
            --primary-pink: #FF6B9D;
            --primary-dark: #2D3748;
            --secondary: #667EEA;
            --success: #48BB78;
            --warning: #F6AD55;
            --danger: #FC8181;
            --light-bg: #F7FAFC;
            --card-bg: #FFFFFF;
            --text-primary: #1A202C;
            --text-secondary: #718096;
            --border-color: #E2E8F0;
            --sidebar-width: 280px;
            --hover-bg: rgba(0, 0, 0, 0.05);
        }

        /* Dark Mode Colors */
        [data-theme="dark"] {
            --primary-pink: #FF6B9D;
            --primary-dark: #1A202C;
            --secondary: #667EEA;
            --success: #48BB78;
            --warning: #F6AD55;
            --danger: #FC8181;
            --light-bg: #1A202C;
            --card-bg: #2D3748;
            --text-primary: #F7FAFC;
            --text-secondary: #A0AEC0;
            --border-color: #4A5568;
            --hover-bg: rgba(255, 255, 255, 0.05);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--light-bg);
            color: var(--text-primary);
            margin-top: 30px;
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Modern Navbar */
        .modern-navbar {
            background: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 2rem;
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            z-index: 999;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-pink);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Theme Toggle Switch */
        .theme-toggle {
            position: relative;
            width: 70px;
            height: 36px;
            border-radius: 18px;
            background: var(--border-color);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            padding: 3px;
        }

        .theme-toggle:hover {
            background: var(--text-secondary);
        }

        .theme-toggle-slider {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: white;
            position: absolute;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            left: 3px;
        }

        [data-theme="dark"] .theme-toggle-slider {
            left: calc(100% - 33px);
            background: var(--primary-pink);
        }

        .theme-toggle-icon {
            font-size: 14px;
            transition: all 0.3s ease;
            position: absolute;
        }

        .theme-toggle-icon.sun {
            color: #F6AD55;
            opacity: 1;
        }

        .theme-toggle-icon.moon {
            color: white;
            opacity: 0;
        }

        [data-theme="dark"] .theme-toggle-icon.sun {
            opacity: 0;
            transform: rotate(180deg);
        }

        [data-theme="dark"] .theme-toggle-icon.moon {
            opacity: 1;
            transform: rotate(0deg);
        }

        .notification-bell {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .notification-bell:hover {
            background: var(--hover-bg);
            transform: translateY(-2px);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--card-bg);
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: var(--light-bg);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .admin-profile:hover {
            background: var(--hover-bg);
        }

        .admin-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-pink), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        /* Modern Sidebar */
        .modern-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--card-bg);
            border-right: 1px solid var(--border-color);
            padding: 2rem 0;
            overflow-y: auto;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-logo {
            padding: 0 2rem 2rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .sidebar-logo h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-pink);
            margin: 0;
        }

        .sidebar-logo p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin: 0.25rem 0 0 0;
        }

        .sidebar-section {
            padding: 0 1rem;
            margin-bottom: 2rem;
        }

        .sidebar-section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
            font-weight: 600;
            padding: 0 1rem;
            margin-bottom: 0.75rem;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.875rem 1.25rem;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
            font-weight: 500;
            position: relative;
        }

        .sidebar-link i {
            width: 20px;
            text-align: center;
            font-size: 1.125rem;
        }

        .sidebar-link:hover {
            background: var(--hover-bg);
            color: var(--primary-pink);
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.15), rgba(102, 126, 234, 0.15));
            color: var(--primary-pink);
            font-weight: 600;
        }

        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: var(--primary-pink);
            border-radius: 0 4px 4px 0;
        }

        .sidebar-link.restricted {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .sidebar-link .badge {
            margin-left: auto;
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 6rem 2rem 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Cards */
        .stat-card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 1.75rem;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.08);
        }

        .stat-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .stat-card-icon.primary {
            background: linear-gradient(135deg, rgba(255, 107, 157, 0.15), rgba(255, 107, 157, 0.05));
            color: var(--primary-pink);
        }

        .stat-card-icon.success {
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.15), rgba(72, 187, 120, 0.05));
            color: var(--success);
        }

        .stat-card-icon.warning {
            background: linear-gradient(135deg, rgba(246, 173, 85, 0.15), rgba(246, 173, 85, 0.05));
            color: var(--warning);
        }

        .stat-card-icon.info {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(102, 126, 234, 0.05));
            color: var(--secondary);
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0.5rem 0;
        }

        .stat-card-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Content Cards */
        .content-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .content-card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .content-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        /* Buttons */
        .btn-modern {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-pink), #FF8AAE);
            color: white;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(255, 107, 157, 0.3);
            color: white;
        }

        .btn-modern-secondary {
            background: var(--light-bg);
            color: var(--text-primary);
        }

        .btn-modern-secondary:hover {
            background: var(--hover-bg);
        }

        /* Tables */
        .modern-table {
            width: 100%;
        }

        .modern-table thead {
            background: var(--light-bg);
        }

        .modern-table th {
            padding: 1rem 1.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border: none;
        }

        .modern-table td {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }

        .modern-table tbody tr {
            transition: all 0.2s ease;
        }

        .modern-table tbody tr:hover {
            background: var(--hover-bg);
        }

        /* Badges */
        .badge-modern {
            padding: 0.375rem 0.875rem;
            border-radius: 8px;
            font-size: 0.8125rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
        }

        .badge-success {
            background: rgba(72, 187, 120, 0.15);
            color: var(--success);
        }

        .badge-warning {
            background: rgba(246, 173, 85, 0.15);
            color: var(--warning);
        }

        .badge-danger {
            background: rgba(252, 129, 129, 0.15);
            color: var(--danger);
        }

        .badge-info {
            background: rgba(102, 126, 234, 0.15);
            color: var(--secondary);
        }

        /* Dropdown Menu Dark Mode */
        .dropdown-menu {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
        }

        .dropdown-item {
            color: var(--text-primary);
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background: var(--hover-bg);
            color: var(--primary-pink);
        }

        /* Input Fields Dark Mode */
        .form-control, .form-select {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        .form-control:focus, .form-select:focus {
            background: var(--card-bg);
            border-color: var(--primary-pink);
            color: var(--text-primary);
        }

        .input-group-text {
            background: var(--light-bg);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
        }

        /* Alert Dark Mode */
        .alert {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
        }

        /* Modal Dark Mode */
        .modal-content {
            background: var(--card-bg);
            color: var(--text-primary);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .modern-sidebar {
                transform: translateX(-100%);
            }

            .modern-sidebar.show {
                transform: translateX(0);
            }

            .modern-navbar,
            .main-content {
                margin-left: 0;
                left: 0;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-bg);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-secondary);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Modern Navbar -->
    @include('admin.layouts.navbar')

    <!-- Modern Sidebar -->
    <aside class="modern-sidebar" id="sidebar">
        <div class="sidebar-logo">
            <h2><i class="fas fa-flower"></i> HookcraftAvenue</h2>
            <p>Admin Dashboard</p>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Main Menu</div>
            
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.orders') }}" class="sidebar-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                <i class="fas fa-shopping-bag"></i>
                <span>Orders</span>
            </a>

            <a href="{{ route('admin.products') }}" class="sidebar-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Products</span>
            </a>

            <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Customers/User</span>
            </a>

            <a href="{{ route('admin.customizations.index') }}" class="sidebar-link {{ request()->routeIs('admin.customizations.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i>
                <span>Customize</span>
            </a>

            <a href="{{ route('admin.livechat.index') }}" class="sidebar-link {{ request()->routeIs('admin.livechat.*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>
                <span>Live Chat</span>
                <span class="badge bg-warning ms-auto" id="waitingBadge" style="display: none;"></span>
            </a>
        </div>

        @if(session('admin_role') === 'Admin' || session('admin_role') === 'SuperAdmin')
        <div class="sidebar-section">
            <div class="sidebar-section-title">
                Staff Management
            </div>

            <a href="{{ route('admin.staff.admins') }}" class="sidebar-link {{ request()->routeIs('admin.staff.admins') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i>
                <span>Admin Accounts</span>
            </a>

            <a href="{{ route('admin.staff.delivery') }}" class="sidebar-link {{ request()->routeIs('admin.staff.delivery') ? 'active' : '' }}">
                <i class="fas fa-truck"></i>
                <span>Delivery Staff</span>
            </a>
        </div>
        @else
        <div class="sidebar-section">
            <div class="sidebar-section-title" style="color: var(--danger);">
                Admin Only
            </div>

            <a href="#" class="sidebar-link restricted" onclick="showAccessDeniedModal(event, 'Admin Accounts')">
                <i class="fas fa-user-shield"></i>
                <span>Admin Accounts</span>
                <i class="fas fa-lock ms-auto" style="font-size: 0.875rem;"></i>
            </a>

            <a href="#" class="sidebar-link restricted" onclick="showAccessDeniedModal(event, 'Delivery Staff')">
                <i class="fas fa-truck"></i>
                <span>Delivery Staff</span>
                <i class="fas fa-lock ms-auto" style="font-size: 0.875rem;"></i>
            </a>
        </div>
        @endif

        <div class="sidebar-section">
            <div class="sidebar-section-title">Quick Links</div>

            <a href="{{ route('home') }}" target="_blank" class="sidebar-link">
                <i class="fas fa-store"></i>
                <span>View Store</span>
                <i class="fas fa-external-link-alt ms-auto" style="font-size: 0.75rem;"></i>
            </a>

            <a href="{{ route('gallery') }}" target="_blank" class="sidebar-link">
                <i class="fas fa-eye"></i>
                <span>View Gallery</span>
                <i class="fas fa-external-link-alt ms-auto" style="font-size: 0.75rem;"></i>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Access Denied Modal -->
    <div class="modal fade" id="accessDeniedModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none;">
                <div class="modal-header" style="background: var(--danger); color: white; border-radius: 16px 16px 0 0;">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Access Denied</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <i class="fas fa-lock fa-4x mb-4" style="color: var(--danger);"></i>
                    <h5>You don't have permission to access <span id="sectionName" style="color: var(--danger);"></span></h5>
                    <p class="text-muted">Only Admin can access this section. Please contact your administrator if you need access.</p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-modern-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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

        // Notification Toggle
        function toggleNotifications() {
            // Add your notification logic here
            alert('Notifications feature coming soon!');
        }

        // Show Access Denied Modal
        function showAccessDeniedModal(event, sectionName) {
            event.preventDefault();
            document.getElementById('sectionName').textContent = sectionName;
            new bootstrap.Modal(document.getElementById('accessDeniedModal')).show();
        }

        // Show modal if error_modal exists in session
        @if(session('error_modal'))
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('accessDeniedModal'));
            document.getElementById('sectionName').textContent = '{{ session('error_modal.title') ?? 'this section' }}';
            modal.show();
        });
        @endif
    </script>
    
    @stack('scripts')
</body>
</html>