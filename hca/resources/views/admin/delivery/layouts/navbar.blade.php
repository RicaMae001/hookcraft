{{-- resources/views/admin/delivery/layouts/navbar.blade.php --}}
<!-- Modern Navbar for Delivery Portal -->
<nav class="modern-navbar">
    <div class="navbar-brand">
        <i class="fas fa-truck"></i>
        <span>Delivery Portal</span>
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
                        <a href="{{ route('delivery.notifications') }}" class="btn btn-sm btn-link p-0" title="View all">
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
                </div>
                <div class="notification-footer">
                    <a href="{{ route('delivery.notifications') }}" class="view-all-btn">View all notifications</a>
                </div>
            </div>
        </div>
        
        <!-- User Profile -->
        <button class="user-profile" id="userProfile" type="button">
            <div class="user-avatar">
                {{ strtoupper(substr(session('coordinator_name', 'D'), 0, 1)) }}
            </div>
            <div class="user-info">
                <div class="user-name">{{ session('coordinator_name', 'Delivery Coordinator') }}</div>
                <div class="user-role">Delivery</div>
            </div>
            <i class="fas fa-chevron-down"></i>
        </button>
    </div>
</nav>

<!-- Profile Dropdown Menu -->
<div class="profile-menu-dropdown" id="profileMenu" style="display: none;">
    <a href="#" class="profile-menu-item">
        <i class="fas fa-user me-2"></i>
        <span>Profile</span>
    </a>
    <a href="{{ route('delivery.history') }}" class="profile-menu-item">
        <i class="fas fa-history me-2"></i>
        <span>History</span>
    </a>
    <div class="dropdown-divider"></div>
    <form action="{{ route('delivery.logout') }}" method="POST" class="w-100 m-0">
        @csrf
        <button type="submit" class="profile-menu-item logout-btn w-100 text-start">
            <i class="fas fa-sign-out-alt me-2"></i>
            <span>Logout</span>
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
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background: var(--hover-bg);
}

.notification-item.unread {
    background: rgba(102, 126, 234, 0.05);
}

.notification-item.unread::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: var(--primary-blue);
}

.notification-item .d-flex {
    display: flex;
}

