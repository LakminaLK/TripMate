<?php

namespace App\Services;

use App\Models\FailedLoginAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class LoginAttemptService
{
    const MAX_ATTEMPTS = 5;
    const LOCKOUT_MINUTES = 10;

    /**
     * Record a failed login attempt
     */
    public function recordFailedAttempt(string $email, Request $request): void
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        $attempt = FailedLoginAttempt::firstOrCreate(
            ['email' => $email, 'ip_address' => $ipAddress],
            [
                'user_agent' => $userAgent,
                'attempt_count' => 0,
                'last_attempt_at' => now(),
            ]
        );

        $attempt->incrementAttempts();

        // Lock account if max attempts reached
        if ($attempt->attempt_count >= self::MAX_ATTEMPTS) {
            $attempt->lockAccount(self::LOCKOUT_MINUTES);
            
            // Also cache it for faster access
            $this->cacheAccountLock($email, $ipAddress);
        }

        // Log the failed attempt for security monitoring
        \App\Services\SecurityMonitoringService::logFailedLogin(
            $email,
            $ipAddress,
            $userAgent
        );
    }

    /**
     * Check if account is locked
     */
    public function isAccountLocked(string $email, string $ipAddress): bool
    {
        // First check cache for performance
        $cacheKey = "account_locked_{$email}_{$ipAddress}";
        if (Cache::has($cacheKey)) {
            return true;
        }

        // Check database
        $attempt = FailedLoginAttempt::where('email', $email)
            ->where('ip_address', $ipAddress)
            ->first();

        if (!$attempt) {
            return false;
        }

        $isLocked = $attempt->isLocked();

        // If not locked but cache exists, clear it
        if (!$isLocked && Cache::has($cacheKey)) {
            Cache::forget($cacheKey);
        }

        return $isLocked;
    }

    /**
     * Get remaining lockout time in minutes
     */
    public function getRemainingLockoutMinutes(string $email, string $ipAddress): int
    {
        $attempt = FailedLoginAttempt::where('email', $email)
            ->where('ip_address', $ipAddress)
            ->first();

        if (!$attempt) {
            return 0;
        }

        return $attempt->getRemainingLockoutMinutes();
    }

    /**
     * Get remaining lockout time in seconds for real-time countdown
     */
    public function getRemainingLockoutSeconds(string $email, string $ipAddress): int
    {
        $attempt = FailedLoginAttempt::where('email', $email)
            ->where('ip_address', $ipAddress)
            ->first();

        if (!$attempt) {
            return 0;
        }

        return $attempt->getRemainingLockoutSeconds();
    }

    /**
     * Get current attempt count
     */
    public function getAttemptCount(string $email, string $ipAddress): int
    {
        $attempt = FailedLoginAttempt::where('email', $email)
            ->where('ip_address', $ipAddress)
            ->first();

        if (!$attempt || !$attempt->isLocked()) {
            return 0;
        }

        return $attempt->attempt_count;
    }

    /**
     * Get remaining attempts before lockout
     */
    public function getRemainingAttempts(string $email, string $ipAddress): int
    {
        $attempt = FailedLoginAttempt::where('email', $email)
            ->where('ip_address', $ipAddress)
            ->first();

        if (!$attempt) {
            return self::MAX_ATTEMPTS;
        }

        if ($attempt->isLocked()) {
            return 0;
        }

        return max(0, self::MAX_ATTEMPTS - $attempt->attempt_count);
    }

    /**
     * Reset failed attempts after successful login
     */
    public function resetFailedAttempts(string $email, string $ipAddress): void
    {
        $attempt = FailedLoginAttempt::where('email', $email)
            ->where('ip_address', $ipAddress)
            ->first();

        if ($attempt) {
            $attempt->resetAttempts();
        }

        // Clear cache
        $cacheKey = "account_locked_{$email}_{$ipAddress}";
        Cache::forget($cacheKey);
    }

    /**
     * Get lockout status for API response
     */
    public function getLockoutStatus(string $email, string $ipAddress): array
    {
        if (!$this->isAccountLocked($email, $ipAddress)) {
            return [
                'locked' => false,
                'remaining_attempts' => $this->getRemainingAttempts($email, $ipAddress),
                'max_attempts' => self::MAX_ATTEMPTS,
            ];
        }

        return [
            'locked' => true,
            'remaining_minutes' => $this->getRemainingLockoutMinutes($email, $ipAddress),
            'remaining_seconds' => $this->getRemainingLockoutSeconds($email, $ipAddress),
            'attempt_count' => $this->getAttemptCount($email, $ipAddress),
            'max_attempts' => self::MAX_ATTEMPTS,
            'lockout_duration' => self::LOCKOUT_MINUTES,
        ];
    }

    /**
     * Cache account lock for faster access
     */
    protected function cacheAccountLock(string $email, string $ipAddress): void
    {
        $cacheKey = "account_locked_{$email}_{$ipAddress}";
        Cache::put($cacheKey, true, self::LOCKOUT_MINUTES * 60); // Cache for lockout duration
    }

    /**
     * Clean up old failed attempts (for maintenance)
     */
    public function cleanupOldAttempts(int $daysOld = 30): void
    {
        FailedLoginAttempt::where('created_at', '<', now()->subDays($daysOld))
            ->delete();
    }

    /**
     * Get lockout statistics (for admin dashboard)
     */
    public function getLockoutStatistics(): array
    {
        $totalAttempts = FailedLoginAttempt::sum('attempt_count');
        $currentlyLocked = FailedLoginAttempt::where('locked_until', '>', now())->count();
        $totalEmails = FailedLoginAttempt::distinct('email')->count();
        
        $topOffenders = FailedLoginAttempt::selectRaw('email, ip_address, SUM(attempt_count) as total_attempts')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('email', 'ip_address')
            ->orderByDesc('total_attempts')
            ->limit(10)
            ->get();

        return [
            'total_failed_attempts' => $totalAttempts,
            'currently_locked_accounts' => $currentlyLocked,
            'unique_emails_with_failures' => $totalEmails,
            'top_offenders_last_7_days' => $topOffenders,
        ];
    }
}