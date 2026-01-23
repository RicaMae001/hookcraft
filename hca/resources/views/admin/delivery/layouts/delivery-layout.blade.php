<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- CRITICAL: CSRF Token Meta Tag - Required for AJAX requests -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Delivery Coordinator - @yield('title', 'Dashboard')</title>
    
    <!-- CRITICAL: Load theme BEFORE any styles to prevent flicker -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('delivery-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <style>
        /* EXACT DESIGN FROM YOUR DASHBOARD - NO CHANGES TO YOUR HTML */
        /* Light Mode Colors */
        :root {
            --primary-blue: #667eea;
            --primary-purple: #764ba2;
            --primary-dark: #2D3748;
            --success: #48BB78;
            --warning: #F6AD55;
            --danger: #FC8181;
            --info: #63B3ED;
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
            --primary-blue: #667eea;
            --primary-purple: #764ba2;
            --primary-dark: #1A202C;
            --success: #48BB78;
            --warning: #F6AD55;
            --danger: #FC8181;
            --info: #63B3ED;
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
            margin-top: 76px;
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Modern Navbar - EXACT from your dashboard */
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
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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

        /* Theme Toggle Switch - EXACT from your dashboard */
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
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
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

        .status-badge {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: rgba(72, 187, 120, 0.15);
            color: var(--success);
            font-weight: 600;
            font-size: 0.875rem;
        }

        .pulse {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--success);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(72, 187, 120, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(72, 187, 120, 0);
            }
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            background: var(--light-bg);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .user-profile:hover {
            background: var(--hover-bg);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 14px;
        }

        /* Modern Sidebar - EXACT from your dashboard */
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
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
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
            color: var(--primary-blue);
            transform: translateX(4px);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
            color: var(--primary-blue);
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
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            border-radius: 0 4px 4px 0;
        }

        /* Main Content - EXACT from your dashboard */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 6rem 2rem 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Page Header - EXACT from your dashboard */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .date-badge {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Stats Cards - EXACT from your dashboard */
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
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(102, 126, 234, 0.05));
            color: var(--primary-blue);
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
            background: linear-gradient(135deg, rgba(99, 179, 237, 0.15), rgba(99, 179, 237, 0.05));
            color: var(--info);
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

        /* Content Cards - EXACT from your dashboard */
        .content-card {
            background: var(--card-bg);
            border-radius: 16px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 2rem;
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
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .content-card-body {
            padding: 2rem;
        }

        /* Badges - EXACT from your dashboard */
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
            background: rgba(99, 179, 237, 0.15);
            color: var(--info);
        }

        /* Buttons - EXACT from your dashboard */
        .btn-modern {
            padding: 0.625rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            color: white;
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
            color: white;
        }

        .btn-modern-success {
            background: var(--success);
            color: white;
        }

        .btn-modern-success:hover {
            background: #38a169;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(72, 187, 120, 0.3);
            color: white;
        }

        .btn-modern-info {
            background: var(--info);
            color: white;
        }

        .btn-modern-info:hover {
            background: #4299e1;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(99, 179, 237, 0.3);
            color: white;
        }

        .btn-modern-danger {
            background: var(--danger);
            color: white;
        }

        .btn-modern-danger:hover {
            background: #f56565;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(252, 129, 129, 0.3);
            color: white;
        }

        .btn-modern-secondary {
            background: var(--light-bg);
            color: var(--text-primary);
        }

        .btn-modern-secondary:hover {
            background: var(--hover-bg);
        }

        /* Alerts - EXACT from your dashboard */
        .alert-modern {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.15);
            color: var(--success);
        }

        .alert-danger {
            background: rgba(252, 129, 129, 0.15);
            color: var(--danger);
        }

        /* Modal Dark Mode - EXACT from your dashboard */
        .modal-content {
            background: var(--card-bg);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 16px;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color);
        }

        .modal-footer {
            border-top: 1px solid var(--border-color);
        }

        /* Scrollbar - EXACT from your dashboard */
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

        /* Animations - EXACT from your dashboard */
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

        /* Responsive - EXACT from your dashboard */
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

            .page-title {
                font-size: 1.5rem;
            }

            .stat-card-value {
                font-size: 1.5rem;
            }

            .main-content {
                padding: 6rem 1rem 1rem;
            }
        }

        /* YOUR ORIGINAL LAYOUT STYLES - KEPT INTACT */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--card-bg);
            z-index: 1000;
            padding: 0;
            border-right: 1px solid var(--border-color);
            transition: all 0.3s;
        }
        
        .sidebar .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            text-align: center;
        }
        
        .sidebar .user-info {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .user-info .avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: white;
            font-size: 24px;
        }
        
        .user-info h6 {
            color: var(--text-primary);
            margin-bottom: 5px;
            text-align: center;
        }
        
        .user-info small {
            color: var(--text-secondary);
            text-align: center;
            display: block;
        }
        
        .sidebar .nav {
            padding: 20px 0;
        }
        
        .sidebar .nav-link {
            color: var(--text-primary);
            padding: 12px 20px;
            margin: 2px 10px;
            border-radius: 8px;
            transition: all 0.3s;
            font-size: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link i {
            width: 25px;
            margin-right: 10px;
            font-size: 16px;
        }
        
        .sidebar .nav-link:hover {
            background: var(--hover-bg);
            color: var(--primary-blue);
        }
        
        .sidebar .nav-link.active {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
            color: var(--primary-blue);
        }
        
        .top-header {
            background: var(--card-bg);
            padding: 15px 0;
            margin: -20px -20px 20px -20px;
            border-bottom: 1px solid var(--border-color);
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
            color: var(--text-primary);
            margin: 0;
        }
        
        .page-title small {
            color: var(--text-secondary);
            font-size: 14px;
        }
        
        .header-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        /* Logout Modal - Updated to match dashboard */
        .logout-modal .modal-content {
            border-radius: 16px;
            border: 1px solid var(--border-color);
        }
        
        .logout-modal .modal-header {
            border-bottom: 1px solid var(--border-color);
        }
        
        .logout-modal .modal-footer {
            border-top: 1px solid var(--border-color);
        }
    </style>
</head>
<body>
    <!-- YOUR EXACT HTML STRUCTURE - NO CHANGES -->
    <!-- Sidebar -->
    <div class="modern-sidebar" id="sidebar">
        <div class="sidebar-logo">
            <h2><i class="fas fa-truck-fast"></i> HCA Delivery</h2>
            <p>Coordinator Dashboard</p>
        </div>

        <!-- User Profile in Sidebar -->
        <div class="sidebar-section">
            <div class="text-center mb-3" style="padding: 0 1rem;">
                <div class="user-avatar" style="width: 60px; height: 60px; margin: 0 auto 1rem; font-size: 1.5rem;">
                    {{ strtoupper(substr(session('coordinator_name', 'D'), 0, 1)) }}
                </div>
                <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.25rem;">
                    {{ session('coordinator_name') ?? 'Delivery Coordinator' }}
                </h6>
                <small style="color: var(--text-secondary);">Delivery Coordinator</small>
            </div>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Main Menu</div>
            
            <a href="{{ route('delivery.dashboard') }}" class="sidebar-link {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('delivery.deliveries') }}" class="sidebar-link {{ request()->routeIs('delivery.deliveries') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>My Deliveries</span>
            </a>

            <a href="{{ route('delivery.livechat.index') }}" class="sidebar-link {{ request()->routeIs('delivery.livechat.*') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>
                <span>Live Chat Support</span>
                <span id="chatNotification" class="badge bg-danger float-end d-none">!</span>
            </a>

            <a href="{{ route('delivery.history') }}" class="sidebar-link {{ request()->routeIs('delivery.history') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Delivery History</span>
            </a>
        </div>

        <div class="sidebar-section">
            <div class="sidebar-section-title">Account</div>

            <a href="#" class="sidebar-link text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Modern Navbar -->
        <nav class="modern-navbar">
            <div class="navbar-start">
                <button class="btn btn-link d-md-none" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div class="navbar-actions">
                <!-- Theme Toggle -->
                <div class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark/Light Mode">
                    <div class="theme-toggle-slider">
                        <i class="fas fa-sun theme-toggle-icon sun"></i>
                        <i class="fas fa-moon theme-toggle-icon moon"></i>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="status-badge">
                    <span class="pulse"></span>
                    Online
                </div>

                <!-- User Profile -->
                <div class="user-profile dropdown">
                    <div data-bs-toggle="dropdown">
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar">D</div>
                            <div class="d-none d-md-block">
                                <div style="font-size: 0.875rem; font-weight: 600;">Delivery Coordinator</div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary);">Delivery Staff</div>
                            </div>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-tachometer-alt me-2" style="color: var(--primary-blue);"></i>
                    @yield('title', 'Dashboard')
                </h1>
                <p class="page-subtitle">Delivery Coordinator Panel</p>
            </div>
            <div class="date-badge">
                <i class="fas fa-calendar"></i>
                <span id="currentDate">{{ date('F d, Y') }}</span>
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
                    <i class="fas fa-question-circle fa-4x" style="color: var(--warning);"></i>
                    <h5 class="mb-3">Are you sure you want to logout?</h5>
                    <p class="text-muted">You will be redirected to the login page.</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn-modern btn-modern-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <form action="{{ route('delivery.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-modern btn-modern-danger">
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
        // Theme Toggle Function
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('delivery-theme', newTheme);
        }

        // Sidebar Toggle for Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Update Current Date
        function updateCurrentDate() {
            const options = { month: 'short', day: 'numeric', year: 'numeric' };
            const dateString = new Date().toLocaleDateString('en-US', options);
            document.getElementById('currentDate').textContent = dateString;
        }
        updateCurrentDate();

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                setTimeout(() => bsAlert.close(), 5000);
            });
        }, 5000);

        // YOUR ORIGINAL FUNCTIONS
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