<!-- Notification Dropdown Component -->
<!-- Include this in your navbar -->

<div class="dropdown notification-dropdown">
    <button class="btn btn-link notification-bell" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fas fa-bell fa-lg"></i>
        <span class="notification-badge" id="notificationCount" style="display: none;">0</span>
    </button>
    
    <div class="dropdown-menu dropdown-menu-end notification-menu" style="width: 380px; max-height: 500px; overflow-y: auto;">
        <!-- Header -->
        <div class="notification-header d-flex justify-content-between align-items-center p-3 border-bottom">
            <h6 class="mb-0 fw-bold">
                <i class="fas fa-bell me-2" style="color: var(--primary-blue);"></i>
                Notifications
            </h6>
            <div>
                <button class="btn btn-sm btn-link text-primary" onclick="markAllAsRead()" title="Mark all as read">
                    <i class="fas fa-check-double"></i>
                </button>
                @if(session('admin_id'))
                <a href="{{ route('admin.notifications') }}" class="btn btn-sm btn-link" title="View all">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                @elseif(session('coordinator_id'))
                <a href="{{ route('delivery.notifications') }}" class="btn btn-sm btn-link" title="View all">
                    <i class="fas fa-external-link-alt"></i>
                </a>
                @endif
            </div>
        </div>

        <!-- Notifications List -->
        <div id="notificationsList" class="notification-list">
            <!-- Loading state -->
            <div class="text-center py-5" id="notificationsLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            
            <!-- Empty state -->
            <div class="text-center py-5 d-none" id="notificationsEmpty">
                <i class="fas fa-inbox fa-3x mb-3" style="color: var(--border-color);"></i>
                <p style="color: var(--text-secondary);">No notifications</p>
            </div>
            
            <!-- Notifications will be inserted here -->
        </div>
    </div>
</div>

<style>
.notification-bell {
    position: relative;
    color: var(--text-primary);
    padding: 0.5rem;
}

.notification-bell:hover {
    color: var(--primary-blue);
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background: var(--danger);
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 0.7rem;
    font-weight: 700;
    min-width: 18px;
    text-align: center;
}

.notification-menu {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
}

.notification-header {
    background: var(--light-bg);
}

.notification-item {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.notification-item:hover {
    background: var(--hover-bg);
}

.notification-item.unread {
    background: rgba(102, 126, 234, 0.05);
    border-left: 3px solid var(--primary-blue);
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 1.25rem;
    width: 8px;
    height: 8px;
    background: var(--primary-blue);
    border-radius: 50%;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.notification-icon.success {
    background: rgba(72, 187, 120, 0.15);
    color: var(--success);
}

.notification-icon.info {
    background: rgba(99, 179, 237, 0.15);
    color: var(--info);
}

.notification-icon.warning {
    background: rgba(246, 173, 85, 0.15);
    color: var(--warning);
}

.notification-icon.danger {
    background: rgba(252, 129, 129, 0.15);
    color: var(--danger);
}

.notification-icon.primary {
    background: rgba(102, 126, 234, 0.15);
    color: var(--primary-blue);
}

.notification-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
}

.notification-message {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.notification-time {
    color: var(--text-secondary);
    font-size: 0.75rem;
}
</style>

<script>
let notificationUpdateInterval;

// Fetch and display notifications
async function fetchNotifications() {
    try {
        const response = await fetch('/api/notifications?limit=10', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        if (!response.ok) throw new Error('Failed to fetch notifications');
        
        const data = await response.json();
        
        if (data.success) {
            updateNotificationBadge(data.unread_count);
            displayNotifications(data.notifications);
        }
    } catch (error) {
        console.error('Error fetching notifications:', error);
    }
}

// Update notification badge
function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationCount');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'block';
    } else {
        badge.style.display = 'none';
    }
}

// Display notifications in dropdown
function displayNotifications(notifications) {
    const container = document.getElementById('notificationsList');
    const loading = document.getElementById('notificationsLoading');
    const empty = document.getElementById('notificationsEmpty');
    
    loading.classList.add('d-none');
    
    if (notifications.length === 0) {
        empty.classList.remove('d-none');
        return;
    }
    
    empty.classList.add('d-none');
    
    const html = notifications.map(notification => `
        <div class="notification-item ${notification.is_read ? '' : 'unread'}" 
             onclick="handleNotificationClick(${notification.id}, '${notification.action_url || '#'}')">
            <div class="d-flex gap-3">
                <div class="notification-icon ${notification.color_class}">
                    <i class="fas ${notification.icon}"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="notification-title">${notification.title}</div>
                    <div class="notification-message">${notification.message}</div>
                    <div class="notification-time">
                        <i class="fas fa-clock me-1"></i>${notification.time_ago}
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = html;
}

// Handle notification click
async function handleNotificationClick(notificationId, actionUrl) {
    try {
        // Mark as read
        await fetch(`/api/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        // Refresh notifications
        await fetchNotifications();
        
        // Navigate to action URL if exists
        if (actionUrl && actionUrl !== '#') {
            window.location.href = actionUrl;
        }
    } catch (error) {
        console.error('Error handling notification click:', error);
    }
}

// Mark all as read
async function markAllAsRead() {
    try {
        const response = await fetch('/api/notifications/mark-all-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            await fetchNotifications();
        }
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

// Initialize notifications
document.addEventListener('DOMContentLoaded', function() {
    // Initial fetch
    fetchNotifications();
    
    // Auto-refresh every 30 seconds
    notificationUpdateInterval = setInterval(fetchNotifications, 30000);
    
    // Fetch when dropdown is opened
    const notificationDropdown = document.querySelector('.notification-dropdown');
    if (notificationDropdown) {
        notificationDropdown.addEventListener('show.bs.dropdown', fetchNotifications);
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (notificationUpdateInterval) {
        clearInterval(notificationUpdateInterval);
    }
});
</script>