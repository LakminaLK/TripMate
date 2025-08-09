<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login submission.
     */
    public function login(Request $request)
    {
        // ✅ Validate input fields
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // ✅ Attempt login with the 'admin' guard
        $credentials = $request->only('username', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            // ✅ Login successful
            return redirect()->route('admin.dashboard');
        }

        // ❌ Login failed - show error
        return back()->withErrors([
            'login' => 'Invalid username or password.',
        ])->withInput();
    }

    /**
     * Handle admin logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
