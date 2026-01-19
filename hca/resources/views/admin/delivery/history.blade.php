<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        main {
            margin-left: 250px;
        }
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
            background: #e0e0e0;
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
        .status-badge {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
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
    @include('admin.delivery.layouts.navbar')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
          @include('admin.delivery.layouts.sidebar')

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                    <h1 class="h2"><i class="fas fa-history me-2 text-primary"></i>Delivery History</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="badge bg-primary fs-6 px-3 py-2">
                            <i class="fas fa-calendar me-2"></i>{{ date('F d, Y') }}
                        </span>
                    </div>
                </div>

                <!-- Stats Summary -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="fas fa-clipboard-list fa-2x text-primary mb-2"></i>
                                <h4 class="mb-0">{{ $logs->count() }}</h4>
                                <p class="text-muted mb-0">Total Status Updates</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-day fa-2x text-success mb-2"></i>
                                <h4 class="mb-0">{{ $logs->where('updated_at', '>=', now()->startOfDay())->count() }}</h4>
                                <p class="text-muted mb-0">Updates Today</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-week fa-2x text-info mb-2"></i>
                                <h4 class="mb-0">{{ $logs->where('updated_at', '>=', now()->subDays(7))->count() }}</h4>
                                <p class="text-muted mb-0">Updates This Week</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- History Timeline -->
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-list-alt me-2"></i>Status Change Timeline</h5>
                    </div>
                    <div class="card-body">
                        @forelse($logs as $log)
                            <div class="timeline-item">
                                <div class="timeline-icon 
                                    @if($log->new_status == 'Delivered') bg-success
                                    @elseif($log->new_status == 'Out for Delivery') bg-info
                                    @elseif($log->new_status == 'Cancelled') bg-danger
                                    @else bg-warning
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

                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h6 class="mb-2">
                                                    <span class="badge bg-secondary me-2">#{{ $log->order_id }}</span>
                                                    <strong>{{ $log->customer_name }}</strong>
                                                </h6>
                                                <p class="mb-2 text-muted">
                                                    <i class="fas fa-map-marker-alt me-2"></i>
                                                    {{ Str::limit($log->address, 60) }}
                                                </p>
                                                <div class="status-change">
                                                    <span class="badge 
                                                        @if($log->old_status == 'Delivered') bg-success
                                                        @elseif($log->old_status == 'Out for Delivery') bg-info
                                                        @elseif($log->old_status == 'Cancelled') bg-danger
                                                        @else bg-warning text-dark
                                                        @endif status-badge">
                                                        {{ $log->old_status ?? 'N/A' }}
                                                    </span>
                                                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                                                    <span class="badge 
                                                        @if($log->new_status == 'Delivered') bg-success
                                                        @elseif($log->new_status == 'Out for Delivery') bg-info
                                                        @elseif($log->new_status == 'Cancelled') bg-danger
                                                        @else bg-warning text-dark
                                                        @endif status-badge">
                                                        {{ $log->new_status }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <p class="mb-1">
                                                    <i class="fas fa-calendar text-primary me-2"></i>
                                                    <small>{{ \Carbon\Carbon::parse($log->updated_at)->format('M d, Y') }}</small>
                                                </p>
                                                <p class="mb-0">
                                                    <i class="fas fa-clock text-info me-2"></i>
                                                    <small>{{ \Carbon\Carbon::parse($log->updated_at)->format('h:i A') }}</small>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-history fa-4x text-muted mb-3"></i>
                                <p class="text-muted">No delivery history available</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Export Options -->
                @if($logs->count() > 0)
                    <div class="text-end mt-4">
                        <button class="btn btn-outline-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i>Print History
                        </button>
                    </div>
                @endif
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Notification Toggle
        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.classList.toggle('show');
        }

        </script>
</body>

</html>