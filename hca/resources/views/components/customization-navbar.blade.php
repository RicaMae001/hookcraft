@php
    $isLoggedIn = Auth::check();
    $user = Auth::user();
    $cartCount = $cartCount ?? 0;
@endphp

<nav class="customization-navbar sticky-top">
    <div class="container">
        <!-- Logo & Brand -->
        <a class="nav-brand" href="{{ route('home') }}">
            <img src="{{ asset('asset/images/logo.jpg') }}" alt="Logo" width="40" height="40" class="brand-logo">
            <span class="brand-name">HookcraftAvenue</span>
        </a>

        <!-- Desktop Navigation -->
        <div class="nav-links">
            <a href="{{ route('home') }}" class="nav-item">
                <i class="bi bi-house-fill"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('customization.create') }}" class="nav-item">
                <i class="bi bi-palette-fill"></i>
                <span>Customize</span>
            </a>
            <a href="{{ route('customization.my-customizations') }}" class="nav-item">
                <i class="bi bi-grid-fill"></i>
                <span>My Designs</span>
            </a>
            <a href="{{ route('shop') }}" class="nav-item">
                <i class="bi bi-shop"></i>
                <span>Shop</span>
            </a>
        </div>

        <!-- Right Side Actions -->
        <div class="nav-actions">
            <!-- Cart -->
            <!-- <a href="{{ route('cart.index') }}" class="action-btn" title="Cart">
                <i class="bi bi-cart3"></i>
                @if($cartCount > 0)
                    <span class="action-badge">{{ $cartCount }}</span>
                @endif
            </a> -->
           <a href="{{ route('cart.index') }}" class="action-btn" title="Cart">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
    </svg>
    @if($cartCount > 0)
        <span class="action-badge">{{ $cartCount }}</span>
    @endif
</a>

            @if($isLoggedIn)
                <!-- User Profile Dropdown -->
                <div class="user-dropdown">
                    <button class="user-btn" id="userDropdownBtn">
                        <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('asset/images/default-profile.png') }}" 
                             alt="Profile" class="user-avatar">
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="dropdown-menu" id="userDropdownMenu" style="display: none;">
                        <div class="dropdown-header">
                            <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('asset/images/default-profile.png') }}" 
                                 alt="Profile" class="dropdown-avatar">
                            <div class="dropdown-info">
                                <div class="dropdown-name">{{ $user->name }}</div>
                                <div class="dropdown-email">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="bi bi-person-circle"></i>
                            <span>My Account</span>
                        </a>
                        <a href="{{ route('profile.purchase-history') }}" class="dropdown-item">
                            <i class="bi bi-clock-history"></i>
                            <span>Purchase History</span>
                        </a>
                        <a href="{{ route('profile.track-order') }}" class="dropdown-item">
                            <i class="bi bi-truck"></i>
                            <span>Track Order</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item logout-item">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="login-btn">
                    <i class="bi bi-person-circle"></i>
                    <span>Login</span>
                </a>
            @endif
        </div>

        <!-- Mobile Menu Button -->
        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu" id="mobileMenu">
        <div class="mobile-menu-header">
            <div class="mobile-brand">
                <img src="{{ asset('asset/images/logo.jpg') }}" alt="Logo" width="35" height="35" class="mobile-logo">
                <span class="mobile-brand-name">HookcraftAvenue</span>
            </div>
            <button class="mobile-close-btn" id="mobileCloseBtn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        @if($isLoggedIn)
            <!-- Mobile User Profile -->
            <div class="mobile-user-profile">
                <img src="{{ $user->profile_image ? asset('storage/'.$user->profile_image) : asset('asset/images/default-profile.png') }}" 
                     alt="Profile" class="mobile-user-avatar">
                <div class="mobile-user-info">
                    <div class="mobile-user-name">{{ $user->name }}</div>
                    <div class="mobile-user-email">{{ $user->email }}</div>
                </div>
            </div>
        @endif

        <!-- Mobile Navigation Links -->
        <div class="mobile-nav-links">
            <a href="{{ route('home') }}" class="mobile-nav-item">
                <i class="bi bi-house-fill"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('customization.create') }}" class="mobile-nav-item">
                <i class="bi bi-palette-fill"></i>
                <span>Customize</span>
            </a>
            <a href="{{ route('customization.my-customizations') }}" class="mobile-nav-item active">
                <i class="bi bi-grid-fill"></i>
                <span>My Designs</span>
            </a>
            <a href="{{ route('shop') }}" class="mobile-nav-item">
                <i class="bi bi-shop"></i>
                <span>Shop</span>
            </a>
            <a href="{{ route('cart.index') }}" class="mobile-nav-item">
                <i class="bi bi-cart3"  style="color: black !important;"></i>
                <span>Cart</span>
                @if($cartCount > 0)
                    <span class="mobile-badge">{{ $cartCount }}</span>
                @endif
            </a>
        </div>

        @if($isLoggedIn)
            <!-- Mobile Account Links -->
            <div class="mobile-account-links">
                <div class="mobile-section-title">Account</div>
                <a href="{{ route('profile.index') }}" class="mobile-account-item">
                    <i class="bi bi-person-circle"></i>
                    <span>My Account</span>
                </a>
                <a href="{{ route('profile.purchase-history') }}" class="mobile-account-item">
                    <i class="bi bi-clock-history"></i>
                    <span>Purchase History</span>
                </a>
                <a href="{{ route('profile.track-order') }}" class="mobile-account-item">
                    <i class="bi bi-truck"></i>
                    <span>Track Order</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-account-item logout-mobile">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        @else
            <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" class="mobile-login-btn">
                <i class="bi bi-person-circle"></i>
                <span>Login to Your Account</span>
            </a>
        @endif
    </div>
