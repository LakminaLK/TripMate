<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
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
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');

        // Attempt login using 'tourist' guard
        if (!Auth::guard('tourist')->attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Invalid email or password.',
            ])->withInput();
        }

        // ✅ Regenerate session to prevent session fixation
        $request->session()->regenerate();

        $user = Auth::guard('tourist')->user();
        $role = $user->role;

        // Check if there's a redirect URL
        $intendedUrl = $request->get('redirect');

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

        // Check if there's a redirect URL
        if ($intendedUrl && filter_var($intendedUrl, FILTER_VALIDATE_URL) && parse_url($intendedUrl, PHP_URL_HOST) === request()->getHost()) {
            return redirect($intendedUrl)->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // ✅ Tourist role: redirect to landing page with optional toast
        return redirect('/')->with('success', 'Welcome back, ' . $user->name . '!');
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
