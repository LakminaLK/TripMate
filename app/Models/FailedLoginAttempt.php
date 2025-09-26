<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class FailedLoginAttempt extends Model
{
    protected $fillable = [
        'email',
        'ip_address',
        'user_agent',
        'attempt_count',
        'locked_until',
        'last_attempt_at',
    ];

    protected $casts = [
        'locked_until' => 'datetime',
        'last_attempt_at' => 'datetime',
    ];

    /**
     * Check if the account is currently locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until > now();
    }

    /**
     * Get remaining lockout time in minutes
     */
    public function getRemainingLockoutMinutes(): int
    {
        if (!$this->isLocked()) {
            return 0;
        }

        return (int) now()->diffInMinutes($this->locked_until);
    }

    /**
     * Get remaining lockout time in seconds
     */
    public function getRemainingLockoutSeconds(): int
    {
        if (!$this->isLocked()) {
            return 0;
        }

        return (int) now()->diffInSeconds($this->locked_until);
    }

    /**
     * Reset the failed attempts
     */
    public function resetAttempts(): void
    {
        $this->update([
            'attempt_count' => 0,
            'locked_until' => null,
        ]);
    }

    /**
     * Lock the account for specified minutes
     */
    public function lockAccount(int $minutes = 10): void
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
        ]);
    }

    /**
     * Increment failed attempts
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempt_count');
        $this->update(['last_attempt_at' => now()]);
    }
}
