<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - HookcraftAvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #FFB6C1 0%, #FF9EAD 100%);
            min-height: 100vh;
        }
        .login-card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        .text-pink {
            color: #FFB6C1;
        }
        .btn-pink {
            background-color: #FFB6C1;
            color: white;
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-pink:hover {
            background-color: #FF9EAD;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 182, 193, 0.4);
        }
        .icon-wrapper {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #FFB6C1 0%, #FF9EAD 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            box-shadow: 0 5px 15px rgba(255, 182, 193, 0.3);
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            border-color: #FFB6C1;
            box-shadow: 0 0 0 0.2rem rgba(255, 182, 193, 0.25);
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="login-card p-5">
                    <div class="text-center mb-4">
                        <div class="icon-wrapper">
                            <i class="fas fa-user-shield fa-3x text-white"></i>
                        </div>
                        <h3 class="fw-bold">Staff Login</h3>
                        <p class="text-muted">HookcraftAvenue Management Portal</p>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ $errors->first() }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('staff.login.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope text-pink"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="Enter your email" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock text-pink"></i></span>
                                <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-pink w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login to Portal
                        </button>

                        <div class="text-center">
                            <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                                <i class="fas fa-arrow-left me-2"></i>Back to Store
                            </a>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Login with your staff credentials
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>