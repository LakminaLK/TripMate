<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class SecurityMonitoringService
{
    /**
     * Log a failed login attempt
     */
    public static function logFailedLogin(string $email, string $ip, string $userAgent): void
    {
        $data = [
            'event' => 'failed_login',
            'email' => $email,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()->toISOString(),
        ];

        Log::warning('Failed login attempt', $data);
        
        // Store in cache for rate limiting
        $cacheKey = "failed_logins_{$ip}";
        $attempts = Cache::get($cacheKey, 0);
        Cache::put($cacheKey, $attempts + 1, 900); // 15 minutes

        // Alert if too many attempts
        if ($attempts >= config('security.monitoring.max_failures_before_alert', 10)) {
            self::alertAdministrators('Multiple failed login attempts detected', $data);
        }

        // Write to security log file
        self::writeSecurityLog($data);
    }

    /**
     * Log successful login
     */
    public static function logSuccessfulLogin(string $email, string $ip, string $userAgent): void
    {
        $data = [
            'event' => 'successful_login',
            'email' => $email,
            'ip' => $ip,
            'user_agent' => $userAgent,
            'timestamp' => now()->toISOString(),
        ];

        Log::info('Successful login', $data);
        self::writeSecurityLog($data);
    }

    /**
     * Log suspicious activity
     */
    public static function logSuspiciousActivity(Request $request, string $reason, array $details = []): void
    {
        $data = [
            'event' => 'suspicious_activity',
            'reason' => $reason,
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_agent' => $request->userAgent(),
            'input' => $request->all(),
            'details' => $details,
            'timestamp' => now()->toISOString(),
        ];

        Log::warning('Suspicious activity detected', $data);
        
        // Increment suspicious activity counter
        $cacheKey = "suspicious_activity_{$request->ip()}";
        $count = Cache::get($cacheKey, 0);
        Cache::put($cacheKey, $count + 1, 3600); // 1 hour

        // Alert if multiple suspicious activities
        if ($count >= 5) {
            self::alertAdministrators('Multiple suspicious activities from same IP', $data);
        }

        self::writeSecurityLog($data);
    }

    /**
     * Log file upload security event
     */
    public static function logFileUploadEvent(string $filename, string $result, array $details = []): void
    {
        $data = [
            'event' => 'file_upload',
            'filename' => $filename,
            'result' => $result, // 'accepted', 'rejected', 'quarantined'
            'details' => $details,
            'timestamp' => now()->toISOString(),
        ];

        if ($result === 'rejected' || $result === 'quarantined') {
            Log::warning('File upload security event', $data);
        } else {
            Log::info('File upload event', $data);
        }

        self::writeSecurityLog($data);
    }

    /**
     * Monitor database queries for suspicious activity
     */
    public static function monitorDatabaseQuery(string $query, array $bindings = []): void
    {
        // Check for suspicious SQL patterns
        $suspiciousPatterns = [
            '/DROP\s+TABLE/i',
            '/DELETE\s+FROM.*WHERE\s+1\s*=\s*1/i',
            '/UNION\s+SELECT/i',
            '/INFORMATION_SCHEMA/i',
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $query)) {
                $data = [
                    'event' => 'suspicious_sql',
                    'query' => $query,
                    'bindings' => $bindings,
                    'timestamp' => now()->toISOString(),
                ];

                Log::critical('Suspicious SQL query detected', $data);
                self::writeSecurityLog($data);
                self::alertAdministrators('Suspicious SQL query detected', $data);
                break;
            }
        }
    }

    /**
     * Check for brute force attacks
     */
    public static function checkBruteForce(string $ip): bool
    {
        $cacheKey = "brute_force_{$ip}";
        $attempts = Cache::get($cacheKey, 0);
        
        return $attempts >= config('security.password.max_attempts', 5);
    }

    /**
     * Block IP address temporarily
     */
    public static function blockIp(string $ip, int $minutes = 60): void
    {
        $cacheKey = "blocked_ip_{$ip}";
        Cache::put($cacheKey, true, $minutes * 60);

        $data = [
            'event' => 'ip_blocked',
            'ip' => $ip,
            'duration_minutes' => $minutes,
            'timestamp' => now()->toISOString(),
        ];

        Log::warning('IP address blocked', $data);
        self::writeSecurityLog($data);
    }

    /**
     * Check if IP is blocked
     */
    public static function isIpBlocked(string $ip): bool
    {
        $cacheKey = "blocked_ip_{$ip}";
        return Cache::has($cacheKey);
    }

    /**
     * Generate security report
     */
    public static function generateSecurityReport(int $days = 7): array
    {
        $logFile = storage_path('logs/security.log');
        
        if (!file_exists($logFile)) {
            return ['error' => 'No security log file found'];
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $events = [];
        $cutoffDate = now()->subDays($days);

        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if ($data && isset($data['timestamp'])) {
                $eventDate = \Carbon\Carbon::parse($data['timestamp']);
                if ($eventDate >= $cutoffDate) {
                    $events[] = $data;
                }
            }
        }

        return [
            'period' => $days . ' days',
            'total_events' => count($events),
            'events_by_type' => array_count_values(array_column($events, 'event')),
            'top_ips' => array_count_values(array_column($events, 'ip')),
            'recent_events' => array_slice($events, -10), // Last 10 events
        ];
    }

    /**
     * Alert administrators about security events
     */
    protected static function alertAdministrators(string $subject, array $data): void
    {
        // You can implement email alerts here
        // For now, we'll just log it with high priority
        Log::critical($subject, $data);

        // Example email implementation:
        /*
        $adminEmails = config('security.admin_emails', []);
        foreach ($adminEmails as $email) {
            Mail::raw(
                "Security Alert: {$subject}\n\nDetails: " . json_encode($data, JSON_PRETTY_PRINT),
                function ($message) use ($email, $subject) {
                    $message->to($email)
                           ->subject("TripMate Security Alert: {$subject}");
                }
            );
        }
        */
    }

    /**
     * Write to dedicated security log file
     */
    protected static function writeSecurityLog(array $data): void
    {
        $logFile = storage_path('logs/security.log');
        $logEntry = json_encode($data) . PHP_EOL;
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    /**
     * Clean old security logs
     */
    public static function cleanOldLogs(int $daysToKeep = 30): void
    {
        $logFile = storage_path('logs/security.log');
        
        if (!file_exists($logFile)) {
            return;
        }

        $lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $filteredLines = [];
        $cutoffDate = now()->subDays($daysToKeep);

        foreach ($lines as $line) {
            $data = json_decode($line, true);
            if ($data && isset($data['timestamp'])) {
                $eventDate = \Carbon\Carbon::parse($data['timestamp']);
                if ($eventDate >= $cutoffDate) {
                    $filteredLines[] = $line;
                }
            }
        }

        file_put_contents($logFile, implode(PHP_EOL, $filteredLines) . PHP_EOL);
    }
}