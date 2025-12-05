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

  .hookcraft-signup-container {
    display: flex;
    min-height: 500px;
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
    width: 100%;
    height: auto;
    filter: drop-shadow(3px 3px 6px rgba(0, 0, 0, 0.2));
  }

  .hookcraft-form-section {
    flex: 1;
    padding: 40px;
    background: white;
    overflow-y: auto;
    max-height: 600px;
  }

  .hookcraft-form-title {
    font-size: 32px;
    font-weight: 700;
    color: #333;
    margin-bottom: 25px;
    text-align: center;
  }

  .hookcraft-alert {
    padding: 12px 15px;
    border-radius: 10px;
    margin-bottom: 20px;
    font-size: 14px;
  }

  .hookcraft-alert-danger {
    background: #fee;
    border: 2px solid #fcc;
    color: #c33;
  }

  .hookcraft-alert-success {
    background: #efe;
    border: 2px solid #cfc;
    color: #3c3;
  }

  .hookcraft-alert ul {
    margin: 8px 0 0 20px;
    padding: 0;
  }

  .hookcraft-input-group {
    position: relative;
    margin-bottom: 18px;
  }

  .hookcraft-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #555;
    margin-bottom: 8px;
  }

  .hookcraft-label-small {
    font-size: 12px;
    font-weight: 400;
    color: #999;
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
    padding: 12px 15px 12px 45px;
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

  .hookcraft-input.is-invalid {
    border-color: #dc3545;
    background: #fff5f5;
  }

  .hookcraft-input.is-invalid:focus {
    box-shadow: 0 0 0 4px rgba(220, 53, 69, 0.1);
  }

  .hookcraft-input-hint {
    font-size: 12px;
    color: #999;
    margin-top: 5px;
    display: block;
  }

  .hookcraft-invalid-feedback {
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
    display: none;
  }

  .hookcraft-invalid-feedback.show {
    display: block;
  }

  .hookcraft-signup-btn {
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
    margin-top: 20px;
  }

  .hookcraft-signup-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 153, 204, 0.4);
  }

  .hookcraft-signup-btn:active {
    transform: translateY(0);
  }

  .hookcraft-login-text {
    text-align: center;
    margin-top: 20px;
    color: #666;
    font-size: 14px;
  }

  .hookcraft-login-link {
    color: #ff99cc;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
  }

  .hookcraft-login-link:hover {
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
    .hookcraft-signup-container {
      flex-direction: column;
    }

    .hookcraft-brand-section {
      min-height: 150px;
    }

    .hookcraft-logo-text {
      font-size: 32px;
    }

    .hookcraft-form-section {
      padding: 30px 20px;
      max-height: none;
    }
  }
</style>

<div class="modal fade hookcraft-modal" id="signupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      <div class="modal-body">
        <div class="hookcraft-signup-container">
          <div class="hookcraft-brand-section">
            <div class="hookcraft-logo-container">
              <img src="{{ asset('asset/images/logo.jpg') }}" alt="Hookcraft Avenue" class="hookcraft-logo-image">
            </div>
          </div>
          
          <div class="hookcraft-form-section">
            <h2 class="hookcraft-form-title">Sign Up</h2>
            
            <form method="POST" action="{{ route('register') }}" id="signupForm">
              @csrf
              
              <!-- Display all validation errors -->
              @if($errors->any())
                <div class="hookcraft-alert hookcraft-alert-danger" role="alert">
                  <strong>Please fix the following errors:</strong>
                  <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              <!-- Success message -->
              @if(session('success'))
                <div class="hookcraft-alert hookcraft-alert-success" role="alert">
                  {{ session('success') }}
                </div>
              @endif
              
              <div class="hookcraft-input-group">
                <label class="hookcraft-label">Full Name</label>
                <div style="position: relative;">
                  <span class="hookcraft-input-icon">👤</span>
                  <input type="text" name="name" class="hookcraft-input @error('name') is-invalid @enderror" 
                         placeholder="Full Name" value="{{ old('name') }}" required>
                </div>
                @error('name')
                  <span class="hookcraft-invalid-feedback show">{{ $message }}</span>
                @enderror
              </div>
              
              <div class="hookcraft-input-group">
                <label class="hookcraft-label">Email Address</label>
                <div style="position: relative;">
                  <span class="hookcraft-input-icon">✉️</span>
                  <input type="email" name="email" class="hookcraft-input @error('email') is-invalid @enderror" 
                         placeholder="Email Address" value="{{ old('email') }}" required>
                </div>
                @error('email')
                  <span class="hookcraft-invalid-feedback show">{{ $message }}</span>
                @enderror
              </div>
              
              <div class="hookcraft-input-group">
                <label class="hookcraft-label">
                  Password <span class="hookcraft-label-small">(minimum 6 characters)</span>
                </label>
                <div style="position: relative;">
                  <span class="hookcraft-input-icon">🔒</span>
                  <input type="password" name="password" id="password" 
                         class="hookcraft-input @error('password') is-invalid @enderror" 
                         placeholder="Password" minlength="6" required>
                </div>
                
                @error('password')
                  <span class="hookcraft-invalid-feedback show">{{ $message }}</span>
                @enderror
              </div>
              
              <div class="hookcraft-input-group">
                <label class="hookcraft-label">Confirm Password</label>
                <div style="position: relative;">
                  <span class="hookcraft-input-icon">🔒</span>
                  <input type="password" name="password_confirmation" id="password_confirmation"
                         class="hookcraft-input" placeholder="Confirm Password" minlength="6" required>
                </div>
                <span class="hookcraft-invalid-feedback" id="passwordMatchError">
                  Passwords do not match
                </span>
              </div>
              
              <button type="submit" class="hookcraft-signup-btn">Sign Up</button>
              
              <p class="hookcraft-login-text">
                Already have an account? <a href="#" class="hookcraft-login-link" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">Log In</a>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Script to reopen modal if there are errors and validate passwords -->
@if($errors->any())
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var myModal = new bootstrap.Modal(document.getElementById('signupModal'));
    myModal.show();
  });
</script>
@endif

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('signupForm');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');
    const passwordMatchError = document.getElementById('passwordMatchError');

    // Real-time password match validation
    function validatePasswordMatch() {
      if (passwordConfirmation.value && password.value !== passwordConfirmation.value) {
        passwordConfirmation.classList.add('is-invalid');
        passwordMatchError.classList.add('show');
        return false;
      } else {
        passwordConfirmation.classList.remove('is-invalid');
        passwordMatchError.classList.remove('show');
        return true;
      }
    }

    // Real-time password length validation
    function validatePasswordLength() {
      if (password.value.length > 0 && password.value.length < 6) {
        password.classList.add('is-invalid');
        return false;
      } else {
        password.classList.remove('is-invalid');
        return true;
      }
    }

    password.addEventListener('input', function() {
      validatePasswordLength();
      if (passwordConfirmation.value) {
        validatePasswordMatch();
      }
    });

    passwordConfirmation.addEventListener('input', validatePasswordMatch);

    // Form submission validation
    form.addEventListener('submit', function(e) {
      const isPasswordValid = validatePasswordLength();
      const isPasswordMatch = validatePasswordMatch();

      if (!isPasswordValid || !isPasswordMatch) {
        e.preventDefault();
        
        if (!isPasswordValid) {
          alert('Password must be at least 6 characters long.');
        } else if (!isPasswordMatch) {
          alert('Passwords do not match. Please check and try again.');
        }
      }
    });
  });
</script>