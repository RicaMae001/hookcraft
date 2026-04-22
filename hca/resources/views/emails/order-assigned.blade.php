<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Order is Being Processed!</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #fdf0f7; color: #333; }
    .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 30px rgba(255,153,204,0.2); }

    /* ── Header ── */
    .header { background: linear-gradient(135deg, #a78bfa 0%, #c4b5fd 100%); padding: 40px 30px; text-align: center; }
    .header .brand { font-size: 20px; font-weight: 800; color: white; letter-spacing: 1px; margin-bottom: 10px; }
    .header .icon { font-size: 56px; display: block; margin: 10px 0; }
    .header h1 { font-size: 24px; font-weight: 700; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }

    /* ── Body ── */
    .body { padding: 36px 40px; }
    .greeting { font-size: 18px; font-weight: 600; color: #333; margin-bottom: 12px; }
    .intro { font-size: 15px; color: #666; line-height: 1.7; margin-bottom: 28px; }

    /* ── Cards ── */
    .order-card { background: #fff8fd; border: 2px solid #ffb3d9; border-radius: 14px; padding: 24px; margin-bottom: 28px; }
    .order-card h2 { font-size: 13px; font-weight: 700; color: #ff85c0; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; border-bottom: 1px dashed #ffb3d9; padding-bottom: 10px; }

    .coordinator-card { background: #f5f3ff; border: 2px solid #c4b5fd; border-radius: 14px; padding: 24px; margin-bottom: 28px; }
    .coordinator-card h2 { font-size: 13px; font-weight: 700; color: #7c3aed; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; border-bottom: 1px dashed #c4b5fd; padding-bottom: 10px; }

    .order-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #fde8f4; font-size: 14px; }
    .coordinator-card .order-row { border-bottom-color: #ede9fe; }
    .order-row:last-child { border-bottom: none; }
    .order-row .label { color: #999; font-weight: 500; }
    .order-row .value { color: #333; font-weight: 600; text-align: right; max-width: 60%; }

    .status-badge { display: inline-block; background: linear-gradient(135deg, #a78bfa, #c4b5fd); color: white; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; }

    /* ── Progress Steps ── */
    .progress-section { margin-bottom: 28px; }
    .progress-section h2 { font-size: 13px; font-weight: 700; color: #ff85c0; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; }
    .steps-table { width: 100%; border-collapse: collapse; }
    .steps-table td { text-align: center; vertical-align: top; width: 25%; padding: 0 4px; }
    .step-dot { width: 36px; height: 36px; border-radius: 50%; display: inline-block; line-height: 36px; font-size: 16px; margin-bottom: 8px; }
    .step-dot.done    { background: linear-gradient(135deg, #ff85c0, #ffb3d9); color: white; }
    .step-dot.active  { background: linear-gradient(135deg, #a78bfa, #c4b5fd); color: white; outline: 4px solid rgba(167,139,250,0.25); }
    .step-dot.pending { background: #fde8f4; color: #ccc; }
    .step-label { font-size: 11px; font-weight: 600; color: #999; display: block; }
    .step-label.active { color: #7c3aed; }

    /* ── Items table ── */
    .items-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    .items-table th { font-size: 12px; color: #ff85c0; text-align: left; padding: 6px 0; border-bottom: 1px dashed #ffb3d9; }
    .items-table td { font-size: 13px; color: #333; padding: 8px 0; border-bottom: 1px solid #fde8f4; }
    .items-table tr:last-child td { border-bottom: none; }
    .total-row { display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, rgba(255,133,192,0.1), rgba(255,179,217,0.1)); border-radius: 10px; padding: 12px 16px; margin-top: 12px; }
    .total-row .total-label { font-size: 15px; font-weight: 700; color: #333; }
    .total-row .total-value { font-size: 17px; font-weight: 800; color: #ff85c0; }

    /* ── CTA ── */
    .cta-section { text-align: center; margin-bottom: 28px; }
    .cta-btn { display: inline-block; background: linear-gradient(135deg, #a78bfa 0%, #c4b5fd 100%); color: white; text-decoration: none; padding: 14px 36px; border-radius: 50px; font-size: 15px; font-weight: 700; letter-spacing: 0.5px; }

    /* ── Footer ── */
    .footer { background: #fff0f8; border-top: 1px solid #fde8f4; padding: 24px 40px; text-align: center; }
    .footer p { font-size: 13px; color: #aaa; line-height: 1.6; }
    .footer .brand-name { color: #ff85c0; font-weight: 700; }
  </style>
</head>
<body>
  <div class="wrapper">

    {{-- Header --}}
    <div class="header">
      <div class="brand">🪡 Hookcraft Avenue</div>
      <span class="icon">🚀</span>
      <h1>Your Order is Being Processed!</h1>
    </div>

    {{-- Body --}}
    <div class="body">
      <p class="greeting">Hi {{ $order->customer_name }},</p>
      <p class="intro">
        Great news! Your order has been reviewed and a delivery coordinator has been assigned.
        Your handmade treasure is now being prepared and will be on its way to you soon! 💜
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
          <span class="value"><span class="status-badge">🚀 Processing</span></span>
        </div>
      </div>

      {{-- Coordinator Info --}}
      <div class="coordinator-card">
        <h2>🧑‍💼 Your Assigned Delivery Coordinator</h2>
        <div class="order-row">
          <span class="label">Name</span>
          <span class="value">{{ $coordinator->name }}</span>
        </div>
        @if(!empty($coordinator->phone))
        <div class="order-row">
          <span class="label">Contact Number</span>
          <span class="value">{{ $coordinator->phone }}</span>
        </div>
        @endif
        <div class="order-row">
          <span class="label">Role</span>
          <span class="value">Delivery Coordinator</span>
        </div>
      </div>

      {{-- Order Items --}}
      @if($order->orderItems->count())
      <div class="order-card">
        <h2>🛍 Items in Your Order</h2>
        <table class="items-table">
          <thead>
            <tr>
              <th>Product</th>
              <th style="text-align:center;">Qty</th>
              <th style="text-align:right;">Price</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->orderItems as $item)
              @if($item->product)
              <tr>
                <td>{{ $item->product->name }}</td>
                <td style="text-align:center;">{{ $item->quantity }}</td>
                <td style="text-align:right;">₱{{ number_format($item->price, 2) }}</td>
              </tr>
              @endif
            @endforeach
          </tbody>
        </table>
        <div class="total-row">
          <span class="total-label">Grand Total</span>
          <span class="total-value">₱{{ number_format($order->grand_total, 2) }}</span>
        </div>
      </div>
      @endif

      {{-- Progress Steps --}}
      <div class="progress-section">
        <h2>🗺 Order Progress</h2>
        <table class="steps-table">
          <tr>
            <td>
              <span class="step-dot done">✓</span>
              <span class="step-label">Order Placed</span>
            </td>
            <td>
              <span class="step-dot active">🚀</span>
              <span class="step-label active">Processing</span>
            </td>
            <td>
              <span class="step-dot pending">🚚</span>
              <span class="step-label">Out for Delivery</span>
            </td>
            <td>
              <span class="step-dot pending">📦</span>
              <span class="step-label">Delivered</span>
            </td>
          </tr>
        </table>
      </div>

      {{-- CTA --}}
      <div class="cta-section">
        <a href="{{ url('/profile/orders') }}" class="cta-btn">Track My Order</a>
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