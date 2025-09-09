<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>How was your trip?</title>
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
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .trip-summary {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status-badge {
            background: #8b5cf6;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .review-btn {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            display: inline-block;
            margin: 20px 0;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .review-btn:hover {
            transform: translateY(-2px);
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .highlight-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .stars {
            font-size: 24px;
            margin: 10px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🌟 Welcome Back!</h1>
        <p>Hope you had an amazing trip!</p>
    </div>

    <div class="content">
        <h2>Hi {{ $tourist->name }},</h2>
        
        <p>We hope you had a fantastic time at {{ $hotel->name }}! Your journey has come to an end, and we'd love to hear all about your experience.</p>
        
        <div class="trip-summary">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3>Your Trip Summary</h3>
                <span class="status-badge">COMPLETED</span>
            </div>
            
            <div class="info-item">
                <span><strong>Booking ID:</strong></span>
                <span>{{ 'B' . str_pad($booking->id, 3, '0', STR_PAD_LEFT) }}</span>
            </div>
            
            <div class="info-item">
                <span><strong>Hotel:</strong></span>
                <span>{{ $hotel->name }}</span>
            </div>
            
            <div class="info-item">
                <span><strong>Stay Period:</strong></span>
                <span>{{ $booking->check_in ? $booking->check_in->format('M d') : 'N/A' }} - {{ $booking->check_out ? $booking->check_out->format('M d, Y') : 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span><strong>Duration:</strong></span>
                <span>{{ $booking->check_in && $booking->check_out ? $booking->check_in->diffInDays($booking->check_out) : 0 }} night(s)</span>
            </div>
        </div>

        <div class="highlight-box">
            <h3 style="margin: 0 0 10px 0; color: #92400e;">⭐ Share Your Experience!</h3>
            <p style="margin: 10px 0; color: #92400e;">Your feedback helps other travelers make great choices and helps hotels improve their services.</p>
            <div class="stars">⭐⭐⭐⭐⭐</div>
            <p style="margin: 0; color: #92400e; font-size: 14px;">Rate your stay and share your thoughts!</p>
        </div>

        <div style="text-align: center;">
            <div style="background: #f3f4f6; border-radius: 8px; padding: 20px; margin: 20px 0; border: 2px dashed #9ca3af;">
                <h4 style="margin: 0 0 10px 0; color: #374151;">✍️ Share Your Experience</h4>
                <p style="margin: 0 0 10px 0; color: #6b7280;">Review system coming soon! We'll notify you once our review feature is available.</p>
                <p style="margin: 0; color: #6b7280; font-size: 14px;">Your feedback will help other travelers make great choices!</p>
            </div>
        </div>

        <div style="background: #e0f2fe; border-radius: 8px; padding: 20px; margin: 20px 0;">
            <h4 style="margin: 0 0 15px 0; color: #0277bd;">💡 What to include in your review:</h4>
            <ul style="margin: 0; padding-left: 20px; color: #0277bd;">
                <li><strong>Room quality</strong> and cleanliness</li>
                <li><strong>Staff service</strong> and friendliness</li>
                <li><strong>Hotel facilities</strong> and amenities</li>
                <li><strong>Location</strong> and nearby attractions</li>
                <li><strong>Overall experience</strong> and recommendations</li>
            </ul>
        </div>

        <div style="background: #f3f4f6; border-radius: 8px; padding: 15px; margin: 20px 0; text-align: center;">
            <h4 style="margin: 0 0 10px 0; color: #374151;">🎯 Why Your Review Matters</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">
                • Helps future travelers make informed decisions<br>
                • Provides valuable feedback to improve hotel services<br>
                • Builds a trusted community of travelers<br>
                • Takes only 2-3 minutes of your time
            </p>
        </div>

        <div style="background: #ecfdf5; border: 1px solid #10b981; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #065f46;">📱 Planning Your Next Adventure?</h4>
            <p style="margin: 0; color: #065f46;">
                Explore more amazing destinations on TripMate. We have thousands of verified hotels waiting for you!
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for choosing TripMate!</p>
        <p>This email was sent to {{ $tourist->email }}</p>
        <p style="margin-top: 15px;">
            <a href="mailto:support@tripmate.com" style="color: #6b7280;">support@tripmate.com</a>
        </p>
    </div>
</body>
</html>
