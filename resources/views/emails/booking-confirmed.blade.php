<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmed</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            background: #10b981;
            color: white;
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
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .highlight-box {
            background: #ecfdf5;
            border: 1px solid #10b981;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .preparation-list {
            background: #fef3c7;
            border-radius: 8px;
            padding: 15px;
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
        <h1>🎉 Booking Confirmed!</h1>
        <p>Your adventure awaits!</p>
    </div>

    <div class="content">
        <h2>Congratulations {{ $tourist->name }}!</h2>
        
        <p>Excellent news! {{ $hotel->name }} has confirmed your booking. You're all set for an amazing experience!</p>
        
        <div class="highlight-box">
            <h3 style="margin: 0 0 10px 0; color: #059669;">✅ Your booking is now CONFIRMED</h3>
            <p style="margin: 0; color: #065f46;">Pack your bags and get ready for your trip!</p>
        </div>
        
        <div class="booking-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h3>Confirmed Booking Details</h3>
                <span class="status-badge">CONFIRMED</span>
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
                <span><strong>Duration:</strong></span>
                <span>{{ $booking->check_in && $booking->check_out ? $booking->check_in->diffInDays($booking->check_out) : 0 }} night(s)</span>
            </div>
            
            <div class="info-item">
                <span><strong>Total Amount:</strong></span>
                <span style="font-weight: bold; color: #059669;">${{ number_format($booking->total_amount, 2) }}</span>
            </div>
        </div>

        <div class="preparation-list">
            <h4 style="margin: 0 0 15px 0; color: #92400e;">📋 Preparation Checklist</h4>
            <ul style="margin: 0; padding-left: 20px; color: #92400e;">
                <li><strong>Save this email</strong> or screenshot for your records</li>
                <li><strong>Check-in time:</strong> Usually 3:00 PM (confirm with hotel)</li>
                <li><strong>Check-out time:</strong> Usually 11:00 AM (confirm with hotel)</li>
                <li><strong>Contact hotel</strong> if you have special requests</li>
                <li><strong>Pack accordingly</strong> for the weather and activities</li>
            </ul>
        </div>

        <div style="background: #e0f2fe; border-radius: 8px; padding: 15px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px 0; color: #0277bd;">🏨 Hotel Contact Information</h4>
            <p style="margin: 5px 0; color: #0277bd;"><strong>{{ $hotel->name }}</strong></p>
            @if($hotel->address)
                <p style="margin: 5px 0; color: #0277bd;">📍 {{ $hotel->address }}</p>
            @endif
            @if($hotel->phone)
                <p style="margin: 5px 0; color: #0277bd;">📞 {{ $hotel->phone }}</p>
            @endif
        </div>

        <div style="text-align: center;">
            <a href="{{ $bookingUrl }}" class="btn">View My Bookings</a>
        </div>

        <div style="background: #f3f4f6; border-radius: 8px; padding: 15px; margin: 20px 0; text-align: center;">
            <h4 style="margin: 0 0 10px 0; color: #374151;">🌟 After Your Stay</h4>
            <p style="margin: 0; color: #6b7280;">
                We'd love to hear about your experience! You'll receive a review invitation after your trip.
            </p>
        </div>
    </div>

    <div class="footer">
        <p>Have an amazing trip!</p>
        <p>This email was sent to {{ $tourist->email }}</p>
        <p style="margin-top: 15px;">
            <a href="mailto:support@tripmate.com" style="color: #6b7280;">support@tripmate.com</a>
        </p>
    </div>
</body>
</html>
