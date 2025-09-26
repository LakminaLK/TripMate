<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripMate OTP Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f3f4f6;
        }
        .email-container {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
        }
        .otp-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .otp-code {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            padding: 20px 30px;
            border-radius: 10px;
            margin: 20px 0;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
        }
        .instructions {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .security-note {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 0 5px 5px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
            padding: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 20px 0;
        }
        .help-section {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🌍 TripMate</div>
            <h1>Verify Your Account</h1>
            <p>Complete your registration with the verification code below</p>
        </div>

        <div class="content">
            <h2>Hello there! 👋</h2>
            
            <p>Thank you for joining <strong>TripMate</strong>! We're excited to help you discover amazing destinations and create unforgettable memories.</p>
            
            <div class="otp-card">
                <h3 style="margin-top: 0; color: #374151;">Your Verification Code</h3>
                <div class="otp-code">{{ $otp }}</div>
                <p style="color: #6b7280; margin-bottom: 0;">This code is valid for <strong>10 minutes</strong></p>
            </div>
            
            <div class="instructions">
                <h4 style="margin-top: 0; color: #1f2937;">📋 How to use this code:</h4>
                <ol style="margin: 10px 0; padding-left: 20px;">
                    <li>Return to the TripMate registration page</li>
                    <li>Enter this 6-digit code in the verification popup</li>
                    <li>Click "Verify OTP" to complete your registration</li>
                </ol>
            </div>
            
            <div class="security-note">
                <h4 style="margin-top: 0; color: #92400e;">🔒 Security Notice:</h4>
                <p style="margin-bottom: 0;">
                    Keep this code confidential. TripMate will never ask for your verification code via phone or email. 
                    If you didn't request this code, please ignore this email.
                </p>
            </div>
            
            <div class="divider"></div>
            
            <div class="help-section">
                <h4 style="margin-top: 0; color: #374151;">Need Help? 🤝</h4>
                <p style="margin-bottom: 0;">
                    If you're having trouble with verification, please contact our support team.<br>
                    We're here to help you get started on your journey!
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>TripMate</strong> - Your Gateway to Amazing Adventures</p>
            <p style="margin: 10px 0;">This is an automated message, please do not reply to this email.</p>
            <div style="margin-top: 20px;">
                <span style="color: #9ca3af;">© {{ date('Y') }} TripMate. All rights reserved.</span>
            </div>
        </div>
    </div>
</body>
</html>
