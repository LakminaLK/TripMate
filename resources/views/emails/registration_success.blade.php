<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to TripMate!</title>
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
        .welcome-card {
            background: white;
            padding: 30px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .welcome-message {
            font-size: 18px;
            color: #374151;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .features-section {
            background: #eff6ff;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .features-section h3 {
            margin-top: 0;
            color: #1f2937;
            font-size: 18px;
        }
        .features-list {
            list-style: none;
            padding: 0;
            margin: 15px 0;
        }
        .features-list li {
            padding: 8px 0;
            position: relative;
            padding-left: 25px;
        }
        .features-list li:before {
            content: "✨";
            position: absolute;
            left: 0;
            top: 8px;
        }
        .cta-section {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .cta-button {
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            display: inline-block;
            box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3);
            transition: transform 0.2s ease;
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(59, 130, 246, 0.4);
        }
        .support-section {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }
        .support-section h4 {
            margin-top: 0;
            color: #92400e;
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
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 20px 0;
        }
        .stat-item {
            background: white;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 24px;
            font-weight: bold;
            color: #3b82f6;
            display: block;
        }
        .stat-label {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }
        @media (max-width: 600px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🌍 TripMate</div>
            <h1>Welcome Aboard!</h1>
            <p>Your journey to amazing destinations starts here</p>
        </div>

        <div class="content">
            <div class="welcome-card">
                <h2 style="margin-top: 0; color: #374151;">Hello {{ $name }}! 🎉</h2>
                <p class="welcome-message">
                    Thank you for joining <strong>TripMate</strong>! We're thrilled to have you as part of our travel community. 
                    Your account has been successfully created and verified, and you're now ready to explore breathtaking destinations around the world.
                </p>
            </div>
            
            <div class="features-section">
                <h3>🚀 What You Can Do Now:</h3>
                <ul class="features-list">
                    <li>Discover amazing hotels and destinations</li>
                    <li>Book your dream accommodations</li>
                    <li>Connect with local hospitality providers</li>
                    <li>Access exclusive travel deals and offers</li>
                    <li>Create and manage your travel itineraries</li>
                    <li>Get personalized travel recommendations</li>
                </ul>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number">500+</span>
                    <div class="stat-label">Hotels</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <div class="stat-label">Destinations</div>
                </div>
                <div class="stat-item">
                    <span class="stat-number">1000+</span>
                    <div class="stat-label">Happy Travelers</div>
                </div>
            </div>
            
            <div class="cta-section">
                <h3 style="margin-top: 0; color: #374151;">Ready to Start Your Adventure? 🌟</h3>
                <p style="margin-bottom: 20px; color: #6b7280;">
                    Log in to your account and begin exploring the world with TripMate!
                </p>
                <a href="{{ route('login') }}" class="cta-button">Start Exploring Now</a>
            </div>
            
            <div class="support-section">
                <h4 style="margin-top: 0;">🤝 Need Help Getting Started?</h4>
                <p style="margin-bottom: 0; color: #92400e;">
                    Our friendly support team is here to help! If you have any questions or need assistance, 
                    don't hesitate to reach out to us at <strong>hello.tripmate@gmail.com</strong>
                </p>
            </div>
            
            <div class="divider"></div>
            
            <div style="background: #f3f4f6; padding: 20px; border-radius: 8px; text-align: center;">
                <h4 style="margin-top: 0; color: #374151;">🔐 Account Security</h4>
                <p style="margin-bottom: 0; color: #6b7280; font-size: 14px;">
                    Your account is secure and your email has been verified. If you didn't create this account, 
                    please contact our support team immediately.
                </p>
            </div>
        </div>

        <div class="footer">
            <p><strong>TripMate</strong> - Your Gateway to Amazing Adventures</p>
            <p style="margin: 10px 0;">Follow your wanderlust and create unforgettable memories!</p>
            <div style="margin-top: 20px;">
                <span style="color: #9ca3af;">© {{ date('Y') }} TripMate. All rights reserved.</span>
            </div>
            <p style="font-size: 12px; margin-top: 15px; color: #9ca3af;">
                This email was sent to {{ $user->email }}. This is an automated message, please do not reply.
            </p>
        </div>
    </div>
</body>
</html>
