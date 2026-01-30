<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delivery Dashboard</title>
    
    <!-- CRITICAL: Load theme BEFORE any styles to prevent flicker -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('delivery-theme') || 'light';
            document.documentElement.setAttribute('data-theme', savedTheme);
        })();
    </script>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
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

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 6rem 2rem 2rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        /* Page Header */
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

        /* Stats Cards */
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

        /* Content Cards */
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

        /* Order Cards */
        .order-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.5rem;
            transition: all 0.2s ease;
            border-left: 4px solid var(--primary-blue);
        }

        .order-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .order-id {
            background: var(--text-secondary);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .customer-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .order-address {
            display: flex;
            align-items: start;
            gap: 0.5rem;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1rem;
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
            background: rgba(99, 179, 237, 0.15);
            color: var(--info);
        }

        /* Buttons */
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

        /* Alerts */
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

        /* Modal Dark Mode */
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

            .page-title {
                font-size: 1.5rem;
            }

            .stat-card-value {
                font-size: 1.5rem;
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

        /* Chart Container */
        .chart-container {
            position: relative;
            height: 300px;
            padding: 1rem 0;
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
</head>
<body>
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
                        <div class="user-avatar">{{ strtoupper(substr(session('coordinator_name', 'D'), 0, 1)) }}</div>
                        <div class="d-none d-md-block">
                            <div style="font-size: 0.875rem; font-weight: 600;">{{ session('coordinator_name') ?? 'Delivery Coordinator' }}</div>
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

    <!-- Modern Sidebar -->
    <aside class="modern-sidebar" id="sidebar">
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
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-chart-line" style="color: var(--primary-blue);"></i>
                    Delivery Dashboard
                </h1>
                <p class="page-subtitle">Welcome back! Here's what's happening today.</p>
            </div>
            <div class="date-badge">
                <i class="fas fa-calendar"></i>
                {{ date('F d, Y') }}
            </div>
        </div>

        <!-- Session Alerts -->
        @if(session('success'))
            <div class="alert-modern alert-success animate-fade-in">
                <i class="fas fa-check-circle fa-2x"></i>
                <div>
                    <strong>Success:</strong> {{ session('success') }}
                </div>
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert-modern alert-danger animate-fade-in">
                <i class="fas fa-exclamation-circle fa-2x"></i>
                <div>
                    <strong>Error:</strong> {{ session('error') }}
                </div>
                <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="stat-card animate-fade-in">
                    <div class="stat-card-icon primary">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-card-value">{{ $totalDeliveries }}</div>
                    <div class="stat-card-label">Total Deliveries</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="stat-card-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-card-value">{{ $pendingDeliveries }}</div>
                    <div class="stat-card-label">Pending</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="stat-card-icon info">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="stat-card-value">{{ $outForDelivery }}</div>
                    <div class="stat-card-label">Out for Delivery</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card animate-fade-in" style="animation-delay: 0.3s;">
                    <div class="stat-card-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-card-value">{{ $completedDeliveries }}</div>
                    <div class="stat-card-label">Completed</div>
                </div>
            </div>
        </div>

        <!-- Chart -->
        <div class="content-card animate-fade-in">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="fas fa-chart-line"></i>
                    Deliveries (Last 7 Days)
                </h5>
            </div>
            <div class="content-card-body">
                <div class="chart-container">
                    <canvas id="deliveryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Quick Status Updates -->
        @if($deliveries->where('delivery_status', '!=', 'Delivered')->where('delivery_status', '!=', 'Cancelled')->count() > 0)
        <div class="content-card animate-fade-in">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="fas fa-bolt"></i>
                    Quick Status Updates
                </h5>
            </div>
            <div class="content-card-body">
                <div class="row g-4">
                    @foreach($deliveries->where('delivery_status', '!=', 'Delivered')->where('delivery_status', '!=', 'Cancelled') as $delivery)
                        <div class="col-md-6">
                            <div class="order-card">
                                <div class="order-header">
                                    <span class="order-id">#{{ $delivery->id }}</span>
                                    @if($delivery->delivery_status == 'Out for Delivery')
                                        <span class="badge-modern badge-info">Out for Delivery</span>
                                    @else
                                        <span class="badge-modern badge-warning">Pending</span>
                                    @endif
                                </div>
                                <h6 class="customer-name">{{ $delivery->customer_name }}</h6>
                                <div class="order-address">
                                    <i class="fas fa-map-marker-alt text-danger"></i>
                                    <span>{{ Str::limit($delivery->address, 50) }}</span>
                                </div>
                                <div class="d-flex flex-column gap-2">
                                    @if($delivery->delivery_status === 'Pending')
                                        <button class="btn-modern btn-modern-info" 
                                            onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Out for Delivery')">
                                            <i class="fas fa-shipping-fast"></i>
                                            Out for Delivery
                                        </button>
                                        <button class="btn-modern btn-modern-danger"
                                            onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Cancelled')">
                                            <i class="fas fa-times-circle"></i>
                                            Cancel Order
                                        </button>
                                    @endif

                                    @if($delivery->delivery_status === 'Out for Delivery')
                                        <button class="btn-modern btn-modern-success"
                                            onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Delivered')">
                                            <i class="fas fa-check-circle"></i>
                                            Mark as Delivered
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Deliveries Table -->
        <div class="content-card animate-fade-in">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="fas fa-list"></i>
                    Recent Deliveries
                </h5>
            </div>
            <div class="content-card-body p-0">
                <div class="table-responsive">
                    <table class="modern-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Address</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentDeliveries as $delivery)
                                <tr>
                                    <td><span class="order-id">#{{ $delivery->id }}</span></td>
                                    <td><strong>{{ $delivery->customer_name }}</strong></td>
                                    <td>
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        {{ Str::limit($delivery->address, 40) }}
                                    </td>
                                    <td>
                                        @if($delivery->delivery_status == 'Delivered')
                                            <span class="badge-modern badge-success">Delivered</span>
                                        @elseif($delivery->delivery_status == 'Out for Delivery')
                                            <span class="badge-modern badge-info">Out for Delivery</span>
                                        @elseif($delivery->delivery_status == 'Cancelled')
                                            <span class="badge-modern badge-danger">Cancelled</span>
                                        @else
                                            <span class="badge-modern badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('delivery.deliveries') }}" class="btn btn-sm btn-modern-primary">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <p>No deliveries assigned yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-circle me-2"></i>Confirm Status Update</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="confirmStatusForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <p class="fw-bold mb-1">Order ID:</p>
                        <p id="confirmOrderId"></p>

                        <p class="fw-bold mb-1">Customer:</p>
                        <p id="confirmCustomerName"></p>

                        <p class="fw-bold mb-1">Change status to:</p>
                        <p class="text-primary fw-bold" id="confirmNewStatus"></p>

                        <input type="hidden" name="delivery_status" id="hiddenStatusValue">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-modern btn-modern-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-modern btn-modern-primary">Update Status</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-sign-out-alt me-2 text-danger"></i>
                        Confirm Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <i class="fas fa-question-circle fa-4x mb-4" style="color: var(--warning);"></i>
                    <h5 class="mb-3">Are you sure you want to logout?</h5>
                    <p class="text-muted">You will be redirected to the login page.</p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn-modern btn-modern-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Cancel
                    </button>
                    <button type="button" class="btn-modern btn-modern-danger">
                        <i class="fas fa-sign-out-alt me-1"></i> Yes, Logout
                    </button>
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
            localStorage.setItem('delivery-theme', newTheme);
            
            // Update chart colors when theme changes
            if (deliveryChart) {
                updateChartColors(newTheme);
            }
        }

        // Sidebar Toggle for Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });

        // Status Update Confirmation
        function confirmStatus(orderId, customerName, newStatus) {
            document.getElementById('confirmOrderId').innerText = '#' + orderId;
            document.getElementById('confirmCustomerName').innerText = customerName;
            document.getElementById('confirmNewStatus').innerText = newStatus;
            document.getElementById('hiddenStatusValue').value = newStatus;

            document.getElementById('confirmStatusForm').action =
                '/delivery/deliveries/' + orderId + '/status';

            new bootstrap.Modal(document.getElementById('confirmModal')).show();
        }
        
        // Delivery Chart
        const ctx = document.getElementById('deliveryChart').getContext('2d');
        let deliveryChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyDeliveries->pluck('date')) !!},
                datasets: [{
                    label: 'Deliveries',
                    data: {!! json_encode($dailyDeliveries->pluck('count')) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        borderRadius: 8,
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: getComputedStyle(document.documentElement)
                                .getPropertyValue('--text-secondary').trim()
                        },
                        grid: {
                            color: getComputedStyle(document.documentElement)
                                .getPropertyValue('--border-color').trim()
                        }
                    },
                    x: {
                        ticks: {
                            color: getComputedStyle(document.documentElement)
                                .getPropertyValue('--text-secondary').trim()
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Update chart colors on theme change
        function updateChartColors(theme) {
            const textColor = getComputedStyle(document.documentElement)
                .getPropertyValue('--text-secondary').trim();
            const gridColor = getComputedStyle(document.documentElement)
                .getPropertyValue('--border-color').trim();

            deliveryChart.options.scales.y.ticks.color = textColor;
            deliveryChart.options.scales.y.grid.color = gridColor;
            deliveryChart.options.scales.x.ticks.color = textColor;
            deliveryChart.update();
        }

        // Auto-close alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert-modern');
                alerts.forEach(alert => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>