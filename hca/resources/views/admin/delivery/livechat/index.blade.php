@extends('admin.delivery.layouts.delivery-layout')

@section('title', 'Delivery Live Chat')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="page-title">
            <i class="fas fa-comments" style="color: var(--primary-blue);"></i>
            Delivery Support Chats
        </h1>
        <p class="page-subtitle">Manage customer support conversations and chat sessions</p>
    </div>
    <div>
        <button class="btn-modern btn-modern-secondary" onclick="location.reload()">
            <i class="fas fa-sync-alt"></i> Refresh
        </button>
    </div>
</div>

<!-- Session Alerts -->
@if(session('success'))
    <div class="alert-modern alert-success animate-fade-in">
        <i class="fas fa-check-circle fa-2x"></i>
        <div>
            <strong>Success:</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert-modern alert-danger animate-fade-in">
        <i class="fas fa-exclamation-circle fa-2x"></i>
        <div>
            <strong>Error:</strong> {{ session('error') }}
        </div>
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    </div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4 g-4">
    <div class="col-md-3">
        <div class="stat-card animate-fade-in">
            <div class="stat-card-icon warning">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-card-value">{{ $waitingSessions->count() }}</div>
            <div class="stat-card-label">Waiting in Queue</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.1s;">
            <div class="stat-card-icon success">
                <i class="fas fa-comment-dots"></i>
            </div>
            <div class="stat-card-value">{{ $activeSessions->count() }}</div>
            <div class="stat-card-label">My Active Chats</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.2s;">
            <div class="stat-card-icon info">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-card-value">{{ $allActiveSessions->count() }}</div>
            <div class="stat-card-label">All Active Chats</div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="stat-card animate-fade-in" style="animation-delay: 0.3s;">
            <div class="stat-card-icon primary">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-card-value">{{ $closedSessions->count() }}</div>
            <div class="stat-card-label">Closed Today</div>
        </div>
    </div>
</div>

