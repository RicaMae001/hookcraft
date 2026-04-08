{{-- resources/views/admin/layouts/navbar.blade.php --}}

<!-- Modern Navbar -->
<nav class="modern-navbar">
    <div class="navbar-brand">
        <i class="fas fa-flower"></i>
        <span>HookcraftAvenue</span>
    </div>
    
    <div class="navbar-actions">
        <!-- Active Status Badge -->
        <div class="status-badge">
            <div class="pulse"></div>
            <span>Active</span>
        </div>
        
        <!-- Theme Toggle -->
        <div class="theme-toggle" id="themeToggle">
            <div class="theme-toggle-slider">
                <i class="fas fa-sun theme-toggle-icon sun"></i>
                <i class="fas fa-moon theme-toggle-icon moon"></i>
            </div>
        </div>
        
        <!-- Notification Dropdown -->
        <div class="notification-dropdown">
            <button class="notification-bell" id="notificationTrigger" type="button">
                <i class="fas fa-bell"></i>
                <span class="notification-badge" id="notificationCount" style="display: none;">0</span>
            </button>
            
            <!-- Notification Menu -->
            <div class="notification-menu" id="notificationDropdown" style="display: none;">
                <div class="notification-header">
                    <h6 class="m-0">
                        <i class="fas fa-bell me-2" style="color: var(--primary-blue);"></i>
                        Notifications
                    </h6>
                    <div>
                        <button class="btn btn-sm btn-link text-primary p-0 me-2" onclick="markAllAsRead()" title="Mark all as read" id="markAllReadBtn" style="display: none;" type="button">
                            <i class="fas fa-check-double"></i>
                        </button>
                        <a href="{{ route('admin.notifications') }}" class="btn btn-sm btn-link p-0" title="View all">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
                <div class="notification-list" id="notificationsList">
                    <!-- Loading state -->
                    <div class="text-center py-5" id="notificationsLoading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <!-- Empty state (will be shown by JS if needed) -->
                </div>
                <div class="notification-footer">
                    <a href="{{ route('admin.notifications') }}" class="view-all-btn">View all notifications</a>
                </div>
            </div>
        </div>
        
        <!-- User Profile -->
        <button class="user-profile" id="adminProfile" type="button">
            <div class="user-avatar">
                {{ strtoupper(substr(session('admin_name') ?? 'A', 0, 1)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ session('admin_name') ?? 'Admin' }}</div>
                <div class="user-role">{{ session('admin_role') ?? 'Admin' }}</div>
            </div>
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
</nav>

<!-- Profile Dropdown Menu -->
<div class="profile-dropdown-menu" id="profileDropdown" style="display: none;">
   
    <div class="dropdown-divider"></div>
    <form action="{{ route('admin.logout') }}" method="POST" class="w-100 m-0">
        @csrf
        <button type="submit" class="dropdown-item text-danger w-100 text-start">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </button>
    </form>
</div>

<style>
/* Navbar Styles */
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

.modern-navbar {
    background: var(--card-bg);
    border-bottom: 1px solid var(--border-color);
    padding: 1rem 2rem;
    position: fixed;
    top: 0;
    right: 0;
    left: var(--sidebar-width);
    z-index: 1000;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
    height: 70px;
}

.navbar-brand {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.navbar-actions {
    display: flex;
    align-items: center;
    gap: 1.5rem;
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

.theme-toggle {
    width: 70px;
    height: 36px;
    border-radius: 18px;
    background: var(--border-color);
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    padding: 3px;
    position: relative;
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

.notification-dropdown {
    position: relative;
}

.notification-bell {
    position: relative;
    cursor: pointer;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: var(--light-bg);
    transition: all 0.2s ease;
    border: none;
}

.notification-bell:hover {
    background: var(--hover-bg);
    transform: translateY(-2px);
}

.notification-bell i {
    color: var(--text-primary);
    font-size: 1.25rem;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: linear-gradient(135deg, var(--danger), #f56565);
    color: white;
    border-radius: 10px;
    padding: 2px 6px;
    font-size: 0.7rem;
    font-weight: 700;
    min-width: 18px;
    text-align: center;
    border: 2px solid var(--card-bg);
    line-height: 1;
}

.notification-menu {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 380px;
    background: var(--card-bg);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    z-index: 1100;
}

.notification-header {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.notification-header h6 {
    margin: 0;
    font-weight: 700;
    color: var(--text-primary);
    font-size: 1rem;
}

.notification-list {
    max-height: 400px;
    overflow-y: auto;
}

.notification-item {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    transition: all 0.2s ease;
    position: relative;
}

.notification-item:not(.delivery-notification) {
    cursor: pointer;
}

.notification-item:not(.delivery-notification):hover {
    background: var(--hover-bg);
}

.notification-item.delivery-notification {
    cursor: default;
    opacity: 1;
    background: rgba(252, 129, 129, 0.05);
}

.notification-item.delivery-notification .notification-icon.delivery {
    background: rgba(252, 129, 129, 0.15);
    color: var(--danger);
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
    flex-shrink: 0;
}

.notification-icon.success { background: rgba(72, 187, 120, 0.15); color: var(--success); }
.notification-icon.info { background: rgba(99, 179, 237, 0.15); color: var(--info); }
.notification-icon.warning { background: rgba(246, 173, 85, 0.15); color: var(--warning); }
.notification-icon.danger { background: rgba(252, 129, 129, 0.15); color: var(--danger); }
.notification-icon.primary { background: rgba(102, 126, 234, 0.15); color: var(--primary-blue); }

.notification-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 0.25rem;
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

.notification-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid var(--border-color);
    text-align: center;
}

.view-all-btn {
    color: var(--primary-blue);
    text-decoration: none;
    font-weight: 500;
    font-size: 0.875rem;
}

.view-all-btn:hover {
    text-decoration: underline;
}

.notification-priority-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-left: 0.5rem;
}

.notification-priority-badge.urgent {
    background: rgba(252, 129, 129, 0.2);
    color: var(--danger);
}

.notification-priority-badge.high {
    background: rgba(246, 173, 85, 0.2);
    color: var(--warning);
}

/* Delivery notification badge */
.notification-delivery-badge {
    display: inline-block;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    background: rgba(252, 129, 129, 0.2);
    color: var(--danger);
    margin-left: 0.5rem;
    vertical-align: middle;
}

.notification-item.delivery-notification .notification-title {
    color: var(--text-primary);
}

.notification-item.delivery-notification .notification-message {
    color: var(--text-secondary);
}

.notification-item.delivery-notification .fa-lock {
    font-size: 0.7rem;
    margin-right: 0.25rem;
    color: var(--text-secondary);
}

.notification-item.delivery-notification .text-muted {
    color: var(--text-secondary) !important;
    font-size: 0.7rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.25rem;
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
    border: none;
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

.user-info {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    line-height: 1.2;
}

.user-role {
    color: var(--text-secondary);
    font-size: 0.75rem;
    line-height: 1.2;
}

.user-profile i.fa-chevron-down {
    color: var(--text-secondary);
    font-size: 0.875rem;
    transition: transform 0.3s ease;
}

.profile-dropdown-menu {
    position: absolute;
    top: 70px;
    right: 20px;
    width: 220px;
    background: var(--card-bg);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    z-index: 1100;
    padding: 0.5rem;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: var(--text-primary);
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.2s ease;
    font-size: 0.875rem;
    width: 100%;
    border: none;
    background: transparent;
    text-align: left;
    cursor: pointer;
}

.dropdown-item:hover {
    background: var(--hover-bg);
}

.dropdown-item.text-danger {
    color: var(--danger) !important;
}

.dropdown-item.text-danger:hover {
    background: rgba(252, 129, 129, 0.1);
}

.dropdown-divider {
    height: 1px;
    background: var(--border-color);
    margin: 0.5rem 0;
}

@media (max-width: 768px) {
    .modern-navbar {
        left: 0;
        padding: 1rem;
    }
    
    .navbar-brand span { display: none; }
    .status-badge span { display: none; }
    .status-badge { padding: 0.5rem; }
    .navbar-actions { gap: 0.75rem; }
    .user-info { display: none; }
    
    .notification-menu,
    .profile-dropdown-menu {
        position: fixed;
        top: 70px;
        right: 10px;
        left: 10px;
        width: auto;
    }
}
</style>

<script>
let notificationUpdateInterval;

// Format time ago
function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return 'Just now';
    if (seconds < 3600) return `${Math.floor(seconds / 60)}m ago`;
    if (seconds < 86400) return `${Math.floor(seconds / 3600)}h ago`;
    if (seconds < 604800) return `${Math.floor(seconds / 86400)}d ago`;
    
    return date.toLocaleDateString();
}

// Check if notification is from delivery
function isDeliveryNotification(notification) {
    if (!notification) return false;
    
    // Check by title keywords
    const deliveryKeywords = [
        'delivery', 'shipping', 'shipment', 'package', 'order', 
        'courier', 'dispatch', 'arrived', 'shipped', 'delivered', 
        'out for delivery', 'on the way', 'transit', 'parcel',
        'tracking', 'delivery update', 'shipping update'
    ];
    
    // Check title
    if (notification.title) {
        const titleLower = notification.title.toLowerCase();
        if (deliveryKeywords.some(keyword => titleLower.includes(keyword))) {
            return true;
        }
    }
    
    // Check message
    if (notification.message) {
        const messageLower = notification.message.toLowerCase();
        if (deliveryKeywords.some(keyword => messageLower.includes(keyword))) {
            return true;
        }
    }
    
    // Check by notification type
    const deliveryTypes = [
        'delivery_status_changed',
        'delivery_assigned',
        'order_shipped',
        'order_delivered',
        'order_out_for_delivery',
        'delivery_update',
        'shipment_created',
        'shipment_updated',
        'package_dispatched'
    ];
    
    if (notification.type && deliveryTypes.includes(notification.type)) {
        return true;
    }
    
    // Check by icon
    const deliveryIcons = [
        'fa-truck', 'fa-shipping-fast', 'fa-box', 'fa-package', 
        'fa-cube', 'fa-truck-moving', 'fa-truck-loading', 'fa-dolly',
        'fa-parcel', 'fa-gift', 'fa-cubes', 'fa-ship'
    ];
    
    const { icon } = getNotificationIcon(notification.type);
    if (deliveryIcons.includes(icon)) {
        return true;
    }
    
    return false;
}

// Get notification icon and color based on type
function getNotificationIcon(type) {
    const iconMap = {
        'order_created': { icon: 'fa-shopping-bag', color: 'primary' },
        'payment_proof_uploaded': { icon: 'fa-money-bill-wave', color: 'info' },
        'payment_received': { icon: 'fa-check-circle', color: 'success' },
        'delivery_status_changed': { icon: 'fa-truck', color: 'info' },
        'delivery_assigned': { icon: 'fa-user-check', color: 'warning' },
        'product_low_stock': { icon: 'fa-box-open', color: 'danger' },
        'product_out_of_stock': { icon: 'fa-exclamation-triangle', color: 'danger' },
        'order_updated': { icon: 'fa-edit', color: 'info' },
        'order_cancelled': { icon: 'fa-times-circle', color: 'danger' },
        'product_created': { icon: 'fa-plus-circle', color: 'success' },
        'product_updated': { icon: 'fa-edit', color: 'info' },
        'chat_message': { icon: 'fa-comment-dots', color: 'primary' },
        'system_alert': { icon: 'fa-exclamation-circle', color: 'warning' }
    };
    
    return iconMap[type] || { icon: 'fa-bell', color: 'primary' };
}

// Fetch and display notifications
async function fetchNotifications() {
    try {
        const response = await fetch('/api/notifications?limit=10', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (!response.ok) {
            hideLoading();
            showEmpty();
            return;
        }
        
        const data = await response.json();
        
        if (data.success) {
            updateNotificationBadge(data.unread_count);
            displayNotifications(data.notifications);
        } else {
            hideLoading();
            showEmpty();
        }
    } catch (error) {
        hideLoading();
        showEmpty();
    }
}

function hideLoading() {
    const loading = document.getElementById('notificationsLoading');
    if (loading) loading.style.display = 'none';
}

function showEmpty() {
    const container = document.getElementById('notificationsList');
    container.innerHTML = `
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x mb-3" style="color: var(--border-color);"></i>
            <p style="color: var(--text-secondary);">No notifications</p>
        </div>
    `;
}

// Update notification badge
function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationCount');
    const markAllBtn = document.getElementById('markAllReadBtn');
    
    if (badge) {
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'block';
            if (markAllBtn) markAllBtn.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
            if (markAllBtn) markAllBtn.style.display = 'none';
        }
    }
}

// Display notifications in dropdown
function displayNotifications(notifications) {
    const container = document.getElementById('notificationsList');
    hideLoading();
    
    if (!notifications || notifications.length === 0) {
        showEmpty();
        return;
    }
    
    const html = notifications.map(notification => {
        const { icon, color } = getNotificationIcon(notification.type);
        const priorityBadge = (notification.priority === 'urgent' || notification.priority === 'high') 
            ? `<span class="notification-priority-badge ${notification.priority}">${notification.priority.toUpperCase()}</span>` 
            : '';
        
        const isDelivery = isDeliveryNotification(notification);
        const deliveryBadge = isDelivery 
            ? `` 
            : '';
        
        const itemClass = `notification-item ${notification.is_read ? '' : 'unread'} ${isDelivery ? 'delivery-notification' : ''}`;
        
        return `
            <div class="${itemClass}" 
                 ${!isDelivery ? `onclick="handleNotificationClick(${notification.id}, '${notification.action_url || '#'}')"` : ''}>
                <div class="d-flex gap-3">
                    <div class="notification-icon ${color} ${isDelivery ? 'delivery' : ''}">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="notification-title">
                            ${escapeHtml(notification.title)}
                            ${priorityBadge}
                            ${deliveryBadge}
                        </div>
                        <div class="notification-message">${escapeHtml(notification.message)}</div>
                        <div class="notification-time">
                            <i class="fas fa-clock me-1"></i>${formatTimeAgo(notification.created_at)}
                        </div>
                        ${isDelivery ? '<small class="text-muted d-block mt-1"></i></small>' : ''}
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = html;
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Handle notification click
async function handleNotificationClick(notificationId, actionUrl) {
    try {
        await fetch(`/api/notifications/${notificationId}/read`, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        // Refresh notifications
        await fetchNotifications();
        
        // Navigate to action URL if provided
        if (actionUrl && actionUrl !== '#' && actionUrl !== 'null') {
            window.location.href = actionUrl;
        }
    } catch (error) {
        // console.error('Error handling notification click:', error); // Removed
    }
}

// Mark all as read (excluding delivery notifications)
async function markAllAsRead() {
    try {
        // Show loading state on button
        const markAllBtn = document.getElementById('markAllReadBtn');
        if (markAllBtn) {
            markAllBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            markAllBtn.disabled = true;
        }
        
        // First, get current notifications to identify which ones to mark as read
        const response = await fetch('/api/notifications?limit=10', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success && data.notifications) {
                // Filter out delivery notifications
                const nonDeliveryNotifications = data.notifications.filter(
                    notification => !isDeliveryNotification(notification)
                );
                
                // Mark each non-delivery notification as read
                for (const notification of nonDeliveryNotifications) {
                    if (!notification.is_read) {
                        await fetch(`/api/notifications/${notification.id}/read`, {
                            method: 'POST',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });
                    }
                }
                
                // Refresh notifications
                await fetchNotifications();
            }
        }
    } catch (error) {
        // console.error('Error marking all as read:', error); // Removed
    } finally {
        // Restore button
        const markAllBtn = document.getElementById('markAllReadBtn');
        if (markAllBtn) {
            markAllBtn.innerHTML = '<i class="fas fa-check-double"></i>';
            markAllBtn.disabled = false;
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;
    
    // Theme toggle
    const savedTheme = localStorage.getItem('admin-theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    
    themeToggle.addEventListener('click', function() {
        const currentTheme = html.getAttribute('data-theme');
        const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', newTheme);
        localStorage.setItem('admin-theme', newTheme);
    });

    // Notification dropdown toggle
    const notificationBell = document.getElementById('notificationTrigger');
    const notificationMenu = document.getElementById('notificationDropdown');
    const profileMenu = document.getElementById('profileDropdown');
    const adminProfile = document.getElementById('adminProfile');
    
    notificationBell.addEventListener('click', function(e) {
        e.stopPropagation();
        const isVisible = notificationMenu.style.display === 'block';
        
        notificationMenu.style.display = isVisible ? 'none' : 'block';
        profileMenu.style.display = 'none';
        
        if (!isVisible) {
            fetchNotifications();
        }
    });

    // Profile dropdown toggle
    adminProfile.addEventListener('click', function(e) {
        e.stopPropagation();
        const isVisible = profileMenu.style.display === 'block';
        
        profileMenu.style.display = isVisible ? 'none' : 'block';
        notificationMenu.style.display = 'none';
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!notificationMenu.contains(e.target) && !notificationBell.contains(e.target)) {
            notificationMenu.style.display = 'none';
        }
        if (!profileMenu.contains(e.target) && !adminProfile.contains(e.target)) {
            profileMenu.style.display = 'none';
        }
    });

    // Prevent dropdown from closing when clicking inside
    notificationMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    profileMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Initial fetch
    fetchNotifications();
    
    // Auto-refresh every 30 seconds
    notificationUpdateInterval = setInterval(fetchNotifications, 30000);
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (notificationUpdateInterval) {
        clearInterval(notificationUpdateInterval);
    }
});
</script>