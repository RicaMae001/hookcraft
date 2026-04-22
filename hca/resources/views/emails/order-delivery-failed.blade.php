<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Attempt Failed</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px 0; }
        .wrapper { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #FC8181, #F56565); padding: 36px; text-align: center; }
        .header h1 { margin: 0; color: #fff; font-size: 1.65rem; font-weight: 700; letter-spacing: -0.3px; }
        .header p  { margin: 8px 0 0; color: rgba(255,255,255,0.88); font-size: 0.95rem; }
        .icon-wrap { text-align: center; padding: 28px 0 4px; }
        .icon-wrap span { font-size: 3.2rem; }
        .body { padding: 8px 36px 36px; }
        .body p { color: #4A5568; line-height: 1.75; font-size: 0.96rem; margin: 0 0 14px; }
        .order-box { background: #FFF5F5; border: 1px solid #FED7D7; border-radius: 10px; padding: 18px 22px; margin: 22px 0; }
        .order-box .row { display: flex; justify-content: space-between; align-items: flex-start; padding: 8px 0; border-bottom: 1px solid #FED7D7; font-size: 0.9rem; gap: 12px; }
        .order-box .row:last-child { border-bottom: none; padding-bottom: 0; }
        .order-box .row:first-child { padding-top: 0; }
        .order-box .label { color: #718096; font-weight: 600; white-space: nowrap; }
        .order-box .value { color: #2D3748; font-weight: 700; text-align: right; }
        .notice { background: #FFFBEB; border: 1px solid #F6E05E; border-left: 4px solid #ECC94B; border-radius: 8px; padding: 14px 16px; margin: 20px 0; font-size: 0.88rem; color: #744210; line-height: 1.6; }
        .notice strong { display: block; margin-bottom: 5px; font-size: 0.9rem; }
        .steps { background: #F7FAFC; border: 1px solid #E2E8F0; border-radius: 10px; padding: 16px 20px; margin: 18px 0; }
        .steps-title { font-weight: 700; font-size: 0.88rem; color: #4A5568; margin-bottom: 12px; }
        .step { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 10px; font-size: 0.88rem; color: #4A5568; }
        .step:last-child { margin-bottom: 0; }
        .step-num { min-width: 22px; height: 22px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; font-size: 0.7rem; font-weight: 700; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
        .cta { text-align: center; margin: 26px 0 10px; }
        .cta a { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; text-decoration: none; padding: 13px 34px; border-radius: 10px; font-weight: 700; font-size: 0.95rem; display: inline-block; letter-spacing: 0.02em; }
        .divider { border: none; border-top: 1px solid #E2E8F0; margin: 24px 0; }
        .footer { background: #F7FAFC; padding: 18px 36px; text-align: center; font-size: 0.79rem; color: #A0AEC0; border-top: 1px solid #E2E8F0; line-height: 1.6; }
        .footer a { color: #667eea; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <h1>Delivery Attempt Failed</h1>
        <p>We were unable to complete your delivery</p>
    </div>

    <div class="icon-wrap"><span>🚫</span></div>

    <div class="body">
        <p>Hi <strong>{{ $order->customer_name }}</strong>,</p>
        <p>
            Our delivery coordinator attempted to deliver your order but was unable to complete it.
            This may have happened because no one was available to receive the package,
            or the address could not be located.
        </p>

        {{-- Order Summary --}}
        <div class="order-box">
            <div class="row">
                <span class="label">Order ID</span>
                <span class="value">#{{ $order->id }}</span>
            </div>
            <div class="row">
                <span class="label">Delivery Address</span>
                <span class="value">{{ $order->address }}</span>
            </div>
            <div class="row">
                <span class="label">Total Amount</span>
                <span class="value">₱{{ number_format($order->grand_total, 2) }}</span>
            </div>
            <div class="row">
                <span class="label">Payment Method</span>
                <span class="value">{{ $order->payment_method ?? 'N/A' }}</span>
            </div>
            <div class="row">
                <span class="label">Current Status</span>
                <span class="value" style="color: #E53E3E;">❌ Delivery Failed</span>
            </div>
        </div>

        {{-- What happens next --}}
        <div class="notice">
            <strong>📦 What happens next?</strong>
            Our team will review your order and reschedule a new delivery attempt.
            You don't need to do anything — we'll reach out to confirm a new schedule.
            You may also contact us directly to arrange a suitable time or update your delivery details.
        </div>

        {{-- Tips --}}
        <div class="steps">
            <div class="steps-title">💡 Tips to ensure successful delivery</div>
            <div class="step">
                <div class="step-num">1</div>
                <span>Make sure someone is available at the delivery address during business hours.</span>
            </div>
            <div class="step">
                <div class="step-num">2</div>
                <span>Double-check that your address and contact number are correct in your profile.</span>
            </div>
            <div class="step">
                <div class="step-num">3</div>
                <span>Keep your phone accessible — our coordinator may call before arriving.</span>
            </div>
        </div>

        <p style="margin-top: 18px;">
            We apologize for the inconvenience and appreciate your patience.
            You can track your order status anytime using the button below.
        </p>

        <div class="cta">
            <a href="{{ url('/profile/track-order') }}">Track Your Order</a>
        </div>

        <hr class="divider">

        <p style="font-size: 0.85rem; color: #718096; text-align: center; margin: 0;">
            Questions? Reply to this email or contact our support team.<br>
            We're here to help. 💜
        </p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} <strong>HookcraftAvenue</strong>. All rights reserved.<br>
        Delivering handmade crochet products within Cebu City, Philippines.<br>
        <a href="{{ url('/') }}">Visit our store</a>
    </div>

</div>
</body>
</html>