.notification-item .gap-3 {
    gap: 0.75rem;
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

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.notification-message {
    color: var(--text-secondary);
    font-size: 0.85rem;
    margin-bottom: 0.5rem;
    line-height: 1.4;
    word-wrap: break-word;
}

.notification-time {
    color: var(--text-secondary);
    font-size: 0.75rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
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
}

.notification-priority-badge.urgent {
    background: rgba(252, 129, 129, 0.2);
    color: var(--danger);
}

.notification-priority-badge.high {
    background: rgba(246, 173, 85, 0.2);
    color: var(--warning);
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

.profile-menu-dropdown {
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

.profile-menu-item {
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

.profile-menu-item:hover {
    background: var(--hover-bg);
}

.profile-menu-item.logout-btn {
    color: var(--danger);
}

.profile-menu-item.logout-btn:hover {
    background: rgba(252, 129, 129, 0.1);
}

.dropdown-divider {
    height: 1px;
    background: var(--border-color);
    margin: 0.5rem 0;
}

.flex-grow-1 {
    flex-grow: 1;
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
    .profile-menu-dropdown {
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

// Fetch and display notifications
async function fetchNotifications() {
    try {
        console.log('Fetching notifications from API...');
        // Use universal API endpoint (handles all user types automatically)
        const response = await fetch('/api/notifications?limit=10', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });
        
        console.log('Response status:', response.status);
        
        if (!response.ok) {
            console.error('Failed to fetch notifications:', response.status, response.statusText);
            hideLoading();
            showEmpty();
            return;
        }
        
        const data = await response.json();
        console.log('Full API Response:', JSON.stringify(data, null, 2));
        console.log('Unread count:', data.unread_count);
        console.log('Notifications array:', data.notifications);
        console.log('Recipient type:', data.recipient_type);
        console.log('Recipient ID:', data.recipient_id);
        
        if (data.success) {
            updateNotificationBadge(data.unread_count || 0);
            displayNotifications(data.notifications || []);
        } else {
            console.warn('API returned success: false');
            hideLoading();
            showEmpty();
        }
    } catch (error) {
        console.error('Error fetching notifications:', error);
        console.error('Error details:', error.message, error.stack);
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
    if (!container) return;
    
    container.innerHTML = `
        <div class="text-center py-5">
            <i class="fas fa-inbox fa-3x mb-3" style="color: var(--border-color);"></i>
            <p style="color: var(--text-secondary); margin: 0;">No notifications</p>
        </div>
    `;
}

// Update notification badge
function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationCount');
    const markAllBtn = document.getElementById('markAllReadBtn');
    
    console.log('Updating badge with count:', count);
    
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
    
    if (!container) return;
    
    console.log('Displaying notifications:', notifications);
    console.log('Raw notification data:', JSON.stringify(notifications, null, 2));
    
    if (!notifications || notifications.length === 0) {
        showEmpty();
        return;
    }
    
    const html = notifications.map(notification => {
        console.log('Processing notification:', notification);
        
        // Determine icon and color based on notification type
        let icon = 'fa-bell';
        let colorClass = 'primary';
        
        // Check notification type (case-insensitive and handle different formats)
        const notifType = (notification.type || '').toLowerCase();
        const notifTitle = (notification.title || '').toLowerCase();
        
        if (notifType.includes('delivery') && notifType.includes('assign')) {
            icon = 'fa-truck';
            colorClass = 'info';
        } else if (notifTitle.includes('delivery') && notifTitle.includes('assign')) {
            icon = 'fa-truck';
            colorClass = 'info';
        } else if (notifType === 'delivery_assigned' || notifType === 'new_delivery' || notifType === 'delivery assigned') {
            icon = 'fa-truck';
            colorClass = 'info';
        } else if (notifType.includes('completed') || notifType === 'delivery_completed') {
            icon = 'fa-check-circle';
            colorClass = 'success';
        } else if (notifType.includes('cancel') || notifType === 'delivery_cancelled' || notifType === 'order_cancelled') {
            icon = 'fa-times-circle';
            colorClass = 'danger';
        } else if (notifType.includes('status') || notifType === 'status_update') {
            icon = 'fa-sync-alt';
            colorClass = 'info';
        } else if (notifType.includes('urgent') || notification.priority === 'urgent') {
            icon = 'fa-exclamation-triangle';
            colorClass = 'warning';
        }
        
        // Override with notification's icon and color if provided
        if (notification.icon) icon = notification.icon;
        if (notification.color_class) colorClass = notification.color_class;
        
        const priorityBadge = (notification.priority === 'urgent' || notification.priority === 'high') 
            ? `<span class="notification-priority-badge ${notification.priority}">${notification.priority.toUpperCase()}</span>` 
            : '';
        
        const actionUrl = notification.action_url && notification.action_url !== 'null' && notification.action_url !== '' ? notification.action_url : '#';
        
        // Check if unread - handle multiple formats
        const isUnread = notification.is_read === 0 || 
                        notification.is_read === false || 
                        notification.is_read === '0' || 
                        notification.read_at === null ||
                        notification.read_at === undefined;
        
        return `
            <div class="notification-item ${isUnread ? 'unread' : ''}" 
                 onclick="handleNotificationClick(${notification.id}, '${actionUrl}')">
                <div class="d-flex gap-3">
                    <div class="notification-icon ${colorClass}">
                        <i class="fas ${icon}"></i>
                    </div>
                    <div class="notification-content flex-grow-1">
                        <div class="notification-title">
                            ${escapeHtml(notification.title)}
                            ${priorityBadge}
                        </div>
                        <div class="notification-message">${escapeHtml(notification.message)}</div>
                        <div class="notification-time">
                            <i class="fas fa-clock"></i>
                            <span>${notification.time_ago || formatTimeAgo(notification.created_at)}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = html;
}

// Format time ago
function formatTimeAgo(dateString) {
    if (!dateString) return 'Just now';
    
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return 'Just now';
    if (seconds < 3600) return Math.floor(seconds / 60) + ' minutes ago';
    if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
    if (seconds < 604800) return Math.floor(seconds / 86400) + ' days ago';
    return Math.floor(seconds / 604800) + ' weeks ago';
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
        // Mark as read using universal API
        await fetch(`/api/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        // Refresh notifications
        await fetchNotifications();
        
        // Navigate if there's an action URL
        if (actionUrl && actionUrl !== '#' && actionUrl !== 'null' && actionUrl !== '') {
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
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });
        
        if (response.ok) {
            await fetchNotifications();
        }
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const themeToggle = document.getElementById('themeToggle');
    const html = document.documentElement;
    
    // Theme toggle
    const savedTheme = localStorage.getItem('delivery-theme') || 'light';
    html.setAttribute('data-theme', savedTheme);
    
    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('delivery-theme', newTheme);
        });
    }

    // Notification dropdown toggle
    const notificationBell = document.getElementById('notificationTrigger');
    const notificationMenu = document.getElementById('notificationDropdown');
    const profileMenu = document.getElementById('profileMenu');
    const userProfile = document.getElementById('userProfile');
    
    if (notificationBell && notificationMenu) {
        notificationBell.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = notificationMenu.style.display === 'block';
            
            notificationMenu.style.display = isVisible ? 'none' : 'block';
            if (profileMenu) profileMenu.style.display = 'none';
            
            if (!isVisible) {
                fetchNotifications();
            }
        });
    }

    // Profile dropdown toggle
    if (userProfile && profileMenu) {
        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            const isVisible = profileMenu.style.display === 'block';
            
            profileMenu.style.display = isVisible ? 'none' : 'block';
            if (notificationMenu) notificationMenu.style.display = 'none';
        });
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (notificationMenu && notificationBell && 
            !notificationMenu.contains(e.target) && !notificationBell.contains(e.target)) {
            notificationMenu.style.display = 'none';
        }
        if (profileMenu && userProfile && 
            !profileMenu.contains(e.target) && !userProfile.contains(e.target)) {
            profileMenu.style.display = 'none';
        }
    });

    // Prevent dropdown from closing when clicking inside
    if (notificationMenu) {
        notificationMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    if (profileMenu) {
        profileMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }

    // Initial fetch
    console.log('Initializing notifications...');
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