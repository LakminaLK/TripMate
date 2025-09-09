<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
        .booking-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .status-badge {
            background: #fbbf24;
            color: #92400e;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .btn {
            background: #3b82f6;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
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
        <h1>🎉 Booking Confirmation</h1>
        <p>Thank you for choosing TripMate!</p>
    </div>

    <div class="content">
        <h2>Hello {{ $tourist->name }},</h2>
        
        <p>Great news! We've received your booking request and it's currently being reviewed by the hotel.</p>
        
        <div class="booking-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3>Booking Details</h3>
                <span class="status-badge">PENDING REVIEW</span>
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
                <span><strong>Check-in:</strong></span>
                <span>{{ $booking->check_in ? $booking->check_in->format('M d, Y') : 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span><strong>Check-out:</strong></span>
                <span>{{ $booking->check_out ? $booking->check_out->format('M d, Y') : 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span><strong>Total Amount:</strong></span>
                <span style="font-weight: bold; color: #059669;">${{ number_format($booking->total_amount, 2) }}</span>
            </div>
            
            <div class="info-item">
                <span><strong>Payment Status:</strong></span>
                <span style="color: #059669;">✅ Paid</span>
            </div>
        </div>

        <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #92400e;">📋 What happens next?</h4>
            <ul style="margin: 0; padding-left: 20px; color: #92400e;">
                <li>The hotel will review your booking request</li>
                <li>You'll receive a confirmation email once approved</li>
                <li>Check your booking status anytime using the link below</li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ $bookingUrl }}" class="btn">View My Bookings</a>
        </div>

        <div style="background: #e0f2fe; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #0277bd;">📞 Need Help?</h4>
            <p style="margin: 0; color: #0277bd;">
                If you have any questions about your booking, feel free to contact us at 
                <a href="mailto:support@tripmate.com" style="color: #0277bd;">support@tripmate.com</a>
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Thank you for choosing TripMate!</p>
        <p>This email was sent to {{ $tourist->email }}</p>
    </div>
</body>
</html>
