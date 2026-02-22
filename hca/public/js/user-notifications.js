/**
 * User Notification System - Improved Version
 * Handles real-time notification updates for authenticated users
 */

let notificationUpdateInterval = null;
let lastNotificationCheck = null;
let isNotificationDropdownOpen = false;

// Configuration
const NOTIFICATION_CONFIG = {
    pollInterval: 15000, // Check every 15 seconds (reduced from 30)
    maxRetries: 3,
    retryDelay: 2000,
    debug: true // Set to false in production
};

// Debug logger
function debugLog(message, data = null) {
    if (NOTIFICATION_CONFIG.debug) {
        console.log(`[Notifications] ${message}`, data || '');
    }
}

// Get CSRF token
function getCsrfToken() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
    return tokenMeta ? tokenMeta.getAttribute('content') : null;
}

// Fetch notifications from API with retry logic
async function fetchNotifications(retryCount = 0) {
    try {
        debugLog('Fetching notifications...', { retryCount, timestamp: new Date().toISOString() });
        
        const csrfToken = getCsrfToken();
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        const response = await fetch('/api/notifications?limit=10', {
            method: 'GET',
            headers: headers,
            credentials: 'same-origin' // Important for session cookies
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.error || 'Failed to fetch notifications');
        }

        debugLog('Notifications fetched successfully', {
            count: data.notifications.length,
            unread: data.unread_count,
            recipient_type: data.recipient_type
        });

        lastNotificationCheck = new Date();
        updateNotificationUI(data.notifications, data.unread_count);
        
        return data;
    } catch (error) {
        console.error('Error fetching notifications:', error);
        
        // Retry logic
        if (retryCount < NOTIFICATION_CONFIG.maxRetries) {
            debugLog(`Retrying... (${retryCount + 1}/${NOTIFICATION_CONFIG.maxRetries})`);
            await new Promise(resolve => setTimeout(resolve, NOTIFICATION_CONFIG.retryDelay));
            return fetchNotifications(retryCount + 1);
        }
        
        // Show error state in UI
        showNotificationError(error.message);
        return null;
    }
}

// Update notification badge count
function updateNotificationBadge(count) {
    const badge = document.getElementById('notificationCount');
    if (!badge) return;
    
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'flex';
        badge.classList.add('pulse'); // Add pulse animation for new notifications
        
        // Remove pulse after animation
        setTimeout(() => badge.classList.remove('pulse'), 1000);
    } else {
        badge.style.display = 'none';
    }
}

// Update notification list UI
function updateNotificationUI(notifications, unreadCount) {
    const notificationsList = document.getElementById('notificationsList');
    const markAllReadBtn = document.getElementById('markAllReadBtn');
    
    if (!notificationsList) return;

    // Update badge
    updateNotificationBadge(unreadCount);
    
    // Show/hide mark all as read button
    if (markAllReadBtn) {
        markAllReadBtn.style.display = unreadCount > 0 ? 'inline-block' : 'none';
    }
    
    // Clear loading state
    const loadingElement = document.getElementById('notificationsLoading');
    if (loadingElement) {
        loadingElement.remove();
    }
    
    // Render notifications
    if (notifications.length === 0) {
        notificationsList.innerHTML = `
            <div class="empty-state text-center py-5">
                <i class="bi bi-bell-slash" style="font-size: 3rem; color: #ddd;"></i>
                <p class="text-muted mt-3 mb-0">No notifications yet</p>
            </div>
        `;
        return;
    }
    
    notificationsList.innerHTML = notifications.map(notification => 
        createNotificationHTML(notification)
    ).join('');
}

// Create HTML for a single notification
function createNotificationHTML(notification) {
    const isUnread = !notification.is_read;
    const priorityClass = getPriorityClass(notification.priority);
    const timeAgo = formatTimeAgo(notification.created_at);
    const icon = getNotificationIcon(notification.type);
    
    return `
        <div class="notification-item ${isUnread ? 'unread' : ''} ${priorityClass}" 
             onclick="handleNotificationClick(${notification.id}, '${escapeHtml(notification.action_url || '#')}')"
             role="button"
             tabindex="0">
            <div class="notification-icon">
                <i class="bi ${icon}"></i>
            </div>
            <div class="notification-content">
                <div class="notification-title">${escapeHtml(notification.title)}</div>
                <div class="notification-message">${escapeHtml(notification.message)}</div>
                <div class="notification-time">
                    <i class="bi bi-clock"></i> ${timeAgo}
                </div>
            </div>
            ${isUnread ? '<div class="unread-indicator"></div>' : ''}
        </div>
    `;
}

// Get notification icon based on type
function getNotificationIcon(type) {
    const icons = {
        'order_created': 'bi-cart-check-fill',
        'order_updated': 'bi-arrow-repeat',
        'order_cancelled': 'bi-x-circle-fill',
        'payment_received': 'bi-cash-coin',
        'payment_proof_uploaded': 'bi-file-earmark-arrow-up-fill',
        'delivery_status_changed': 'bi-truck',
        'product_low_stock': 'bi-exclamation-triangle-fill',
        'product_out_of_stock': 'bi-x-octagon-fill',
        'chat_message': 'bi-chat-dots-fill',
        'system_alert': 'bi-info-circle-fill',
        'default': 'bi-bell-fill'
    };
    
    return icons[type] || icons.default;
}

