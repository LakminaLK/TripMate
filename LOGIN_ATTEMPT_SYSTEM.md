# TripMate Login Attempt Limiting System

## Overview

The TripMate application now includes a comprehensive login attempt limiting system that protects against brute force attacks by temporarily locking accounts after failed login attempts.

## Key Features

### 🔒 **Account Lockout System**
- **Maximum Attempts**: 5 failed login attempts allowed
- **Lockout Duration**: 10 minutes automatic lockout
- **Reset Mechanism**: Successful login resets all failed attempts
- **Automatic Unlock**: Accounts unlock automatically after lockout period

### 📊 **Real-time Status Tracking**
- **Attempt Counter**: Shows remaining attempts before lockout
- **Live Countdown**: Real-time countdown timer during lockout
- **Visual Indicators**: Clear status messages and warnings
- **Toast Notifications**: Dynamic success/error/warning messages

### 🛡️ **Security Features**
- **IP-based Tracking**: Attempts tracked per email + IP combination
- **Database Storage**: Persistent storage with proper indexing
- **Cache Integration**: Fast lockout checks with Redis/database cache
- **Security Logging**: All attempts logged for monitoring

## File Structure

### **Core Service Files**
```
app/Services/LoginAttemptService.php          # Main service for attempt tracking
app/Models/FailedLoginAttempt.php            # Database model
app/Http/Middleware/CheckAccountLockout.php  # Pre-login lockout check
app/Http/Controllers/Auth/LoginAttemptController.php  # Enhanced login handling
```

### **Database**
```
database/migrations/*_create_failed_login_attempts_table.php  # Database schema
```

### **Frontend**
```
resources/views/auth/login.blade.php          # Enhanced login form with AJAX
```

### **Console Commands**
```
app/Console/Commands/CleanLoginAttemptsCommand.php  # Cleanup old records
```

## Database Schema

### `failed_login_attempts` Table
```sql
- id (bigint, primary key)
- email (varchar, indexed)
- ip_address (varchar)
- user_agent (text)
- attempt_count (integer)
- locked_until (timestamp, nullable)
- last_attempt_at (timestamp)
- created_at (timestamp)
- updated_at (timestamp)

Indexes:
- email, ip_address (composite)
- locked_until
```

## API Endpoints

### **Authentication Routes**
```php
POST /auth/login-attempt          # Tourist login with attempt tracking
POST /auth/hotel-login-attempt    # Hotel login with attempt tracking
POST /auth/admin-login-attempt    # Admin login with attempt tracking
POST /auth/check-lockout-status   # Check current lockout status
POST /auth/remaining-time         # Get remaining lockout time
```

## Configuration

### **Default Settings** (customizable in LoginAttemptService)
```php
const MAX_ATTEMPTS = 5;          # Maximum failed attempts allowed
const LOCKOUT_MINUTES = 10;      # Lockout duration in minutes
```

### **Middleware Registration**
```php
// In app/Http/Kernel.php
'lockout.check' => \App\Http\Middleware\CheckAccountLockout::class,
```

## User Experience

### **Login Flow**
1. **Normal Login**: User enters credentials and submits
2. **Failed Attempt**: Shows error + remaining attempts warning
3. **Multiple Failures**: Shows increasing warnings (when ≤ 2 attempts remain)
4. **Account Locked**: Shows lockout message with countdown timer
5. **Auto Unlock**: Account unlocks after 10 minutes with success message

### **Visual Feedback**
- **Success Toast**: Green notification for successful login
- **Error Toast**: Red notification for failed login
- **Warning Toast**: Yellow notification for low remaining attempts
- **Lockout Display**: Red banner with countdown timer
- **Loading States**: Spinner during form submission

## JavaScript Features

### **Real-time Functionality**
- **AJAX Form Submission**: No page reloads
- **Live Countdown**: Updates every second during lockout
- **Status Polling**: Checks lockout status on email change
- **Auto-redirect**: Successful login redirects appropriately
- **Form State Management**: Disables inputs during lockout/submission

