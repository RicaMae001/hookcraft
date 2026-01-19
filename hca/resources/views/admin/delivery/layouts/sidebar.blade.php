<!-- Sidebar -->
<nav class="col-md-2 d-md-block sidebar collapse">
    <div class="position-sticky pt-3">
        <div class="text-center mb-4">
            <i class="fas fa-user-circle fa-3x text-primary"></i>
            <p class="mt-2 mb-0"><strong>{{ session('coordinator_name') }}</strong></p>
            <small class="text-muted">Delivery Coordinator</small>
        </div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}" 
                   href="{{ route('delivery.dashboard') }}">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('delivery.deliveries') ? 'active' : '' }}" 
                   href="{{ route('delivery.deliveries') }}">
                    <i class="fas fa-box me-2"></i>My Deliveries
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('delivery.livechat.*') ? 'active' : '' }}" 
                   href="{{ route('delivery.livechat.index') }}">
                    <i class="fas fa-comments me-2"></i>Live Chat Support
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('delivery.history') ? 'active' : '' }}" 
                   href="{{ route('delivery.history') }}">
                    <i class="fas fa-history me-2"></i>Delivery History
                </a>
            </li>
        </ul>
    </div>
</nav>