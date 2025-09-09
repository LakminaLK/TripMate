<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancelled</title>
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
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
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
            background: #ef4444;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }
        .refund-box {
            background: #ecfdf5;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .timeline {
            background: #fef3c7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
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
        <h1>📋 Booking Cancelled</h1>
        <p>We understand plans can change</p>
    </div>

    <div class="content">
        <h2>Hi {{ $tourist->name }},</h2>
        
        <p>We've successfully cancelled your booking as requested. While we're sorry to see your trip plans change, we understand that sometimes it's necessary.</p>
        
        <div class="booking-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3>Cancelled Booking Details</h3>
                <span class="status-badge">CANCELLED</span>
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
                <span><strong>Original Check-in:</strong></span>
                <span>{{ $booking->check_in ? $booking->check_in->format('M d, Y') : 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span><strong>Original Check-out:</strong></span>
                <span>{{ $booking->check_out ? $booking->check_out->format('M d, Y') : 'N/A' }}</span>
            </div>
            
            <div class="info-item">
                <span><strong>Cancelled Amount:</strong></span>
                <span style="font-weight: bold; color: #dc2626;">${{ number_format($booking->total_amount, 2) }}</span>
            </div>
            
            <div class="info-item">
                <span><strong>Cancellation Date:</strong></span>
                <span>{{ now()->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="refund-box">
            <h3 style="margin: 0 0 15px 0; color: #065f46;">💰 Refund Information</h3>
            <p style="margin: 0 0 10px 0; color: #065f46;">
                <strong>Good news!</strong> Your refund is being processed and will be credited back to your original payment method.
            </p>
            <div style="background: white; padding: 15px; border-radius: 6px; margin-top: 15px;">
                <p style="margin: 0; color: #059669;"><strong>Refund Amount: ${{ number_format($booking->total_amount, 2) }}</strong></p>
                <p style="margin: 5px 0 0 0; color: #6b7280; font-size: 14px;">Processing to your original payment method</p>
            </div>
        </div>

        <div class="timeline">
            <h4 style="margin: 0 0 15px 0; color: #92400e;">⏰ Refund Timeline</h4>
            <div style="color: #92400e;">
                <p style="margin: 5px 0;"><strong>📋 Processing:</strong> 1-2 business days</p>
                <p style="margin: 5px 0;"><strong>💳 Credit Card:</strong> 3-5 business days</p>
                <p style="margin: 5px 0;"><strong>🏦 Bank Transfer:</strong> 5-7 business days</p>
                <p style="margin: 15px 0 5px 0; font-size: 14px;">
                    <em>You should see the refund in your account within 1-7 working days depending on your payment method.</em>
                </p>
            </div>
        </div>

        <div style="background: #e0f2fe; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #0277bd;">📧 What's Next?</h4>
            <ul style="margin: 0; padding-left: 20px; color: #0277bd;">
                <li>You'll receive a refund confirmation email once processed</li>
                <li>Check your bank/card statement in 1-7 working days</li>
                <li>Contact us if you don't see the refund after 7 business days</li>
                <li>Your booking history will show this cancellation</li>
            </ul>
        </div>

        <div style="text-align: center;">
            <a href="{{ $bookingUrl }}" class="btn">View Booking History</a>
        </div>

        <div style="background: #f3f4f6; border-radius: 8px; padding: 15px; margin: 20px 0; text-align: center;">
            <h4 style="margin: 0 0 10px 0; color: #374151;">🌍 Planning Another Trip?</h4>
            <p style="margin: 0; color: #6b7280; font-size: 14px;">
                We'd love to help you plan your next adventure! Explore thousands of amazing destinations on TripMate.
            </p>
        </div>

        <div style="background: #fef2f2; border: 1px solid #ef4444; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #dc2626;">❓ Questions About Your Refund?</h4>
            <p style="margin: 0; color: #dc2626; font-size: 14px;">
                If you have any questions about your refund or need assistance, please contact our support team at 
                <a href="mailto:support@tripmate.com" style="color: #dc2626; font-weight: bold;">support@tripmate.com</a>
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