// Get priority CSS class
function getPriorityClass(priority) {
    const classes = {
        'urgent': 'priority-urgent',
        'high': 'priority-high',
        'normal': '',
        'low': 'priority-low'
    };
    
    return classes[priority] || '';
}

// Format timestamp to relative time
function formatTimeAgo(timestamp) {
    if (!timestamp) return 'Just now';
    
    const now = new Date();
    const time = new Date(timestamp);
    const diffInSeconds = Math.floor((now - time) / 1000);
    
    if (diffInSeconds < 60) return 'Just now';
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
    if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;
    
    return time.toLocaleDateString();
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = String(text);
    return div.innerHTML;
}

// Handle notification click
async function handleNotificationClick(notificationId, actionUrl) {
    try {
        debugLog('Notification clicked', { notificationId, actionUrl });
        
        const csrfToken = getCsrfToken();
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        // Mark as read
        await fetch(`/api/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: headers,
            credentials: 'same-origin'
        });
        
        // Refresh notifications
        await fetchNotifications();
        
        // Navigate to action URL
        if (actionUrl && actionUrl !== '#' && actionUrl !== 'null' && actionUrl !== '') {
            window.location.href = actionUrl;
        }
    } catch (error) {
        console.error('Error handling notification click:', error);
    }
}

// Mark all notifications as read
async function markAllAsRead() {
    try {
        debugLog('Marking all as read...');
        
        const csrfToken = getCsrfToken();
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken;
        }
        
        const response = await fetch('/api/notifications/mark-all-read', {
            method: 'POST',
            headers: headers,
            credentials: 'same-origin'
        });
        
        if (response.ok) {
            debugLog('All notifications marked as read');
            await fetchNotifications();
        }
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

// Show error state in notification list
function showNotificationError(errorMessage) {
    const notificationsList = document.getElementById('notificationsList');
    if (!notificationsList) return;
    
    notificationsList.innerHTML = `
        <div class="error-state text-center py-5">
            <i class="bi bi-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3 mb-2">Failed to load notifications</p>
            <small class="text-muted">${escapeHtml(errorMessage)}</small>
            <br>
            <button class="btn btn-sm btn-primary mt-3" onclick="fetchNotifications()">
                <i class="bi bi-arrow-clockwise"></i> Retry
            </button>
        </div>
    `;
}

// Initialize notification system
function initializeNotifications() {
    debugLog('Initializing notification system...');
    
    const notificationBell = document.getElementById('notificationTrigger');
    const notificationMenu = document.getElementById('notificationDropdown');
    
    if (!notificationBell || !notificationMenu) {
        debugLog('Notification elements not found, skipping initialization');
        return;
    }

    // Toggle notification dropdown
    notificationBell.addEventListener('click', function(e) {
        e.stopPropagation();
        isNotificationDropdownOpen = !isNotificationDropdownOpen;
        notificationMenu.style.display = isNotificationDropdownOpen ? 'block' : 'none';
        
        if (isNotificationDropdownOpen) {
            debugLog('Dropdown opened, fetching notifications');
            fetchNotifications();
        }
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (notificationMenu && notificationBell && 
            !notificationMenu.contains(e.target) && 
            !notificationBell.contains(e.target)) {
            isNotificationDropdownOpen = false;
            notificationMenu.style.display = 'none';
        }
    });

    // Prevent dropdown from closing when clicking inside
    notificationMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // Initial fetch
    debugLog('Performing initial notification fetch');
    fetchNotifications();
    
    // Set up polling interval
    if (notificationUpdateInterval) {
        clearInterval(notificationUpdateInterval);
    }
    
    notificationUpdateInterval = setInterval(() => {
        if (!document.hidden) { // Only poll when tab is visible
            fetchNotifications();
        }
    }, NOTIFICATION_CONFIG.pollInterval);
    
    debugLog(`Polling started (interval: ${NOTIFICATION_CONFIG.pollInterval}ms)`);
}

// Clean up on page unload
window.addEventListener('beforeunload', function() {
    if (notificationUpdateInterval) {
        clearInterval(notificationUpdateInterval);
        debugLog('Polling stopped');
    }
});

// Pause polling when tab is hidden, resume when visible
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        debugLog('Tab hidden, pausing polling');
    } else {
        debugLog('Tab visible, resuming polling');
        fetchNotifications(); // Fetch immediately when tab becomes visible
    }
});

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeNotifications);
} else {
    // DOM already loaded
    initializeNotifications();
}

// Export functions for global use
window.handleNotificationClick = handleNotificationClick;
window.markAllAsRead = markAllAsRead;
window.fetchNotifications = fetchNotifications;