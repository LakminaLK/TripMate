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

    public function dashboard(Request $request)
    {
        // Redirect to the proper dashboard controller with data
        $hotelProfileController = new HotelProfileController();
        return $hotelProfileController->dashboard($request);
    }

    public function login(Request $request)
    {
        // Validate input fields
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'username' => 'required',
            'password' => ['required', 'regex:/^(?=.*[a-z])(?=.*[A-Z]).+$/'],
        ], [
            'password.regex' => 'Password must contain at least one uppercase and one lowercase letter.',
            'username.required' => 'Username is required.',
            'password.required' => 'Password is required.'
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput($request->except('password'))
                ->with('error', $validator->errors()->first());
        }

        // Find the hotel by exact username match using binary comparison
        $hotel = \App\Models\Hotel::whereRaw('BINARY username = ?', [$request->username])->first();
        
        if ($hotel && Hash::check($request->password, $hotel->password)) {
            Auth::guard('hotel')->login($hotel);
            $request->session()->regenerate();
            
            return redirect()
                ->route('hotel.dashboard')
                ->with('success', 'Welcome back, ' . $hotel->name);
        }

        return back()
            ->withInput($request->except('password'))
            ->with('error', 'The provided username or password is incorrect. Please note that usernames are case-sensitive.');

    return back()->withErrors([
        'username' => 'Invalid credentials.',
    ])->onlyInput('username');
}



    public function logout(Request $request)
{
    Auth::guard('hotel')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('hotel.login');
}

}
