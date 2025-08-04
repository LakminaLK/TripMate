<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Platform!</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background-color: #f4f7fc;
            padding: 20px;
        }

        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            margin: 0 auto;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .cta-button {
            display: inline-block;
            background-color: #4CAF50;
            color: #fff;
            padding: 12px 30px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
        }

        .cta-button:hover {
            background-color: #45a049;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            margin-top: 30px;
        }

        .footer a {
            color: #4CAF50;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .logo {
            display: block;
            margin: 0 auto;
            max-width: 150px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Logo -->
        <!-- <img src="{{ asset('images/2.jpg') }}" alt="Company Logo" class="logo"> -->

        <h1>Welcome, {{ $name }}!</h1>

        <p>Thank you for registering with our platform. We are excited to have you on board. Your account has been successfully created, and you are now ready to start exploring all the amazing features we offer.</p>

        <p>If you have any questions or need assistance, feel free to <a href="tripmate@gmail.com">contact our support team</a>.</p>

        <!-- Call to action button -->
        <p style="text-align: center;">
            <a href="{{ route('login') }}" class="cta-button">Go to TripMate</a>
        </p>

        <div class="footer">
            <!-- <p>If you did not register for an account, please ignore this email.</p> -->
            <p>© {{ date('Y') }} TripMate. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
