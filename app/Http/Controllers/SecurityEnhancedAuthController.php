<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\SecurityMonitoringService;
use App\Services\InputSanitizationService;
use App\Models\Tourist;

class SecurityEnhancedAuthController extends Controller
{
    /**
     * Enhanced login with security monitoring
     */
    public function login(Request $request)
    {
        // Check if IP is blocked
        if (SecurityMonitoringService::isIpBlocked($request->ip())) {
            return response()->json([
                'error' => 'Your IP has been temporarily blocked due to suspicious activity.'
            ], 403);
        }

        // Sanitize and validate input
        try {
            $credentials = InputSanitizationService::sanitizeInput($request->all(), [
                'email' => 'required|email|max:255',
                'password' => 'required|string|min:8|max:255',
            ]);
        } catch (\Exception $e) {
            SecurityMonitoringService::logSuspiciousActivity(
                $request, 
                'Invalid login input', 
                ['validation_errors' => $e->getMessage()]
            );
            return response()->json(['error' => 'Invalid input provided.'], 400);
        }

        // Check for brute force attempts
        if (SecurityMonitoringService::checkBruteForce($request->ip())) {
            SecurityMonitoringService::blockIp($request->ip(), 60);
            return response()->json([
                'error' => 'Too many login attempts. Please try again later.'
            ], 429);
        }

        // Attempt authentication
        if (Auth::attempt($credentials)) {
            // Regenerate session for security
            $request->session()->regenerate();
            
            // Log successful login
            SecurityMonitoringService::logSuccessfulLogin(
                $credentials['email'],
                $request->ip(),
                $request->userAgent()
            );

            return response()->json([
                'success' => true,
                'user' => Auth::user(),
                'redirect' => $this->getRedirectUrl()
            ]);
        } else {
            // Log failed login attempt
            SecurityMonitoringService::logFailedLogin(
                $credentials['email'],
                $request->ip(),
                $request->userAgent()
            );

            return response()->json([
                'error' => 'Invalid credentials provided.'
            ], 401);
        }
    }

    /**
     * Enhanced registration with security validation
     */
    public function register(Request $request)
    {
        // Sanitize and validate input
        try {
            $data = InputSanitizationService::sanitizeInput($request->all(), [
                'name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
                'email' => 'required|email|max:255|unique:tourists',
                'mobile' => 'required|string|regex:/^[0-9+\-\s()]+$/|max:20',
                'password' => 'required|string|min:8|confirmed',
                'location' => 'nullable|string|max:255',
            ]);
        } catch (\Exception $e) {
            SecurityMonitoringService::logSuspiciousActivity(
                $request, 
                'Invalid registration input', 
                ['validation_errors' => $e->getMessage()]
            );
            return response()->json(['error' => 'Invalid input provided.'], 400);
        }

        // Additional password strength validation
        if (!InputSanitizationService::validatePasswordStrength($data['password'])) {
            return response()->json([
                'error' => 'Password must contain at least 8 characters with uppercase, lowercase, number and special character.'
            ], 400);
        }

        // Check for SQL injection attempts
        foreach ($data as $key => $value) {
            if (is_string($value) && InputSanitizationService::containsSqlInjection($value)) {
                SecurityMonitoringService::logSuspiciousActivity(
                    $request, 
                    'SQL injection attempt in registration', 
                    ['field' => $key, 'value' => $value]
                );
                return response()->json(['error' => 'Invalid input detected.'], 400);
            }
        }

        try {
            // Create user
            $user = Tourist::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'password' => Hash::make($data['password']),
                'location' => $data['location'] ?? null,
            ]);

            // Log successful registration
            SecurityMonitoringService::logSuspiciousActivity(
                $request, 
                'User registration', 
                ['email' => $data['email'], 'result' => 'success']
            );

            return response()->json([
                'success' => true,
                'message' => 'Registration successful. Please login to continue.',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            // Log registration failure
            SecurityMonitoringService::logSuspiciousActivity(
                $request, 
                'Registration failure', 
                ['email' => $data['email'], 'error' => $e->getMessage()]
            );

            return response()->json([
                'error' => 'Registration failed. Please try again.'
            ], 500);
        }
    }

    /**
     * Enhanced logout with session cleanup
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log logout event
        if ($user) {
            SecurityMonitoringService::logSuspiciousActivity(
                $request, 
                'User logout', 
                ['email' => $user->email, 'result' => 'success']
            );
        }

        Auth::logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ]);
    }

    /**
     * Get redirect URL based on user role
     */
    private function getRedirectUrl(): string
    {
        $user = Auth::user();
        
        if ($user instanceof \App\Models\Admin) {
            return route('admin.dashboard');
        } elseif ($user instanceof \App\Models\Hotel) {
            return route('hotel.dashboard');
        } else {
            return route('tourist.dashboard');
        }
    }
}