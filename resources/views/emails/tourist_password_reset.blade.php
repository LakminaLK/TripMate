<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your TripMate Password</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
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
        .reset-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .reset-message {
            font-size: 18px;
            color: #374151;
            margin-bottom: 25px;
            line-height: 1.6;
        }
        .reset-button {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white !important;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3);
            transition: transform 0.2s ease;
        }
        .reset-button:hover {
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(239, 68, 68, 0.4);
        }
        .security-section {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .security-section h3 {
            margin-top: 0;
            color: #92400e;
            font-size: 18px;
        }
        .security-list {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }
        .security-list li {
            padding: 5px 0;
            position: relative;
            padding-left: 25px;
            color: #92400e;
        }
        .security-list li:before {
            content: "🔒";
            position: absolute;
            left: 0;
            top: 5px;
        }
        .warning-section {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .warning-section h4 {
            margin-top: 0;
            color: #dc2626;
        }
        .info-section {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .info-section h4 {
            margin-top: 0;
            color: #1e40af;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
            padding: 20px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 25px 0;
        }
        .expiry-notice {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            border: 2px dashed #f59e0b;
        }
        .expiry-notice .clock {
            font-size: 24px;
            color: #f59e0b;
            margin-bottom: 10px;
        }
        .contact-section {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .contact-section h4 {
            margin-top: 0;
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🌍 TripMate</div>
            <h1>Reset Your Password</h1>
            <p>Secure your account with a new password</p>
        </div>

        <div class="content">
            <div class="reset-card">
                <h2 style="margin-top: 0; color: #374151;">Hello {{ $user->name }}! 🔐</h2>
                <p class="reset-message">
                    We received a request to reset the password for your <strong>TripMate</strong> account. 
                    If you made this request, click the button below to set a new password.
                </p>
                
                <a href="{{ $resetUrl }}" class="reset-button">Reset My Password</a>
                
                <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">
                    This button will take you to a secure page where you can create your new password.
                </p>
            </div>
            
            <div class="expiry-notice">
                <div class="clock">⏰</div>
                <h3 style="margin: 10px 0; color: #92400e;">Time Sensitive</h3>
                <p style="margin-bottom: 0; color: #92400e;">
                    This password reset link will expire in <strong>60 minutes</strong> for your security.
                </p>
            </div>
            
            <div class="security-section">
                <h3>🛡️ Security Information:</h3>
                <ul class="security-list">
                    <li>This link can only be used once</li>
                    <li>The link expires in 60 minutes</li>
                    <li>Only you should have access to this email</li>
                    <li>We never ask for passwords via email</li>
                </ul>
            </div>
            
            <div class="warning-section">
                <h4>⚠️ Didn't Request This?</h4>
                <p style="margin-bottom: 0; color: #dc2626;">
                    If you didn't request a password reset, please ignore this email. Your password will remain unchanged. 
                    However, if you're concerned about your account security, please contact our support team immediately.
                </p>
            </div>
            
            <div class="info-section">
                <h4>🔗 Alternative Method</h4>
                <p style="margin-bottom: 10px; color: #1e40af;">
                    If the button above doesn't work, copy and paste this link into your browser:
                </p>
                <p style="margin-bottom: 0; word-break: break-all; font-size: 12px; color: #1e40af; background: #dbeafe; padding: 10px; border-radius: 4px;">
                    {{ $resetUrl }}
                </p>
            </div>
            
            <div class="divider"></div>
            
            <div class="contact-section">
                <h4>🤝 Need Help?</h4>
                <p style="margin-bottom: 0; color: #6b7280;">
                    If you're having trouble resetting your password or have questions about your account, 
                    our support team is here to help at <strong>tripmate@gmail.com</strong>
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>TripMate</strong> - Your Gateway to Amazing Adventures</p>
            <p style="margin: 10px 0;">Keeping your travel memories secure</p>
            <div style="margin-top: 20px;">
                <span style="color: #9ca3af;">© {{ date('Y') }} TripMate. All rights reserved.</span>
            </div>
            <p style="font-size: 12px; margin-top: 15px; color: #9ca3af;">
                This email was sent to {{ $user->email }}. This is an automated security message.
            </p>
        </div>
    </div>
</body>
</html>