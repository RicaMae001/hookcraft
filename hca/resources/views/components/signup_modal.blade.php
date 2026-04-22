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
    max-height: 650px;
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

  .hookcraft-signup-btn:disabled {
    background: #ccc;
    box-shadow: none;
    cursor: not-allowed;
    transform: none;
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

  /* OTP specific styles */
  .otp-section {
    background: #fff8fd;
    border: 2px dashed #ffb3d9;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 18px;
  }

  .otp-row {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .otp-row .hookcraft-input-group {
    width: 100%;
    margin-bottom: 0;
  }

  .send-otp-btn {
    padding: 12px 14px;
    background: #ff99cc;
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    transition: background 0.3s;
    width: 100%;
  }

  .send-otp-btn:hover {
    background: #ff66b3;
  }

  .send-otp-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
  }

  .otp-verified-badge {
    display: none;
    background: #efe;
    border: 2px solid #cfc;
    color: #3a3;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 14px;
    font-weight: 600;
    text-align: center;
    margin-top: 10px;
  }

  .otp-verified-badge.show {
    display: block;
  }

  #otpCountdown {
    font-size: 12px;
    color: #ff99cc;
    margin-top: 6px;
    display: none;
  }

  #otpCountdown.expired {
    color: #dc3545;
  }

  @media (max-width: 768px) {
    .hookcraft-signup-container {
      flex-direction: column;
    }

    .hookcraft-brand-section {
      min-height: 150px;
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
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
        <i class="fas fa-times"></i>
      </button>
      <div class="modal-body">
        <div class="hookcraft-signup-container">

          {{-- Brand Section --}}
          <div class="hookcraft-brand-section">
            <div class="hookcraft-logo-container">
              <img src="{{ asset('asset/images/logo.jpg') }}" alt="Hookcraft Avenue" class="hookcraft-logo-image">
            </div>
          </div>

          {{-- Form Section --}}
          <div class="hookcraft-form-section">
            <h2 class="hookcraft-form-title">Sign Up</h2>

            {{-- Validation errors --}}
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

            {{-- Success message --}}
            @if(session('success'))
              <div class="hookcraft-alert hookcraft-alert-success" role="alert">
                {{ session('success') }}
              </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="signupForm">
              @csrf

              {{-- Full Name --}}
              <div class="hookcraft-input-group">
                <label class="hookcraft-label">Full Name</label>
                <div style="position: relative;">
                  <span class="hookcraft-input-icon">👤</span>
                  <input type="text" name="name"
                         class="hookcraft-input @error('name') is-invalid @enderror"
                         placeholder="Full Name" value="{{ old('name') }}" required>
                </div>
                @error('name')
                  <span class="hookcraft-invalid-feedback show">{{ $message }}</span>
                @enderror
              </div>

              {{-- Email + OTP Section --}}
              <div class="otp-section">
                <label class="hookcraft-label">Email Address</label>

                {{-- Email input + Send OTP button --}}
                <div class="otp-row">
                  <div class="hookcraft-input-group">
                    <div style="position: relative;">
                      <span class="hookcraft-input-icon">✉️</span>
                      <input type="email" name="email" id="emailInput"
                             class="hookcraft-input @error('email') is-invalid @enderror"
                             placeholder="Email Address"
                             value="{{ old('email') }}" required>
                    </div>
                    @error('email')
                      <span class="hookcraft-invalid-feedback show">{{ $message }}</span>
                    @enderror
                  </div>
                  <button type="button" class="send-otp-btn" id="sendOtpBtn" onclick="sendOtp()">
                    Send OTP
                  </button>
                </div>

                {{-- Countdown timer --}}
                <div id="otpCountdown"></div>

                {{-- OTP input (hidden until OTP is sent) --}}
                <div id="otpInputWrapper" style="display: none; margin-top: 14px;">
                  <label class="hookcraft-label">
                    Enter OTP <span class="hookcraft-label-small">(check your email)</span>
                  </label>
                  <div style="position: relative;">
                    <span class="hookcraft-input-icon">🔑</span>
                    <input type="text" id="otpInput"
                           class="hookcraft-input"
                           placeholder="6-digit OTP"
                           maxlength="6"
                           inputmode="numeric">
                  </div>
                  <button type="button" class="send-otp-btn"
                          style="margin-top: 10px; width: 100%;"
                          onclick="verifyOtp()">
                    Verify OTP
                  </button>
                </div>

                {{-- Verified badge (shown after successful OTP) --}}
                <div class="otp-verified-badge" id="otpVerifiedBadge">
                  ✅ Email verified! You can now complete registration.
                </div>
              </div>

              {{-- Password --}}
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

              {{-- Confirm Password --}}
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

              <button type="submit" class="hookcraft-signup-btn" id="submitBtn">
                Sign Up
              </button>

              <p class="hookcraft-login-text">
                Already have an account?
                <a href="#" class="hookcraft-login-link"
                   data-bs-toggle="modal"
                   data-bs-target="#loginModal"
                   data-bs-dismiss="modal">Log In</a>
              </p>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

{{-- Reopen modal if there are server-side errors --}}
@if($errors->any())
<script>
  document.addEventListener('DOMContentLoaded', function () {
    new bootstrap.Modal(document.getElementById('signupModal')).show();
  });
</script>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
  const form                = document.getElementById('signupForm');
  const password            = document.getElementById('password');
  const passwordConfirmation = document.getElementById('password_confirmation');
  const passwordMatchError  = document.getElementById('passwordMatchError');

  // ── Password match validation ──
  function validatePasswordMatch() {
    if (passwordConfirmation.value && password.value !== passwordConfirmation.value) {
      passwordConfirmation.classList.add('is-invalid');
      passwordMatchError.classList.add('show');
      return false;
    }
    passwordConfirmation.classList.remove('is-invalid');
    passwordMatchError.classList.remove('show');
    return true;
  }

  // ── Password length validation ──
  function validatePasswordLength() {
    if (password.value.length > 0 && password.value.length < 6) {
      password.classList.add('is-invalid');
      return false;
    }
    password.classList.remove('is-invalid');
    return true;
  }

  password.addEventListener('input', function () {
    validatePasswordLength();
    if (passwordConfirmation.value) validatePasswordMatch();
  });

  passwordConfirmation.addEventListener('input', validatePasswordMatch);

  // ── Form submit guard ──
  form.addEventListener('submit', function (e) {
    const isPasswordValid = validatePasswordLength();
    const isPasswordMatch = validatePasswordMatch();

    // Block if OTP not verified
    if (!window.otpVerified) {
      e.preventDefault();
      alert('Please verify your email with OTP before registering.');
      return;
    }

    if (!isPasswordValid || !isPasswordMatch) {
      e.preventDefault();
      if (!isPasswordValid) {
        alert('Password must be at least 6 characters long.');
      } else {
        alert('Passwords do not match. Please check and try again.');
      }
    }
  });
});

