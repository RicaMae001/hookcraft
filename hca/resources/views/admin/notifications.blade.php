@extends('admin.layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-bell me-3" style="color: var(--primary-pink);"></i>
                Notifications
            </h1>
            <p style="color: var(--text-secondary); margin: 0;">Manage your notifications and stay updated</p>
        </div>
        
        <div class="d-flex gap-2">
            @if($unreadCount > 0)
            <button class="btn btn-modern-primary" onclick="markAllAsRead()">
                <i class="fas fa-check-double me-2"></i>Mark All as Read
            </button>
            @endif
        </div>
    </div>

    <!-- Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-card-icon primary">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stat-card-label">Total Notifications</div>
                <div class="stat-card-value">{{ count($notifications) }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card">
                <div class="stat-card-icon warning">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-card-label">Unread</div>
                <div class="stat-card-value">{{ $unreadCount }}</div>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="content-card">
        <div class="content-card-header">
            <h3 class="content-card-title">All Notifications</h3>
        </div>
        
        <div class="p-4">
            @forelse($notifications as $notification)
            @php
                // Determine icon and color based on notification type
                $type = $notification['type'] ?? '';
                $priority = $notification['priority'] ?? 'normal';
                
                // Set icon
                if (str_contains($type, 'order')) {
                    $icon = 'fa-shopping-cart';
                    $colorClass = 'success';
                } elseif (str_contains($type, 'delivery')) {
                    $icon = 'fa-truck';
                    $colorClass = 'info';
                } elseif (str_contains($type, 'payment')) {
                    $icon = 'fa-money-bill-wave';
                    $colorClass = 'success';
                } elseif (str_contains($type, 'product')) {
                    $icon = 'fa-box';
                    $colorClass = 'primary';
                } elseif (str_contains($type, 'stock')) {
                    $icon = 'fa-exclamation-triangle';
                    $colorClass = 'warning';
                } else {
                    $icon = 'fa-bell';
                    $colorClass = 'primary';
                }
                
                // Calculate time ago
                $timeAgo = \Carbon\Carbon::parse($notification['created_at'])->diffForHumans();
            @endphp
            
            <div class="notification-card {{ $notification['is_read'] ? '' : 'unread' }}" 
                 onclick="handleNotificationClick({{ $notification['id'] }}, '{{ $notification['action_url'] ?? '#' }}')">
                <div class="d-flex gap-3 align-items-start">
                    <div class="notification-icon {{ $colorClass }}">
                        <i class="fas {{ $icon }}"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="notification-title mb-0">{{ $notification['title'] }}</h5>
                            <small class="text-muted">{{ $timeAgo }}</small>
                        </div>
                        <p class="notification-message mb-2">{{ $notification['message'] }}</p>
                        <div class="d-flex gap-2 align-items-center">
                            <span class="badge badge-modern badge-{{ $colorClass }}">
                                {{ ucfirst(str_replace('_', ' ', $notification['type'])) }}
                            </span>
                            @if($priority === 'high' || $priority === 'urgent')
                            <span class="badge badge-modern badge-danger">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ ucfirst($priority) }}
                            </span>
                            @endif
                            @if(!$notification['is_read'])
                            <span class="badge badge-modern badge-info">
                                <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>New
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        @if(!$notification['is_read'])
                        <button class="btn btn-sm btn-modern-primary" onclick="event.stopPropagation(); markAsRead({{ $notification['id'] }})" title="Mark as read">
                            <i class="fas fa-check"></i>
                        </button>
                        @endif
                        <button class="btn btn-sm btn-modern-danger" onclick="event.stopPropagation(); deleteNotification({{ $notification['id'] }})" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x mb-3" style="color: var(--border-color);"></i>
                <h5 style="color: var(--text-secondary);">No notifications yet</h5>
                <p style="color: var(--text-secondary);">You'll see notifications here when there's activity</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<style>
.notification-card {
    background: var(--card-bg);
    border: 1px solid var(--border-color);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.notification-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-pink);
}

.notification-card.unread {
    background: rgba(255, 107, 157, 0.05);
    border-left: 4px solid var(--primary-pink);
}

.notification-card:last-child {
    margin-bottom: 0;
}

.notification-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
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
    font-size: 1.1rem;
}

.notification-message {
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0;
}

.badge-modern {
    padding: 0.25rem 0.75rem;
    border-radius: 6px;
    font-size: 0.8125rem;
    font-weight: 600;
}

.badge-success {
    background: rgba(72, 187, 120, 0.15);
    color: var(--success);
}

.badge-info {
    background: rgba(99, 179, 237, 0.15);
    color: var(--info);
}

.badge-warning {
    background: rgba(246, 173, 85, 0.15);
    color: var(--warning);
}

.badge-danger {
    background: rgba(252, 129, 129, 0.15);
    color: var(--danger);
}

.badge-primary {
    background: rgba(102, 126, 234, 0.15);
    color: var(--primary-blue);
}
</style>

<script>
async function markAsRead(notificationId) {
    try {
        const response = await fetch(`/api/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            location.reload();
        } else {
            console.error('Failed to mark notification as read');
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

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
            location.reload();
        } else {
            console.error('Failed to mark all notifications as read');
        }
    } catch (error) {
        console.error('Error marking all as read:', error);
    }
}

async function deleteNotification(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) {
        return;
    }
    
    try {
        const response = await fetch(`/api/notifications/${notificationId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        });
        
        if (response.ok) {
            location.reload();
        } else {
            console.error('Failed to delete notification');
        }
    } catch (error) {
        console.error('Error deleting notification:', error);
    }
}

function handleNotificationClick(notificationId, actionUrl) {
    // Mark as read first
    if (actionUrl && actionUrl !== '#' && actionUrl !== 'null' && actionUrl !== '') {
        markAsRead(notificationId).then(() => {
            window.location.href = actionUrl;
        });
    } else {
        // Just mark as read if no action URL
        markAsRead(notificationId);
    }
}
</script>
@endsection