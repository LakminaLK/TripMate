<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller; // ✅ This is what you're missing
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class HotelAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('hotel.login');
    }

    public function dashboard()
{
    return view('hotel.dashboard'); // Make sure this Blade view exists!
}

    public function login(Request $request)
{
    $request->validate([
        'username' => 'required',
        'password' => 'required',
    ]);

    $credentials = $request->only('username', 'password');

    if (Auth::guard('hotel')->attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('hotel.dashboard');  // ✅ correct redirect
    }

    return back()->withErrors([
        'username' => 'Invalid credentials.',
    ])->onlyInput('username');
}



    public function logout()
    {
        Session::flush();
        return redirect()->route('hotel.login');
    }
}
