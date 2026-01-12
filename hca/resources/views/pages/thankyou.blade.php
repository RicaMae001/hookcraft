<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - Thank You</title>
    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('asset/stylesthankyou.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/stylesnav.css') }}">
</head>
<body>

@include('components.login_modal')
@include('components.signup_modal')

<!-- Navbar -->
 @include('components.navbar')
<!-- Thank You Section -->
<section class="thankyou py-5">
    <div class="container text-center">
        <div class="card shadow-lg p-5 rounded-4">
            <div class="mb-4 text-success">
                <i class="bi bi-check-circle-fill display-1"></i>
            </div>
            <h1 class="mb-3">Thank You for Your Order!</h1>
            <p class="lead">Your order <strong>#{{ $order_id ?? '' }}</strong> has been placed successfully.</p>
            <p class="text-muted">We’ll contact you soon to confirm your delivery details.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary mt-4">
                <i class="bi bi-shop me-2"></i>Continue Shopping
            </a>
        </div>
    </div>
</section>

<!-- Footer -->
@include('components.footer')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
