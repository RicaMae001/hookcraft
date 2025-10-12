<div class="modal fade" id="signupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('register') }}" id="signupForm">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Sign Up</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <!-- Display all validation errors -->
          @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <strong>Please fix the following errors:</strong>
              <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <!-- Success message -->
          @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
          @endif

          <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                   placeholder="Full Name" value="{{ old('name') }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   placeholder="Email Address" value="{{ old('email') }}" required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Password <small class="text-muted">(minimum 6 characters)</small></label>
            <input type="password" name="password" id="password" 
                   class="form-control @error('password') is-invalid @enderror" 
                   placeholder="Password" minlength="6" required>
            <small class="text-muted">Must be at least 6 characters long</small>
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="form-control" placeholder="Confirm Password" minlength="6" required>
            <div class="invalid-feedback" id="passwordMatchError" style="display: none;">
              Passwords do not match
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success w-100">Sign Up</button>
        </div>
      </form>
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
        passwordMatchError.style.display = 'block';
        return false;
      } else {
        passwordConfirmation.classList.remove('is-invalid');
        passwordMatchError.style.display = 'none';
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