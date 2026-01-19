<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- CRITICAL: CSRF Token Meta Tag - Required for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Delivery Coordinator - @yield('title', 'Dashboard')</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #2c3e50;
            --sidebar-hover: #34495e;
            --primary-color: #3498db;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
            overflow-x: hidden;
        }
        
        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1000;
            padding: 0;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }
        
        .sidebar .sidebar-header {
            padding: 20px;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }
        
        .sidebar .user-info {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .user-info .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: white;
            font-size: 24px;
        }
        
        .user-info h6 {
            color: white;
            margin-bottom: 5px;
            text-align: center;
        }
        
        .user-info small {
            color: #bdc3c7;
            text-align: center;
            display: block;
        }
        
        .sidebar .nav {
            padding: 20px 0;
        }
        
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 12px 20px;
            margin: 2px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 15px;
            font-weight: 500;
        }
        
        .sidebar .nav-link i {
            width: 25px;
            margin-right: 10px;
            font-size: 16px;
        }
        
        .sidebar .nav-link:hover {
            background: var(--sidebar-hover);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background: var(--primary-color);
            color: white;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        /* Header */
        .top-header {
            background: white;
            padding: 15px 0;
            margin: -20px -20px 20px -20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        
        .page-title h1 {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        .page-title small {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1);
        }
        
        .stats-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }
        
        .stats-card .icon.bg-primary {
            background: rgba(52, 152, 219, 0.1);
            color: var(--primary-color);
        }
        
        .stats-card .icon.bg-success {
            background: rgba(46, 204, 113, 0.1);
            color: var(--success-color);
        }
        
        .stats-card .icon.bg-warning {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning-color);
        }
        
        .stats-card .icon.bg-danger {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger-color);
        }
        
        .stats-card h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-card p {
            color: #7f8c8d;
            margin: 0;
            font-size: 14px;
        }
        
        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            margin-bottom: 20px;
        }
        
        .content-card .card-header {
            background: none;
            border-bottom: 1px solid #e9ecef;
            padding: 0 0 15px 0;
            margin-bottom: 20px;
        }
        
        .content-card .card-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        /* Tables */
        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .table {
            margin: 0;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 12px 15px;
        }
        
        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
            border-color: #e9ecef;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        /* Badges */
        .badge {
            padding: 5px 10px;
            font-weight: 500;
            border-radius: 6px;
        }
        
        /* Alerts */
        .alert {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        
        /* Buttons */
        .btn {
            border-radius: 6px;
            padding: 8px 16px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 13px;
        }
        
        .btn i {
            margin-right: 5px;
        }
        
        /* Tabs */
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }
        
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 8px 8px 0 0;
            margin-right: 5px;
        }
        
        .nav-tabs .nav-link:hover {
            border: none;
            color: #495057;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            background-color: white;
            border-bottom: 3px solid var(--primary-color);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .top-header {
                margin: -20px -15px 20px -15px;
            }
        }
        
        /* Animations */
        .pulse {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(0, 123, 255, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(0, 123, 255, 0);
            }
        }
        
        /* Logout Modal */
        .logout-modal .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .logout-modal .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }
        
        .logout-modal .modal-footer {
            border-top: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="https://placehold.co/150x40/2c3e50/ffffff?text=HCA+LARA" alt="Logo" style="max-width: 150px;">
        </div>
        
        <div class="user-info">
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            <h6>{{ session('coordinator_name') ?? 'Delivery Coordinator' }}</h6>
            <small>Delivery Staff</small>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}" href="{{ route('delivery.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('delivery.deliveries') ? 'active' : '' }}" href="{{ route('delivery.deliveries') }}">
                <i class="fas fa-box"></i> My Deliveries
            </a>
            <a class="nav-link {{ request()->routeIs('delivery.livechat.*') ? 'active' : '' }}" href="{{ route('delivery.livechat.index') }}">
                <i class="fas fa-comments"></i> Live Chat
                <span id="chatNotification" class="badge bg-danger float-end d-none">!</span>
            </a>
            <a class="nav-link {{ request()->routeIs('delivery.history') ? 'active' : '' }}" href="{{ route('delivery.history') }}">
                <i class="fas fa-history"></i> History
            </a>
            <hr style="border-color: rgba(255,255,255,0.1); margin: 20px;">
            <a class="nav-link text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Header -->
        <div class="top-header">
            <div class="header-content">
                <div class="page-title">
                    <h1>@yield('title', 'Dashboard')</h1>
                    <small>Delivery Coordinator Panel</small>
                </div>
                
                <div class="header-actions">
                    <span class="badge bg-success">
                        <i class="fas fa-circle pulse"></i> Online
                    </span>
                    <button class="btn btn-outline-secondary d-md-none" id="sidebarToggle">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Content -->
        @yield('content')
    </div>

    <!-- Logout Modal -->
    <div class="modal fade logout-modal" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Confirm Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-question-circle fa-4x text-warning mb-3"></i>
                    <h5 class="mb-3">Are you sure you want to logout?</h5>
                    <p class="text-muted">You will be redirected to the login page.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <form action="{{ route('delivery.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-sign-out-alt me-1"></i> Yes, Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const toggleBtn = document.getElementById('sidebarToggle');
            
            if (window.innerWidth < 768 && 
                sidebar &&
                toggleBtn &&
                !sidebar.contains(event.target) && 
                !toggleBtn.contains(event.target) && 
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                setTimeout(() => bsAlert.close(), 5000);
            });
        }, 5000);
        
        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            });
            const dateString = now.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            
            const timeElement = document.getElementById('currentTime');
            if (timeElement) {
                timeElement.textContent = `${dateString} • ${timeString}`;
            }
        }
        
        // Update time every minute
        setInterval(updateTime, 60000);
        updateTime(); // Initial call
        
        // Debug: Log CSRF token on page load
        console.log('=== DELIVERY LAYOUT LOADED ===');
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            console.log('✓ CSRF Token found:', csrfToken.content.substring(0, 20) + '...');
        } else {
            console.error('✗ CSRF Token NOT FOUND!');
        }
    </script>
    
    @stack('scripts')
</body>
</html>