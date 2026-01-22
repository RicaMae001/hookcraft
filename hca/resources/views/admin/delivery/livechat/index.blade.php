@extends('admin.delivery.layouts.delivery-layout')

@section('title', 'Delivery Live Chat')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
    <h1 class="h2"><i class="fas fa-comments me-2 text-primary"></i>Delivery Support Chats</h1>
    <button class="btn btn-primary" onclick="location.reload()">
        <i class="fas fa-sync-alt"></i> Refresh
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body text-center">
                <i class="fas fa-clock fa-3x mb-3 opacity-50"></i>
                <h3 class="mb-0">{{ $waitingSessions->count() }}</h3>
                <p class="mb-0">Waiting in Queue</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body text-center">
                <i class="fas fa-comment-dots fa-3x mb-3 opacity-50"></i>
                <h3 class="mb-0">{{ $activeSessions->count() }}</h3>
                <p class="mb-0">My Active Chats</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x mb-3 opacity-50"></i>
                <h3 class="mb-0">{{ $allActiveSessions->count() }}</h3>
                <p class="mb-0">All Active Chats</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-secondary">
            <div class="card-body text-center">
                <i class="fas fa-check-circle fa-3x mb-3 opacity-50"></i>
                <h3 class="mb-0">{{ $closedSessions->count() }}</h3>
                <p class="mb-0">Closed Today</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" id="chatTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="waiting-tab" data-bs-toggle="tab" data-bs-target="#waiting" type="button">
            <i class="fas fa-clock"></i> Waiting Queue 
            @if($waitingSessions->count() > 0)
                <span class="badge bg-warning">{{ $waitingSessions->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="my-active-tab" data-bs-toggle="tab" data-bs-target="#my-active" type="button">
            <i class="fas fa-comment-dots"></i> My Active Chats
            @if($activeSessions->count() > 0)
                <span class="badge bg-success">{{ $activeSessions->count() }}</span>
            @endif
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="all-active-tab" data-bs-toggle="tab" data-bs-target="#all-active" type="button">
            <i class="fas fa-users"></i> All Active Chats
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="closed-tab" data-bs-toggle="tab" data-bs-target="#closed" type="button">
            <i class="fas fa-history"></i> Chat History
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="chatTabsContent">
    <!-- Waiting Queue -->
    <div class="tab-pane fade show active" id="waiting" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Customers Waiting for Delivery Support</h5>
            </div>
            <div class="card-body">
                @if($waitingSessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                    <td><span class="badge bg-warning text-dark fs-6">#{{ $session->queue_position }}</span></td>
                                    <td><strong>{{ $session->customer_name }}</strong></td>
                                    <td>{{ $session->customer_email ?? 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ \Carbon\Carbon::parse($session->created_at)->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td>
                                        <form action="{{ route('delivery.livechat.accept', $session->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Accept Chat
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
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No customers waiting in queue</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- My Active Chats -->
    <div class="tab-pane fade" id="my-active" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-comment-dots me-2"></i>My Active Chat Sessions</h5>
            </div>
            <div class="card-body">
                @if($activeSessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                        <span class="badge bg-info">
                                            {{ \Carbon\Carbon::parse($session->started_at)->diffForHumans() }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle pulse"></i> Active
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('delivery.livechat.chat', $session->id) }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-comments"></i> Open Chat
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <p class="text-muted">You don't have any active chats</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- All Active Chats -->
    <div class="tab-pane fade" id="all-active" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-users me-2"></i>All Active Chat Sessions</h5>
            </div>
            <div class="card-body">
                @if($allActiveSessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                <tr class="{{ $session->is_inactive_critical ?? false ? 'table-danger' : ($session->is_inactive_warning ?? false ? 'table-warning' : '') }}">
                                    <td><strong>{{ $session->customer_name }}</strong></td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <i class="fas fa-user"></i> {{ $session->delivery_name ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($session->started_at)->format('M d, h:i A') }}</td>
                                    <td>
                                        @if(isset($session->last_customer_activity))
                                            <span class="badge {{ $session->is_inactive_critical ?? false ? 'bg-danger' : ($session->is_inactive_warning ?? false ? 'bg-warning text-dark' : 'bg-success') }}">
                                                @if($session->minutes_inactive < 5)
                                                    <i class="fas fa-circle pulse"></i> Active Now
                                                @else
                                                    <i class="fas fa-clock"></i> {{ $session->minutes_inactive }} min ago
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">Unknown</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-success">
                                            <i class="fas fa-circle pulse"></i> Active
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-comments fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No active chats at the moment</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chat History -->
    <div class="tab-pane fade" id="closed" role="tabpanel">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Chat History</h5>
            </div>
            <div class="card-body">
                @if($closedSessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                            {{ \DB::table('delivery_coordinator')->where('coordinator_id', $session->delivery_id)->value('name') }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('M d, h:i A') : 'N/A' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($session->closed_at)->format('M d, h:i A') }}</td>
                                    <td>
                                        @if($session->started_at && $session->closed_at)
                                            {{ \Carbon\Carbon::parse($session->started_at)->diffInMinutes(\Carbon\Carbon::parse($session->closed_at)) }} min
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-history fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No closed chats found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.pulse {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.card {
    border-radius: 10px;
}

.table-warning {
    background-color: #fff3cd !important;
}

.table-danger {
    background-color: #f8d7da !important;
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
</script>
@endsection