<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      padding: 30px;
      margin: 0;
    }
    .card {
      background: white;
      border-radius: 16px;
      padding: 40px;
      max-width: 420px;
      margin: auto;
      text-align: center;
      border: 2px solid #ffe0f0;
    }
    .brand {
      font-size: 22px;
      font-weight: bold;
      color: #ff99cc;
      margin-bottom: 6px;
    }
    .subtitle {
      color: #999;
      font-size: 14px;
      margin-bottom: 30px;
    }
    .otp-label {
      font-size: 14px;
      color: #666;
      margin-bottom: 10px;
    }
    .otp-code {
      font-size: 42px;
      font-weight: bold;
      color: #ff66b3;
      letter-spacing: 10px;
      background: #fff0f8;
      padding: 16px 24px;
      border-radius: 12px;
      display: inline-block;
      margin-bottom: 24px;
      border: 2px dashed #ffb3d9;
    }
    .note {
      color: #aaa;
      font-size: 12px;
      margin-top: 20px;
    }
    .warning {
      color: #e06090;
      font-size: 13px;
      font-weight: bold;
      margin-top: 10px;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="brand">Hookcraft Avenue</div>
    <div class="subtitle">Your One-Time Password</div>

    <div class="otp-label">Use this code to complete your registration:</div>
    <div class="otp-code">{{ $otp }}</div>

    <div class="warning">⏱ This code expires in 5 minutes.</div>
    <div class="note">If you did not request this, please ignore this email.</div>
  </div>
</body>
</html>