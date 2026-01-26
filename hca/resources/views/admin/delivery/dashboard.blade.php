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
            margin-top: 20px;
            line-height: 1.6;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Modern Navbar */
    

    
      
        /* Modern Sidebar */
    

      

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
            margin-left: 20px;
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
   @include('admin.delivery.layouts.navbar')

    <!-- Modern Sidebar -->
  

   @include('admin.delivery.layouts.sidebar')

     
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
                <span id="currentDate"></span>
            </div>
        </div>

        <!-- Alerts -->
        <div class="alert-modern alert-success animate-fade-in">
            <i class="fas fa-check-circle fa-2x"></i>
            <div>
                <strong>System Status:</strong> All delivery systems are operational
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="stat-card animate-fade-in">
                    <div class="stat-card-icon primary">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-card-value">45</div>
                    <div class="stat-card-label">Total Deliveries</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="stat-card-icon warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-card-value">12</div>
                    <div class="stat-card-label">Pending</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
                    <div class="stat-card-icon info">
                        <i class="fas fa-shipping-fast"></i>
                    </div>
                    <div class="stat-card-value">8</div>
                    <div class="stat-card-label">Out for Delivery</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="stat-card animate-fade-in" style="animation-delay: 0.3s;">
                    <div class="stat-card-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-card-value">25</div>
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
        <div class="content-card animate-fade-in">
            <div class="content-card-header">
                <h5 class="content-card-title">
                    <i class="fas fa-bolt"></i>
                    Quick Status Updates
                </h5>
            </div>
            <div class="content-card-body">
                <div class="row g-4">
                    <!-- Sample Order Card 1 -->
                    <div class="col-md-6">
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">#1234</span>
                                <span class="badge-modern badge-warning">Pending</span>
                            </div>
                            <h6 class="customer-name">John Doe</h6>
                            <div class="order-address">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <span>123 Main Street, Cebu City</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <button class="btn-modern btn-modern-info" onclick="updateStatus(1234, 'Out for Delivery')">
                                    <i class="fas fa-shipping-fast"></i>
                                    Out for Delivery
                                </button>
                                <button class="btn-modern btn-modern-danger" onclick="updateStatus(1234, 'Cancelled')">
                                    <i class="fas fa-times-circle"></i>
                                    Cancel Order
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Sample Order Card 2 -->
                    <div class="col-md-6">
                        <div class="order-card">
                            <div class="order-header">
                                <span class="order-id">#1235</span>
                                <span class="badge-modern badge-info">Out for Delivery</span>
                            </div>
                            <h6 class="customer-name">Jane Smith</h6>
                            <div class="order-address">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <span>456 Oak Avenue, Mandaue City</span>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <button class="btn-modern btn-modern-success" onclick="updateStatus(1235, 'Delivered')">
                                    <i class="fas fa-check-circle"></i>
                                    Mark as Delivered
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                            <tr>
                                <td><span class="order-id">#1234</span></td>
                                <td><strong>John Doe</strong></td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    123 Main Street, Cebu City
                                </td>
                                <td><span class="badge-modern badge-warning">Pending</span></td>
                                <td>Jan 22, 2026</td>
                                <td>
                                    <button class="btn btn-sm btn-modern-primary">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="order-id">#1235</span></td>
                                <td><strong>Jane Smith</strong></td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    456 Oak Avenue, Mandaue City
                                </td>
                                <td><span class="badge-modern badge-info">Out for Delivery</span></td>
                                <td>Jan 22, 2026</td>
                                <td>
                                    <button class="btn btn-sm btn-modern-primary">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="order-id">#1236</span></td>
                                <td><strong>Bob Johnson</strong></td>
                                <td>
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    789 Pine Road, Lapu-Lapu City
                                </td>
                                <td><span class="badge-modern badge-success">Delivered</span></td>
                                <td>Jan 21, 2026</td>
                                <td>
                                    <button class="btn btn-sm btn-modern-primary">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

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
            updateChartColors(newTheme);
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

        // Update Status Function
        function updateStatus(orderId, status) {
            alert(`Updating Order #${orderId} to: ${status}`);
            // Add your AJAX call here
        }

        // Delivery Chart
        const ctx = document.getElementById('deliveryChart').getContext('2d');
        let deliveryChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan 16', 'Jan 17', 'Jan 18', 'Jan 19', 'Jan 20', 'Jan 21', 'Jan 22'],
                datasets: [{
                    label: 'Deliveries',
                    data: [3, 5, 7, 4, 8, 6, 9],
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
                            stepSize: 2,
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
    </script>
</body>
</html>