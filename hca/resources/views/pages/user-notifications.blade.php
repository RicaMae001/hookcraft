<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Notifications - HookcraftAvenue</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Stats Cards */
        .stat-card {
            background: white;
            border: 1px solid #FFE4EC;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(255, 105, 180, 0.08);
            transition: all 0.3s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(255, 105, 180, 0.15);
        }

        .stat-card-icon {
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .stat-card-icon.primary {
            background: linear-gradient(135deg, rgba(255, 105, 180, 0.2), rgba(255, 182, 193, 0.2));
            color: #FF69B4;
        }

        .stat-card-icon.warning {
            background: linear-gradient(135deg, rgba(246, 173, 85, 0.2), rgba(242, 153, 74, 0.2));
            color: #F6AD55;
        }

        .stat-card-icon.info {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
            color: #667EEA;
        }

        .stat-card-label {
            font-size: 0.875rem;
            color: #718096;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-card-value {
            font-size: 2rem;
            font-weight: 700;
            color: #2D3748;
        }

        /* Content Card */
        .content-card {
            background: white;
            border: 1px solid #FFE4EC;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 25px rgba(255, 105, 180, 0.1);
        }

        .content-card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #FFE4EC;
            background: linear-gradient(135deg, #FFF5F8 0%, #ffffff 100%);
        }

        .content-card-title {
            margin: 0;
            font-weight: 700;
            color: #FF69B4;
            font-size: 1.25rem;
            font-family: 'Playfair Display', serif;
        }

        /* Notification Card */
        .notification-card {
            background: white;
            border: 1px solid #FFE4EC;
            border-radius: 14px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .notification-card:hover {
            transform: translateX(4px);
            border-color: #FF69B4;
            box-shadow: 0 4px 12px rgba(255, 105, 180, 0.15);
        }

        .notification-card.unread {
            background: rgba(255, 105, 180, 0.05);
            border-left: 4px solid #FF69B4;
        }

        .notification-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .notification-icon.success { background: rgba(72, 187, 120, 0.15); color: #48BB78; }
        .notification-icon.info { background: rgba(99, 179, 237, 0.15); color: #63B3ED; }
        .notification-icon.warning { background: rgba(246, 173, 85, 0.15); color: #F6AD55; }
        .notification-icon.danger { background: rgba(252, 129, 129, 0.15); color: #FC8181; }
        .notification-icon.primary { background: rgba(255, 105, 180, 0.15); color: #FF69B4; }

        .notification-title {
            font-weight: 600;
            color: #2D3748;
            font-size: 1.1rem;
        }

        .notification-message {
            color: #718096;
            line-height: 1.6;
            margin: 0;
        }

        .badge-modern {
            padding: 0.375rem 0.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .badge-primary { background: rgba(255, 105, 180, 0.15); color: #FF69B4; }
        .badge-success { background: rgba(72, 187, 120, 0.15); color: #48BB78; }
        .badge-info { background: rgba(99, 179, 237, 0.15); color: #63B3ED; }
        .badge-warning { background: rgba(246, 173, 85, 0.15); color: #F6AD55; }
        .badge-danger { background: rgba(252, 129, 129, 0.15); color: #FC8181; }

        /* Buttons */
        .btn-pink-gradient {
            background: linear-gradient(135deg, #FF69B4, #FFB6C1);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-pink-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(255, 105, 180, 0.3);
            color: white;
        }

        /* Page header */
        .page-header {
            background: linear-gradient(135deg, #FF69B4 0%, #FFB6C1 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(255, 105, 180, 0.2);
        }

        .page-header h1 {
            color: white;
            font-family: 'Playfair Display', serif;
        }

        /* Loading */
        .spinner-border {
            color: #FF69B4;
        }
    </style>
</head>
<body>
    <!-- Include Navbar -->
    @include('components.navbar')

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-2">
                        <i class="bi bi-bell-fill me-3"></i>My Notifications
                    </h1>
                    <p class="mb-0 opacity-90">Stay updated on your orders and important announcements</p>
                </div>
                
                <button class="btn btn-light" onclick="markAllAsRead()" id="markAllBtn" style="display: none;">
                    <i class="bi bi-check2-all me-2"></i>Mark All as Read
                </button>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Stats Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-card-icon primary">
                        <i class="bi bi-bell"></i>
                    </div>
                    <div class="stat-card-label">Total Notifications</div>
                    <div class="stat-card-value" id="totalCount">0</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-card-icon warning">
                        <i class="bi bi-envelope"></i>
                    </div>
                    <div class="stat-card-label">Unread</div>
                    <div class="stat-card-value" id="unreadCount">0</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-card-icon info">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="stat-card-label">Order Updates</div>
                    <div class="stat-card-value" id="orderCount">0</div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="content-card">
            <div class="content-card-header">
                <h3 class="content-card-title">All Notifications</h3>
            </div>
            
            <div class="p-4" id="notificationsContainer">
                <!-- Loading -->
                <div class="text-center py-5" id="loadingState">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3 text-muted">Loading notifications...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Prevent navbar from fetching while on this page
    window.userNotificationsPageActive = true;

    // Load notifications on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
    });

    // LOAD NOTIFICATIONS FROM API
    async function loadNotifications() {
        try {
            const response = await fetch('/api/notifications?limit=100', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });

            if (!response.ok) {
                throw new Error('Failed to fetch notifications');
            }

            const data = await response.json();

            // Hide loading
            document.getElementById('loadingState').style.display = 'none';

            if (!data.success || !data.notifications) {
                showEmpty();
                return;
            }

            const notifications = data.notifications;
            const unreadCount = data.unread_count || 0;

            // Update stats
            updateStats(notifications, unreadCount);
            
            // Sync navbar badge
            syncNavbarBadge(unreadCount);

            // Display notifications
            if (notifications.length === 0) {
                showEmpty();
            } else {
                displayNotifications(notifications);
            }

        } catch (error) {
            document.getElementById('loadingState').innerHTML = `
                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                <p class="mt-3 text-danger">Failed to load notifications</p>
                <button class="btn btn-pink-gradient" onclick="loadNotifications()">
                    <i class="bi bi-arrow-clockwise me-2"></i>Retry
                </button>
            `;
        }
    }

    // UPDATE STATS
    function updateStats(notifications, unreadCount) {
        document.getElementById('totalCount').textContent = notifications.length;
        document.getElementById('unreadCount').textContent = unreadCount;
        
        // Count order notifications
        const orderCount = notifications.filter(n => {
            const type = (n.type || '').toLowerCase();
            return type.includes('order');
        }).length;
        document.getElementById('orderCount').textContent = orderCount;

        // Show/hide mark all button
        const markAllBtn = document.getElementById('markAllBtn');
        if (markAllBtn) {
            markAllBtn.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        }
    }

    // DISPLAY NOTIFICATIONS
    function displayNotifications(notifications) {
        const container = document.getElementById('notificationsContainer');
        
        const html = notifications.map(notif => {
            const isUnread = !notif.is_read;
            const icon = getIcon(notif.type);
            const colorClass = notif.color_class || 'primary';
            
            return `
                <div class="notification-card ${isUnread ? 'unread' : ''}" 
                     onclick="handleNotificationClick(${notif.id}, '${escapeHtml(notif.action_url || '')}')">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="notification-icon ${colorClass}">
                            <i class="bi ${icon}"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="notification-title mb-0">${escapeHtml(notif.title || 'Notification')}</h5>
                                <small class="text-muted">${escapeHtml(notif.time_ago || 'Just now')}</small>
                            </div>
                            <p class="notification-message mb-2">${escapeHtml(notif.message || '')}</p>
                            <div class="d-flex gap-2 align-items-center">
                                <span class="badge badge-modern badge-${colorClass}">
                                    ${formatType(notif.type)}
                                </span>
                                ${notif.priority === 'high' || notif.priority === 'urgent' ? 
                                    `<span class="badge badge-modern badge-danger">
                                        <i class="bi bi-exclamation-circle me-1"></i>${notif.priority}
                                    </span>` : ''}
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            ${isUnread ? `
                                <button class="btn btn-sm btn-outline-success" onclick="event.stopPropagation(); markAsRead(${notif.id})">
                                    <i class="bi bi-check"></i>
                                </button>
                            ` : ''}
                            <button class="btn btn-sm btn-outline-danger" onclick="event.stopPropagation(); deleteNotification(${notif.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        container.innerHTML = html;
    }

    // SHOW EMPTY STATE
    function showEmpty() {
        const container = document.getElementById('notificationsContainer');
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="color: #E2E8F0; font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No notifications yet</h5>
                <p class="text-muted">You'll see order updates and announcements here</p>
                <a href="{{ route('shop') }}" class="btn btn-pink-gradient mt-3">
                    <i class="bi bi-bag me-2"></i>Start Shopping
                </a>
            </div>
        `;
    }

    // GET ICON
    function getIcon(type) {
        if (!type) return 'bi-bell';
        const t = type.toLowerCase();
        if (t.includes('confirm')) return 'bi-check-circle';
        if (t.includes('ship') || t.includes('deliver')) return 'bi-truck';
        if (t.includes('cancel')) return 'bi-x-circle';
        if (t.includes('order')) return 'bi-box-seam';
        if (t.includes('payment')) return 'bi-credit-card';
        return 'bi-bell';
    }

    // FORMAT TYPE
    function formatType(type) {
        if (!type) return 'General';
        return type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    }

    // ESCAPE HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = String(text);
        return div.innerHTML;
    }

    // MARK AS READ
    async function markAsRead(notificationId) {
        try {
            const response = await fetch(`/api/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                loadNotifications(); // Reload
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // MARK ALL AS READ
    async function markAllAsRead() {
        if (!confirm('Mark all notifications as read?')) return;
        
        try {
            const response = await fetch('/api/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                loadNotifications(); // Reload
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // DELETE NOTIFICATION
    async function deleteNotification(notificationId) {
        if (!confirm('Delete this notification?')) return;
        
        try {
            const response = await fetch(`/api/notifications/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                loadNotifications(); // Reload
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // HANDLE NOTIFICATION CLICK
    function handleNotificationClick(notificationId, actionUrl) {
        markAsRead(notificationId).then(() => {
            if (actionUrl && actionUrl !== '#' && actionUrl !== 'null' && actionUrl !== '') {
                window.location.href = actionUrl;
            }
        });
    }
    
    // SYNC NAVBAR BADGE WITH PAGE DATA
    function syncNavbarBadge(unreadCount) {
        const navbarBadge = document.getElementById('notificationCount');
        if (navbarBadge) {
            if (unreadCount > 0) {
                navbarBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                navbarBadge.style.display = 'flex';
            } else {
                navbarBadge.style.display = 'none';
            }
        }
    }
    
    // OVERRIDE NAVBAR FETCH TO PREVENT CONFLICTS
    setTimeout(function() {
        // Stop navbar's auto-update interval
        if (window.notificationUpdateInterval) {
            clearInterval(window.notificationUpdateInterval);
        }
        
        // Hide navbar loading spinner
        const navbarLoading = document.getElementById('notificationsLoading');
        if (navbarLoading) {
            navbarLoading.style.display = 'none';
        }
        
        // Replace navbar's fetchNotifications with our own
        window.fetchNotifications = function() {
            const navbarList = document.getElementById('notificationsList');
            if (navbarList) {
                navbarList.innerHTML = `
                    <div class="text-center py-4 px-3">
                        <i class="bi bi-check-circle text-success" style="font-size: 2.5rem;"></i>
                        <p class="mt-3 mb-2 fw-bold" style="color: #2D3748;">You're viewing the full page</p>
                        <p class="mb-0" style="color: #718096; font-size: 0.85rem;">Scroll down to see all your notifications</p>
                    </div>
                `;
            }
            
            // Update badge from page data
            const unreadCount = parseInt(document.getElementById('unreadCount')?.textContent || 0);
            const navbarBadge = document.getElementById('notificationCount');
            if (navbarBadge) {
                if (unreadCount > 0) {
                    navbarBadge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                    navbarBadge.style.display = 'flex';
                } else {
                    navbarBadge.style.display = 'none';
                }
            }
            
            return Promise.resolve();
        };
        
        // Call it immediately to fix the navbar
        window.fetchNotifications();
    }, 100);
    </script>
</body>
</html>