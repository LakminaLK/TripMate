<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\LoginAttemptService;

class CheckAccountLockout
{
    protected $loginAttemptService;

    public function __construct(LoginAttemptService $loginAttemptService)
    {
        $this->loginAttemptService = $loginAttemptService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Only apply to login routes
        if (!$this->isLoginRoute($request)) {
            return $next($request);
        }

        $email = $request->input('email');
        $ipAddress = $request->ip();

        if (!$email) {
            return $next($request);
        }

        // Check if account is locked
        if ($this->loginAttemptService->isAccountLocked($email, $ipAddress)) {
            $lockoutStatus = $this->loginAttemptService->getLockoutStatus($email, $ipAddress);

            // Return appropriate response based on request type
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Account temporarily locked due to too many failed login attempts.',
                    'lockout_status' => $lockoutStatus,
                    'message' => "Account locked for {$lockoutStatus['remaining_minutes']} more minutes. Try again after {$lockoutStatus['remaining_minutes']} minutes."
                ], 429);
            } else {
                return redirect()->back()
                    ->withErrors(['email' => "Account temporarily locked. Try again in {$lockoutStatus['remaining_minutes']} minutes."])
                    ->with('lockout_status', $lockoutStatus);
            }
        }

        return $next($request);
    }

    /**
     * Check if the current request is for a login route
     */
    protected function isLoginRoute(Request $request): bool
    {
        $loginRoutes = [
            'login',
            'admin/login',
            'hotel/login',
            'api/login',
            'api/admin/login',
            'api/hotel/login',
        ];

        $currentPath = trim($request->path(), '/');

        return in_array($currentPath, $loginRoutes) && $request->isMethod('POST');
    }
}