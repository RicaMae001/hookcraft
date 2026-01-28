<style>
  .hookcraft-modal .modal-content {
    border-radius: 20px;
    border: none;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
  }

  .hookcraft-modal .modal-dialog {
    max-width: 800px;
  }

  .hookcraft-modal .modal-body {
    padding: 0;
  }

  .hookcraft-login-container {
    display: flex;
    min-height: 400px;
  }

  .hookcraft-brand-section {
    background: linear-gradient(135deg, #ffc0e3 0%, #ffb3d9 50%, #ff99cc 100%);
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px;
    position: relative;
    overflow: hidden;
  }

  .hookcraft-brand-section::before {
    content: '';
    position: absolute;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
    top: -50%;
    left: -50%;
    animation: float 15s ease-in-out infinite;
  }

  @keyframes float {
    0%, 100% { transform: translate(0, 0); }
    50% { transform: translate(30px, 30px); }
  }

  .hookcraft-logo-container {
    position: relative;
    z-index: 1;
    text-align: center;
  }

  .hookcraft-logo-text {
    font-size: 48px;
    font-weight: 800;
    color: white;
    text-shadow: 3px 3px 0px rgba(0, 0, 0, 0.1);
    letter-spacing: 2px;
    margin: 0;
    font-family: 'Comic Sans MS', cursive, sans-serif;
  }

  .hookcraft-logo-image {
    max-width: 200px;
    width: 200%;
    height: auto;
    filter: drop-shadow(3px 3px 6px rgba(0, 0, 0, 0.2));
  }

  .hookcraft-form-section {
    flex: 1;
    padding: 50px 40px;
    background: white;
  }

  .hookcraft-form-title {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin-bottom: 30px;
    text-align: center;
  }

  .hookcraft-input-group {
    position: relative;
    margin-bottom: 20px;
  }

  .hookcraft-input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 18px;
    z-index: 1;
  }

  .hookcraft-input {
    width: 100%;
    padding: 14px 15px 14px 45px;
    border: 2px solid #e5e5e5;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f8f9fa;
  }

  .hookcraft-input:focus {
    outline: none;
    border-color: #ff99cc;
    background: white;
    box-shadow: 0 0 0 4px rgba(255, 153, 204, 0.1);
  }

  .hookcraft-checkbox-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    font-size: 14px;
  }

  .hookcraft-checkbox-label {
    display: flex;
    align-items: center;
    color: #666;
    cursor: pointer;
  }

  .hookcraft-checkbox-label input[type="checkbox"] {
    margin-right: 8px;
    width: 18px;
    height: 18px;
    cursor: pointer;
    accent-color: #ff99cc;
  }

  .hookcraft-forgot-link {
    color: #ff99cc;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
  }

  .hookcraft-forgot-link:hover {
    color: #ff66b3;
  }

  .hookcraft-login-btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #ff99cc 0%, #ffb3d9 100%);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(255, 153, 204, 0.3);
  }

  .hookcraft-login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 153, 204, 0.4);
  }

  .hookcraft-login-btn:active {
    transform: translateY(0);
  }

  .hookcraft-signup-text {
    text-align: center;
    margin-top: 20px;
    color: #666;
    font-size: 14px;
  }

  .hookcraft-signup-link {
    color: #ff99cc;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
  }

  .hookcraft-signup-link:hover {
    color: #ff66b3;
    text-decoration: underline;
  }

  .btn-close {
    position: absolute;
    top: 15px;
    right: 15px;
    z-index: 10;
    background: white;
    border-radius: 50%;
    opacity: 0.7;
    transition: opacity 0.3s ease;
  }

  .btn-close:hover {
    opacity: 1;
  }

  @media (max-width: 768px) {
    .hookcraft-login-container {
      flex-direction: column;
    }

    .hookcraft-brand-section {
      min-height: 200px;
    }

    .hookcraft-logo-text {
      font-size: 36px;
    }

    .hookcraft-form-section {
      padding: 30px 20px;
    }
  }
</style>

<div class="modal fade hookcraft-modal" id="loginModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="hookcraft-login-container">
          <div class="hookcraft-brand-section">
            <div class="hookcraft-logo-container">
              <img src="{{ asset('asset/images/logo.jpg') }}" alt="Hookcraft Avenue" class="hookcraft-logo-image">
            </div>
          </div>
          
          <div class="hookcraft-form-section">
            <h2 class="hookcraft-form-title">Log In</h2>
            
            <form method="POST" action="{{ route('login.submit') }}">
              @csrf
              
              <div class="hookcraft-input-group">
                <span class="hookcraft-input-icon">👤</span>
                <input type="email" name="email" class="hookcraft-input" placeholder="Email" required>
              </div>
              
              <div class="hookcraft-input-group">
                <span class="hookcraft-input-icon">🔒</span>
                <input type="password" name="password" class="hookcraft-input" placeholder="Password" required>
              </div>
              
              <div class="hookcraft-checkbox-row">
                <label class="hookcraft-checkbox-label">
                  <input type="checkbox" name="remember">
                  Remember me
                </label>
                <a href="#" class="hookcraft-forgot-link">Forgot Password?</a>
              </div>
              
              <button type="submit" class="hookcraft-login-btn">Login</button>
              
              <p class="hookcraft-signup-text">
                Not a member yet? <a href="#" class="hookcraft-signup-link" data-bs-toggle="modal" data-bs-target="#signupModal" data-bs-dismiss="modal">Create an Account</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>