// ── Global OTP state ──
window.otpVerified   = false;
let countdownTimer   = null;

// ── Helper: get CSRF token safely ──
function getCsrfToken() {
  return document.querySelector('meta[name="csrf-token"]')?.content
      || document.querySelector('input[name="_token"]')?.value
      || '{{ csrf_token() }}';
}

// ── Send OTP ──
async function sendOtp() {
  const email  = document.getElementById('emailInput').value.trim();
  const btn    = document.getElementById('sendOtpBtn');

  if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    alert('Please enter a valid email address first.');
    return;
  }

  btn.disabled    = true;
  btn.textContent = 'Sending...';

  try {
    const res  = await fetch('{{ route("otp.send") }}', {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),
      },
      body: JSON.stringify({ email }),
    });

    const data = await res.json();

    if (res.ok && data.success) {
      document.getElementById('otpInputWrapper').style.display = 'block';
      document.getElementById('otpInput').value = '';
      document.getElementById('otpInput').focus();
      btn.textContent = 'Resend OTP';
      btn.disabled    = false;
      startCountdown(300);
    } else {
      alert(data.message || 'Failed to send OTP. Please check the email address.');
      btn.disabled    = false;
      btn.textContent = 'Send OTP';
    }
  } catch (err) {
    alert('Network error. Please try again.');
    btn.disabled    = false;
    btn.textContent = 'Send OTP';
  }
}

// ── Verify OTP ──  ✅ FIXED: now uses getCsrfToken() helper (no more null crash)
async function verifyOtp() {
  const otp   = document.getElementById('otpInput').value.trim();
  const email = document.getElementById('emailInput').value.trim();

  if (otp.length !== 6 || isNaN(otp)) {
    alert('Please enter the 6-digit OTP from your email.');
    return;
  }

  try {
    const res  = await fetch('{{ route("otp.verify") }}', {
      method:  'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken(),   // ✅ FIXED: was crashing when meta tag absent
      },
      body: JSON.stringify({ otp, email }),
    });

    const data = await res.json();

    if (res.ok && data.success) {
      // Mark as verified
      window.otpVerified = true;

      // Hide OTP input, stop countdown, lock email, show badge
      document.getElementById('otpInputWrapper').style.display = 'none';
      document.getElementById('otpVerifiedBadge').classList.add('show');
      document.getElementById('sendOtpBtn').style.display      = 'none';
      document.getElementById('emailInput').readOnly           = true;
      document.getElementById('otpCountdown').style.display    = 'none';

      if (countdownTimer) clearInterval(countdownTimer);
    } else {
      alert(data.message || 'Invalid OTP. Please try again.');
    }
  } catch (err) {
    alert('Network error. Please try again.');
  }
}

// ── Countdown timer ──
function startCountdown(seconds) {
  const el = document.getElementById('otpCountdown');
  el.style.display = 'block';
  el.classList.remove('expired');

  if (countdownTimer) clearInterval(countdownTimer);

  let remaining = seconds;

  countdownTimer = setInterval(function () {
    remaining--;
    const m = Math.floor(remaining / 60);
    const s = remaining % 60;
    el.textContent = `⏱ OTP expires in ${m}:${s.toString().padStart(2, '0')}`;

    if (remaining <= 0) {
      clearInterval(countdownTimer);
      el.textContent = '❌ OTP expired. Please click Resend OTP.';
      el.classList.add('expired');
      document.getElementById('otpInputWrapper').style.display = 'none';
      document.getElementById('sendOtpBtn').disabled           = false;
      document.getElementById('sendOtpBtn').textContent        = 'Resend OTP';
    }
  }, 1000);
}
</script>