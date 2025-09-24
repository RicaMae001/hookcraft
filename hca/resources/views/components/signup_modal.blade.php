<div class="modal fade" id="signupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Sign Up</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="text" name="name" class="form-control mb-2" placeholder="Name" required>
          <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
          <input type="password" name="password" class="form-control mb-2" placeholder="Password" required>
          <input type="password" name="password_confirmation" class="form-control mb-2" placeholder="Confirm Password" required>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Sign Up</button>
        </div>
      </form>
    </div>
  </div>
</div>