</nav>

<style>
/* ============================================
   CUSTOMIZATION NAVBAR STYLES
   ============================================ */

.customization-navbar {
    background: linear-gradient(135deg, #ffffff 0%, #fff5f8 100%);
    box-shadow: 0 2px 20px rgba(255, 105, 180, 0.1);
    backdrop-filter: blur(10px);
    padding: 0.875rem 0;
    position: sticky;
    top: 0;
    z-index: 1000;
}

.customization-navbar .container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}

/* Brand/Logo */
.nav-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.nav-brand:hover {
    transform: scale(1.05);
}

.brand-logo {
    border-radius: 50%;
    border: 2px solid #FFB6C1;
    box-shadow: 0 2px 10px rgba(255, 105, 180, 0.2);
    transition: all 0.3s ease;
}

.nav-brand:hover .brand-logo {
    border-color: #FF69B4;
    box-shadow: 0 4px 15px rgba(255, 105, 180, 0.4);
}

.brand-name {
    font-size: 1.35rem;
    font-weight: 800;
    background: linear-gradient(135deg, #FF69B4 0%, #FF1744 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Navigation Links */
.nav-links {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    border-radius: 10px;
    color: #555;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    position: relative;
}

.nav-item i {
    font-size: 1.1rem;
    color: #FF69B4;
}

.nav-item:hover {
    background: rgba(255, 105, 180, 0.1);
    color: #FF1744;
    transform: translateY(-2px);
}

.nav-item:hover i {
    color: #FF1744;
}

.nav-item.active {
    background: linear-gradient(135deg, #FF1744 0%, #FF69B4 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(255, 23, 68, 0.3);
}

.nav-item.active i {
    color: white;
}

/* Action Buttons */
.nav-actions {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.action-btn {
    position: relative;
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    border-radius: 50%;
    border: 2px solid #FFE4EF;
    color: #FF1744;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.action-btn:hover {
    background: linear-gradient(135deg, #FF1744 0%, #FF69B4 100%);
    color: white;
    border-color: #FF1744;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 23, 68, 0.3);
}

.action-btn i {
    font-size: 1.2rem;
}

.action-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #FF1744;
    color: white;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 10px;
    min-width: 18px;
    text-align: center;
    border: 2px solid white;
}

/* User Dropdown */
.user-dropdown {
    position: relative;
}

.user-btn {
    width: 44px;
    height: 44px;
    padding: 0;
    border: none;
    background: none;
    cursor: pointer;
}

.user-avatar {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    border: 3px solid #FFB6C1;
    transition: all 0.3s ease;
    object-fit: cover;
}

.user-btn:hover .user-avatar {
    border-color: #FF1744;
    box-shadow: 0 0 15px rgba(255, 23, 68, 0.4);
}

/* Dropdown Menu */
.dropdown-menu {
    position: absolute;
    top: calc(100% + 12px);
    right: 0;
    width: 280px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    border: 1px solid #E8E8E8;
    overflow: hidden;
    z-index: 1100;
}

.dropdown-header {
    padding: 1.25rem;
    background: linear-gradient(135deg, #FF1744 0%, #FF69B4 100%);
    display: flex;
    align-items: center;
    gap: 1rem;
}

.dropdown-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    border: 3px solid white;
    object-fit: cover;
}

.dropdown-info {
    flex: 1;
    min-width: 0;
}

.dropdown-name {
    font-weight: 700;
    font-size: 1rem;
    color: white;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dropdown-email {
    font-size: 0.8rem;
    color: rgba(255, 255, 255, 0.9);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.dropdown-divider {
    height: 1px;
    background: #F0F0F0;
    margin: 0;
}

.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.875rem 1.25rem;
    color: #555;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    background: none;
    width: 100%;
    cursor: pointer;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
}

.dropdown-item:hover {
    background: linear-gradient(90deg, #FFF5F8 0%, #ffffff 100%);
    border-left-color: #FF1744;
    color: #FF1744;
}

.dropdown-item i {
    font-size: 1.1rem;
    color: #FF69B4;
}

.dropdown-item:hover i {
    color: #FF1744;
}

.logout-item {
    color: #dc3545;
}

.logout-item:hover {
    background: linear-gradient(90deg, #FFEBEE 0%, #ffffff 100%);
    border-left-color: #dc3545;
}

.logout-item i {
    color: #dc3545;
}

/* Login Button */
.login-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1.25rem;
    background: linear-gradient(135deg, #FF1744 0%, #FF69B4 100%);
    color: white;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(255, 23, 68, 0.3);
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 23, 68, 0.4);
    color: white;
}

.login-btn i {
    font-size: 1.1rem;
}

/* Mobile Menu Button */
.mobile-menu-btn {
    display: none;
    flex-direction: column;
    justify-content: space-between;
    width: 32px;
    height: 24px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
}

.hamburger-line {
    width: 100%;
    height: 3px;
    background: linear-gradient(90deg, #FF1744 0%, #FF69B4 100%);
    border-radius: 2px;
    transition: all 0.3s ease;
}

.mobile-menu-btn.active .hamburger-line:nth-child(1) {
    transform: translateY(10.5px) rotate(45deg);
}

.mobile-menu-btn.active .hamburger-line:nth-child(2) {
    opacity: 0;
}

.mobile-menu-btn.active .hamburger-line:nth-child(3) {
    transform: translateY(-10.5px) rotate(-45deg);
}

/* Mobile Menu */
.mobile-menu {
    position: fixed;
    top: 0;
    right: -100%;
    width: 85%;
    max-width: 360px;
    height: 100vh;
    background: white;
    box-shadow: -8px 0 30px rgba(0, 0, 0, 0.3);
    transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
    z-index: 2000;
}

.mobile-menu.active {
    right: 0;
}

.mobile-menu::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    z-index: -1;
}

.mobile-menu.active::before {
    opacity: 1;
    pointer-events: auto;
}

.mobile-menu-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(135deg, #FF1744 0%, #FF69B4 100%);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.mobile-brand {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.mobile-logo {
    border-radius: 50%;
    border: 2px solid white;
}

.mobile-brand-name {
    font-size: 1.1rem;
    font-weight: 800;
    color: white;
}

.mobile-close-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.mobile-close-btn:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

.mobile-close-btn i {
    font-size: 1.2rem;
}

/* Mobile User Profile */
.mobile-user-profile {
    padding: 1.5rem;
    background: linear-gradient(135deg, #FFF5F8 0%, white 100%);
    border-bottom: 1px solid #F0F0F0;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.mobile-user-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: 3px solid #FF69B4;
    object-fit: cover;
}

.mobile-user-info {
    flex: 1;
    min-width: 0;
}

.mobile-user-name {
    font-weight: 700;
    font-size: 1.05rem;
    color: #1F1F1F;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.mobile-user-email {
    font-size: 0.85rem;
    color: #6B6B6B;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Mobile Navigation Links */
.mobile-nav-links {
    padding: 1.25rem;
}

.mobile-nav-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.875rem 1rem;
    background: #FAFAFA;
    border-radius: 12px;
    color: #555;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    position: relative;
}

.mobile-nav-item i {
    font-size: 1.2rem;
    color: #FF69B4;
}

.mobile-nav-item:hover,
.mobile-nav-item.active {
    background: linear-gradient(135deg, #FF1744 0%, #FF69B4 100%);
    color: white;
    border-left-color: #FF1744;
    transform: translateX(5px);
}

.mobile-nav-item:hover i,
.mobile-nav-item.active i {
    color: white;
}

.mobile-badge {
    margin-left: auto;
    background: #FF1744;
    color: white;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 12px;
    min-width: 22px;
    text-align: center;
}

/* Mobile Account Links */
.mobile-account-links {
    padding: 0 1.25rem 1.25rem;
    border-top: 1px solid #F0F0F0;
}

.mobile-section-title {
    font-size: 0.8rem;
    font-weight: 700;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
    padding: 1rem 0 0.5rem;
}

.mobile-account-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.875rem 1rem;
    background: #FAFAFA;
    border-radius: 12px;
    color: #555;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    border-left: 3px solid transparent;
    border: none;
    width: 100%;
    cursor: pointer;
}

.mobile-account-item i {
    font-size: 1.2rem;
    color: #FF69B4;
}

.mobile-account-item:hover {
    background: linear-gradient(90deg, #FFF5F8 0%, #FAFAFA 100%);
    color: #FF1744;
    border-left-color: #FF1744;
    transform: translateX(5px);
}

.mobile-account-item:hover i {
    color: #FF1744;
}

.logout-mobile {
    color: #dc3545;
}

.logout-mobile i {
    color: #dc3545;
}

.logout-mobile:hover {
    background: linear-gradient(90deg, #FFEBEE 0%, #FAFAFA 100%);
    border-left-color: #dc3545;
}

.mobile-login-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    margin: 1.25rem;
    background: linear-gradient(135deg, #FF1744 0%, #FF69B4 100%);
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    font-size: 1rem;
    box-shadow: 0 4px 12px rgba(255, 23, 68, 0.3);
}

.mobile-login-btn i {
    font-size: 1.3rem;
}

/* Responsive Breakpoints */
@media (max-width: 1024px) {
    .nav-links {
        gap: 0.25rem;
    }

    .nav-item {
        padding: 0.5rem 0.875rem;
        font-size: 0.9rem;
    }
}

@media (max-width: 768px) {
    .customization-navbar .container {
        padding: 0 20px;
    }

    .brand-name {
        font-size: 1.2rem;
    }

    .brand-logo {
        width: 38px;
        height: 38px;
    }

    .nav-links,
    .nav-actions {
        display: none;
    }

    .mobile-menu-btn {
        display: flex;
    }
}

@media (max-width: 480px) {
    .customization-navbar .container {
        padding: 0 16px;
    }

    .brand-name {
        font-size: 1.1rem;
    }

    .brand-logo {
        width: 35px;
        height: 35px;
    }

    .mobile-menu {
        width: 90%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // User Dropdown Toggle
    const userBtn = document.getElementById('userDropdownBtn');
    const userMenu = document.getElementById('userDropdownMenu');
    
    if (userBtn && userMenu) {
        userBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.style.display = userMenu.style.display === 'block' ? 'none' : 'block';
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userBtn.contains(e.target) && !userMenu.contains(e.target)) {
                userMenu.style.display = 'none';
            }
        });
    }

    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileCloseBtn = document.getElementById('mobileCloseBtn');

    function openMobileMenu() {
        mobileMenu.classList.add('active');
        mobileMenuBtn.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileMenu() {
        mobileMenu.classList.remove('active');
        mobileMenuBtn.classList.remove('active');
        document.body.style.overflow = '';
    }

    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (mobileMenu.classList.contains('active')) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
    }

    if (mobileCloseBtn) {
        mobileCloseBtn.addEventListener('click', closeMobileMenu);
    }

    // Close mobile menu when clicking on overlay
    if (mobileMenu) {
        mobileMenu.addEventListener('click', function(e) {
            if (e.target === mobileMenu) {
                closeMobileMenu();
            }
        });
    }

    // Close mobile menu when clicking on a link
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-item, .mobile-account-item');
    mobileNavLinks.forEach(link => {
        link.addEventListener('click', function() {
            closeMobileMenu();
        });
    });
});
</script>