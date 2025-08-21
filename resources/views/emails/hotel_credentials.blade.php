<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif; line-height:1.6;">
  <h2>Welcome to TripMate (Hotel Portal)</h2>
  <p>Hello {{ $hotel->name }},</p>
  <p>Your hotel profile has been created. Use the credentials below to sign in:</p>
  <ul>
    <li><strong>Username:</strong> {{ $username }}</li>
    <li><strong>Password:</strong> {{ $password }}</li>
  </ul>
  <p><a href="{{ $loginUrl }}">Click here to login</a></p>
  <p style="color:#888;">Please change your password after first login.</p>
</body>
</html>
