{{-- Save as: resources/views/admin/livechat/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Live Chat Management')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* Live Chat Variables - Works with existing admin theme */
.livechat-container { font-family: 'Inter', -apple-system; }

/* Reuse admin theme colors with live chat additions */
.livechat-header {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid var(--border-color);
}

.header-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; }
.title-group { display: flex; align-items: center; gap: 1.5rem; }
.icon-wrapper {
    width: 56px; height: 56px; border-radius: 12px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(37,99,235,0.3);
}
.icon-wrapper i { font-size: 24px; color: white; }
.page-title { font-size: 28px; font-weight: 700; color: var(--text-primary); margin: 0; }
.page-subtitle { font-size: 14px; color: var(--text-secondary); margin: 4px 0 0; }
.header-actions { display: flex; gap: 0.5rem; }
.btn-action {
    display: inline-flex; align-items: center; gap: 0.5rem;
    padding: 10px 20px; border-radius: 12px; font-size: 14px;
    font-weight: 500; border: none; cursor: pointer; transition: all 0.2s;
}
.btn-action.btn-secondary { background: var(--light-bg); color: var(--text-primary); }
.btn-action.btn-secondary:hover { background: var(--hover-bg); transform: translateY(-1px); }
.btn-action.btn-danger { background: #ef4444; color: white; }
.btn-action.btn-danger:hover { background: #dc2626; box-shadow: 0 4px 12px rgba(239,68,68,0.3); }

/* Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
.stat-card {
    background: var(--card-bg); border-radius: 16px; padding: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid var(--border-color);
    transition: all 0.2s; position: relative; overflow: hidden;
}
.stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; }
.stat-card.stat-warning::before { background: #f59e0b; }
.stat-card.stat-success::before { background: #10b981; }
.stat-card.stat-info::before { background: #06b6d4; }
.stat-card.stat-secondary::before { background: #64748b; }
.stat-card:hover { transform: translateY(-4px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
.stat-header { display: flex; justify-content: space-between; margin-bottom: 1.5rem; }
.stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.stat-warning .stat-icon { background: #fef3c7; color: #d97706; }
.stat-success .stat-icon { background: #d1fae5; color: #059669; }
.stat-info .stat-icon { background: #cffafe; color: #0891b2; }
.stat-secondary .stat-icon { background: #f1f5f9; color: #475569; }
.stat-badge { font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-secondary); }
.stat-number { font-size: 36px; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem; }
.stat-label { font-size: 14px; color: var(--text-secondary); font-weight: 500; }
.stat-footer { padding-top: 1rem; border-top: 1px solid var(--border-color); margin-top: 1rem; }
.stat-indicator { display: inline-flex; align-items: center; gap: 6px; font-size: 12px; padding: 4px 10px; border-radius: 99px; }
.stat-indicator.urgent { background: #fee2e2; color: #dc2626; }
.stat-indicator.active { background: #d1fae5; color: #059669; }
.stat-indicator.normal { background: var(--light-bg); color: var(--text-secondary); }

/* Tabs */
.tabs-container { background: var(--card-bg); border-radius: 16px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); border: 1px solid var(--border-color); overflow: hidden; }
.tabs-nav { display: flex; background: var(--light-bg); border-bottom: 1px solid var(--border-color); padding: 0.5rem; gap: 0.25rem; overflow-x: auto; }
.tab-button {
    display: flex; align-items: center; gap: 0.5rem; padding: 12px 20px;
    background: transparent; border: none; border-radius: 12px;
    font-size: 14px; font-weight: 500; color: var(--text-secondary);
    cursor: pointer; transition: all 0.2s; white-space: nowrap;
}
.tab-button:hover { background: var(--hover-bg); color: var(--text-primary); }
.tab-button.active { background: var(--card-bg); color: var(--primary-pink); box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.tab-badge { padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 600; color: white; }
.tab-badge.warning { background: #f59e0b; }
.tab-badge.success { background: #10b981; }
.tabs-content { padding: 2rem; }
.tab-pane { display: none; }
.tab-pane.active { display: block; animation: fadeIn 0.3s; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

/* Tables */
.content-card { background: var(--light-bg); border-radius: 12px; overflow: hidden; }
.card-header { background: var(--card-bg); padding: 1.5rem; border-bottom: 1px solid var(--border-color); }
.card-title { display: flex; align-items: center; gap: 0.5rem; font-size: 18px; font-weight: 600; margin: 0; }
.card-title i { color: var(--primary-pink); }
.card-body { padding: 1.5rem; }
.data-table { background: var(--card-bg); border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
.data-table table { width: 100%; border-collapse: collapse; }
.data-table thead { background: var(--light-bg); border-bottom: 2px solid var(--border-color); }
.data-table th { padding: 16px; text-align: left; font-size: 12px; font-weight: 600; text-transform: uppercase; color: var(--text-secondary); }
.data-table td { padding: 16px; font-size: 14px; color: var(--text-primary); border-bottom: 1px solid var(--border-color); }
.table-row-hover { transition: all 0.2s; }
.table-row-hover:hover { background: var(--hover-bg); }
.table-row-hover.row-warning { background: rgba(245,158,11,0.05); }
.table-row-hover.row-critical { background: rgba(239,68,68,0.05); }

/* Customer & Badges */
.customer-info { display: flex; align-items: center; gap: 0.5rem; }
.customer-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-pink), var(--secondary));
    color: white; display: flex; align-items: center; justify-content: center;
    font-weight: 600; font-size: 14px;
}
.customer-avatar.active-avatar { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 0 0 3px #d1fae5; }
.customer-avatar.closed-avatar { background: var(--border-color); color: var(--text-secondary); }
.queue-number { padding: 6px 12px; background: #f59e0b; color: white; font-weight: 700; border-radius: 8px; font-size: 14px; }
.duration-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: var(--light-bg); color: var(--text-primary); border-radius: 8px; font-size: 13px; }
.duration-badge.active { background: #cffafe; color: #0891b2; }
.status-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: 600; }
.status-badge.active { background: #d1fae5; color: #059669; }
.agent-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: #dbeafe; color: #1d4ed8; border-radius: 8px; font-size: 13px; }
.activity-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; font-size: 13px; }
.activity-badge.active { background: #d1fae5; color: #059669; }
.activity-badge.warning { background: #fef3c7; color: #d97706; }
.activity-badge.critical { background: #fee2e2; color: #dc2626; }
.activity-badge.normal { background: var(--light-bg); color: var(--text-secondary); }
.activity-badge small { font-size: 11px; opacity: 0.8; }
.reason-badge { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; font-size: 13px; }
.reason-badge.auto { background: #fef3c7; color: #d97706; }
.reason-badge.manual { background: var(--light-bg); color: var(--text-secondary); }

/* Buttons */
.btn-primary, .btn-info, .btn-danger, .btn-secondary {
    display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px;
    border-radius: 8px; font-size: 13px; font-weight: 500;
    border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
}
.btn-sm { padding: 6px 12px; font-size: 12px; }
.btn-primary { background: var(--primary-pink); color: white; }
.btn-primary:hover { background: #FF8AAE; transform: translateY(-1px); color: white; }
.btn-info { background: #06b6d4; color: white; }
.btn-info:hover { background: #0891b2; color: white; }
.btn-danger { background: #ef4444; color: white; }
.btn-danger:hover { background: #dc2626; }
.btn-secondary { background: var(--light-bg); color: var(--text-primary); }
.action-buttons { display: flex; gap: 0.5rem; }
.inline-form { display: inline; }

/* Empty State */
.empty-state { text-align: center; padding: 3rem 1.5rem; }
.empty-icon { width: 80px; height: 80px; margin: 0 auto 1.5rem; border-radius: 50%; background: var(--light-bg); display: flex; align-items: center; justify-content: center; }
.empty-icon i { font-size: 32px; color: var(--text-secondary); }
.empty-state h3 { font-size: 18px; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; }
.empty-state p { font-size: 14px; color: var(--text-secondary); margin: 0; }

/* Info Banner */
.info-banner { display: flex; gap: 1rem; background: #cffafe; border: 1px solid #06b6d4; border-radius: 12px; padding: 1.5rem; margin-top: 1.5rem; }
.info-banner i { font-size: 20px; color: #0891b2; }
.info-banner strong, .info-banner div { color: #0891b2; font-size: 14px; }

/* Modal */
.modal-title-wrapper { display: flex; align-items: center; gap: 1rem; }
.modal-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
.modal-icon.danger { background: #fee2e2; color: #ef4444; }
.modal-close { background: none; border: none; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; transition: all 0.2s; }
.modal-close:hover { background: var(--hover-bg); }
.modal-tabs { display: flex; gap: 0.5rem; margin-bottom: 2rem; border-bottom: 2px solid var(--border-color); }
.modal-tab { display: flex; align-items: center; gap: 0.5rem; padding: 12px 20px; background: none; border: none; border-bottom: 2px solid transparent; margin-bottom: -2px; font-size: 14px; color: var(--text-secondary); cursor: pointer; }
.modal-tab.active { color: var(--primary-pink); border-bottom-color: var(--primary-pink); }
.modal-pane { display: none; }
.modal-pane.active { display: block; }
.form-group { margin-bottom: 1.5rem; }
.form-label { display: flex; align-items: center; gap: 0.5rem; font-size: 14px; margin-bottom: 0.5rem; }
.warning-box, .danger-box { display: flex; gap: 1rem; padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; }
.warning-box { background: #fef3c7; border: 1px solid #f59e0b; }
.danger-box { background: #fee2e2; border: 1px solid #ef4444; }
.warning-icon, .danger-icon { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 18px; }
.warning-icon { background: #f59e0b; color: white; }
.danger-icon { background: #ef4444; color: white; }
.warning-content, .danger-content { flex: 1; }
.warning-content { color: #d97706; }
.danger-content { color: #dc2626; }
.stats-summary { background: var(--light-bg); border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
.summary-item { display: flex; align-items: center; gap: 1rem; }
.summary-value { font-size: 24px; font-weight: 700; color: var(--text-primary); }
.modal-actions { display: flex; justify-content: flex-end; gap: 0.5rem; margin-top: 2rem; }
.pulse { animation: pulse 2s infinite; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.5; } }

@media (max-width: 768px) {
    .header-content { flex-direction: column; }
    .stats-grid { grid-template-columns: 1fr; }
    .tabs-nav { overflow-x: auto; }
}
</style>
@endpush

@section('content')
<div class="livechat-container">
    <div class="livechat-header">
        <div class="header-content">
            <div class="title-group">
                <div class="icon-wrapper"><i class="fas fa-comments"></i></div>
                <div>
                    <h1 class="page-title">Live Chat Management</h1>
                    <p class="page-subtitle">Monitor and manage customer conversations in real-time</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn-action btn-secondary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i><span>Refresh</span>
                </button>
                <button class="btn-action btn-danger" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal">
                    <i class="fas fa-trash-alt"></i><span>Clean History</span>
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <i class="fas fa-check-circle"></i>
        <div><strong>Success!</strong> {{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <i class="fas fa-exclamation-circle"></i>
        <div><strong>Error!</strong> {{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="stats-grid">
        <div class="stat-card stat-warning">
            <div class="stat-header">
                <div class="stat-icon"><i class="fas fa-clock"></i></div>
                <div class="stat-badge">Queue</div>
            </div>
            <div class="stat-body">
                <div class="stat-number">{{ $waitingSessions->count() }}</div>
                <div class="stat-label">Waiting in Queue</div>
            </div>
            <div class="stat-footer">
                @if($waitingSessions->count() > 0)
                <span class="stat-indicator urgent"><i class="fas fa-exclamation-circle"></i> Needs attention</span>
                @else
                <span class="stat-indicator normal"><i class="fas fa-check"></i> All clear</span>
                @endif
            </div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-header">
                <div class="stat-icon"><i class="fas fa-user-headset"></i></div>
                <div class="stat-badge">Active</div>
            </div>
            <div class="stat-body">
                <div class="stat-number">{{ $activeSessions->count() }}</div>
                <div class="stat-label">My Active Chats</div>
            </div>
            <div class="stat-footer">
                @if($activeSessions->count() > 0)
                <span class="stat-indicator active"><i class="fas fa-circle pulse"></i> In conversation</span>
                @else
                <span class="stat-indicator normal"><i class="fas fa-coffee"></i> Available</span>
                @endif
            </div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-header">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-badge">Team</div>
            </div>
            <div class="stat-body">
                <div class="stat-number">{{ $allActiveSessions->count() }}</div>
                <div class="stat-label">All Active Chats</div>
            </div>
            <div class="stat-footer">
                <span class="stat-indicator normal"><i class="fas fa-chart-line"></i> Team activity</span>
            </div>
        </div>

        <div class="stat-card stat-secondary">
            <div class="stat-header">
                <div class="stat-icon"><i class="fas fa-check-double"></i></div>
                <div class="stat-badge">History</div>
            </div>
            <div class="stat-body">
                <div class="stat-number">{{ $closedSessions->count() }}</div>
                <div class="stat-label">Closed Today</div>
            </div>
            <div class="stat-footer">
                <span class="stat-indicator normal"><i class="fas fa-calendar-day"></i> Last 24 hours</span>
            </div>
        </div>
    </div>

    <div class="tabs-container">
        <nav class="tabs-nav">
            <button class="tab-button active" data-tab="waiting">
                <i class="fas fa-clock"></i><span>Waiting Queue</span>
                @if($waitingSessions->count() > 0)
                <span class="tab-badge warning">{{ $waitingSessions->count() }}</span>
                @endif
            </button>
            <button class="tab-button" data-tab="my-active">
                <i class="fas fa-comment-dots"></i><span>My Active Chats</span>
                @if($activeSessions->count() > 0)
                <span class="tab-badge success">{{ $activeSessions->count() }}</span>
                @endif
            </button>
            <button class="tab-button" data-tab="all-active">
                <i class="fas fa-users"></i><span>All Active Chats</span>
            </button>
            <button class="tab-button" data-tab="closed">
                <i class="fas fa-history"></i><span>Chat History</span>
            </button>
        </nav>

        <div class="tabs-content">
            <div class="tab-pane active" data-content="waiting">
                <div class="content-card">
                    <div class="card-header">
                        <div class="card-title"><i class="fas fa-clock"></i> Customers Waiting for Support</div>
                    </div>
                    <div class="card-body">
                        @if($waitingSessions->count() > 0)
                        <div class="data-table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Queue #</th><th>Customer Name</th><th>Email</th><th>Waiting Since</th><th>Duration</th><th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($waitingSessions as $session)
                                    <tr class="table-row-hover">
                                        <td><span class="queue-number">#{{ $session->queue_position }}</span></td>
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-avatar">{{ substr($session->user_name ?? $session->customer_name ?? 'G', 0, 1) }}</div>
                                                <strong>{{ $session->user_name ?? $session->customer_name }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $session->user_email ?? $session->customer_email ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y h:i A') }}</td>
                                        <td><span class="duration-badge"><i class="fas fa-hourglass-half"></i> {{ \Carbon\Carbon::parse($session->created_at)->diffForHumans() }}</span></td>
                                        <td>
                                            <form action="{{ route('admin.livechat.accept', $session->id) }}" method="POST" class="inline-form">
                                                @csrf
                                                <button type="submit" class="btn-primary btn-sm"><i class="fas fa-check"></i> Accept Chat</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                            <h3>No Customers Waiting</h3>
                            <p>All customers have been attended to. Great job!</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="tab-pane" data-content="my-active">
                <div class="content-card">
                    <div class="card-header">
                        <div class="card-title"><i class="fas fa-comment-dots"></i> My Active Chat Sessions</div>
                    </div>
                    <div class="card-body">
                        @if($activeSessions->count() > 0)
                        <div class="data-table">
                            <table>
                                <thead>
                                    <tr><th>Customer</th><th>Email</th><th>Started</th><th>Duration</th><th>Status</th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($activeSessions as $session)
                                    <tr class="table-row-hover">
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-avatar active-avatar">{{ substr($session->user_name ?? $session->customer_name ?? 'G', 0, 1) }}</div>
                                                <strong>{{ $session->user_name ?? $session->customer_name }}</strong>
                                            </div>
                                        </td>
                                        <td class="text-muted">{{ $session->user_email ?? $session->customer_email ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($session->started_at)->format('M d, Y h:i A') }}</td>
                                        <td><span class="duration-badge active"><i class="fas fa-clock"></i> {{ \Carbon\Carbon::parse($session->started_at)->diffForHumans() }}</span></td>
                                        <td><span class="status-badge active"><i class="fas fa-circle pulse"></i> Active</span></td>
                                        <td><a href="{{ route('admin.livechat.chat', $session->id) }}" class="btn-primary btn-sm"><i class="fas fa-comments"></i> Open Chat</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-comments"></i></div>
                            <h3>No Active Chats</h3>
                            <p>You're all caught up! Accept new chats from the waiting queue.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="tab-pane" data-content="all-active">
                <div class="content-card">
                    <div class="card-header">
                        <div class="card-title"><i class="fas fa-users"></i> All Active Chat Sessions</div>
                    </div>
                    <div class="card-body">
                        @if($allActiveSessions->count() > 0)
                        <div class="data-table">
                            <table>
                                <thead>
                                    <tr><th>Customer</th><th>Handled By</th><th>Started</th><th>Last Activity</th><th>Status</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($allActiveSessions as $session)
                                    <tr class="table-row-hover {{ $session->is_inactive_critical ? 'row-critical' : ($session->is_inactive_warning ? 'row-warning' : '') }}">
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-avatar">{{ substr($session->user_name ?? $session->customer_name ?? 'G', 0, 1) }}</div>
                                                <strong>{{ $session->user_name ?? $session->customer_name }}</strong>
                                            </div>
                                        </td>
                                        <td><span class="agent-badge"><i class="fas fa-user-tie"></i> {{ $session->admin_name ?? 'Unknown' }}</span></td>
                                        <td>{{ \Carbon\Carbon::parse($session->started_at)->format('M d, h:i A') }}</td>
                                        <td>
                                            @if($session->last_activity)
                                                @if($session->minutes_inactive < 5)
                                                <span class="activity-badge active"><i class="fas fa-circle pulse"></i> Active Now</span>
                                                @elseif($session->is_inactive_critical)
                                                <span class="activity-badge critical"><i class="fas fa-exclamation-triangle"></i> {{ $session->minutes_inactive }} min ago <small>(Auto-closing soon)</small></span>
                                                @elseif($session->is_inactive_warning)
                                                <span class="activity-badge warning"><i class="fas fa-clock"></i> {{ $session->minutes_inactive }} min ago <small>(Inactive)</small></span>
                                                @else
                                                <span class="activity-badge normal"><i class="fas fa-clock"></i> {{ $session->minutes_inactive }} min ago</span>
                                                @endif
                                            @else
                                            <span class="activity-badge normal">Unknown</span>
                                            @endif
                                        </td>
                                        <td><span class="status-badge active"><i class="fas fa-circle pulse"></i> Active</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="info-banner">
                            <i class="fas fa-info-circle"></i>
                            <div><strong>Auto-close Policy:</strong> Chats inactive for more than 15 minutes will be automatically closed to maintain queue efficiency.</div>
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-comment-slash"></i></div>
                            <h3>No Active Chats</h3>
                            <p>Currently no team members are in active conversations.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="tab-pane" data-content="closed">
                <div class="content-card">
                    <div class="card-header">
                        <div class="card-title"><i class="fas fa-history"></i> Recent Chat History</div>
                    </div>
                    <div class="card-body">
                        @if($closedSessions->count() > 0)
                        <div class="data-table">
                            <table>
                                <thead>
                                    <tr><th>Customer</th><th>Handled By</th><th>Started</th><th>Ended</th><th>Duration</th><th>Reason</th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($closedSessions as $session)
                                    <tr class="table-row-hover">
                                        <td>
                                            <div class="customer-info">
                                                <div class="customer-avatar closed-avatar">{{ substr($session->user_name ?? $session->customer_name ?? 'G', 0, 1) }}</div>
                                                {{ $session->user_name ?? $session->customer_name }}
                                            </div>
                                        </td>
                                        <td>
                                            @if($session->admin_id)
                                            <span class="agent-badge"><i class="fas fa-user-tie"></i> {{ \DB::table('admin')->where('id', $session->admin_id)->value('name') }}</span>
                                            @else
                                            <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('M d, h:i A') : 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($session->closed_at)->format('M d, h:i A') }}</td>
                                        <td>
                                            @if($session->started_at && $session->closed_at)
                                            <span class="duration-badge">{{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} min</span>
                                            @else
                                            <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($session->closed_reason === 'auto_inactive')
                                            <span class="reason-badge auto"><i class="fas fa-robot"></i> Auto-closed</span>
                                            @else
                                            <span class="reason-badge manual"><i class="fas fa-user-check"></i> Manual</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.livechat.view', $session->id) }}" class="btn-info btn-sm"><i class="fas fa-eye"></i> View</a>
                                                <button class="btn-danger btn-sm" onclick="deleteSession({{ $session->id }}, '{{ $session->user_name ?? $session->customer_name }}')"><i class="fas fa-trash"></i> Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-history"></i></div>
                            <h3>No Chat History</h3>
                            <p>Closed conversations will appear here for review.</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title-wrapper">
                    <div class="modal-icon danger"><i class="fas fa-trash-alt"></i></div>
                    <h5 class="modal-title">Clean Chat History</h5>
                </div>
                <button type="button" class="modal-close" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="modal-tabs">
                    <button class="modal-tab active" data-modal-tab="delete-old"><i class="fas fa-calendar-times"></i> Delete Old Chats</button>
                    <button class="modal-tab" data-modal-tab="delete-all"><i class="fas fa-trash-restore"></i> Delete All Closed</button>
                </div>
                <div class="modal-tab-content">
                    <div class="modal-pane active" data-modal-content="delete-old">
                        <form action="{{ route('admin.livechat.bulk-delete') }}" method="POST" onsubmit="return confirmBulkDelete()">
                            @csrf
                            <div class="form-group">
                                <label for="days" class="form-label"><i class="fas fa-calendar"></i> Delete chats older than:</label>
                                <select class="form-select" id="days" name="days" required>
                                    <option value="7">7 days</option>
                                    <option value="14">14 days</option>
                                    <option value="30" selected>30 days (Recommended)</option>
                                    <option value="60">60 days</option>
                                    <option value="90">90 days</option>
                                    <option value="180">180 days</option>
                                </select>
                            </div>
                            <div class="warning-box">
                                <div class="warning-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                <div class="warning-content"><strong>Warning</strong><p>This will permanently delete all closed chat sessions and their messages older than the selected period. This action cannot be undone.</p></div>
                            </div>
                            <div class="modal-actions">
                                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn-danger"><i class="fas fa-trash"></i> Delete Old Chats</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-pane" data-modal-content="delete-all">
                        <form action="{{ route('admin.livechat.delete-all-closed') }}" method="POST" onsubmit="return confirmDeleteAll()">
                            @csrf
                            <div class="danger-box">
                                <div class="danger-icon"><i class="fas fa-exclamation-circle"></i></div>
                                <div class="danger-content"><strong>Danger Zone</strong><p>This will permanently delete <strong>ALL</strong> closed chat sessions and their messages. This action cannot be undone!</p></div>
                            </div>
                            <div class="stats-summary">
                                <div class="summary-item">
                                    <i class="fas fa-database"></i>
                                    <div><strong>Total Closed Sessions</strong><span class="summary-value">{{ $closedSessions->count() }}</span></div>
                                </div>
                            </div>
                            <div class="modal-actions">
                                <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn-danger"><i class="fas fa-trash-alt"></i> Delete All Closed Chats</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="deleteSessionForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tab = button.dataset.tab;
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
        button.classList.add('active');
        document.querySelector(`[data-content="${tab}"]`).classList.add('active');
    });
});

document.querySelectorAll('.modal-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const target = tab.dataset.modalTab;
        document.querySelectorAll('.modal-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.modal-pane').forEach(p => p.classList.remove('active'));
        tab.classList.add('active');
        document.querySelector(`[data-modal-content="${target}"]`).classList.add('active');
    });
});

setInterval(() => {
    const waitingTab = document.querySelector('[data-tab="waiting"]');
    if (waitingTab && waitingTab.classList.contains('active')) {
        location.reload();
    }
}, 30000);

function deleteSession(sessionId, customerName) {
    if (confirm(`Are you sure you want to delete the chat history with ${customerName}?\n\nThis action cannot be undone.`)) {
        const form = document.getElementById('deleteSessionForm');
        form.action = `/admin/livechat/delete/${sessionId}`;
        form.submit();
    }
}

function confirmBulkDelete() {
    const days = document.getElementById('days').value;
    return confirm(`Are you sure you want to delete all chat sessions older than ${days} days?\n\nThis action cannot be undone.`);
}

function confirmDeleteAll() {
    const confirmation = prompt('Type "DELETE ALL" to confirm deletion of all closed chat sessions:');
    if (confirmation === 'DELETE ALL') {
        return true;
    } else {
        alert('Deletion cancelled. Please type "DELETE ALL" exactly to confirm.');
        return false;
    }
}
</script>
@endpush