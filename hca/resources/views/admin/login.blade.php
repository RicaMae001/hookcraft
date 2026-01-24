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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #e91e63 0%, #ff4081 50%, #f48fb1 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            top: -200px;
            left: -200px;
            animation: float 25s infinite ease-in-out;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -150px;
            right: -150px;
            animation: float 20s infinite ease-in-out reverse;
        }

        @keyframes float {
            0%, 100% { 
                transform: translate(0, 0) scale(1);
                opacity: 1;
            }
            50% { 
                transform: translate(50px, 50px) scale(1.1);
                opacity: 0.8;
            }
        }

        /* Floating shapes */
        .shape {
            position: absolute;
            opacity: 0.15;
            animation: floatShapes 15s infinite ease-in-out;
        }

        .shape-1 {
            width: 80px;
            height: 80px;
            background: white;
            border-radius: 20px;
            top: 15%;
            left: 10%;
            animation-delay: 0s;
            transform: rotate(45deg);
        }

        .shape-2 {
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            top: 60%;
            right: 15%;
            animation-delay: 3s;
        }

        .shape-3 {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 30px;
            bottom: 20%;
            left: 20%;
            animation-delay: 6s;
        }

        @keyframes floatShapes {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(180deg); }
        }
        
        .login-wrapper {
            width: 100%;
            max-width: 1000px;
            display: grid;
            grid-template-columns: 50% 50%;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(233, 30, 99, 0.4);
            position: relative;
            z-index: 10;
            animation: slideUp 0.6s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .logo-section {
            background: linear-gradient(135deg, #d81b60 0%, #ec407a 50%, #f06292 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 3rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative circles in logo section */
        .logo-section::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
            animation: pulse 8s infinite ease-in-out;
        }

        .logo-section::after {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            bottom: -50px;
            left: -50px;
            animation: pulse 6s infinite ease-in-out reverse;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.6; }
        }

        /* Grid pattern overlay */
        .logo-section .grid-pattern {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 30px 30px;
            opacity: 0.5;
        }
        
        .logo-circle {
            width: 260px;
            height: 260px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(20px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 4px solid rgba(255, 255, 255, 0.4);
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.3),
                inset 0 0 30px rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 2;
            transition: all 0.4s ease;
        }

        .logo-circle:hover {
            transform: scale(1.08) rotate(5deg);
            box-shadow: 
                0 25px 80px rgba(0, 0, 0, 0.4),
                inset 0 0 40px rgba(255, 255, 255, 0.3);
        }
        
        .logo-circle img {
            width: 88%;
            height: 88%;
            object-fit: cover;
            border-radius: 50%;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .brand-text {
            color: white;
            font-size: 1.8rem;
            font-weight: 700;
            margin-top: 2rem;
            text-align: center;
            position: relative;
            z-index: 2;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            letter-spacing: 0.5px;
        }

        .tagline {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            margin-top: 0.5rem;
            text-align: center;
            position: relative;
            z-index: 2;
            font-weight: 400;
        }
        
        .form-section {
            background: #ffffff;
            padding: 4rem 3.5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #d81b60 0%, #ec407a 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 2.5rem;
            font-weight: 400;
        }
        
        .input-wrapper {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .input-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.6rem;
            letter-spacing: 0.3px;
        }
        
        .input-group {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 14px;
            overflow: hidden;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .input-icon {
            padding: 0 1.2rem;
            color: #d81b60;
            font-size: 1.15rem;
            transition: all 0.3s ease;
        }
        
        .form-control {
            border: none;
            padding: 1rem 1.2rem;
            font-size: 0.95rem;
            background: transparent;
            flex: 1;
            color: #212529;
            font-weight: 400;
        }
        
        .form-control:focus {
            outline: none;
            box-shadow: none;
        }
        
        .input-group:focus-within {
            border-color: #d81b60;
            background: white;
            box-shadow: 0 0 0 4px rgba(216, 27, 96, 0.1);
        }

        .input-group:focus-within .input-icon {
            color: #c2185b;
            transform: scale(1.1);
        }
        
        .form-control::placeholder {
            color: #adb5bd;
            font-weight: 400;
        }

        /* Password toggle button */
        .password-toggle {
            padding: 0 1.2rem;
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #d81b60;
        }
        
        .btn-login {
            width: 100%;
            padding: 1.1rem;
            background: linear-gradient(135deg, #d81b60 0%, #ec407a 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 600;
            font-size: 1rem;
            margin-top: 1.8rem;
            cursor: pointer;
            transition: all 0.4s ease;
            box-shadow: 0 8px 20px rgba(216, 27, 96, 0.3);
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }
        
        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(216, 27, 96, 0.4);
        }

        .btn-login:active {
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(216, 27, 96, 0.35);
        }
        
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.5rem;
            font-size: 0.875rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            color: #555;
            cursor: pointer;
            user-select: none;
            transition: color 0.3s ease;
        }

        .remember-me:hover {
            color: #333;
        }
        
        .remember-me input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #d81b60;
        }
        
        .forgot-link {
            color: #d81b60;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .forgot-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #d81b60;
            transition: width 0.3s ease;
        }
        
        .forgot-link:hover {
            color: #c2185b;
        }

        .forgot-link:hover::after {
            width: 100%;
        }
        
        .alert {
            border-radius: 14px;
            padding: 1.1rem 1.3rem;
            margin-bottom: 1.8rem;
            border: none;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            animation: slideIn 0.4s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #ffebee 0%, #fce4ec 100%);
            color: #c62828;
            border-left: 4px solid #d81b60;
        }

        .alert i {
            font-size: 1.3rem;
        }

        /* Loading spinner */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
        }

        @keyframes spin {
            to { transform: translateY(-50%) rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 1rem;
            }

            .login-wrapper {
                grid-template-columns: 1fr;
                border-radius: 24px;
            }
            
            .logo-section {
                padding: 3rem 2rem;
                min-height: 280px;
            }

            .logo-circle {
                width: 220px;
                height: 220px;
            }

            .brand-text {
                font-size: 1.6rem;
                margin-top: 1.5rem;
            }

            .tagline {
                font-size: 0.875rem;
            }
            
            .form-section {
                padding: 2.5rem 2rem;
            }

            h1 {
                font-size: 2rem;
            }

            .subtitle {
                font-size: 0.875rem;
                margin-bottom: 2rem;
            }
        }

        @media (max-width: 480px) {
            .logo-section {
                min-height: 240px;
                padding: 2.5rem 1.5rem;
            }

            .logo-circle {
                width: 180px;
                height: 180px;
            }

            .brand-text {
                font-size: 1.4rem;
            }

            .form-section {
                padding: 2rem 1.5rem;
            }

            h1 {
                font-size: 1.75rem;
            }

            .form-footer {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .btn-login {
                padding: 1rem;
                font-size: 0.95rem;
            }

            .input-wrapper {
                margin-bottom: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="shape shape-1"></div>
    <div class="shape shape-2"></div>
    <div class="shape shape-3"></div>

    <div class="login-wrapper">
        <div class="logo-section">
            <div class="grid-pattern"></div>
            <div class="logo-circle">
                <img src="{{ asset('asset/images/logo.jpg') }}" alt="HookcraftAvenue Logo">
            </div>
            <div class="brand-text">HookcraftAvenue</div>
            <div class="tagline">Staff Portal</div>
        </div>
        
        <div class="form-section">
            <h1>Welcome Back</h1>
            <p class="subtitle">Sign in to access your staff dashboard</p>

            @if($errors->any())
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('staff.login.submit') }}" id="loginForm">
                @csrf
                
                <div class="input-wrapper">
                    <label class="input-label">Email Address</label>
                    <div class="input-group">
                        <span class="input-icon">
                            <i class="fas fa-envelope"></i>
                        </span>
                        <input type="email" name="email" class="form-control" placeholder="Enter your email" required autofocus>
                    </div>
                </div>

                <div class="input-wrapper">
                    <label class="input-label">Password</label>
                    <div class="input-group">
                        <span class="input-icon">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-footer">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Add loading state on form submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = this.querySelector('.btn-login');
            btn.classList.add('loading');
        });
    </script>
</body>
</html>