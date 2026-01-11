{{-- Save as: resources/views/admin/livechat/livechat_index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Live Chat Management')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-comments"></i> Live Chat Management
        </h1>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-primary" onclick="refreshPage()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#bulkDeleteModal">
                <i class="fas fa-trash-alt"></i> Clean History
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Waiting in Queue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $waitingSessions->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                My Active Chats
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $activeSessions->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                All Active Chats
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $allActiveSessions->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Closed Today
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $closedSessions->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
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

    <!-- Tabs Content -->
    <div class="tab-content" id="chatTabsContent">
        <!-- Waiting Queue Tab -->
        <div class="tab-pane fade show active" id="waiting" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-clock"></i> Customers Waiting for Support
                    </h6>
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
                                        <td>
                                            <span class="badge bg-warning text-dark fs-6">#{{ $session->queue_position }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $session->user_name ?? $session->customer_name }}</strong>
                                        </td>
                                        <td>{{ $session->user_email ?? $session->customer_email ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($session->created_at)->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <span class="badge bg-secondary">
                                                {{ \Carbon\Carbon::parse($session->created_at)->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.livechat.accept', $session->id) }}" method="POST" class="d-inline">
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

        <!-- My Active Chats Tab -->
        <div class="tab-pane fade" id="my-active" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-comment-dots"></i> My Active Chat Sessions
                    </h6>
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
                                        <td><strong>{{ $session->user_name ?? $session->customer_name }}</strong></td>
                                        <td>{{ $session->user_email ?? $session->customer_email ?? 'N/A' }}</td>
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
                                            <a href="{{ route('admin.livechat.chat', $session->id) }}" class="btn btn-primary btn-sm">
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

        <!-- All Active Chats Tab -->
        <div class="tab-pane fade" id="all-active" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-users"></i> All Active Chat Sessions
                    </h6>
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
                                    <tr class="{{ $session->is_inactive_critical ? 'table-danger' : ($session->is_inactive_warning ? 'table-warning' : '') }}">
                                        <td><strong>{{ $session->user_name ?? $session->customer_name }}</strong></td>
                                        <td>
                                            <span class="badge bg-primary">
                                                <i class="fas fa-user"></i> {{ $session->admin_name ?? 'Unknown' }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($session->started_at)->format('M d, h:i A') }}</td>
                                        <td>
                                            @if($session->last_activity)
                                                <span class="badge {{ $session->is_inactive_critical ? 'bg-danger' : ($session->is_inactive_warning ? 'bg-warning text-dark' : 'bg-success') }}">
                                                    @if($session->minutes_inactive < 5)
                                                        <i class="fas fa-circle pulse"></i> Active Now
                                                    @else
                                                        <i class="fas fa-clock"></i> {{ $session->minutes_inactive }} min ago
                                                        @if($session->is_inactive_critical)
                                                            (Auto-closing soon!)
                                                        @elseif($session->is_inactive_warning)
                                                            (Inactive)
                                                        @endif
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
                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> 
                            <strong>Note:</strong> Chats inactive for more than 15 minutes will be automatically closed.
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

        <!-- Chat History Tab -->
        <div class="tab-pane fade" id="closed" role="tabpanel">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">
                        <i class="fas fa-history"></i> Recent Chat History
                    </h6>
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
                                        <th>Reason</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($closedSessions as $session)
                                    <tr>
                                        <td>{{ $session->user_name ?? $session->customer_name }}</td>
                                        <td>
                                            @if($session->admin_id)
                                                {{ \DB::table('admin')->where('id', $session->admin_id)->value('name') }}
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
                                        <td>
                                            @if($session->closed_reason === 'auto_inactive')
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-clock"></i> Auto (Inactive)
                                                </span>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-check"></i> Manual
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.livechat.view', $session->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <button class="btn btn-danger btn-sm" onclick="deleteSession({{ $session->id }}, '{{ $session->user_name ?? $session->customer_name }}')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
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
</div>

<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="bulkDeleteModalLabel">
                    <i class="fas fa-trash-alt"></i> Clean Chat History
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs mb-3" id="deleteTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="delete-old-tab" data-bs-toggle="tab" data-bs-target="#delete-old" type="button">
                            Delete Old Chats
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="delete-all-tab" data-bs-toggle="tab" data-bs-target="#delete-all" type="button">
                            Delete All Closed
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="deleteTabContent">
                    <!-- Delete Old Chats Tab -->
                    <div class="tab-pane fade show active" id="delete-old" role="tabpanel">
                        <form action="{{ route('admin.livechat.bulk-delete') }}" method="POST" onsubmit="return confirmBulkDelete()">
                            @csrf
                            <div class="mb-3">
                                <label for="days" class="form-label">Delete chats older than:</label>
                                <select class="form-select" id="days" name="days" required>
                                    <option value="7">7 days</option>
                                    <option value="14">14 days</option>
                                    <option value="30" selected>30 days</option>
                                    <option value="60">60 days</option>
                                    <option value="90">90 days</option>
                                    <option value="180">180 days</option>
                                </select>
                            </div>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                <strong>Warning:</strong> This will permanently delete all closed chat sessions and their messages older than the selected period.
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Old Chats
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Delete All Closed Tab -->
                    <div class="tab-pane fade" id="delete-all" role="tabpanel">
                        <form action="{{ route('admin.livechat.delete-all-closed') }}" method="POST" onsubmit="return confirmDeleteAll()">
                            @csrf
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> 
                                <strong>Danger Zone:</strong> This will permanently delete ALL closed chat sessions and their messages. This action cannot be undone!
                            </div>
                            <div class="mb-3">
                                <p><strong>Total Closed Sessions:</strong> {{ $closedSessions->count() }}</p>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash-alt"></i> Delete All Closed Chats
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Single Session Form -->
<form id="deleteSessionForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

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

.border-left-warning {
    border-left: 4px solid #f6c23e;
}

.border-left-success {
    border-left: 4px solid #1cc88a;
}

.border-left-info {
    border-left: 4px solid #36b9cc;
}

.border-left-secondary {
    border-left: 4px solid #858796;
}

.nav-tabs .nav-link {
    color: #666;
}

.nav-tabs .nav-link.active {
    color: #4e73df;
    font-weight: 600;
}

.table-warning {
    background-color: #fff3cd !important;
}

.table-danger {
    background-color: #f8d7da !important;
}
</style>

<script>
// Auto-refresh page every 30 seconds for waiting queue
setInterval(function() {
    const waitingTab = document.getElementById('waiting-tab');
    if (waitingTab && waitingTab.classList.contains('active')) {
        location.reload();
    }
}, 30000);

function refreshPage() {
    location.reload();
}

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
@endsection