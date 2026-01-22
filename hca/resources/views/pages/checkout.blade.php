<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hookcraft Avenue - Checkout</title>

    <link rel="icon" href="{{ asset('asset/images/logo.jpg') }}" type="image/png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <style>
        :root {
            --primary-pink: #ec4899;
            --accent-rose: #fb7185;
            --dark-navy: #1e293b;
            --border-color: #e5e7eb;
            --success-green: #ec4899;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fdf2f8, #f8fafc);
        }

        .checkout-container {
            max-width: 1200px;
            margin: auto;
            padding: 2rem 1rem;
        }

        .card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: #fff;
        }

        #map {
            height: 350px;
            border-radius: 10px;
            border: 2px solid var(--border-color);
            margin-bottom: 1rem;
        }

        .product-item {
            display: flex;
            gap: 1rem;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: .75rem;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
        }

        .total-highlight {
            background: linear-gradient(135deg, var(--primary-pink), var(--accent-rose));
            color: white;
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            font-size: 1.4rem;
            font-weight: bold;
        }

        footer {
            background: #0f172a;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
    </style>
</head>

<body>

@include('components.login_modal')
@include('components.signup_modal')
@include('components.navbar')

<div class="checkout-container">

    <h2 class="text-center mb-4">
        <i class="bi bi-credit-card text-danger"></i> Checkout
    </h2>

    @if($cartItems->isEmpty())
        <div class="alert alert-info text-center">
            Your cart is empty.
            <a href="{{ route('shop') }}" class="btn btn-primary mt-3">Shop Now</a>
        </div>
    @else
    <div class="row">

        <!-- CUSTOMER INFO -->
        <div class="col-lg-7">
            <form method="POST" action="{{ route('checkout.store') }}">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h5>Customer Information</h5>
                    </div>

                    <div class="card-body">
                        <input class="form-control mb-3" name="name" placeholder="Full Name" required>
                        <input class="form-control mb-3" name="phone" placeholder="09123456789" required>

                        <input id="mapSearch" class="form-control mb-2" placeholder="Search location">
                        <div id="map"></div>

                        <input type="hidden" id="latitude" name="latitude">
                        <input type="hidden" id="longitude" name="longitude">

                        <input class="form-control mt-2" name="street" placeholder="Street / House No." required>

                        <div class="mt-3">
                            <label><strong>Payment Method</strong></label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="GCash" checked>
                                <label class="form-check-label">GCash</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" value="COD">
                                <label class="form-check-label">Cash on Delivery</label>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-success btn-lg w-100">
                    Place Order
                </button>
            </form>
        </div>

        <!-- ORDER SUMMARY -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <h5>Order Summary</h5>
                </div>

                <div class="card-body">
                    @foreach($cartItems as $item)
                        <div class="product-item">
                            <img class="product-image" src="{{ asset('asset/images/'.$item->product->image) }}">
                            <div class="flex-grow-1">
                                <strong>{{ $item->product->name }}</strong><br>
                                Qty: {{ $item->quantity }}
                            </div>
                            ₱{{ number_format($item->product->price * $item->quantity, 2) }}
                        </div>
                    @endforeach

                    <div class="total-highlight mt-3">
                        Total: ₱{{ number_format($total, 2) }}
                    </div>
                </div>
            </div>
        </div>

    </div>
    @endif
</div>

<footer class="text-center">
    &copy; 2025 Hookcraft Avenue
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
const map = L.map('map').setView([10.3157, 123.8854], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

let marker = null;

map.on('click', e => {
    if (marker) map.removeLayer(marker);
    marker = L.marker(e.latlng).addTo(map);
    document.getElementById('latitude').value = e.latlng.lat;
    document.getElementById('longitude').value = e.latlng.lng;
});
</script>

</body>
</html>
