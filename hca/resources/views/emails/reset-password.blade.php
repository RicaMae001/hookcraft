<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Password Reset OTP</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #fdf0f7; color: #333; }
    .wrapper { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 30px rgba(255,153,204,0.2); }
    .header { background: linear-gradient(135deg, #ff85c0 0%, #ffb3d9 100%); padding: 40px 30px; text-align: center; }
    .header .brand { font-size: 20px; font-weight: 800; color: white; letter-spacing: 1px; margin-bottom: 10px; }
    .header .icon { font-size: 56px; display: block; margin: 10px 0; }
    .header h1 { font-size: 24px; font-weight: 700; color: white; text-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .body { padding: 36px 40px; text-align: center; }
    .intro { font-size: 15px; color: #666; line-height: 1.7; margin-bottom: 28px; }
    .otp-box { display: inline-block; background: linear-gradient(135deg, #fff0f8, #fde8f4); border: 2px dashed #ffb3d9; border-radius: 16px; padding: 24px 48px; margin-bottom: 28px; }
    .otp-label { font-size: 13px; font-weight: 700; color: #ff85c0; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 12px; }
    .otp-code { font-size: 48px; font-weight: 900; color: #ff85c0; letter-spacing: 12px; font-family: 'Courier New', monospace; }
    .expiry-note { font-size: 13px; color: #999; margin-bottom: 28px; }
    .expiry-note strong { color: #ff85c0; }
    .warning-box { background: #fff8e1; border: 1px solid #ffe082; border-radius: 10px; padding: 14px 20px; margin-bottom: 28px; font-size: 13px; color: #f57f17; text-align: left; }
    .warning-box strong { display: block; margin-bottom: 4px; }
    .footer { background: #fff0f8; border-top: 1px solid #fde8f4; padding: 24px 40px; text-align: center; }
    .footer p { font-size: 13px; color: #aaa; line-height: 1.6; }
    .footer .brand-name { color: #ff85c0; font-weight: 700; }
  </style>
</head>
<body>
  <div class="wrapper">

    <div class="header">
      <div class="brand">🪡 Hookcraft Avenue</div>
      <span class="icon">🔐</span>
      <h1>Password Reset Request</h1>
    </div>

    <div class="body">
      <p class="intro">
        We received a request to reset your password. Use the OTP code below to proceed.
        If you did not request this, please ignore this email — your account is safe.
      </p>

      <div class="otp-box">
        <div class="otp-label">Your OTP Code</div>
        <div class="otp-code">{{ $otp }}</div>
      </div>

      <p class="expiry-note">
        This code expires in <strong>5 minutes</strong>. Do not share it with anyone.
      </p>

      <div class="warning-box">
        <strong>⚠️ Security Notice</strong>
        Hookcraft Avenue will never ask for your OTP via phone or chat.
        If someone is asking for this code, please do not share it.
      </div>
    </div>

    <div class="footer">
      <p>
        Thank you for using <span class="brand-name">Hookcraft Avenue</span>! 🪡<br>
        This is an automated email — please do not reply directly to this message.
      </p>
    </div>

  </div>
</body>
</html>