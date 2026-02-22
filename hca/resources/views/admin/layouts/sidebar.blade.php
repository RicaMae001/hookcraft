<nav id="sidebarMenu" class="col-md-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}" href="{{ route('admin.orders') }}">
                    <i class="fas fa-shopping-cart me-2"></i>Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}" href="{{ route('admin.products') }}">
                    <i class="fas fa-box me-2"></i>Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}" href="{{ route('admin.users') }}">
                    <i class="fas fa-users me-2"></i>Users
                </a>
            </li>
            <!-- Gallery Management -->
              <!-- In sidebar.blade.php - Add this menu item -->

            <!-- Live Chat -->
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.livechat.*') ? 'active' : '' }}" href="{{ route('admin.livechat.index') }}">
                    <i class="fas fa-comments me-2"></i>Live Chat
                    <span class="badge bg-warning ms-2" id="waitingBadge" style="display: none;"></span>
                </a>
            </li>
       
        </ul>

        <!-- Staff Management Section with Restrictions -->
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 {{ session('admin_role') === 'Staff' ? 'text-danger' : 'text-muted' }}">
            <span>
                <i class="fas fa-users-cog me-1"></i>Staff Management
            </span>
            @if(session('admin_role') === 'Staff')
                <span class="badge bg-danger">Admin Only</span>
            @endif
        </h6>
        <ul class="nav flex-column mb-2 {{ session('admin_role') === 'Staff' ? 'restricted-section' : '' }}">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.staff.admins') ? 'active' : '' }} {{ session('admin_role') === 'Staff' ? 'restricted-link' : '' }}" 
                   href="{{ session('admin_role') === 'Admin' ? route('admin.staff.admins') : '#' }}"
                   @if(session('admin_role') === 'Staff') onclick="showAccessDeniedModal(event, 'Admin Accounts')" @endif>
                    <i class="fas fa-user-shield me-2"></i>Admin Accounts
                    @if(session('admin_role') === 'Staff')
                        <i class="fas fa-lock text-danger ms-auto"></i>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.staff.delivery') ? 'active' : '' }} {{ session('admin_role') === 'Staff' ? 'restricted-link' : '' }}" 
                   href="{{ session('admin_role') === 'Admin' ? route('admin.staff.delivery') : '#' }}"
                   @if(session('admin_role') === 'Staff') onclick="showAccessDeniedModal(event, 'Delivery Staff')" @endif>
                    <i class="fas fa-truck me-2"></i>Delivery Staff
                    @if(session('admin_role') === 'Staff')
                        <i class="fas fa-lock text-danger ms-auto"></i>
                    @endif
                </a>
            </li>
        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
            <span>Quick Actions</span>
        </h6>
        <ul class="nav flex-column mb-2">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i>View Store
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('gallery') }}" target="_blank">
                    <i class="fas fa-eye me-2"></i>View Gallery
                </a>
            </li>
        </ul>
    </div>
</nav>

<!-- Access Denied Modal -->
<div class="modal fade" id="accessDeniedModal" tabindex="-1" aria-labelledby="accessDeniedModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="accessDeniedModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Access Denied
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-5">
                <div class="access-denied-icon mb-4">
                    <i class="fas fa-lock fa-4x text-danger"></i>
                </div>
                <h5 class="mb-3 fw-bold">You don't have permission to access <span id="sectionName" class="text-danger"></span></h5>
                <p class="text-muted mb-2">
                    <i class="fas fa-info-circle me-1"></i>
                    Only <strong>Admin</strong> can access this section.
                </p>
                <p class="text-muted mb-0">
                    Please contact your administrator if you need access.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
}

.sidebar .nav-link {
    font-weight: 500;
    color: #333;
    padding: 0.75rem 1rem;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.sidebar .nav-link.active {
    color: #fff;
    background-color: #FFB6C1;
}

.sidebar .nav-link:hover {
    color: #FFB6C1;
    background-color: rgba(255, 182, 193, 0.1);
}

.sidebar-heading {
    font-size: .75rem;
    text-transform: uppercase;
    font-weight: 600;
}

/* Restricted Section Styles */
.restricted-section {
    background: rgba(220, 53, 69, 0.05);
    border-left: 3px solid #dc3545;
    padding: 0.25rem 0;
    margin: 0 0.5rem;
    border-radius: 4px;
}

.restricted-link {
    color: #dc3545 !important;
    cursor: not-allowed !important;
    opacity: 0.8;
    position: relative;
}

.restricted-link:hover {
    background-color: rgba(220, 53, 69, 0.1) !important;
    color: #dc3545 !important;
}

.restricted-link .fa-lock {
    font-size: 0.875rem;
    margin-left: auto;
}

.sidebar-heading.text-danger {
    font-weight: 700;
    animation: pulse-red 2s infinite;
}

@keyframes pulse-red {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.badge.bg-danger {
    font-size: 0.65rem;
    padding: 0.25rem 0.5rem;
    font-weight: 600;
}

/* Modal Animations */
.modal-content {
    border-radius: 15px;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.access-denied-icon {
    animation: shake 0.6s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
    20%, 40%, 60%, 80% { transform: translateX(10px); }
}

.modal-header.bg-danger {
    border-radius: 15px 15px 0 0;
}

/* Tooltip for restricted items */
.restricted-link::after {
    content: "Admin access required";
    position: absolute;
    right: -150px;
    top: 50%;
    transform: translateY(-50%);
    background: #dc3545;
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.75rem;
    white-space: nowrap;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
}

.restricted-link:hover::after {
    opacity: 1;
}
</style>

<script>
function showAccessDeniedModal(event, sectionName) {
    event.preventDefault();
    document.getElementById('sectionName').textContent = sectionName;
    
    // Use Bootstrap 5 modal
    var modal = new bootstrap.Modal(document.getElementById('accessDeniedModal'));
    modal.show();
}

// Show modal if error_modal exists in session
@if(session('error_modal'))
document.addEventListener('DOMContentLoaded', function() {
    var modal = new bootstrap.Modal(document.getElementById('accessDeniedModal'));
    document.getElementById('sectionName').textContent = '{{ session('error_modal.title') ?? 'this section' }}';
    modal.show();
});
@endif
</script>