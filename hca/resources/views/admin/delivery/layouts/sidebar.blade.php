<!-- Modern Sidebar -->
<aside class="modern-sidebar" id="sidebar">
    <div class="sidebar-logo">
        <h2><i class="fas fa-truck-fast"></i> HCA Delivery</h2>
        <p>Coordinator Dashboard</p>
    </div>

    <!-- User Profile in Sidebar -->
    <div class="sidebar-section">
        <div class="text-center mb-3" style="padding: 0 1rem;">
            <div class="user-avatar" style="width: 60px; height: 60px; margin: 0 auto 1rem; font-size: 1.5rem;">
                {{ strtoupper(substr(session('coordinator_name', 'D'), 0, 1)) }}
            </div>
            <h6 style="color: var(--text-primary); font-weight: 600; margin-bottom: 0.25rem;">
                {{ session('coordinator_name') ?? 'Delivery Coordinator' }}
            </h6>
            <small style="color: var(--text-secondary);">Delivery Coordinator</small>
        </div>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Main Menu</div>
        
        <a href="{{ route('delivery.dashboard') }}" class="sidebar-link {{ request()->routeIs('delivery.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('delivery.deliveries') }}" class="sidebar-link {{ request()->routeIs('delivery.deliveries') ? 'active' : '' }}">
            <i class="fas fa-box"></i>
            <span>My Deliveries</span>
        </a>

        <a href="{{ route('delivery.livechat.index') }}" class="sidebar-link {{ request()->routeIs('delivery.livechat.*') ? 'active' : '' }}">
            <i class="fas fa-comments"></i>
            <span>Live Chat Support</span>
        </a>

        <a href="{{ route('delivery.history') }}" class="sidebar-link {{ request()->routeIs('delivery.history') ? 'active' : '' }}">
            <i class="fas fa-history"></i>
            <span>Delivery History</span>
        </a>
    </div>

    <div class="sidebar-section">
        <div class="sidebar-section-title">Account</div>

        <a href="#" class="sidebar-link text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

<style>
/* Sidebar Styles */
:root {
    --primary-blue: #667eea;
    --primary-purple: #764ba2;
    --primary-dark: #2D3748;
    --success: #48BB78;
    --warning: #F6AD55;
    --danger: #FC8181;
    --info: #63B3ED;
    --light-bg: #F7FAFC;
    --card-bg: #FFFFFF;
    --text-primary: #1A202C;
    --text-secondary: #718096;
    --border-color: #E2E8F0;
    --sidebar-width: 280px;
    --hover-bg: rgba(0, 0, 0, 0.05);
}

[data-theme="dark"] {
    --primary-blue: #667eea;
    --primary-purple: #764ba2;
    --primary-dark: #1A202C;
    --success: #48BB78;
    --warning: #F6AD55;
    --danger: #FC8181;
    --info: #63B3ED;
    --light-bg: #1A202C;
    --card-bg: #2D3748;
    --text-primary: #F7FAFC;
    --text-secondary: #A0AEC0;
    --border-color: #4A5568;
    --hover-bg: rgba(255, 255, 255, 0.05);
}

.modern-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: var(--sidebar-width);
    height: 100vh;
    background: var(--card-bg);
    border-right: 1px solid var(--border-color);
    padding: 2rem 0;
    overflow-y: auto;
    z-index: 1000;
    transition: all 0.3s ease;
}

.sidebar-logo {
    padding: 0 2rem 2rem;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 2rem;
}

.sidebar-logo h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0;
}

.sidebar-logo p {
    color: var(--text-secondary);
    font-size: 0.875rem;
    margin: 0.25rem 0 0 0;
}

.sidebar-section {
    padding: 0 1rem;
    margin-bottom: 2rem;
}

.sidebar-section-title {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-secondary);
    font-weight: 600;
    padding: 0 1rem;
    margin-bottom: 0.75rem;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.875rem 1.25rem;
    color: var(--text-primary);
    text-decoration: none;
    border-radius: 12px;
    margin-bottom: 0.25rem;
    transition: all 0.2s ease;
    font-weight: 500;
    position: relative;
}

.sidebar-link i {
    width: 20px;
    text-align: center;
    font-size: 1.125rem;
}

