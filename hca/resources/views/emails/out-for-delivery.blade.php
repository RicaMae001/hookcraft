<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Order is Out for Delivery!</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #fdf0f7; color: #333; }

    .wrapper {
      max-width: 600px;
      margin: 40px auto;
      background: #ffffff;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 8px 30px rgba(255,153,204,0.2);
    }

    /* ── Header ── */
    .header {
      background: linear-gradient(135deg, #ff85c0 0%, #ffb3d9 100%);
      padding: 40px 30px;
      text-align: center;
    }
    .header .brand {
      font-size: 20px; font-weight: 800;
      color: white; letter-spacing: 1px; margin-bottom: 10px;
    }
    .header .truck-icon { font-size: 56px; display: block; margin: 10px 0; }
    .header h1 {
      font-size: 24px; font-weight: 700; color: white;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    /* ── Body ── */
    .body { padding: 36px 40px; }
    .greeting { font-size: 18px; font-weight: 600; color: #333; margin-bottom: 12px; }
    .intro { font-size: 15px; color: #666; line-height: 1.7; margin-bottom: 28px; }

    /* ── Order Card ── */
    .order-card {
      background: #fff8fd;
      border: 2px solid #ffb3d9;
      border-radius: 14px;
      padding: 24px;
      margin-bottom: 28px;
    }
    .order-card h2 {
      font-size: 13px; font-weight: 700; color: #ff85c0;
      text-transform: uppercase; letter-spacing: 1px;
      margin-bottom: 16px; border-bottom: 1px dashed #ffb3d9; padding-bottom: 10px;
    }
    .order-row {
      display: flex; justify-content: space-between; align-items: center;
      padding: 8px 0; border-bottom: 1px solid #fde8f4; font-size: 14px;
    }
    .order-row:last-child { border-bottom: none; }
    .order-row .label { color: #999; font-weight: 500; }
    .order-row .value { color: #333; font-weight: 600; text-align: right; max-width: 60%; }
    .status-badge {
      display: inline-block;
      background: linear-gradient(135deg, #ff85c0, #ffb3d9);
      color: white; padding: 4px 14px; border-radius: 20px;
      font-size: 13px; font-weight: 700;
    }

    /* ── Product Item ── */
    .product-item {
      display: flex; align-items: center; gap: 14px;
      padding: 10px 0; border-bottom: 1px solid #fde8f4;
    }
    .product-item:last-child { border-bottom: none; }
    .product-item img {
      width: 64px; height: 64px; border-radius: 10px;
      object-fit: cover; border: 2px solid #ffb3d9; flex-shrink: 0;
    }
    .product-info { flex: 1; }
    .product-name { font-size: 14px; font-weight: 600; color: #333; }
    .product-meta { font-size: 13px; color: #999; margin-top: 4px; }

    /* ── Progress Steps ── */
    .progress-section { margin-bottom: 28px; }
    .progress-section h2 {
      font-size: 13px; font-weight: 700; color: #ff85c0;
      text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;
    }
    .steps-table { width: 100%; border-collapse: collapse; }
    .steps-table td { text-align: center; vertical-align: top; width: 25%; padding: 0 4px; }
    .step-dot {
      width: 36px; height: 36px; border-radius: 50%;
      display: inline-block; line-height: 36px; font-size: 16px; margin-bottom: 8px;
    }
    .step-dot.done   { background: linear-gradient(135deg, #ff85c0, #ffb3d9); color: white; }
    .step-dot.active { background: linear-gradient(135deg, #ff85c0, #ffb3d9); color: white; outline: 4px solid rgba(255,133,192,0.25); }
    .step-dot.pending { background: #fde8f4; color: #ccc; }
    .step-label { font-size: 11px; font-weight: 600; color: #999; display: block; }
    .step-label.active { color: #ff85c0; }

    /* ── CTA ── */
    .cta-section { text-align: center; margin-bottom: 28px; }
    .cta-btn {
      display: inline-block;
      background: linear-gradient(135deg, #ff85c0 0%, #ffb3d9 100%);
      color: white; text-decoration: none;
      padding: 14px 36px; border-radius: 50px;
      font-size: 15px; font-weight: 700; letter-spacing: 0.5px;
    }

    /* ── Footer ── */
    .footer {
      background: #fff0f8; border-top: 1px solid #fde8f4;
      padding: 24px 40px; text-align: center;
    }
    .footer p { font-size: 13px; color: #aaa; line-height: 1.6; }
    .footer .brand-name { color: #ff85c0; font-weight: 700; }
  </style>
</head>
<body>
  <div class="wrapper">

    {{-- Header --}}
    <div class="header">
      <div class="brand">🪡 Hookcraft Avenue</div>
      <span class="truck-icon">🚚</span>
      <h1>Your Order is On Its Way!</h1>
    </div>

    {{-- Body --}}
    <div class="body">
      <p class="greeting">Hi {{ $order->customer_name }},</p>
      <p class="intro">
        Great news! Your order has been picked up and is now <strong>out for delivery</strong>.
        Our delivery coordinator is heading your way — please make sure someone is available to receive it.
      </p>

      {{-- Order Details --}}
      <div class="order-card">
        <h2>📦 Order Details</h2>
        <div class="order-row">
          <span class="label">Order Number</span>
          <span class="value">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="order-row">
          <span class="label">Customer</span>
          <span class="value">{{ $order->customer_name }}</span>
        </div>
        <div class="order-row">
          <span class="label">Delivery Address</span>
          <span class="value">{{ $order->address }}</span>
        </div>
        @if(!empty($order->phone))
        <div class="order-row">
          <span class="label">Contact Number</span>
          <span class="value">{{ $order->phone }}</span>
        </div>
        @endif
        <div class="order-row">
          <span class="label">Payment Method</span>
          <span class="value">{{ $order->payment_method }}</span>
        </div>
        <div class="order-row">
          <span class="label">Status</span>
          <span class="value"><span class="status-badge">🚚 Out for Delivery</span></span>
        </div>
      </div>

      {{-- Delivery Coordinator --}}
      @if($order->coordinator)
      <div class="order-card">
        <h2>🧑‍💼 Your Delivery Coordinator</h2>
        <div class="order-row">
          <span class="label">Name</span>
          <span class="value">{{ $order->coordinator->name }}</span>
        </div>
        @if(!empty($order->coordinator->phone))
        <div class="order-row">
          <span class="label">Contact Number</span>
          <span class="value">{{ $order->coordinator->phone }}</span>
        </div>
        @endif
      </div>
      @endif

      {{-- Order Items --}}
      @if($order->orderItems->count())
      <div class="order-card">
        <h2>🛍 Items in Your Order</h2>
        @foreach($order->orderItems as $item)
          @if($item->product)
          <div class="product-item">
            <img
               src="{{ asset('asset/images/' . $item->product->image) }}"
    alt="{{ $item->product->name }}"
            />
            <div class="product-info">
              <div class="product-name">{{ $item->product->name }}</div>
              <div class="product-meta">
                Qty: {{ $item->quantity }} &nbsp;·&nbsp; ₱{{ number_format($item->price, 2) }}
              </div>
            </div>
          </div>
          @endif
        @endforeach
      </div>
      @endif

      {{-- Progress Steps --}}
      <div class="progress-section">
        <h2>🗺 Delivery Progress</h2>
        <table class="steps-table">
          <tr>
            <td>
              <span class="step-dot done">✓</span>
              <span class="step-label">Order Placed</span>
            </td>
            <td>
              <span class="step-dot done">✓</span>
              <span class="step-label">Processing</span>
            </td>
            <td>
              <span class="step-dot active">🚚</span>
              <span class="step-label active">Out for Delivery</span>
            </td>
            <td>
              <span class="step-dot pending">📦</span>
              <span class="step-label">Delivered</span>
            </td>
          </tr>
        </table>
      </div>

      {{-- CTA Button --}}
      <div class="cta-section">
        <a href="{{ url('/profile/track-order') }}" class="cta-btn">Track My Order</a>
      </div>

      <p style="font-size:14px; color:#999; text-align:center;">
        If you have any questions, feel free to reach out to us anytime. 💕
      </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
      <p>
        Thank you for shopping with <span class="brand-name">Hookcraft Avenue</span>! 🪡<br>
        This is an automated email — please do not reply directly to this message.
      </p>
    </div>

  </div>
</body>
</html>