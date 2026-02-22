{{-- resources/views/admin/delivery/notifications.blade.php --}}
@extends('admin.delivery.layouts.delivery-layout')

@section('title', 'Notifications')

@section('content')
<div class="animate-fade-in">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.25rem; font-weight: 700; margin-bottom: 0.5rem;">
                <i class="fas fa-bell me-3" style="color: var(--primary-blue);"></i>
                Delivery Notifications
            </h1>
            <p style="color: var(--text-secondary); margin: 0;">Stay updated on your delivery assignments and updates</p>
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
                
                // Set icon and color class
                if (str_contains($type, 'delivery_assigned')) {
                    $icon = 'fa-truck';
                    $colorClass = 'info';
                } elseif (str_contains($type, 'delivery_status')) {
                    $icon = 'fa-shipping-fast';
                    $colorClass = 'primary';
                } elseif (str_contains($type, 'order')) {
                    $icon = 'fa-shopping-cart';
                    $colorClass = 'success';
                } elseif (str_contains($type, 'payment')) {
                    $icon = 'fa-money-bill-wave';
                    $colorClass = 'success';
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
                <p style="color: var(--text-secondary);">You'll see delivery assignments and updates here</p>
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
    border-color: var(--primary-blue);
}

.notification-card.unread {
    background: rgba(102, 126, 234, 0.05);
    border-left: 4px solid var(--primary-blue);
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

.stat-card {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
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
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.2), rgba(118, 75, 162, 0.2));
    color: var(--primary-blue);
}

.stat-card-icon.warning {
    background: linear-gradient(135deg, rgba(246, 173, 85, 0.2), rgba(242, 153, 74, 0.2));
    color: var(--warning);
}

.stat-card-label {
    font-size: 0.875rem;
    color: var(--text-secondary);
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.stat-card-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
}

.content-card {
    background: var(--card-bg);
    border-radius: 16px;
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.content-card-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-color);
}

.content-card-title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

.badge-modern {
    padding: 0.375rem 0.75rem;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.75rem;
}

.badge-success { background: rgba(72, 187, 120, 0.15); color: var(--success); }
.badge-info { background: rgba(99, 179, 237, 0.15); color: var(--info); }
.badge-warning { background: rgba(246, 173, 85, 0.15); color: var(--warning); }
.badge-danger { background: rgba(252, 129, 129, 0.15); color: var(--danger); }
.badge-primary { background: rgba(102, 126, 234, 0.15); color: var(--primary-blue); }

.btn-modern-primary {
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-modern-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
    color: white;
}

.btn-modern-danger {
    background: transparent;
    color: var(--danger);
    border: 1px solid var(--danger);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-modern-danger:hover {
    background: var(--danger);
    color: white;
}

.animate-fade-in {
    animation: fadeIn 0.5s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
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
        }
    } catch (error) {
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
        }
    } catch (error) {
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
        }
    } catch (error) {
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