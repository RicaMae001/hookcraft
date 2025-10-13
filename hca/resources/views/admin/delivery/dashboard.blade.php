<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        @media (max-width: 768px) {
            main {
                margin-left: 0;
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
        <div class="navbar-nav">
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

                <!-- Recent Deliveries -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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