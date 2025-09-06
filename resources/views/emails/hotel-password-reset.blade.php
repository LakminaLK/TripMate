<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #047857;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 0 0 5px 5px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #047857;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #718096;
            text-align: center;
        }
        .warning {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reset Your Password</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $hotel->name }},</p>

        <p>We received a request to reset the password for your TripMate hotel account. To proceed with the password reset, click the button below:</p>

        <div style="text-align: center;">
            <a href="{{ route('hotel.password.reset', ['token' => $token, 'email' => $hotel->email]) }}" class="btn">Reset Password</a>
        </div>

        <div class="warning">
            <p><strong>Important:</strong></p>
            <ul>
                <li>This link will expire in 60 minutes</li>
                <li>If you didn't request a password reset, please ignore this email</li>
            </ul>
        </div>

        <p>For security reasons, this password reset link can only be used once. If you need to reset your password again, please visit our website and request another reset.</p>

        <div class="footer">
            <p>Best regards,<br>The TripMate Team</p>
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
