@extends('admin.layouts.admin')

@section('title', 'Manage Customizations')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* Customization Management Styles */
.customization-container { 
    font-family: 'Inter', -apple-system; 
    margin-top: -40px !important;
}

/* Header Section */
.customization-header {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid var(--border-color);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.title-group {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    background: linear-gradient(135deg, #ec4899, #be185d);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
}

.icon-wrapper i {
    font-size: 24px;
    color: white;
}

.page-title {
    font-size: 28px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.page-subtitle {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 4px 0 0;
}

.header-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 10px 20px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-action.btn-primary {
    background: linear-gradient(135deg, #ec4899, #be185d);
    color: white;
    box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
}

.btn-action.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(236, 72, 153, 0.4);
    color: white;
}

.btn-action.btn-secondary {
    background: var(--light-bg);
    color: var(--text-primary);
}

.btn-action.btn-secondary:hover {
    background: var(--hover-bg);
    transform: translateY(-1px);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: var(--card-bg);
    border-radius: 16px;
    padding: 1.75rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid var(--border-color);
    transition: all 0.2s;
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
}

.stat-card.stat-warning::before { background: #f59e0b; }
.stat-card.stat-success::before { background: #10b981; }
.stat-card.stat-danger::before { background: #ef4444; }
.stat-card.stat-info::before { background: #06b6d4; }

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.stat-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.stat-warning .stat-icon { background: #fef3c7; color: #d97706; }
.stat-success .stat-icon { background: #d1fae5; color: #059669; }
.stat-danger .stat-icon { background: #fee2e2; color: #dc2626; }
.stat-info .stat-icon { background: #cffafe; color: #0891b2; }

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.stat-label {
    font-size: 14px;
    color: var(--text-secondary);
    font-weight: 500;
}

/* Tabs */
.tabs-container {
    background: var(--card-bg);
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid var(--border-color);
    overflow: hidden;
}

.tabs-nav {
    display: flex;
    background: var(--light-bg);
    border-bottom: 1px solid var(--border-color);
    padding: 0.5rem;
    gap: 0.25rem;
    overflow-x: auto;
}

.tab-button {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 12px 20px;
    background: transparent;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.tab-button:hover {
    background: var(--hover-bg);
    color: var(--text-primary);
}

.tab-button.active {
    background: var(--card-bg);
    color: var(--primary-pink);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.tab-badge {
    padding: 3px 8px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 600;
    color: white;
}

.tab-badge.warning { background: #f59e0b; }
.tab-badge.success { background: #10b981; }
.tab-badge.danger { background: #ef4444; }
.tab-badge.info { background: #06b6d4; }

.tabs-content {
    padding: 2rem;
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Table Styles */
.data-table {
    background: var(--card-bg);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.data-table table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: var(--light-bg);
    border-bottom: 2px solid var(--border-color);
}

.data-table th {
    padding: 16px;
    text-align: left;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    color: var(--text-secondary);
}

.data-table td {
    padding: 16px;
    font-size: 14px;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-color);
    vertical-align: middle;
}

.data-table tbody tr {
    transition: all 0.2s;
}

.data-table tbody tr:hover {
    background: var(--hover-bg);
}

.data-table tbody tr:last-child td {
    border-bottom: none;
}

/* Customer Info */
.customer-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ec4899, #be185d);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    flex-shrink: 0;
}

.customer-name {
    font-weight: 600;
    color: var(--text-primary);
}

.customer-email {
    font-size: 12px;
    color: var(--text-secondary);
}

/* Product Display */
.product-display {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.product-thumbnail {
    width: 50px;
    height: 50px;
    border-radius: 8px;
    object-fit: cover;
    border: 1px solid var(--border-color);
    flex-shrink: 0;
}

.product-name {
    font-weight: 500;
    color: var(--text-primary);
}

.product-category {
    font-size: 12px;
    color: var(--text-secondary);
}

/* Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
}

.status-badge.pending {
    background: #fef3c7;
    color: #d97706;
}

.status-badge.approved {
    background: #d1fae5;
    color: #059669;
}

.status-badge.rejected {
    background: #fee2e2;
    color: #dc2626;
}

.status-badge.completed {
    background: #cffafe;
    color: #0891b2;
}

.price-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: #f0fdf4;
    color: #15803d;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
}

.price-badge.admin-price {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-sm {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
}

.btn-info {
    background: #06b6d4;
    color: white;
}

.btn-info:hover {
    background: #0891b2;
    transform: translateY(-1px);
    color: white;
}

.btn-success {
    background: #10b981;
    color: white;
}

.btn-success:hover {
    background: #059669;
    color: white;
}

.btn-danger {
    background: #ef4444;
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    color: white;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1.5rem;
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    border-radius: 50%;
    background: var(--light-bg);
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon i {
    font-size: 32px;
    color: var(--text-secondary);
}

.empty-state h3 {
    font-size: 18px;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.empty-state p {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .tabs-nav {
        overflow-x: auto;
    }
    
    .data-table {
        overflow-x: auto;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}

/* Alerts */
.alert {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    border: none;
}

.alert-success {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
    border-left: 4px solid #10b981;
}

.alert-danger {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    border-left: 4px solid #ef4444;
}

.alert i {
    font-size: 20px;
}

.alert strong {
    font-weight: 600;
}
</style>
@endpush

@section('content')
<div class="customization-container">
    <!-- Header -->
    <div class="customization-header">
        <div class="header-content">
            <div class="title-group">
                <div class="icon-wrapper">
                    <i class="fas fa-palette"></i>
                </div>
                <div>
                    <h1 class="page-title">Customization Management</h1>
                    <p class="page-subtitle">Review and manage customer customization requests</p>
                </div>
            </div>
            <div class="header-actions">
                <button class="btn-action btn-secondary" onclick="location.reload()">
                    <i class="fas fa-sync-alt"></i>
                    <span>Refresh</span>
                </button>
               
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <i class="fas fa-check-circle"></i>
        <div>
            <strong>Success!</strong> {{ session('success') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <i class="fas fa-exclamation-circle"></i>
        <div>
            <strong>Error!</strong> {{ session('error') }}
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card stat-warning">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-number">{{ $customizations->where('status', 'Pending')->count() }}</div>
            <div class="stat-label">Pending Review</div>
        </div>

        <div class="stat-card stat-success">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>
            <div class="stat-number">{{ $customizations->where('status', 'Approved')->count() }}</div>
            <div class="stat-label">Approved Requests</div>
        </div>

        <div class="stat-card stat-danger">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
            </div>
            <div class="stat-number">{{ $customizations->where('status', 'Rejected')->count() }}</div>
            <div class="stat-label">Rejected Requests</div>
        </div>

        <div class="stat-card stat-info">
            <div class="stat-header">
                <div class="stat-icon">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>
            <div class="stat-number">{{ $customizations->where('status', 'Completed')->count() }}</div>
            <div class="stat-label">Completed Orders</div>
        </div>
    </div>

    <!-- Tabs Container -->
    <div class="tabs-container">
        <nav class="tabs-nav">
            <button class="tab-button active" data-tab="pending">
                <i class="fas fa-clock"></i>
                <span>Pending</span>
                @if($customizations->where('status', 'Pending')->count() > 0)
                <span class="tab-badge warning">{{ $customizations->where('status', 'Pending')->count() }}</span>
                @endif
            </button>
            <button class="tab-button" data-tab="approved">
                <i class="fas fa-check-circle"></i>
                <span>Approved</span>
                @if($customizations->where('status', 'Approved')->count() > 0)
                <span class="tab-badge success">{{ $customizations->where('status', 'Approved')->count() }}</span>
                @endif
            </button>
            <button class="tab-button" data-tab="rejected">
                <i class="fas fa-times-circle"></i>
                <span>Rejected</span>
                @if($customizations->where('status', 'Rejected')->count() > 0)
                <span class="tab-badge danger">{{ $customizations->where('status', 'Rejected')->count() }}</span>
                @endif
            </button>
            <button class="tab-button" data-tab="completed">
                <i class="fas fa-check-double"></i>
                <span>Completed</span>
                @if($customizations->where('status', 'Completed')->count() > 0)
                <span class="tab-badge info">{{ $customizations->where('status', 'Completed')->count() }}</span>
                @endif
            </button>
        </nav>

        <div class="tabs-content">
            @foreach(['Pending' => 'pending', 'Approved' => 'approved', 'Rejected' => 'rejected', 'Completed' => 'completed'] as $status => $tabId)
            <div class="tab-pane {{ $tabId === 'pending' ? 'active' : '' }}" data-content="{{ $tabId }}">
                @if($customizations->where('status', $status)->count() > 0)
                <div class="data-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Customer</th>
                                <th>Product</th>
                                <th>Customization Name</th>
                                <!-- <th>Base Price</th> -->
                                <th>Admin Price</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customizations->where('status', $status) as $customization)
                            <tr>
                                <td><strong>#{{ $customization->id }}</strong></td>
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-avatar">
                                            {{ substr($customization->user->name ?? 'N', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="customer-name">{{ $customization->user->name ?? 'N/A' }}</div>
                                            <div class="customer-email">{{ $customization->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($customization->product)
                                    <div class="product-display">
                                        <img src="{{ asset('asset/images/' . $customization->product->image) }}" 
                                             alt="{{ $customization->product->name }}" 
                                             class="product-thumbnail">
                                        <div>
                                            <div class="product-name">{{ $customization->product->name }}</div>
                                            <div class="product-category">{{ $customization->product->category->name ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $customization->customization_name }}</strong>
                                </td>
                                <td>
                                    <span class="price-badge">
                                        ₱{{ number_format($customization->total_price, 2) }}
                                    </span>
                                </td>
                                <td>
                                    @if($customization->admin_price)
                                    <span class="price-badge admin-price">
                                        <i class="fas fa-star"></i>
                                        ₱{{ number_format($customization->admin_price, 2) }}
                                    </span>
                                    @else
                                    <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>{{ $customization->created_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="status-badge {{ strtolower($status) }}">
                                        @if($status === 'Pending')
                                        <i class="fas fa-clock"></i>
                                        @elseif($status === 'Approved')
                                        <i class="fas fa-check-circle"></i>
                                        @elseif($status === 'Rejected')
                                        <i class="fas fa-times-circle"></i>
                                        @else
                                        <i class="fas fa-check-double"></i>
                                        @endif
                                        {{ $status }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.customizations.show', $customization->id) }}" class="btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3>No {{ $status }} Customizations</h3>
                    <p>There are currently no {{ strtolower($status) }} customization requests.</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Tab switching functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tab = button.dataset.tab;
        
        // Remove active class from all buttons and panes
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
        
        // Add active class to clicked button and corresponding pane
        button.classList.add('active');
        document.querySelector(`[data-content="${tab}"]`).classList.add('active');
    });
});

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});
</script>
@endpush