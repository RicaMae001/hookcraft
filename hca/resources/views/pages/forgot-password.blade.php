<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Forgot Password — Hookcraft Avenue</title>
  <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: linear-gradient(135deg, #fdf0f7 0%, #fff5fb 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .card {
      background: white;
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(255, 133, 192, 0.2);
      width: 100%;
      max-width: 460px;
      overflow: hidden;
    }

    /* ── Header ── */
    .card-header {
      background: linear-gradient(135deg, #ff85c0 0%, #ffb3d9 100%);
      padding: 32px 40px;
      text-align: center;
    }
    .card-header .brand { font-size: 16px; font-weight: 800; color: white; letter-spacing: 1px; margin-bottom: 8px; }
    .card-header .icon { font-size: 44px; display: block; margin: 8px 0; }
    .card-header h1 { font-size: 22px; font-weight: 700; color: white; }
    .card-header p { font-size: 13px; color: rgba(255,255,255,0.85); margin-top: 6px; }

    /* ── Steps indicator ── */
    .steps {
      display: flex;
      justify-content: center;
      gap: 8px;
      padding: 20px 40px 0;
    }
    .step-dot {
      width: 10px; height: 10px; border-radius: 50%;
      background: #fde8f4;
      transition: background 0.3s;
    }
    .step-dot.active { background: #ff85c0; }
    .step-dot.done   { background: #ffb3d9; }

    /* ── Body ── */
    .card-body { padding: 28px 40px 36px; }

    .step-panel { display: none; }
    .step-panel.active { display: block; }

    .step-title {
      font-size: 17px; font-weight: 700; color: #333;
      margin-bottom: 6px;
    }
    .step-sub {
      font-size: 13px; color: #999;
      margin-bottom: 22px; line-height: 1.5;
    }
    .step-sub strong { color: #ff85c0; }

    /* ── Form fields ── */
    .form-group { margin-bottom: 16px; }
    .form-label {
      display: block; font-size: 13px; font-weight: 600;
      color: #555; margin-bottom: 6px;
    }
    .form-control {
      width: 100%; padding: 12px 14px;
      border: 1.5px solid #e8e8e8; border-radius: 10px;
      font-size: 15px; color: #333;
      transition: border-color 0.2s, box-shadow 0.2s;
      outline: none;
    }
    .form-control:focus {
      border-color: #ff85c0;
      box-shadow: 0 0 0 3px rgba(255,133,192,0.15);
    }

    /* ── OTP input ── */
    .otp-input {
      letter-spacing: 8px; font-size: 24px; font-weight: 700;
      text-align: center; font-family: 'Courier New', monospace;
    }

    /* ── Password strength ── */
    .strength-bar-wrap {
      height: 4px; background: #f0f0f0; border-radius: 4px;
      margin-top: 6px; overflow: hidden;
    }
    .strength-bar {
      height: 100%; width: 0%; border-radius: 4px;
      transition: width 0.3s, background 0.3s;
    }
    .strength-text { font-size: 11px; margin-top: 4px; font-weight: 600; }

    /* ── Buttons ── */
    .btn {
      width: 100%; padding: 13px;
      border: none; border-radius: 50px;
      font-size: 15px; font-weight: 700;
      cursor: pointer; transition: all 0.2s;
      display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-primary {
      background: linear-gradient(135deg, #ff85c0, #ffb3d9);
      color: white;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255,133,192,0.35); }
    .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; box-shadow: none; }

    /* ── Resend / Back ── */
    .helper-row {
      display: flex; justify-content: space-between;
      align-items: center; margin-top: 14px;
      font-size: 13px;
    }
    .link-btn {
      background: none; border: none; cursor: pointer;
      color: #ff85c0; font-weight: 600; font-size: 13px;
      padding: 0; text-decoration: underline;
    }
    .link-btn:disabled { color: #ccc; cursor: not-allowed; text-decoration: none; }
    .timer { color: #999; font-size: 12px; }

    /* ── Alert ── */
    .alert {
      padding: 10px 14px; border-radius: 8px;
      font-size: 13px; margin-bottom: 16px;
      display: none; align-items: center; gap: 8px;
    }
    .alert.show { display: flex; }
    .alert-error   { background: #fef2f2; border: 1px solid #fca5a5; color: #991b1b; }
    .alert-success { background: #f0fdf4; border: 1px solid #86efac; color: #166534; }

    /* ── Success panel ── */
    .success-panel {
      text-align: center; padding: 10px 0;
    }
    .success-icon { font-size: 60px; display: block; margin-bottom: 16px; }
    .success-panel h2 { font-size: 20px; font-weight: 700; color: #333; margin-bottom: 8px; }
    .success-panel p { font-size: 14px; color: #999; margin-bottom: 24px; line-height: 1.6; }

    /* ── Back to login ── */
    .back-link {
      display: block; text-align: center;
      margin-top: 18px; font-size: 13px; color: #999;
    }
    .back-link a { color: #ff85c0; font-weight: 600; text-decoration: none; }
    .back-link a:hover { text-decoration: underline; }

    /* ── Spinner ── */
    .spinner {
      width: 16px; height: 16px;
      border: 2px solid rgba(255,255,255,0.4);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.7s linear infinite;
      display: none;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
  </style>
</head>
<body>

<div class="card">

  <!-- Header -->
  <div class="card-header">
    <div class="brand">🪡 Hookcraft Avenue</div>
    <span class="icon">🔐</span>
    <h1>Forgot Password</h1>
    <p>Reset your password in 3 easy steps</p>
  </div>

  <!-- Step dots -->
  <div class="steps">
    <div class="step-dot active" id="dot1"></div>
    <div class="step-dot"        id="dot2"></div>
    <div class="step-dot"        id="dot3"></div>
  </div>

  <div class="card-body">

    <!-- ── STEP 1: Email ── -->
    <div class="step-panel active" id="step1">
      <div class="step-title">Enter your email</div>
      <div class="step-sub">We'll send a 6-digit OTP to verify it's you.</div>

      <div class="alert alert-error" id="step1Error"></div>

      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" id="emailInput" class="form-control" placeholder="you@example.com" autocomplete="email"/>
      </div>

      <button class="btn btn-primary" id="sendOtpBtn" onclick="sendOtp()">
        <span id="sendOtpText">Send OTP</span>
        <span class="spinner" id="sendOtpSpinner"></span>
      </button>

      <p class="back-link">
        Remembered it? <a href="{{ route('home') }}">Back to login</a>
      </p>
    </div>

    <!-- ── STEP 2: OTP Verify ── -->
    <div class="step-panel" id="step2">
      <div class="step-title">Enter OTP</div>
      <div class="step-sub">We sent a 6-digit code to <strong id="emailDisplay"></strong>. It expires in 5 minutes.</div>

      <div class="alert alert-error"   id="step2Error"></div>
      <div class="alert alert-success" id="step2Success"></div>

      <div class="form-group">
        <label class="form-label">6-Digit OTP</label>
        <input type="text" id="otpInput" class="form-control otp-input"
               placeholder="••••••" maxlength="6" inputmode="numeric"
               oninput="this.value=this.value.replace(/\D/g,'')"/>
      </div>

      <button class="btn btn-primary" id="verifyOtpBtn" onclick="verifyOtp()">
        <span id="verifyOtpText">Verify OTP</span>
        <span class="spinner" id="verifyOtpSpinner"></span>
      </button>

      <div class="helper-row">
        <button class="link-btn" onclick="goBack(1)">← Change email</button>
        <span>
          <button class="link-btn" id="resendBtn" onclick="resendOtp()" disabled>Resend OTP</button>
          <span class="timer" id="timerDisplay"></span>
        </span>
      </div>
    </div>

    <!-- ── STEP 3: New Password ── -->
    <div class="step-panel" id="step3">
      <div class="step-title">Set new password</div>
      <div class="step-sub">Choose a strong password for your account.</div>

      <div class="alert alert-error"   id="step3Error"></div>
      <div class="alert alert-success" id="step3Success"></div>

      <div class="form-group">
        <label class="form-label">New Password</label>
        <input type="password" id="newPassword" class="form-control"
               placeholder="At least 8 characters"
               oninput="checkStrength(this.value)"/>
        <div class="strength-bar-wrap">
          <div class="strength-bar" id="strengthBar"></div>
        </div>
        <div class="strength-text" id="strengthText"></div>
      </div>

      <div class="form-group">
        <label class="form-label">Confirm Password</label>
        <input type="password" id="confirmPassword" class="form-control" placeholder="Repeat password"/>
      </div>

      <button class="btn btn-primary" id="resetBtn" onclick="resetPassword()">
        <span id="resetText">Reset Password</span>
        <span class="spinner" id="resetSpinner"></span>
      </button>
    </div>

    <!-- ── SUCCESS ── -->
    <div class="step-panel" id="stepSuccess">
      <div class="success-panel">
        <span class="success-icon">🎉</span>
        <h2>Password Reset!</h2>
        <p>Your password has been successfully updated. You can now log in with your new password.</p>
        <button class="btn btn-primary" onclick="window.location.href='{{ route('home') }}'">
          Go to Login
        </button>
      </div>
    </div>

  </div>
</div>

<script>
  const CSRF = document.querySelector('meta[name="csrf-token"]').content;
  let currentEmail = '';
  let timerInterval = null;

  // ── Helpers ────────────────────────────────────────────────
  function setLoading(btnId, spinnerId, textId, loading) {
    document.getElementById(btnId).disabled = loading;
    document.getElementById(spinnerId).style.display = loading ? 'inline-block' : 'none';
    document.getElementById(textId).style.opacity    = loading ? '0' : '1';
  }

  function showAlert(id, message, type = 'error') {
    const el = document.getElementById(id);
    el.className = 'alert show alert-' + type;
    el.textContent = message;
  }

  function hideAlert(id) {
    document.getElementById(id).classList.remove('show');
  }

  function goToStep(n) {
    document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
    document.getElementById('step' + n).classList.add('active');

    // Update dots
    [1,2,3].forEach(i => {
      const dot = document.getElementById('dot' + i);
      dot.classList.remove('active', 'done');
      if (i < n)  dot.classList.add('done');
      if (i === n) dot.classList.add('active');
    });
  }

  function goBack(n) { goToStep(n); }

  // ── Timer ──────────────────────────────────────────────────
  function startTimer(seconds) {
    clearInterval(timerInterval);
    document.getElementById('resendBtn').disabled = true;
    let remaining = seconds;

    timerInterval = setInterval(() => {
      remaining--;
      document.getElementById('timerDisplay').textContent = remaining > 0 ? `(${remaining}s)` : '';
      if (remaining <= 0) {
        clearInterval(timerInterval);
        document.getElementById('resendBtn').disabled = false;
        document.getElementById('timerDisplay').textContent = '';
      }
    }, 1000);
  }

  // ── Step 1: Send OTP ───────────────────────────────────────
  async function sendOtp() {
    hideAlert('step1Error');
    const email = document.getElementById('emailInput').value.trim();

    if (!email) { showAlert('step1Error', 'Please enter your email address.'); return; }

    setLoading('sendOtpBtn', 'sendOtpSpinner', 'sendOtpText', true);

    try {
      const res  = await fetch('{{ route('password.send-otp') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ email }),
      });
      const data = await res.json();

      if (data.success) {
        currentEmail = email;
        document.getElementById('emailDisplay').textContent = email;
        goToStep(2);
        startTimer(60);
      } else {
        showAlert('step1Error', data.message || 'Something went wrong.');
      }
    } catch (e) {
      showAlert('step1Error', 'Network error. Please try again.');
    } finally {
      setLoading('sendOtpBtn', 'sendOtpSpinner', 'sendOtpText', false);
    }
  }

  // ── Step 2: Resend OTP ─────────────────────────────────────
  async function resendOtp() {
    hideAlert('step2Error');
    hideAlert('step2Success');

    setLoading('verifyOtpBtn', 'verifyOtpSpinner', 'verifyOtpText', true);

    try {
      const res  = await fetch('{{ route('password.send-otp') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ email: currentEmail }),
      });
      const data = await res.json();

      if (data.success) {
        showAlert('step2Success', 'New OTP sent! Check your email.', 'success');
        document.getElementById('otpInput').value = '';
        startTimer(60);
      } else {
        showAlert('step2Error', data.message || 'Failed to resend OTP.');
      }
    } catch (e) {
      showAlert('step2Error', 'Network error. Please try again.');
    } finally {
      setLoading('verifyOtpBtn', 'verifyOtpSpinner', 'verifyOtpText', false);
    }
  }

  // ── Step 2: Verify OTP ─────────────────────────────────────
  async function verifyOtp() {
    hideAlert('step2Error');
    hideAlert('step2Success');

    const otp = document.getElementById('otpInput').value.trim();
    if (otp.length !== 6) { showAlert('step2Error', 'Please enter the 6-digit OTP.'); return; }

    setLoading('verifyOtpBtn', 'verifyOtpSpinner', 'verifyOtpText', true);

    try {
      const res  = await fetch('{{ route('password.verify-otp') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ email: currentEmail, otp }),
      });
      const data = await res.json();

      if (data.success) {
        clearInterval(timerInterval);
        goToStep(3);
      } else {
        showAlert('step2Error', data.message || 'Invalid OTP.');
      }
    } catch (e) {
      showAlert('step2Error', 'Network error. Please try again.');
    } finally {
      setLoading('verifyOtpBtn', 'verifyOtpSpinner', 'verifyOtpText', false);
    }
  }

  // ── Step 3: Reset Password ─────────────────────────────────
  async function resetPassword() {
    hideAlert('step3Error');
    hideAlert('step3Success');

    const password              = document.getElementById('newPassword').value;
    const password_confirmation = document.getElementById('confirmPassword').value;

    if (password.length < 8) {
      showAlert('step3Error', 'Password must be at least 8 characters.'); return;
    }
    if (password !== password_confirmation) {
      showAlert('step3Error', 'Passwords do not match.'); return;
    }

    setLoading('resetBtn', 'resetSpinner', 'resetText', true);

    try {
      const res  = await fetch('{{ route('password.reset') }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ email: currentEmail, password, password_confirmation }),
      });
      const data = await res.json();

      if (data.success) {
        // Show success panel
        document.querySelectorAll('.step-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.step-dot').forEach(d => { d.classList.remove('active'); d.classList.add('done'); });
        document.getElementById('stepSuccess').classList.add('active');
      } else {
        showAlert('step3Error', data.message || 'Failed to reset password.');
      }
    } catch (e) {
      showAlert('step3Error', 'Network error. Please try again.');
    } finally {
      setLoading('resetBtn', 'resetSpinner', 'resetText', false);
    }
  }

  // ── Password strength ──────────────────────────────────────
  function checkStrength(val) {
    const bar  = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    let score  = 0;

    if (val.length >= 8)                        score++;
    if (/[A-Z]/.test(val))                      score++;
    if (/[0-9]/.test(val))                      score++;
    if (/[^A-Za-z0-9]/.test(val))              score++;

    const levels = [
      { w: '0%',   color: '#e0e0e0', label: '' },
      { w: '25%',  color: '#ef5350', label: '😟 Weak' },
      { w: '50%',  color: '#ffa726', label: '😐 Fair' },
      { w: '75%',  color: '#66bb6a', label: '😊 Good' },
      { w: '100%', color: '#42a5f5', label: '💪 Strong' },
    ];

    bar.style.width      = levels[score].w;
    bar.style.background = levels[score].color;
    text.textContent     = levels[score].label;
    text.style.color     = levels[score].color;
  }

  // ── Enter key support ──────────────────────────────────────
  document.addEventListener('keydown', e => {
    if (e.key !== 'Enter') return;
    if (document.getElementById('step1').classList.contains('active')) sendOtp();
    if (document.getElementById('step2').classList.contains('active')) verifyOtp();
    if (document.getElementById('step3').classList.contains('active')) resetPassword();
  });
</script>
</body>
</html>