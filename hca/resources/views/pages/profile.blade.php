<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - HookcraftAvenue</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- ADD THIS LINE - Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Font Awesome for other icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">

    <style>
        :root {
            --primary-pink: #FFB6C1;
            --primary-pink-dark: #FF9EAD;
            --gradient-start: #FFB6C1;
            --gradient-end: #FF9EAD;
            --success: #10b981;
            --info: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --light-bg: #f8c2e8;
        }

        body {
            background: linear-gradient(135deg, #e7c9cf 50%, #beb2b2 100%);
            min-height: 100vh;
        }

        /* Profile Card Styles */
        .profile-card {
            border-radius: 0;
            overflow: hidden;
            transition: transform 0.3s ease;
            max-width: 280px;
        }

        .profile-card:hover {
            transform: translateY(-5px);
        }

        .profile-image-wrapper {
            position: relative;
            display: inline-block;
        }

        .profile-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border: 4px solid var(--primary-pink);
            box-shadow: 0 4px 15px rgba(255, 182, 193, 0.3);
        }

        .profile-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--success);
            color: white;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
            font-size: 12px;
        }

        .list-group-item {
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .list-group-item:hover:not(.active) {
            background-color: #fff5f7;
            color: var(--primary-pink);
        }

        .list-group-item.active {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            font-weight: 600;
        }

        /* Main Card Styles */
        .main-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .gradient-header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            padding: 1.5rem;
        }

        /* Section Card Styles */
        .section-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 1.5rem;
        }

        .section-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }

        .section-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, #fff5f7, #ffffff);
            border-bottom: 1px solid #f0f0f0;
        }

        .section-body {
            padding: 1.5rem;
        }

        /* Icon Styles */
        .section-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
            margin-right: 1rem;
        }

        /* Form Styles */
        .form-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            border: 2px solid #f0f0f0;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 3px rgba(255, 182, 193, 0.2);
        }

        /* Button Styles */
        .btn-gradient {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            border: none;
            font-weight: 600;
            transition: all 0.3s ease;
            padding: 0.75rem 2rem;
            border-radius: 8px;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 182, 193, 0.4);
            color: white;
        }

        .btn-action {
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        /* Toast Notifications */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            color: white;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 9999;
            animation: slideIn 0.3s ease;
        }

        .toast-notification.success {
            background: linear-gradient(135deg, #34d399, #10b981);
        }

        .toast-notification.error {
            background: linear-gradient(135deg, #f87171, #ef4444);
        }

        .toast-notification.warning {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .info-item {
            display: flex;
            gap: 0.75rem;
            align-items: start;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .profile-card {
                max-width: 100%;
                margin-bottom: 1.5rem;
            }
            
            .profile-image {
                width: 80px;
                height: 80px;
            }
            
            .list-group-item {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
            
            .section-header,
            .section-body {
                padding: 1rem;
            }
            
            .section-icon {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }
            
            .form-card {
                padding: 1rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-gradient {
                width: 100%;
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
        }

        /* Ultra Mobile Optimization */
        @media (max-width: 480px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .profile-image {
                width: 70px;
                height: 70px;
            }
            
            .gradient-header {
                padding: 1rem;
            }
            
            .gradient-header h5 {
                font-size: 1rem;
            }
            
            .btn-gradient span {
                display: none;
            }
            
            .btn-gradient i {
                font-size: 0.9rem;
            }
            
            .toast-notification {
                left: 10px;
                right: 10px;
                top: 10px;
                text-align: center;
                animation: slideDown 0.3s ease;
            }
            
            @keyframes slideDown {
                from {
                    transform: translateY(-100px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
        }
    </style>
</head>

<body>

@include('components.navbar')

<div class="container py-5">
<div class="row g-4">

<!-- Sidebar -->
<div class="col-lg-3">
    <div class="card shadow-sm border-0 profile-card">
        <div class="card-body text-center p-4">
            <div class="profile-image-wrapper mb-3">
                <img src="{{ asset('asset/images/default-profile.png') }}" alt="User Image" 
                     class="rounded-circle profile-image">
                <div class="profile-badge">
                    <i class="fas fa-check"></i>
                </div>
            </div>
            <h5 class="mb-1 fw-bold">{{ $user->name }}</h5>
            <p class="text-muted small mb-0">{{ $user->email }}</p>
        </div>

        <div class="list-group list-group-flush">
            <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action active border-0 py-3">
                <i class="fas fa-user me-3"></i> My Account
            </a>
            <a href="{{ route('profile.purchase-history') }}" class="list-group-item list-group-item-action border-0 py-3">
                <i class="fas fa-history me-3"></i> Purchase History
            </a>
            <a href="{{ route('profile.track-order') }}" class="list-group-item list-group-item-action border-0 py-3">
                <i class="fas fa-truck me-3"></i> Track Order
            </a>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="col-lg-9">
    <div class="card shadow-sm border-0 main-card">
        <div class="card-header border-0 p-4 gradient-header">
            <h4 class="mb-0 text-black fw-bold">
                <i class="fas fa-user-cog me-2"></i> My Profile
            </h4>
            <p class="mb-0 text-black opacity-75 small mt-1">Manage your account information and security</p>
        </div>
        <div class="card-body p-4">

            <!-- Personal Information Section -->
            <div class="section-card">
                <div class="section-header">
                    <div class="d-flex align-items-center">
                        <div class="section-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold">Personal Information</h6>
                            <p class="text-muted small mb-0">Update your name and email address</p>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-card">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </span>
                                        <input type="text" name="name" class="form-control border-start-0 ps-0"
                                               value="{{ old('name', $user->name) }}" required
                                               placeholder="Enter your full name">
                                    </div>
                                    @error('name')
                                        <small class="text-danger mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email Address</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </span>
                                        <input type="email" name="email" class="form-control border-start-0 ps-0"
                                               value="{{ old('email', $user->email) }}" required
                                               placeholder="Enter your email">
                                    </div>
                                    @error('email')
                                        <small class="text-danger mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-gradient">
                                        <i class="fas fa-save me-2"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Section -->
            <div class="section-card">
                <div class="section-header">
                    <div class="d-flex align-items-center">
                        <div class="section-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold">Change Password</h6>
                            <p class="text-muted small mb-0">Update your password to keep your account secure</p>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-card">
                            <div class="row g-3">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Current Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="fas fa-key text-muted"></i>
                                        </span>
                                        <input type="password" name="current_password" 
                                               class="form-control border-start-0 ps-0" required
                                               placeholder="Enter current password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                        <small class="text-danger mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" name="new_password" 
                                               class="form-control border-start-0 ps-0" minlength="6" required
                                               placeholder="Enter new password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Minimum 6 characters</small>
                                    @error('new_password')
                                        <small class="text-danger mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-transparent border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </span>
                                        <input type="password" name="new_password_confirmation" 
                                               class="form-control border-start-0 ps-0" minlength="6" required
                                               placeholder="Confirm new password">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('new_password_confirmation')
                                        <small class="text-danger mt-1">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="col-12 mt-4">
                                    <button type="submit" class="btn btn-gradient">
                                        <i class="fas fa-key me-2"></i> Update Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Information Section -->
            <div class="section-card">
                <div class="section-header">
                    <div class="d-flex align-items-center">
                        <div class="section-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold">Account Information</h6>
                            <p class="text-muted small mb-0">View your account details</p>
                        </div>
                    </div>
                </div>

                <div class="section-body">
                    <div class="form-card">
                        <div class="info-grid">
                            <div class="info-item">
                                <i class="fas fa-user text-muted"></i>
                                <div>
                                    <small class="text-muted d-block">Account Type</small>
                                    <span class="fw-medium">Regular Customer</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-calendar text-muted"></i>
                                <div>
                                    <small class="text-muted d-block">Member Since</small>
                                    <span class="fw-medium">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-id-card text-muted"></i>
                                <div>
                                    <small class="text-muted d-block">User ID</small>
                                    <span class="fw-medium">#{{ $user->id }}</span>
                                </div>
                            </div>
                            <div class="info-item">
                                <i class="fas fa-check-circle text-muted"></i>
                                <div>
                                    <small class="text-muted d-block">Verification Status</small>
                                    <span class="fw-medium text-success">Verified</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

</div>
</div>

<!-- Toast Notifications -->
@if(session('success'))
    <div class="toast-notification success">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="toast-notification error">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    </div>
@endif

@if(session('warning'))
    <div class="toast-notification warning">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('warning') }}
    </div>
@endif

@if($errors->any())
    <div class="toast-notification error">
        <i class="fas fa-exclamation-circle me-2"></i>Please check the form for errors
    </div>
@endif

@include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Auto-hide toast notifications after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast-notification');
    
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(400px)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);
    });
    
    // Add click to dismiss functionality
    toasts.forEach(toast => {
        toast.addEventListener('click', function() {
            this.style.opacity = '0';
            this.style.transform = 'translateX(400px)';
            setTimeout(() => {
                this.remove();
            }, 300);
        });
    });
    
    // Toggle password visibility
    const toggleButtons = document.querySelectorAll('.toggle-password');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const icon = this.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });
    
    // Form validation enhancement
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    });
    
    // Enhanced form focus effects
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>

</body>
</html>