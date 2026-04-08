<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Order Cancelled</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #fdf0f7; color: #333; }
    .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 30px rgba(255,153,204,0.2); }

    /* ── Header ── */
    .header { background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); padding: 40px 30px; text-align: center; }
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

    .reason-card { background: #fef2f2; border: 2px solid #fca5a5; border-radius: 14px; padding: 24px; margin-bottom: 28px; }
    .reason-card h2 { font-size: 13px; font-weight: 700; color: #dc2626; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; border-bottom: 1px dashed #fca5a5; padding-bottom: 10px; }
    .reason-card p { font-size: 14px; color: #7f1d1d; line-height: 1.6; }

    .order-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #fde8f4; font-size: 14px; }
    .order-row:last-child { border-bottom: none; }
    .order-row .label { color: #999; font-weight: 500; }
    .order-row .value { color: #333; font-weight: 600; text-align: right; max-width: 60%; }

    .status-badge { display: inline-block; background: linear-gradient(135deg, #ef4444, #f87171); color: white; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; }
    .cancelled-by-badge { display: inline-block; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .by-customer { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }
    .by-admin    { background: #dbeafe; color: #1e40af; border: 1px solid #93c5fd; }
    .by-delivery { background: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe; }

    /* ── Items table ── */
    .items-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    .items-table th { font-size: 12px; color: #ff85c0; text-align: left; padding: 6px 0; border-bottom: 1px dashed #ffb3d9; }
    .items-table td { font-size: 13px; color: #333; padding: 8px 0; border-bottom: 1px solid #fde8f4; }
    .items-table tr:last-child td { border-bottom: none; }
    .total-row { display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, rgba(239,68,68,0.07), rgba(248,113,113,0.05)); border-radius: 10px; padding: 12px 16px; margin-top: 12px; }
    .total-row .total-label { font-size: 15px; font-weight: 700; color: #333; }
    .total-row .total-value { font-size: 17px; font-weight: 800; color: #ef4444; }

    /* ── Info box ── */
    .info-box { background: #eff6ff; border: 1px solid #93c5fd; border-radius: 10px; padding: 16px 20px; margin-bottom: 28px; font-size: 13px; color: #1e40af; line-height: 1.6; }
    .info-box strong { display: block; margin-bottom: 4px; font-size: 14px; }

    /* ── CTA ── */
    .cta-section { text-align: center; margin-bottom: 28px; }
    .cta-btn { display: inline-block; background: linear-gradient(135deg, #ff85c0 0%, #ffb3d9 100%); color: white; text-decoration: none; padding: 14px 36px; border-radius: 50px; font-size: 15px; font-weight: 700; letter-spacing: 0.5px; }

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
      <span class="icon">❌</span>
      <h1>Order Cancelled</h1>
    </div>

    {{-- Body --}}
    <div class="body">
      <p class="greeting">Hi {{ $order->customer_name }},</p>
      <p class="intro">
        @if($cancelledBy === 'customer')
          Your order has been successfully cancelled as requested. We're sorry to see it go! 💕
        @elseif($cancelledBy === 'admin')
          We regret to inform you that your order has been cancelled by our team. We sincerely apologize for any inconvenience this may have caused.
        @else
          We regret to inform you that your order has been cancelled by the delivery coordinator. We sincerely apologize for the inconvenience.
        @endif
      </p>

      {{-- Cancellation Reason --}}
      <div class="reason-card">
        <h2>📋 Cancellation Details</h2>
        <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
          <span style="font-size:13px; color:#999; font-weight:500;">Cancelled by:</span>
          @if($cancelledBy === 'customer')
            <span class="cancelled-by-badge by-customer">👤 You (Customer)</span>
          @elseif($cancelledBy === 'admin')
            <span class="cancelled-by-badge by-admin">🛡️ Hookcraft Admin</span>
          @else
            <span class="cancelled-by-badge by-delivery">🚚 Delivery Coordinator</span>
          @endif
        </div>
        <p><strong style="color:#991b1b;">Reason:</strong> {{ $reason }}</p>
      </div>

      {{-- Order Details --}}
      <div class="order-card">
        <h2>📦 Cancelled Order Details</h2>
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
          <span class="value"><span class="status-badge">❌ Cancelled</span></span>
        </div>
      </div>

      {{-- Order Items --}}
      @if($order->orderItems->count())
      <div class="order-card">
        <h2>🛍 Cancelled Items</h2>
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
          <span class="total-label">Order Total</span>
          <span class="total-value">₱{{ number_format($order->grand_total, 2) }}</span>
        </div>
      </div>
      @endif

      {{-- Refund info if paid --}}
      @if($order->payment_status === 'Paid' || $order->payment_status === 'Refunded')
      <div class="info-box">
        <strong>💳 Refund Information</strong>
        Since your order was already paid, a refund will be processed within 5–7 business days.
        Please contact us if you have any concerns about your refund.
      </div>
      @endif

      {{-- CTA --}}
      <div class="cta-section">
        <a href="{{ url('/shop') }}" class="cta-btn">Continue Shopping 🌸</a>
      </div>

      <p style="font-size:14px; color:#999; text-align:center;">
        If you have any questions or concerns, feel free to reach out to us anytime. 💕
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