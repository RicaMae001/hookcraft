<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Delivery Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar-delivery {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background: white;
        }
        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            padding: 0.75rem 1rem;
        }
        .sidebar .nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link:hover {
            background-color: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        main {
            margin-left: 250px;
        }
        .order-card {
            transition: transform 0.2s;
            border-left: 4px solid #667eea;
        }
        .order-card:hover {
            transform: translateY(-5px);
        }
        
        /* Notification Styles */
        .notification-bell {
            position: relative;
            cursor: pointer;
            padding: 8px 15px;
        }
        .notification-badge {
            position: absolute;
            top: 2px;
            right: 8px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
        }
        .notification-dropdown {
            position: absolute;
            right: 80px;
            top: 100%;
            width: 380px;
            max-height: 500px;
            overflow-y: auto;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            border-radius: 8px;
            z-index: 1000;
            display: none;
            margin-top: 10px;
        }
        .notification-dropdown.show {
            display: block;
        }
        .notification-item {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
            color: #333;
        }
        .notification-item:hover {
            background: #f8f9fa;
        }
        .notification-item.unread {
            background: #e3f2fd;
        }
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 2px solid #f0f0f0;
            background: white;
        }
        .notification-time {
            font-size: 11px;
            color: #999;
        }
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }
        .notification-icon.admin {
            background: #fff3cd;
            color: #856404;
        }
        
        @media (max-width: 768px) {
            main {
                margin-left: 0;
            }
            .notification-dropdown {
                right: 10px;
                width: 320px;
            }
        }
    </style>
</head>
<body>
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

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-circle fa-3x text-primary"></i>
                        <p class="mt-2 mb-0"><strong>{{ session('coordinator_name') }}</strong></p>
                        <small class="text-muted">Delivery Coordinator</small>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('delivery.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('delivery.deliveries') }}">
                                <i class="fas fa-box me-2"></i>My Deliveries
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('delivery.history') }}">
                                <i class="fas fa-history me-2"></i>Delivery History
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                    <h1 class="h2"><i class="fas fa-chart-line me-2 text-primary"></i>Delivery Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-primary fs-6 px-3 py-2">
                            <i class="fas fa-calendar me-2"></i>{{ date('F d, Y') }}
                        </span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-boxes fa-3x mb-3 opacity-50"></i>
                                <h3 class="mb-0">{{ $totalDeliveries }}</h3>
                                <p class="mb-0">Total Deliveries</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-clock fa-3x mb-3 opacity-50"></i>
                                <h3 class="mb-0">{{ $pendingDeliveries }}</h3>
                                <p class="mb-0">Pending</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-white bg-info">
                            <div class="card-body text-center">
                                <i class="fas fa-shipping-fast fa-3x mb-3 opacity-50"></i>
                                <h3 class="mb-0">{{ $outForDelivery }}</h3>
                                <p class="mb-0">Out for Delivery</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-white bg-success">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle fa-3x mb-3 opacity-50"></i>
                                <h3 class="mb-0">{{ $completedDeliveries }}</h3>
                                <p class="mb-0">Completed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Deliveries (Last 7 Days)</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="deliveryChart" height="80"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Status Updates Section -->
                @if($deliveries->where('delivery_status', '!=', 'Delivered')->where('delivery_status', '!=', 'Cancelled')->count() > 0)
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Status Updates</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($deliveries->where('delivery_status', '!=', 'Delivered')->where('delivery_status', '!=', 'Cancelled') as $delivery)
                                <div class="col-md-6 mb-3">
                                    <div class="card order-card">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <span class="badge bg-secondary">#{{ $delivery->id }}</span>
                                                {{ $delivery->customer_name }}
                                            </h6>
                                            <p class="card-text small mb-2">
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                {{ Str::limit($delivery->address, 50) }}
                                            </p>
                                            <p class="mb-2">
                                                <strong>Status:</strong> 
                                                @if($delivery->delivery_status == 'Out for Delivery')
                                                    <span class="badge bg-info">Out for Delivery</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </p>

                                            @php
                                                $status = $delivery->delivery_status;
                                            @endphp

                                            <div class="d-flex flex-column gap-2">
                                                @if($status === 'Pending')
                                                    <button class="btn btn-info btn-sm text-white fw-bold"
                                                        onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Out for Delivery')">
                                                        <i class="fas fa-shipping-fast me-1"></i> Out for Delivery
                                                    </button>

                                                    <button class="btn btn-danger btn-sm fw-bold"
                                                        onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Cancelled')">
                                                        <i class="fas fa-times-circle me-1"></i> Cancel Order
                                                    </button>
                                                @endif

                                                @if($status === 'Out for Delivery')
                                                    <button class="btn btn-success btn-sm fw-bold"
                                                        onclick="confirmStatus('{{ $delivery->id }}', '{{ $delivery->customer_name }}', 'Delivered')">
                                                        <i class="fas fa-check-circle me-1"></i> Mark as Delivered
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Recent Deliveries Table -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Recent Deliveries</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
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
                                            <td><span class="badge bg-secondary">#{{ $delivery->id }}</span></td>
                                            <td><strong>{{ $delivery->customer_name }}</strong></td>
                                            <td><i class="fas fa-map-marker-alt text-danger me-2"></i>{{ Str::limit($delivery->address, 40) }}</td>
                                            <td>
                                                @if($delivery->delivery_status == 'Delivered')
                                                    <span class="badge bg-success">Delivered</span>
                                                @elseif($delivery->delivery_status == 'Out for Delivery')
                                                    <span class="badge bg-info">Out for Delivery</span>
                                                @elseif($delivery->delivery_status == 'Cancelled')
                                                    <span class="badge bg-danger">Cancelled</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($delivery->created_at)->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('delivery.deliveries') }}" class="btn btn-sm btn-primary">
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
        </div>
    </div>

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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-primary">Yes, Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Notification Toggle
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const bell = document.querySelector('.notification-bell');
            const dropdown = document.getElementById('notificationDropdown');
            
            if (bell && dropdown && !bell.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Mark all as read
        function markAllAsRead() {
            document.getElementById('notificationDropdown').classList.remove('show');
            // Add AJAX call here to mark all as read in backend
        }

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
        const deliveryChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dailyDeliveries->pluck('date')) !!},
                datasets: [{
                    label: 'Deliveries',
                    data: {!! json_encode($dailyDeliveries->pluck('count')) !!},
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>