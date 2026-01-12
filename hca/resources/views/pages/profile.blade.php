<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - HookcraftAvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
     <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
</head>
<body>
    <!-- Include your navbar here -->
 @include('components.navbar')

    <div class="container py-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <img src="{{ asset('asset/images/default-profile.png') }}" alt="User Image" 
                             class="rounded-circle mb-3" width="120" height="120" 
                             style="object-fit: cover; border: 3px solid #FFB6C1;">
                        <h5>{{ $user->name }}</h5>
                        <p class="text-muted small">{{ $user->email }}</p>
                    </div>

                    <div class="list-group list-group-flush">
                        <a href="{{ route('profile.index') }}" class="list-group-item list-group-item-action active">
                            <i class="fas fa-user me-2"></i> My Account
                        </a>
                        <a href="{{ route('profile.purchase-history') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-history me-2"></i> Purchase History
                        </a>
                        <a href="{{ route('profile.track-order') }}" class="list-group-item list-group-item-action">
                            <i class="fas fa-truck me-2"></i> Track Order
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Personal Settings -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header" style="background-color: #FFB6C1;">
                        <h5 class="mb-0 text-white"><i class="fas fa-cog me-2"></i> Personal Setting</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Name</label>
                                    <input type="text" name="name" class="form-control" 
                                           placeholder="Enter your name" value="{{ old('name', $user->name) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control" 
                                           placeholder="Enter email" value="{{ old('email', $user->email) }}" required>
                                </div>

                                <div class="col-12">
                                    <button type="submit" class="btn btn-pink">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update-password') }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="current_password" class="form-control" 
                                       placeholder="Enter current password" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password <small class="text-muted">(minimum 6 characters)</small></label>
                                <input type="password" name="new_password" class="form-control" 
                                       placeholder="Enter new password" minlength="6" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="new_password_confirmation" class="form-control" 
                                       placeholder="Confirm new password" minlength="6" required>
                            </div>

                            <button type="submit" class="btn btn-pink">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include your footer here if you have one -->
@include('components.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <style>
    .btn-pink {
        background-color:rgb(250, 118, 138);
        color: white;
        border: none;
    }
    .btn-pink:hover {
        background-color:rgb(250, 17, 114);
        color: white;
    }
    .list-group-item.active {
        background-color:rgb(255, 176, 188);
        border-color:rgb(245, 162, 174);
    }
    </style>
</body>
</html>