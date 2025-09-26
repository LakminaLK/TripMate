<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Services\LoginAttemptService;
use App\Services\SecurityMonitoringService;
use App\Models\Tourist;
use App\Models\Hotel;
use App\Models\Admin;

class LoginAttemptController extends Controller
{
    protected $loginAttemptService;

    public function __construct(LoginAttemptService $loginAttemptService)
    {
        $this->loginAttemptService = $loginAttemptService;
    }

    /**
     * Handle tourist login with attempt tracking
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input provided.',
                'errors' => $validator->errors()
            ], 400);
        }

        $email = $request->email;
        $password = $request->password;
        $ipAddress = $request->ip();

        // Check lockout status before attempting login
        $lockoutStatus = $this->loginAttemptService->getLockoutStatus($email, $ipAddress);
        
        if ($lockoutStatus['locked']) {
            return response()->json([
                'error' => 'Account temporarily locked.',
                'lockout_status' => $lockoutStatus,
                'message' => "Too many failed attempts. Try again in {$lockoutStatus['remaining_minutes']} minutes."
            ], 429);
        }

        // Attempt to authenticate
        $tourist = Tourist::where('email', $email)->first();
        
        if ($tourist && Hash::check($password, $tourist->password)) {
            // Successful login
            Auth::guard('web')->login($tourist);
            $request->session()->regenerate();

            // Reset failed attempts
            $this->loginAttemptService->resetFailedAttempts($email, $ipAddress);

            // Log successful login
            SecurityMonitoringService::logSuccessfulLogin($email, $ipAddress, $request->userAgent());

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'user' => $tourist,
                'redirect' => route('dashboard') // Adjust route as needed
            ]);
        } else {
            // Failed login - record attempt
            $this->loginAttemptService->recordFailedAttempt($email, $request);

            // Get updated status after recording attempt
            $updatedStatus = $this->loginAttemptService->getLockoutStatus($email, $ipAddress);

            $errorMessage = 'Invalid credentials.';
            $responseData = [
                'error' => $errorMessage,
                'lockout_status' => $updatedStatus
            ];

            if ($updatedStatus['locked']) {
                $responseData['message'] = "Account locked for {$updatedStatus['lockout_duration']} minutes due to too many failed attempts.";
                $responseData['locked'] = true;
            } else {
                $remaining = $updatedStatus['remaining_attempts'];
                if ($remaining <= 2) {
                    $responseData['warning'] = "Warning: {$remaining} attempts remaining before account lockout.";
                }
            }

            return response()->json($responseData, $updatedStatus['locked'] ? 429 : 401);
        }
    }

    /**
     * Handle hotel login with attempt tracking
     */
    public function hotelLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input provided.',
                'errors' => $validator->errors()
            ], 400);
        }

        $email = $request->email;
        $password = $request->password;
        $ipAddress = $request->ip();

        $lockoutStatus = $this->loginAttemptService->getLockoutStatus($email, $ipAddress);
        
        if ($lockoutStatus['locked']) {
            return response()->json([
                'error' => 'Account temporarily locked.',
                'lockout_status' => $lockoutStatus,
                'message' => "Too many failed attempts. Try again in {$lockoutStatus['remaining_minutes']} minutes."
            ], 429);
        }

        $hotel = Hotel::where('email', $email)->first();
        
        if ($hotel && Hash::check($password, $hotel->password)) {
            Auth::guard('hotel')->login($hotel);
            $request->session()->regenerate();

            $this->loginAttemptService->resetFailedAttempts($email, $ipAddress);
            SecurityMonitoringService::logSuccessfulLogin($email, $ipAddress, $request->userAgent());

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'user' => $hotel,
                'redirect' => route('hotel.dashboard')
            ]);
        } else {
            $this->loginAttemptService->recordFailedAttempt($email, $request);
            $updatedStatus = $this->loginAttemptService->getLockoutStatus($email, $ipAddress);

            $responseData = [
                'error' => 'Invalid credentials.',
                'lockout_status' => $updatedStatus
            ];

            if ($updatedStatus['locked']) {
                $responseData['message'] = "Account locked for {$updatedStatus['lockout_duration']} minutes.";
                $responseData['locked'] = true;
            } else {
                $remaining = $updatedStatus['remaining_attempts'];
                if ($remaining <= 2) {
                    $responseData['warning'] = "Warning: {$remaining} attempts remaining.";
                }
            }

            return response()->json($responseData, $updatedStatus['locked'] ? 429 : 401);
        }
    }

    /**
     * Handle admin login with attempt tracking
     */
    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid input provided.',
                'errors' => $validator->errors()
            ], 400);
        }

        $email = $request->email;
        $password = $request->password;
        $ipAddress = $request->ip();

        $lockoutStatus = $this->loginAttemptService->getLockoutStatus($email, $ipAddress);
        
        if ($lockoutStatus['locked']) {
            return response()->json([
                'error' => 'Account temporarily locked.',
                'lockout_status' => $lockoutStatus,
                'message' => "Too many failed attempts. Try again in {$lockoutStatus['remaining_minutes']} minutes."
            ], 429);
        }

        $admin = Admin::where('email', $email)->first();
        
        if ($admin && Hash::check($password, $admin->password)) {
            Auth::guard('admin')->login($admin);
            $request->session()->regenerate();

            $this->loginAttemptService->resetFailedAttempts($email, $ipAddress);
            SecurityMonitoringService::logSuccessfulLogin($email, $ipAddress, $request->userAgent());

            return response()->json([
                'success' => true,
                'message' => 'Login successful.',
                'user' => $admin,
                'redirect' => route('admin.dashboard')
            ]);
        } else {
            $this->loginAttemptService->recordFailedAttempt($email, $request);
            $updatedStatus = $this->loginAttemptService->getLockoutStatus($email, $ipAddress);

            $responseData = [
                'error' => 'Invalid credentials.',
                'lockout_status' => $updatedStatus
            ];

            if ($updatedStatus['locked']) {
                $responseData['message'] = "Account locked for {$updatedStatus['lockout_duration']} minutes.";
                $responseData['locked'] = true;
            } else {
                $remaining = $updatedStatus['remaining_attempts'];
                if ($remaining <= 2) {
                    $responseData['warning'] = "Warning: {$remaining} attempts remaining.";
                }
            }

            return response()->json($responseData, $updatedStatus['locked'] ? 429 : 401);
        }
    }

    /**
     * Check lockout status endpoint (for AJAX calls)
     */
    public function checkLockoutStatus(Request $request)
    {
        $email = $request->input('email');
        $ipAddress = $request->ip();

        if (!$email) {
            return response()->json(['error' => 'Email required'], 400);
        }

        $status = $this->loginAttemptService->getLockoutStatus($email, $ipAddress);

        return response()->json([
            'status' => 'success',
            'lockout_status' => $status
        ]);
    }

    /**
     * Get remaining lockout time (for real-time countdown)
     */
    public function getRemainingTime(Request $request)
    {
        $email = $request->input('email');
        $ipAddress = $request->ip();

        if (!$email) {
            return response()->json(['error' => 'Email required'], 400);
        }

        $remainingSeconds = $this->loginAttemptService->getRemainingLockoutSeconds($email, $ipAddress);

        return response()->json([
            'remaining_seconds' => $remainingSeconds,
            'remaining_minutes' => ceil($remainingSeconds / 60),
            'is_locked' => $remainingSeconds > 0
        ]);
    }
}