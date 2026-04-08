<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Order Placed!</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #fdf0f7; color: #333; }
    .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 30px rgba(255,153,204,0.2); }
    .header { background: linear-gradient(135deg, #ff85c0 0%, #ffb3d9 100%); padding: 40px 30px; text-align: center; }
    .header .brand { font-size: 20px; font-weight: 800; color: white; letter-spacing: 1px; margin-bottom: 10px; }
    .header .icon { font-size: 56px; display: block; margin: 10px 0; }
    .header h1 { font-size: 24px; font-weight: 700; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .body { padding: 36px 40px; }
    .greeting { font-size: 18px; font-weight: 600; color: #333; margin-bottom: 12px; }
    .intro { font-size: 15px; color: #666; line-height: 1.7; margin-bottom: 28px; }
    .order-card { background: #fff8fd; border: 2px solid #ffb3d9; border-radius: 14px; padding: 24px; margin-bottom: 28px; }
    .order-card h2 { font-size: 13px; font-weight: 700; color: #ff85c0; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; border-bottom: 1px dashed #ffb3d9; padding-bottom: 10px; }
    .order-row { display: flex; justify-content: space-between; align-items: center; padding: 8px 0; border-bottom: 1px solid #fde8f4; font-size: 14px; }
    .order-row:last-child { border-bottom: none; }
    .order-row .label { color: #999; font-weight: 500; }
    .order-row .value { color: #333; font-weight: 600; text-align: right; max-width: 60%; }
    .status-badge { display: inline-block; background: linear-gradient(135deg, #ff85c0, #ffb3d9); color: white; padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 700; }
    .items-table { width: 100%; border-collapse: collapse; margin-top: 8px; }
    .items-table th { font-size: 12px; color: #ff85c0; text-align: left; padding: 6px 0; border-bottom: 1px dashed #ffb3d9; }
    .items-table td { font-size: 13px; color: #333; padding: 8px 0; border-bottom: 1px solid #fde8f4; }
    .items-table tr:last-child td { border-bottom: none; }
    .total-row { display: flex; justify-content: space-between; align-items: center; background: linear-gradient(135deg, rgba(255,133,192,0.1), rgba(255,179,217,0.1)); border-radius: 10px; padding: 12px 16px; margin-top: 12px; }
    .total-row .total-label { font-size: 15px; font-weight: 700; color: #333; }
    .total-row .total-value { font-size: 17px; font-weight: 800; color: #ff85c0; }
    .cta-section { text-align: center; margin-bottom: 28px; }
    .cta-btn { display: inline-block; background: linear-gradient(135deg, #ff85c0 0%, #ffb3d9 100%); color: white; text-decoration: none; padding: 14px 36px; border-radius: 50px; font-size: 15px; font-weight: 700; letter-spacing: 0.5px; }
    .footer { background: #fff0f8; border-top: 1px solid #fde8f4; padding: 24px 40px; text-align: center; }
    .footer p { font-size: 13px; color: #aaa; line-height: 1.6; }
    .footer .brand-name { color: #ff85c0; font-weight: 700; }
  </style>
</head>
<body>
  <div class="wrapper">

    <div class="header">
      <div class="brand">🪡 Hookcraft Avenue</div>
      <span class="icon">🌸</span>
      <h1>Order Successfully Placed!</h1>
    </div>

    <div class="body">
      <p class="greeting">Hi {{ $order->customer_name }},</p>
      <p class="intro">
        Thank you for your order! We've received it and will start processing it shortly.
        You'll receive another email once your order is out for delivery. 💕
      </p>

      <div class="order-card">
        <h2>📦 Order Summary</h2>
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
          <span class="value"><span class="status-badge">🌸 Order Placed</span></span>
        </div>
      </div>

      {{-- Order Items --}}
      @if($order->orderItems->count())
      <div class="order-card">
        <h2>🛍 Items Ordered</h2>
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

      <div class="cta-section">
        <a href="{{ url('/profile/orders') }}" class="cta-btn">View My Order</a>
      </div>

      <p style="font-size:14px; color:#999; text-align:center;">
        If you have any questions, feel free to reach out to us anytime. 💕
      </p>
    </div>

    <div class="footer">
      <p>
        Thank you for shopping with <span class="brand-name">Hookcraft Avenue</span>! 🪡<br>
        This is an automated email — please do not reply directly to this message.
      </p>
    </div>

  </div>
</body>
</html>