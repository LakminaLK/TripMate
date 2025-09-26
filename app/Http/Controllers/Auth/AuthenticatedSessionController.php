<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\LoginAttemptService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the tourist login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle tourist login request.
     */
    public function store(Request $request)
    {
        $email = $request->input('email');
        $ipAddress = $request->ip();
        $credentials = $request->only('email', 'password');
        $loginAttemptService = app(LoginAttemptService::class);

        // Check if account is locked out
        if ($loginAttemptService->isAccountLocked($email, $ipAddress)) {
            $remainingMinutes = $loginAttemptService->getRemainingLockoutMinutes($email, $ipAddress);
            
            if ($request->ajax()) {
                return response()->json([
                    'errors' => ['email' => ["Account temporarily locked. Try again in {$remainingMinutes} minutes."]]
                ], 422);
            }
            
            return back()->withErrors([
                'email' => "Account temporarily locked. Try again in {$remainingMinutes} minutes.",
            ])->withInput();
        }

        // Attempt login using 'tourist' guard
        if (!Auth::guard('tourist')->attempt($credentials)) {
            // Record failed attempt
            $loginAttemptService->recordFailedAttempt($email, $request);
            
            // Check if account should be locked after this attempt
            if ($loginAttemptService->isAccountLocked($email, $ipAddress)) {
                $remainingMinutes = $loginAttemptService->getRemainingLockoutMinutes($email, $ipAddress);
                
                if ($request->ajax()) {
                    return response()->json([
                        'errors' => ['email' => ["Too many failed attempts. Account locked for {$remainingMinutes} minutes."]]
                    ], 422);
                }
                
                return back()->withErrors([
                    'email' => "Too many failed attempts. Account locked for {$remainingMinutes} minutes.",
                ])->withInput();
            }
            
            if ($request->ajax()) {
                return response()->json([
                    'errors' => ['email' => ['Invalid email or password.']]
                ], 422);
            }
            
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->withInput();
        }

        // Clear any existing failed attempts on successful login
        $loginAttemptService->resetFailedAttempts($email, $ipAddress);

        // ✅ Regenerate session to prevent session fixation
        $request->session()->regenerate();

        $user = Auth::guard('tourist')->user();
        $role = $user->role;

        // Get intended URL from request or session
        $intendedUrl = $request->get('redirect') ?: session('url.intended');
        
        // Redirect based on role or intended URL
        if ($role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($role === 'hotel') {
            return redirect('/hotel/dashboard');
        }

        // For tourists, check if there's intended booking data, then redirect to payment
        if (session('intended_booking_data')) {
            return redirect()->route('tourist.payment.create')
                ->with('success', 'Welcome back, ' . $user->name . '! Please complete your booking.');
        }

        // Check if there's a valid redirect URL
        if ($intendedUrl && $this->isValidRedirectUrl($intendedUrl)) {
            // Clear the intended URL from session
            session()->forget('url.intended');
            return redirect($intendedUrl)->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // ✅ Tourist role: redirect to landing page with optional toast
        return redirect('/')->with('success', 'Welcome back, ' . $user->name . '!');
    }

    /**
     * Validate redirect URL to ensure it's safe
     */
    private function isValidRedirectUrl(?string $url): bool
    {
        if (!$url) {
            return false;
        }

        // Check if it's a valid URL format
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            // If it's not a full URL, check if it's a valid relative path
            if (!str_starts_with($url, '/') || str_contains($url, '..')) {
                return false;
            }
        } else {
            // If it's a full URL, ensure it's the same domain
            $urlHost = parse_url($url, PHP_URL_HOST);
            $requestHost = request()->getHost();
            
            if ($urlHost !== $requestHost) {
                return false;
            }
        }

        // Prevent redirect to admin or hotel areas
        if (str_contains($url, '/admin/') || str_contains($url, '/hotel/')) {
            return false;
        }

        return true;
    }

    /**
     * Handle tourist logout and redirect to landing page.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // ✅ Logout using correct guard
        Auth::guard('tourist')->logout();

        // 🔐 Invalidate the current session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 🚀 Redirect to landing (home) page
        return redirect('/');
    }
}
