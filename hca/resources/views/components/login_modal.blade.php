<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
  .hookcraft-modal .modal-content {
    border-radius: 24px;
    border: none;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    background: transparent;
  }

  .hookcraft-modal .modal-dialog {
    max-width: 900px;
  }

  .hookcraft-modal .modal-body {
    padding: 0;
  }

  .hookcraft-login-container {
    display: flex;
    min-height: 500px;
    background: white;
    border-radius: 24px;
    position: relative;
  }

  .hookcraft-brand-section {
    background: linear-gradient(135deg, #ff85c0 0%, #ffb3d9 50%, #ffd4ec 100%);
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 50px;
    position: relative;
    overflow: hidden;
    border-radius: 24px 0 0 24px;
  }

  /* Animated background elements */
  .hookcraft-brand-section::before {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%);
    border-radius: 50%;
    top: -100px;
    right: -100px;
    animation: pulse 8s ease-in-out infinite;
  }

  .hookcraft-brand-section::after {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    bottom: -50px;
    left: -50px;
    animation: pulse 6s ease-in-out infinite;
    animation-delay: 1s;
  }

  @keyframes pulse {
    0%, 100% { 
      transform: scale(1);
      opacity: 0.5;
    }
    50% { 
      transform: scale(1.2);
      opacity: 0.8;
    }
  }

  /* Decorative circles */
  .hookcraft-decorative-circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.15);
    animation: float 10s ease-in-out infinite;
  }

  .hookcraft-decorative-circle:nth-child(1) {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
  }

  .hookcraft-decorative-circle:nth-child(2) {
    width: 60px;
    height: 60px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
  }

  .hookcraft-decorative-circle:nth-child(3) {
    width: 40px;
    height: 40px;
    bottom: 30%;
    left: 20%;
    animation-delay: 4s;
  }

  @keyframes float {
    0%, 100% { 
      transform: translate(0, 0) rotate(0deg);
    }
    25% { 
      transform: translate(10px, -10px) rotate(90deg);
    }
    50% { 
      transform: translate(20px, 10px) rotate(180deg);
    }
    75% { 
      transform: translate(-10px, 15px) rotate(270deg);
    }
  }

  .hookcraft-logo-container {
    position: relative;
    z-index: 2;
    text-align: center;
    animation: fadeInScale 0.8s ease-out;
  }

  @keyframes fadeInScale {
    from {
      opacity: 0;
      transform: scale(0.8);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }

  .hookcraft-logo-image {
    max-width: 220px;
    width: 100%;
    height: auto;
    filter: drop-shadow(0 10px 25px rgba(0, 0, 0, 0.15));
    transition: transform 0.3s ease;
  }

  .hookcraft-logo-image:hover {
    transform: scale(1.05) rotate(2deg);
  }

  .hookcraft-brand-tagline {
    color: white;
    font-size: 18px;
    font-weight: 600;
    margin-top: 20px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
    letter-spacing: 1px;
  }

  .hookcraft-form-section {
    flex: 1;
    padding: 40px 50px;
    background: white;
    border-radius: 0 24px 24px 0;
    animation: slideInRight 0.6s ease-out;
  }

  @keyframes slideInRight {
    from {
      opacity: 0;
      transform: translateX(30px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  .hookcraft-form-title {
    font-size: 36px;
    font-weight: 800;
    background: linear-gradient(135deg, #ff85c0 0%, #ff66b3 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 8px;
    margin-top: 10px;
    text-align: center;
  }

  .hookcraft-form-subtitle {
    text-align: center;
    color: #888;
    font-size: 15px;
    margin-bottom: 25px;
  }

  .hookcraft-input-group {
    position: relative;
    margin-bottom: 18px;
  }

  .hookcraft-input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 18px;
    z-index: 1;
    transition: transform 0.3s ease;
    color: #ff99cc;
  }

  .hookcraft-input-group:focus-within .hookcraft-input-icon {
    transform: translateY(-50%) scale(1.1);
  }

  .hookcraft-input {
    width: 100%;
    padding: 16px 20px 16px 52px;
    border: 2px solid #e8e8e8;
    border-radius: 14px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #fafafa;
    color: #333;
  }

  .hookcraft-input::placeholder {
    color: #aaa;
  }

  .hookcraft-input:focus {
    outline: none;
    border-color: #ff99cc;
    background: white;
    box-shadow: 0 0 0 4px rgba(255, 153, 204, 0.12);
    transform: translateY(-1px);
  }

  .hookcraft-checkbox-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    font-size: 14px;
  }

  .hookcraft-checkbox-label {
    display: flex;
    align-items: center;
    color: #666;
    cursor: pointer;
    font-weight: 500;
    transition: color 0.3s ease;
  }

  .hookcraft-checkbox-label:hover {
    color: #ff99cc;
  }

  .hookcraft-checkbox-label input[type="checkbox"] {
    margin-right: 10px;
    width: 20px;
    height: 20px;
    cursor: pointer;
    accent-color: #ff99cc;
  }

  .hookcraft-forgot-link {
    color: #ff99cc;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
  }

  .hookcraft-forgot-link::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -2px;
    left: 0;
    background: #ff99cc;
    transition: width 0.3s ease;
  }

  .hookcraft-forgot-link:hover::after {
    width: 100%;
  }

  .hookcraft-forgot-link:hover {
    color: #ff66b3;
  }

  .hookcraft-login-btn {
    width: 100%;
    padding: 17px;
    background: linear-gradient(135deg, #ff85c0 0%, #ff99cc 50%, #ffb3d9 100%);
    background-size: 200% 200%;
    border: none;
    border-radius: 14px;
    color: white;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s ease;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    box-shadow: 0 8px 20px rgba(255, 133, 192, 0.35);
    position: relative;
    overflow: hidden;
  }

  .hookcraft-login-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    transition: left 0.5s ease;
  }

  .hookcraft-login-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 28px rgba(255, 133, 192, 0.45);
    background-position: 100% 0;
  }

  .hookcraft-login-btn:hover::before {
    left: 100%;
  }

  .hookcraft-login-btn:active {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(255, 133, 192, 0.35);
  }

  .hookcraft-divider {
    text-align: center;
    margin: 20px 0;
    position: relative;
  }

  .hookcraft-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: linear-gradient(to right, transparent, #e0e0e0, transparent);
  }

  .hookcraft-divider-text {
    background: white;
    padding: 0 15px;
    color: #999;
    font-size: 13px;
    font-weight: 600;
    position: relative;
    display: inline-block;
  }

  .hookcraft-signup-text {
    text-align: center;
    margin-top: 18px;
    color: #666;
    font-size: 15px;
    font-weight: 500;
  }

  .hookcraft-signup-link {
    color: #ff99cc;
    text-decoration: none;
    font-weight: 700;
    transition: all 0.3s ease;
    position: relative;
  }

  .hookcraft-signup-link::after {
    content: ' →';
    transition: transform 0.3s ease;
    display: inline-block;
  }

  .hookcraft-signup-link:hover {
    color: #ff66b3;
  }

  .hookcraft-signup-link:hover::after {
    transform: translateX(4px);
  }

  .btn-close {
    position: absolute;
    top: 20px;
    right: 20px;
    z-index: 100;
    background: white;
    border-radius: 50%;
    opacity: 0.8;
    transition: all 0.3s ease;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .btn-close:hover {
    opacity: 1;
    transform: rotate(90deg);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
  }

  /* Responsive Design */
  @media (max-width: 768px) {
    .hookcraft-login-container {
      flex-direction: column;
      min-height: auto;
    }

    .hookcraft-brand-section {
      min-height: 250px;
      border-radius: 24px 24px 0 0;
      padding: 40px 30px;
    }

    .hookcraft-logo-image {
      max-width: 180px;
    }

    .hookcraft-form-section {
      padding: 40px 30px;
      border-radius: 0 0 24px 24px;
    }

    .hookcraft-form-title {
      font-size: 30px;
    }

    .hookcraft-checkbox-row {
      flex-direction: column;
      gap: 15px;
      align-items: flex-start;
    }
  }

  /* Loading Animation */
  .hookcraft-loading {
    pointer-events: none;
    opacity: 0.7;
  }

  .hookcraft-loading::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    top: 50%;
    left: 50%;
    margin-left: -10px;
    margin-top: -10px;
    border: 3px solid white;
    border-top-color: transparent;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }
</style>

<div class="modal fade hookcraft-modal" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="hookcraft-login-container">
          <!-- Brand Section with Decorative Elements -->
          <div class="hookcraft-brand-section">
            <div class="hookcraft-decorative-circle"></div>
            <div class="hookcraft-decorative-circle"></div>
            <div class="hookcraft-decorative-circle"></div>
            
            <div class="hookcraft-logo-container">
              <img src="{{ asset('asset/images/logo.jpg') }}" alt="Hookcraft Avenue" class="hookcraft-logo-image">
              <p class="hookcraft-brand-tagline">Craft Your Style</p>
            </div>
          </div>
          
          <!-- Form Section -->
          <div class="hookcraft-form-section">
            <h2 class="hookcraft-form-title">Welcome Back</h2>
            <p class="hookcraft-form-subtitle">Log in to continue your journey</p>
            
            <form method="POST" action="{{ route('login.submit') }}">
              @csrf
              
              <div class="hookcraft-input-group">
                <i class="hookcraft-input-icon fas fa-envelope"></i>
                <input type="email" name="email" class="hookcraft-input" placeholder="Enter your email" required>
              </div>
              
              <div class="hookcraft-input-group">
                <i class="hookcraft-input-icon fas fa-lock"></i>
                <input type="password" name="password" class="hookcraft-input" placeholder="Enter your password" required>
              </div>
              
              <div class="hookcraft-checkbox-row">
                <label class="hookcraft-checkbox-label">
                  <input type="checkbox" name="remember">
                  Remember me
                </label>
                <a href="#" class="hookcraft-forgot-link">Forgot Password?</a>
              </div>
              
              <button type="submit" class="hookcraft-login-btn">Login</button>
              
              <div class="hookcraft-divider">
                <span class="hookcraft-divider-text">OR</span>
              </div>
              
              <p class="hookcraft-signup-text">
                Don't have an account? <a href="#" class="hookcraft-signup-link" data-bs-toggle="modal" data-bs-target="#signupModal" data-bs-dismiss="modal">Sign Up</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
// Optional: Add form submission animation
document.addEventListener('DOMContentLoaded', function() {
  const loginForm = document.getElementById('loginForm');
  const loginBtn = loginForm?.querySelector('.hookcraft-login-btn');
  
  loginForm?.addEventListener('submit', function(e) {
    if (loginBtn) {
      loginBtn.classList.add('hookcraft-loading');
      loginBtn.textContent = 'Logging in...';
    }
  });
});
</script>