.sidebar-link:hover {
    background: var(--hover-bg);
    color: var(--primary-blue);
    transform: translateX(4px);
}

.sidebar-link.active {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.15), rgba(118, 75, 162, 0.15));
    color: var(--primary-blue);
    font-weight: 600;
}

.sidebar-link.active::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 60%;
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
    border-radius: 0 4px 4px 0;
}

.user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: linear-gradient(135deg, var(--primary-blue), var(--primary-purple));
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 14px;
}

/* Responsive Sidebar */
@media (max-width: 768px) {
    .modern-sidebar {
        transform: translateX(-100%);
    }

    .modern-sidebar.show {
        transform: translateX(0);
    }
    
    /* Mobile toggle button */
    .sidebar-toggle-btn {
        position: fixed;
        top: 20px;
        left: 20px;
        width: 40px;
        height: 40px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1001;
        transition: all 0.3s ease;
    }
    
    .sidebar-toggle-btn:hover {
        background: var(--hover-bg);
    }
    
    .sidebar-toggle-btn i {
        font-size: 1.25rem;
        color: var(--text-primary);
    }
    
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        display: none;
    }
    
    .sidebar-overlay.show {
        display: block;
    }
}

/* Scrollbar */
.sidebar-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.sidebar-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-scrollbar::-webkit-scrollbar-thumb {
    background: var(--border-color);
    border-radius: 3px;
}

.sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
    background: var(--text-secondary);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar toggle functionality
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Create mobile toggle button if it doesn't exist
    if (window.innerWidth <= 768 && !document.getElementById('sidebarToggle')) {
        const toggleBtn = document.createElement('button');
        toggleBtn.id = 'sidebarToggle';
        toggleBtn.className = 'sidebar-toggle-btn';
        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
        toggleBtn.title = 'Toggle Sidebar';
        
        const overlay = document.createElement('div');
        overlay.id = 'sidebarOverlay';
        overlay.className = 'sidebar-overlay';
        
        document.body.appendChild(toggleBtn);
        document.body.appendChild(overlay);
        
        // Toggle sidebar
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            toggleBtn.style.left = sidebar.classList.contains('show') ? '300px' : '20px';
        });
        
        // Close sidebar when clicking overlay
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            toggleBtn.style.left = '20px';
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth <= 768 && 
                !sidebar.contains(e.target) && 
                !toggleBtn.contains(e.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                toggleBtn.style.left = '20px';
            }
        });
    }
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            // On desktop, ensure sidebar is visible
            sidebar.classList.remove('show');
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('show');
            }
            if (sidebarToggle) {
                sidebarToggle.style.display = 'none';
            }
        } else {
            // On mobile, add toggle button if not exists
            if (!document.getElementById('sidebarToggle')) {
                const toggleBtn = document.createElement('button');
                toggleBtn.id = 'sidebarToggle';
                toggleBtn.className = 'sidebar-toggle-btn';
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.title = 'Toggle Sidebar';
                toggleBtn.style.display = 'block';
                
                const overlay = document.createElement('div');
                overlay.id = 'sidebarOverlay';
                overlay.className = 'sidebar-overlay';
                
                document.body.appendChild(toggleBtn);
                document.body.appendChild(overlay);
                
                toggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                    toggleBtn.style.left = sidebar.classList.contains('show') ? '300px' : '20px';
                });
                
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    toggleBtn.style.left = '20px';
                });
            }
        }
    });
    
    // Initialize based on current screen size
    if (window.innerWidth <= 768 && !document.getElementById('sidebarToggle')) {
        const toggleBtn = document.createElement('button');
        toggleBtn.id = 'sidebarToggle';
        toggleBtn.className = 'sidebar-toggle-btn';
        toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
        toggleBtn.title = 'Toggle Sidebar';
        toggleBtn.style.display = 'block';
        
        const overlay = document.createElement('div');
        overlay.id = 'sidebarOverlay';
        overlay.className = 'sidebar-overlay';
        
        document.body.appendChild(toggleBtn);
        document.body.appendChild(overlay);
        
        toggleBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            toggleBtn.style.left = sidebar.classList.contains('show') ? '300px' : '20px';
        });
        
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
            toggleBtn.style.left = '20px';
        });
    }
    
    // Add scrollbar class
    sidebar.classList.add('sidebar-scrollbar');
});
</script>