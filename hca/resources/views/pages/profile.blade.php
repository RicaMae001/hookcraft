<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - HookcraftAvenue</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">

    <style>
        :root {
            --pink: #ffb6c1;
            --pink-dark: #ff9ead;
            --pink-light: #fff5f7;
            --border: #e5e7eb;
        }

        body {
            background: #f8f9fa;
        }

        /* ===== SIDEBAR (MATCH PURCHASE HISTORY) ===== */
        .sidebar-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .sidebar-header {
            text-align: center;
            padding: 2rem 1.5rem 1.5rem;
        }

        .sidebar-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            padding: 5px;
            background: white;
            border: 4px solid var(--pink);
            margin-bottom: 1rem;
        }

        .sidebar-header h6 {
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .sidebar-header p {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 0;
        }

        .sidebar-menu {
            border-top: 1px solid #f1f1f1;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.25rem;
            font-weight: 600;
            color: #111827;
            text-decoration: none;
            transition: 0.3s ease;
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
        }

        .sidebar-menu a.active {
            background: var(--pink);
            color: white;
        }

        .sidebar-menu a:hover:not(.active) {
            background: var(--pink-light);
            color: var(--pink-dark);
        }

        /* ===== BUTTON ===== */
        .btn-pink {
            background: var(--pink);
            border: none;
            font-weight: 600;
        }

        .btn-pink:hover {
            background: var(--pink-dark);
        }
    </style>
</head>

<body>

@include('components.navbar')

<div class="container py-5">
<div class="row">

<!-- ================= SIDEBAR ================= -->
<div class="col-md-3 mb-4">
    <div class="sidebar-card">
        <div class="sidebar-header">
            <img src="{{ asset('asset/images/default-profile.png') }}" class="sidebar-avatar">
            <h6>{{ $user->name }}</h6>
            <p>{{ $user->email }}</p>
        </div>

        <div class="sidebar-menu">
            <a href="{{ route('profile.index') }}" class="active">
                <i class="fas fa-user"></i> My Account
            </a>
            <a href="{{ route('profile.purchase-history') }}">
                <i class="fas fa-history"></i> Purchase History
            </a>
            <a href="{{ route('profile.track-order') }}">
                <i class="fas fa-truck"></i> Track Order
            </a>
        </div>
    </div>
</div>

<!-- ================= MAIN CONTENT ================= -->
<div class="col-md-9">

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Personal Settings -->
    <div class="card shadow-sm mb-4">
        <div class="card-header" style="background: var(--pink);">
            <h5 class="mb-0"><i class="fas fa-cog me-2"></i> Personal Setting</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control"
                               value="{{ old('name',$user->name) }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email',$user->email) }}" required>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-pink">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Change Password -->
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0"><i class="fas fa-lock me-2"></i> Change Password</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('profile.update-password') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" minlength="6" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" minlength="6" required>
                    </div>
                </div>

                <button class="btn btn-pink">
                    <i class="fas fa-key me-2"></i> Update Password
                </button>
            </form>
        </div>
    </div>

</div>
</div>
</div>

@include('components.footer')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
/* ===== MOBILE FIXES ONLY - NO HORIZONTAL SCROLLING ===== */
@media (max-width: 768px) {
    body {
        overflow-x: hidden;
        width: 100%;
    }
    
    .container {
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .row {
        margin-left: 0;
        margin-right: 0;
    }
    
    .col-md-3, .col-md-9, .col-md-6, .col-12 {
        padding-left: 0;
        padding-right: 0;
        width: 100%;
    }
    
    .sidebar-card, .card, .alert, form {
        width: 100%;
        max-width: 100%;
    }
    
    /* Fix for Bootstrap grid */
    .g-3 {
        --bs-gutter-x: 0;
    }
}
</style>
</body>
</html>