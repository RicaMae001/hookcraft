<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Login - HookcraftAvenue</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DM Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #FFE8F4;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 900px;
            display: grid;
            grid-template-columns: 45% 55%;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }
        
        .logo-section {
            background: #FFD4E8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
        }
        
        .logo-circle {
            width: 280px;
            height: 280px;
            background: rgba(255, 182, 217, 0.5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-circle img {
            width: 90%;
            height: auto;
            object-fit: contain;
        }
        
        .form-section {
            background: #FFE8F4;
            padding: 3rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #0c0409ff;
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .input-wrapper {
            margin-bottom: 1.2rem;
        }
        
        .input-group {
            display: flex;
            align-items: center;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        
        .input-icon {
            padding: 0 1rem;
            color: #1A202C;
        }
        
        .form-control {
            border: none;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            background: white;
            flex: 1;
        }
        
        .form-control:focus {
            outline: none;
            box-shadow: none;
        }
        
        .input-group:focus-within {
            border-color: #FF6B9D;
        }
        
        .form-control::placeholder {
            color: #999;
        }
        
        .btn-login {
            width: 100%;
            padding: 0.85rem;
            background: #cf59daff;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            background: #1A202C;
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #1A202C;
        }
        
        .remember-me input {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
        
        .forgot-link {
            color: #718096;
            text-decoration: none;
        }
        
        .forgot-link:hover {
            color: #FF6B9D;
        }
        
        .signup-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
            color: #718096;
        }
        
        .signup-text a {
            color: #1A202C;
            text-decoration: none;
            font-weight: 600;
        }
        
        .signup-text a:hover {
            color: #FF6B9D;
        }
        
        .alert {
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .alert-danger {
            background: rgba(252, 129, 129, 0.15);
            color: #c53030;
            border-left: 4px solid #FC8181;
        }
        
        @media (max-width: 768px) {
            .login-wrapper {
                grid-template-columns: 1fr;
            }
            
            .logo-section {
                display: none;
            }
            
            .form-section {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-section">
            <div class="logo-circle">
                <img src="{{ asset('asset/images/logo.jpg') }}" alt="HookcraftAvenue Logo" style ="border-radius: 50%;">
            </div>
        </div>
        
        <div class="form-section">
            <h1>Log In</h1>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('staff.login.submit') }}">
                @csrf
                
                <div class="input-wrapper">
                    <div class="input-group">
                        <span class="input-icon">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="email" name="email" class="form-control" placeholder="Username" required autofocus>
                    </div>
                </div>

                <div class="input-wrapper">
                    <div class="input-group">
                        <span class="input-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                </div>

                <button type="submit" class="btn-login">Login</button>
                
                <div class="form-footer">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
            </form>

            
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>