<!-- Modern Tabs Navigation -->
<div class="content-card mb-4">
    <div class="content-card-header">
        <h5 class="content-card-title">
            <i class="fas fa-list-alt"></i>
            Chat Sessions Overview
        </h5>
    </div>
    <div class="content-card-body p-0">
        <!-- Tabs Navigation -->
        <div class="tabs-container">
            <nav class="modern-tabs">
                <div class="nav nav-pills" id="chatTabs" role="tablist">
                    <button class="modern-tab active" id="waiting-tab" data-bs-toggle="pill" data-bs-target="#waiting" type="button">
                        <i class="fas fa-clock"></i>
                        <span>Waiting Queue</span>
                        @if($waitingSessions->count() > 0)
                            <span class="tab-badge badge-modern badge-warning">{{ $waitingSessions->count() }}</span>
                        @endif
                    </button>
                    
                    <button class="modern-tab" id="my-active-tab" data-bs-toggle="pill" data-bs-target="#my-active" type="button">
                        <i class="fas fa-comment-dots"></i>
                        <span>My Active Chats</span>
                        @if($activeSessions->count() > 0)
                            <span class="tab-badge badge-modern badge-success">{{ $activeSessions->count() }}</span>
                        @endif
                    </button>
                    
                    <button class="modern-tab" id="all-active-tab" data-bs-toggle="pill" data-bs-target="#all-active" type="button">
                        <i class="fas fa-users"></i>
                        <span>All Active Chats</span>
                    </button>
                    
                    <button class="modern-tab" id="closed-tab" data-bs-toggle="pill" data-bs-target="#closed" type="button">
                        <i class="fas fa-history"></i>
                        <span>Chat History</span>
                    </button>
                </div>
            </nav>
            
            <!-- Tab Content -->
            <div class="tab-content p-4" id="chatTabsContent">
                <!-- Waiting Queue -->
                <div class="tab-pane fade show active" id="waiting" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-clock me-2 text-warning"></i>
                            Customers Waiting for Delivery Support
                        </h6>
                        <small class="text-muted">{{ $waitingSessions->count() }} waiting</small>
                    </div>
                    
                    @if($waitingSessions->count() > 0)
                        <div class="modern-table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Queue #</th>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Waiting Since</th>
                                        <th>Duration</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($waitingSessions as $session)
                                    <tr>
                                        <td>
                                            <span class="order-id" style="background: var(--warning); color: #000;">
                                                #{{ $session->queue_position }}
                                            </span>
                                        </td>
                                        <td><strong>{{ $session->customer_name }}</strong></td>
                                        <td>{{ $session->customer_email ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <span class="badge-modern badge-secondary">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($session->created_at)->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('delivery.livechat.accept', $session->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn-modern btn-modern-success">
                                                    <i class="fas fa-check me-1"></i> Accept Chat
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state-icon mb-3">
                                <i class="fas fa-inbox fa-3x" style="color: var(--text-secondary);"></i>
                            </div>
                            <h5 class="text-muted mb-2">No customers waiting</h5>
                            <p class="text-muted">The queue is currently empty</p>
                        </div>
                    @endif
                </div>

                <!-- My Active Chats -->
                <div class="tab-pane fade" id="my-active" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-comment-dots me-2 text-success"></i>
                            My Active Chat Sessions
                        </h6>
                        <small class="text-muted">{{ $activeSessions->count() }} active</small>
                    </div>
                    
                    @if($activeSessions->count() > 0)
                        <div class="modern-table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Email</th>
                                        <th>Started</th>
                                        <th>Duration</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeSessions as $session)
                                    <tr>
                                        <td><strong>{{ $session->customer_name }}</strong></td>
                                        <td>{{ $session->customer_email ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($session->started_at)->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <span class="badge-modern badge-info">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($session->started_at)->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-modern badge-success">
                                                <i class="fas fa-circle pulse me-1"></i> Active
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('delivery.livechat.chat', $session->id) }}" class="btn-modern btn-modern-primary btn-sm">
                                                <i class="fas fa-comments me-1"></i> Open Chat
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state-icon mb-3">
                                <i class="fas fa-comments fa-3x" style="color: var(--text-secondary);"></i>
                            </div>
                            <h5 class="text-muted mb-2">No active chats</h5>
                            <p class="text-muted">You don't have any active chat sessions</p>
                        </div>
                    @endif
                </div>

                <!-- All Active Chats -->
                <div class="tab-pane fade" id="all-active" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-users me-2 text-info"></i>
                            All Active Chat Sessions
                        </h6>
                        <small class="text-muted">{{ $allActiveSessions->count() }} total active</small>
                    </div>
                    
                    @if($allActiveSessions->count() > 0)
                        <div class="modern-table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Handled By</th>
                                        <th>Started</th>
                                        <th>Last Activity</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allActiveSessions as $session)
                                    <tr class="
                                        {{ $session->is_inactive_critical ?? false ? 'inactive-critical' : '' }}
                                        {{ $session->is_inactive_warning ?? false ? 'inactive-warning' : '' }}
                                    ">
                                        <td><strong>{{ $session->customer_name }}</strong></td>
                                        <td>
                                            <span class="badge-modern badge-primary">
                                                <i class="fas fa-user me-1"></i> {{ $session->delivery_name ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($session->started_at)->format('M d, h:i A') }}</td>
                                        <td>
                                            @if(isset($session->last_customer_activity))
                                                @if($session->is_inactive_critical ?? false)
                                                    <span class="badge-modern badge-danger">
                                                        <i class="fas fa-exclamation-triangle me-1"></i> {{ $session->minutes_inactive }} min ago
                                                    </span>
                                                @elseif($session->is_inactive_warning ?? false)
                                                    <span class="badge-modern badge-warning">
                                                        <i class="fas fa-clock me-1"></i> {{ $session->minutes_inactive }} min ago
                                                    </span>
                                                @else
                                                    <span class="badge-modern badge-success">
                                                        <i class="fas fa-circle pulse me-1"></i> Active Now
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge-modern badge-secondary">
                                                    <i class="fas fa-question-circle me-1"></i> Unknown
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge-modern badge-success">
                                                <i class="fas fa-circle pulse me-1"></i> Active
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state-icon mb-3">
                                <i class="fas fa-comments fa-3x" style="color: var(--text-secondary);"></i>
                            </div>
                            <h5 class="text-muted mb-2">No active chats</h5>
                            <p class="text-muted">There are no active chat sessions at the moment</p>
                        </div>
                    @endif
                </div>

                <!-- Chat History -->
                <div class="tab-pane fade" id="closed" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0">
                            <i class="fas fa-history me-2 text-primary"></i>
                            Recent Chat History
                        </h6>
                        <small class="text-muted">{{ $closedSessions->count() }} closed today</small>
                    </div>
                    
                    @if($closedSessions->count() > 0)
                        <div class="modern-table-container">
                            <table class="modern-table">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Handled By</th>
                                        <th>Started</th>
                                        <th>Ended</th>
                                        <th>Duration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($closedSessions as $session)
                                    <tr>
                                        <td>{{ $session->customer_name }}</td>
                                        <td>
                                            @if($session->delivery_id)
                                                @php
                                                    $coordinator = \DB::table('delivery_coordinator')->where('coordinator_id', $session->delivery_id)->first();
                                                @endphp
                                                <span class="badge-modern badge-secondary">
                                                    <i class="fas fa-user me-1"></i> {{ $coordinator->name ?? 'Unknown' }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('M d, h:i A') : 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($session->closed_at)->format('M d, h:i A') }}</td>
                                        <td>
                                            @if($session->started_at && $session->closed_at)
                                                <span class="badge-modern badge-info">
                                                    <i class="fas fa-hourglass-end me-1"></i>
                                                    {{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} min
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="empty-state-icon mb-3">
                                <i class="fas fa-history fa-3x" style="color: var(--text-secondary);"></i>
                            </div>
                            <h5 class="text-muted mb-2">No chat history</h5>
                            <p class="text-muted">No closed chats found for today</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modern Tabs Styling */
.tabs-container {
    background: var(--card-bg);
    border-radius: 16px;
    overflow: hidden;
}

.modern-tabs {
    background: var(--light-bg);
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.modern-tabs .nav-pills {
    display: flex;
    gap: 0.5rem;
    border: none;
}

.modern-tab {
    flex: 1;
    background: transparent;
    border: none;
    color: var(--text-secondary);
    padding: 0.875rem 1.25rem;
    border-radius: 10px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    transition: all 0.2s ease;
    cursor: pointer;
    position: relative;
}

.modern-tab:hover {
    background: var(--hover-bg);
    color: var(--text-primary);
}

.modern-tab.active {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
    color: var(--primary-blue);
    font-weight: 600;
}

.tab-badge {
    position: absolute;
    top: -8px;
    right: -8px;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

/* Table row states */
.inactive-warning {
    background: rgba(246, 173, 85, 0.1) !important;
    border-left: 4px solid var(--warning);
}

.inactive-critical {
    background: rgba(252, 129, 129, 0.1) !important;
    border-left: 4px solid var(--danger);
}

/* Modern Table Container */
.modern-table-container {
    background: var(--card-bg);
    border-radius: 12px;
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.modern-table {
    width: 100%;
    margin: 0;
}

.modern-table thead {
    background: var(--light-bg);
}

.modern-table th {
    padding: 1rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    border: none;
}

.modern-table td {
    padding: 1.25rem 1.5rem;
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}

.modern-table tbody tr {
    transition: all 0.2s ease;
}

.modern-table tbody tr:hover {
    background: var(--hover-bg);
}

/* Empty State */
.empty-state-icon {
    opacity: 0.6;
}

/* Pulse animation for active status */
.pulse {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--success);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.7;
        transform: scale(0.9);
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modern-tabs .nav-pills {
        flex-direction: column;
    }
    
    .modern-tab {
        justify-content: flex-start;
    }
    
    .modern-table-container {
        overflow-x: auto;
    }
    
    .modern-table {
        min-width: 800px;
    }
}

/* Content Card for tabs */
.content-card .content-card-body.p-0 {
    border-radius: 0 0 16px 16px;
}

/* Button styles for consistency */
.btn-modern.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
}
</style>

<script>
// Auto-refresh every 30 seconds when on waiting tab
setInterval(function() {
    const waitingTab = document.getElementById('waiting-tab');
    if (waitingTab && waitingTab.classList.contains('active')) {
        location.reload();
    }
}, 30000);

// Add tab switching animation
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.modern-tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Auto-close alerts after 5 seconds
    setTimeout(() => {
        const alerts = document.querySelectorAll('.alert-modern');
        alerts.forEach(alert => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s ease';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
});
</script>
@endsection