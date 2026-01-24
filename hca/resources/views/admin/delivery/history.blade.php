<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery History</title>
    
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
            margin-top: 20px;
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
            padding: 0.30rem;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease;
            height: 105%;
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
            margin-left: 20px;  
            margin-top: 10px;
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
            margin-left: 25px;
        }

        .stat-card-label {
            color: var(--text-secondary);
            font-size: 0.875rem;
            font-weight: 500;
            margin-left: 20px;
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
            background: rgba(211, 214, 216, 0.15);
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

        /* YOUR ORIGINAL DELIVERY HISTORY STYLES - KEPT INTACT */
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 50px;
            padding-bottom: 30px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: -30px;
            width: 2px;
            background: var(--border-color);
        }
        
        .timeline-item:last-child::before {
            display: none;
        }
        
        .timeline-icon {
            position: absolute;
            left: 0;
            top: 0;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }
        
        .status-badge-old {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        /* Enhanced timeline styling */
        .timeline-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.2s ease;
        }

        .timeline-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .status-change {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.75rem;
        }

        /* Empty state */
        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-secondary);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .address-text {
    color: #bfcfdd; /* clean medium gray for light mode */
}

.dark-mode .address-text {
    color: #d5e0ec; /* soft cool gray for dark mode */
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
                    <i class="fas fa-history me-2" style="color: var(--primary-blue);"></i>
                    Delivery History
                </h1>
                <p class="page-subtitle">Track all delivery status updates and changes</p>
            </div>
            <div class="date-badge">
                <i class="fas fa-calendar"></i>
                <span id="currentDate">{{ date('F d, Y') }}</span>
            </div>
        </div>

        <!-- Stats Summary - Updated with modern styling -->
        <div class="row mb-4 g-4">
            <div class="col-md-4">
                <div class="stat-card animate-fade-in">
                    <div class="stat-card-icon primary">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-card-value">{{ $logs->count() }}</div>
                    <div class="stat-card-label">Total Status Updates</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="stat-card-icon success">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-card-value">{{ $logs->where('updated_at', '>=', now()->startOfDay())->count() }}</div>
                    <div class="stat-card-label">Updates Today</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="stat-card-icon info">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stat-card-value">{{ $logs->where('updated_at', '>=', now()->subDays(7))->count() }}</div>
                    <div class="stat-card-label">Updates This Week</div>
                </div>
            </div>
        </div>

        <!-- History Timeline -->
        <div class="content-card animate-fade-in">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="fas fa-list-alt me-2"></i>
                    Status Change Timeline
                </h5>
                <div class="badge-modern badge-primary">
                    <i class="fas fa-sync-alt me-1"></i>
                    {{ $logs->count() }} Updates
                </div>
            </div>
            <div class="content-card-body">
                <div class="timeline">
                    @forelse($logs as $log)
                        <div class="timeline-item">
                            <div class="timeline-icon 
                                @if($log->new_status == 'Delivered') bg-success
                                @elseif($log->new_status == 'Out for Delivery') bg-info
                                @elseif($log->new_status == 'Cancelled') bg-danger
                                @else bg-warning text-dark
                                @endif">
                                @if($log->new_status == 'Delivered')
                                    <i class="fas fa-check"></i>
                                @elseif($log->new_status == 'Out for Delivery')
                                    <i class="fas fa-truck"></i>
                                @elseif($log->new_status == 'Cancelled')
                                    <i class="fas fa-times"></i>
                                @else
                                    <i class="fas fa-clock"></i>
                                @endif
                            </div>

                            <div class="timeline-card">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h6 class="mb-2">
                                            <span class="badge bg-secondary me-2">#{{ $log->order_id }}</span>
                                            <strong>{{ $log->customer_name }}</strong>
                                        </h6>
                                   <p class="mb-2 address-text">
    <i class="fas fa-map-marker-alt me-2"></i>
    {{ Str::limit($log->address, 60) }}
</p>

                                        <div class="status-change">
                                            <span class="badge-modern 
                                                @if($log->old_status == 'Delivered') badge-success
                                                @elseif($log->old_status == 'Out for Delivery') badge-info
                                                @elseif($log->old_status == 'Cancelled') badge-danger
                                                @else badge-warning
                                                @endif">
                                                {{ $log->old_status ?? 'N/A' }}
                                            </span>
                                            <i class="fas fa-arrow-right mx-2" style="color: var(--text-secondary);"></i>
                                            <span class="badge-modern 
                                                @if($log->new_status == 'Delivered') badge-success
                                                @elseif($log->new_status == 'Out for Delivery') badge-info
                                                @elseif($log->new_status == 'Cancelled') badge-danger
                                                @else badge-warning
                                                @endif">
                                                {{ $log->new_status }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <p class="mb-1">
                                            <i class="fas fa-calendar" style="color: var(--primary-blue);"></i>
                                            <small style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($log->updated_at)->format('M d, Y') }}</small>
                                        </p>
                                        <p class="mb-0">
                                            <i class="fas fa-clock" style="color: var(--info);"></i>
                                            <small style="color: var(--text-secondary);">{{ \Carbon\Carbon::parse($log->updated_at)->format('h:i A') }}</small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-state">
                            <i class="fas fa-history"></i>
                            <h5 class="mb-3">No delivery history available</h5>
                            <p class="text-muted">No status updates have been recorded yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Export Options -->
        @if($logs->count() > 0)
            <div class="text-end mt-4">
                <button class="btn-modern btn-modern-secondary" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>Print History
                </button>
            </div>
        @endif

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
                        <button type="button" class="btn-modern btn-modern-danger" onclick="window.location.href='{{ route('delivery.logout') }}'">
                            <i class="fas fa-sign-out-alt me-1"></i> Yes, Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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

        // YOUR ORIGINAL FUNCTIONS
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }
    </script>
</body>
</html>