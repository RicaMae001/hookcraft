<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - HookcraftAvenue</title>
    
    @stack('head-scripts')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .notification-bell {
            position: relative;
            cursor: pointer;
        }
        
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
        }
        
        .notification-dropdown {
            position: absolute;
            right: 0;
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
            cursor: pointer;
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
        
        .notification-icon.delivery {
            background: #e3f2fd;
            color: #2196f3;
        }

        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 48px 0 0;
            box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
            background: #f8f9fa;
        }

        .sidebar .nav-link {
            font-weight: 500;
            color: #333;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: #FFB6C1;
        }

        .sidebar .nav-link:hover {
            color: #FFB6C1;
            background-color: rgba(255, 182, 193, 0.1);
        }

        .sidebar-heading {
            font-size: .75rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        /* Restricted Section Styles */
        .restricted-section {
            background: rgba(220, 53, 69, 0.05);
            border-left: 3px solid #dc3545;
            padding: 0.25rem 0;
            margin: 0 0.5rem;
            border-radius: 4px;
        }

        .restricted-link {
            color: #dc3545 !important;
            cursor: not-allowed !important;
            opacity: 0.8;
            position: relative;
        }

        .restricted-link:hover {
            background-color: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
        }

        .restricted-link .fa-lock {
            font-size: 0.875rem;
            margin-left: auto;
        }

        .sidebar-heading.text-danger {
            font-weight: 700;
            animation: pulse-red 2s infinite;
        }

        @keyframes pulse-red {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .badge.bg-danger {
            font-size: 0.65rem;
            padding: 0.25rem 0.5rem;
            font-weight: 600;
        }

        main {
            margin-left: 16.6667%; /* Same as col-md-2 */
        }

        @media (max-width: 768px) {
            main {
                margin-left: 0;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @include('admin.layouts.navbar')

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            @include('admin.layouts.sidebar')

            <!-- Main Content -->
            <main class="col-md-10 ms-sm-auto px-md-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Access Denied Modal -->
    <div class="modal fade" id="accessDeniedModal" tabindex="-1" aria-labelledby="accessDeniedModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title" id="accessDeniedModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Access Denied
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-5">
                    <div class="access-denied-icon mb-4">
                        <i class="fas fa-lock fa-4x text-danger"></i>
                    </div>
                    <h5 class="mb-3 fw-bold">You don't have permission to access <span id="sectionName" class="text-danger"></span></h5>
                    <p class="text-muted mb-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Only <strong>Admin</strong> can access this section.
                    </p>
                    <p class="text-muted mb-0">
                        Please contact your administrator if you need access.
                    </p>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Close
                    </button>
                </div>
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

        // Mark notification as read
        function markAsRead(notificationId, orderId) {
            fetch('/admin/notifications/' + notificationId + '/read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(response => {
                if (response.ok) {
                    window.location.href = '/admin/orders';
                }
            });
        }

        // Show access denied modal
        function showAccessDeniedModal(event, sectionName) {
            event.preventDefault();
            document.getElementById('sectionName').textContent = sectionName;
            
            var modal = new bootstrap.Modal(document.getElementById('accessDeniedModal'));
            modal.show();
        }

        // Show modal if error_modal exists in session
        @if(session('error_modal'))
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('accessDeniedModal'));
            document.getElementById('sectionName').textContent = '{{ session('error_modal.title') ?? 'this section' }}';
            modal.show();
        });
        @endif

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('accessDeniedModal');
            if (event.target == modal) {
                bootstrap.Modal.getInstance(modal).hide();
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>