### **Toast System**
```javascript
showToast(type, message, submessage)
// Types: 'success', 'error', 'warning', 'info'
```

## Security Considerations

### **Attack Prevention**
- **Brute Force Protection**: Rate limiting with progressive lockout
- **IP-based Tracking**: Prevents cross-session attacks
- **Database Persistence**: Survives server restarts
- **Cache Performance**: Fast lockout checks without database hits

### **Logging & Monitoring**
- **Failed Attempts**: All failures logged with IP, user agent, timestamp
- **Successful Logins**: Login success events tracked
- **Security Events**: Integration with SecurityMonitoringService
- **Admin Statistics**: Lockout analytics available

## Maintenance

### **Cleanup Commands**
```bash
# Clean old login attempt records (default: 30 days)
php artisan login-attempts:cleanup

# Custom retention period
php artisan login-attempts:cleanup --days=7
```

### **Monitoring Queries**
```php
// Get lockout statistics
$service = new LoginAttemptService();
$stats = $service->getLockoutStatistics();

// Check if specific account is locked
$isLocked = $service->isAccountLocked($email, $ip);

// Get remaining time
$minutes = $service->getRemainingLockoutMinutes($email, $ip);
```

## Testing

### **Test Scenarios**
1. **Normal Login**: Verify successful authentication
2. **Single Failure**: Check error message and remaining attempts
3. **Multiple Failures**: Verify progressive warnings
4. **Account Lockout**: Test 10-minute lockout enforcement
5. **Countdown Timer**: Verify real-time countdown updates
6. **Auto Unlock**: Test automatic unlock after timeout
7. **Reset on Success**: Verify attempts reset after successful login

### **Test Data**
```bash
# Manually trigger lockout for testing
# Attempt login 5 times with wrong password for any email
```

## Integration Points

### **Existing Authentication**
- **Tourist Login**: `/auth/login`
- **Hotel Login**: `/hotel/auth/login`
- **Admin Login**: `/admin/auth/login`

### **Middleware Integration**
```php
// Apply to specific routes
Route::middleware(['lockout.check'])->group(function () {
    // Login routes
});
```

## Customization

### **Adjusting Lockout Settings**
```php
// In LoginAttemptService.php
const MAX_ATTEMPTS = 3;          # Change to 3 attempts
const LOCKOUT_MINUTES = 15;      # Change to 15 minutes
```

### **Custom Messages**
```php
// In controller responses
'message' => "Account locked for {$minutes} minutes due to security."
```

### **UI Customization**
```html
<!-- In login.blade.php -->
<div class="lockout-banner">
    Custom lockout message styling
</div>
```

## Production Deployment

### **Environment Setup**
1. **Run Migration**: `php artisan migrate`
2. **Clear Cache**: `php artisan config:clear`
3. **Test Features**: Verify lockout functionality
4. **Schedule Cleanup**: Add cleanup command to cron

### **Monitoring Setup**
1. **Log Analysis**: Monitor `storage/logs/security.log`
2. **Database Monitoring**: Track `failed_login_attempts` table growth
3. **Performance**: Monitor cache hit rates for lockout checks

### **Cron Job Setup**
```bash
# Add to crontab for daily cleanup
0 2 * * * cd /path/to/tripmate && php artisan login-attempts:cleanup
```

## Troubleshooting

### **Common Issues**
1. **Routes Not Working**: Check route registration in `routes/auth.php`
2. **CSRF Errors**: Verify CSRF token in JavaScript AJAX calls
3. **Database Errors**: Ensure migration was run successfully
4. **Cache Issues**: Clear application cache if lockout status inconsistent

### **Debug Commands**
```bash
# Check route registration
php artisan route:list | grep login-attempt

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

## Success Criteria

✅ **Implemented Features:**
- 5 attempt limit with 10-minute lockout
- Real-time countdown timer
- Toast notifications for all states
- AJAX-based login (no page refresh)
- Database persistence
- Security logging integration
- Automatic cleanup system
- Multiple user type support (Tourist/Hotel/Admin)

The login attempt limiting system is now fully functional and ready for production use!