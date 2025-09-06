<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password - TripMate</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f0fdf4; font-family: 'Segoe UI', Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="min-width: 100%; background-color: #f0fdf4;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #10B981; border-radius: 12px 12px 0 0;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 40px 40px 30px 40px;">
                                        <img src="{{ asset('images/tm1.png') }}" alt="TripMate" width="60" style="display: block; margin-bottom: 20px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding: 0 40px 40px 40px;">
                                        <h1 style="margin: 0; color: #ffffff; font-size: 32px; font-weight: 700; letter-spacing: -0.5px;">Reset Your Password</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <!-- Greeting -->
                                <tr>
                                    <td>
                                        <p style="margin: 0 0 20px; color: #111827; font-size: 16px; line-height: 24px;">
                                            Dear {{ $hotel->name }},
                                        </p>
                                        <p style="margin: 0 0 24px; color: #374151; font-size: 16px; line-height: 24px;">
                                            We received a request to reset the password for your TripMate hotel account. To proceed with the password reset, please click the button below.
                                        </p>
                                    </td>
                                </tr>

                                <!-- Action Button -->
                                <tr>
                                    <td align="center" style="padding: 10px 0 30px;">
                                        <table border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td align="center" bgcolor="#10B981" style="border-radius: 8px;">
                                                    <a href="{{ $resetUrl }}" target="_blank" 
                                                       style="display: inline-block; padding: 16px 36px; 
                                                              font-size: 16px; color: #ffffff; 
                                                              text-decoration: none; font-weight: 600;
                                                              border-radius: 8px; 
                                                              background-color: #10B981;
                                                              box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                                                        Reset Password
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Important Notice -->
                                <tr>
                                    <td style="padding: 0 0 30px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" 
                                               style="background-color: #ecfdf5; border-radius: 8px; 
                                                      border-left: 4px solid #10B981;">
                                            <tr>
                                                <td style="padding: 16px;">
                                                    <p style="margin: 0 0 8px; font-weight: 600; 
                                                              color: #065f46; font-size: 14px;">
                                                        Important:
                                                    </p>
                                                    <ul style="margin: 0; padding-left: 20px; 
                                                               color: #047857; font-size: 14px;">
                                                        <li style="margin-bottom: 6px;">
                                                            This link will expire in 60 minutes
                                                        </li>
                                                        <li>
                                                            If you didn't request this reset, please ignore this email
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Alternative Link Box -->
                                <tr>
                                    <td style="padding: 0 0 30px;">
                                        <table border="0" cellpadding="0" cellspacing="0" width="100%" 
                                               style="background-color: #f9fafb; border-radius: 8px;">
                                            <tr>
                                                <td style="padding: 16px;">
                                                    <p style="margin: 0 0 8px; color: #374151; 
                                                              font-size: 14px; font-weight: 500;">
                                                        If the button doesn't work, copy and paste this link:
                                                    </p>
                                                    <p style="margin: 0; padding: 8px; 
                                                              background-color: #ffffff; 
                                                              border-radius: 4px; 
                                                              color: #4B5563; 
                                                              font-size: 12px; 
                                                              font-family: monospace; 
                                                              word-break: break-all;">
                                                        {{ $resetUrl }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <!-- Security Note -->
                                <tr>
                                    <td>
                                        <p style="margin: 0; color: #6B7280; 
                                                  font-size: 14px; line-height: 20px;">
                                            For security reasons, this password reset link can only be used once. 
                                            If you need to reset your password again, please visit our website 
                                            and request another reset.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding: 0 40px;">
                            <div style="height: 1px; background-color: #e5e7eb;"></div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px 40px; background-color: #f9fafb; border-radius: 0 0 12px 12px;">
                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <img src="{{ asset('images/tm1.png') }}" alt="TripMate" width="30" 
                                             style="display: block; margin-bottom: 15px;">
                                        
                                        <p style="margin: 0 0 4px; color: #374151; 
                                                  font-size: 14px; font-weight: 500;">
                                            Best regards,
                                        </p>
                                        <p style="margin: 0 0 20px; color: #10B981; 
                                                  font-size: 16px; font-weight: 600;">
                                            The TripMate Team
                                        </p>
                                        
                                        <p style="margin: 0 0 8px; color: #6B7280; 
                                                  font-size: 12px; line-height: 16px;">
                                            &copy; {{ date('Y') }} TripMate. All rights reserved.
                                        </p>
                                        <p style="margin: 0; color: #9CA3AF; 
                                                  font-size: 11px; font-style: italic;">
                                            This is an automated message. Please do not reply.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <!-- Space at bottom -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td height="20"></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
