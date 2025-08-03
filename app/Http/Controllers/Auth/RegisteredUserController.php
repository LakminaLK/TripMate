<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tourist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Mail\TouristOtpMail;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // Check if email or mobile exists before validating
        if (Tourist::where('email', $request->email)->exists()) {
            return back()->withInput()->withErrors(['email' => 'This email is already registered.']);
        }

        if (Tourist::where('mobile', $request->mobile)->exists()) {
            return back()->withInput()->withErrors(['mobile' => 'This mobile number is already registered.']);
        }

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'mobile' => 'required|string|max:20',
            'password' => 'required|string|confirmed|min:8',
            'location' => 'nullable|string|max:255',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store user data in session (not DB yet)
        Session::put('register_data', [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'location' => $request->location,
            'otp' => $otp,
        ]);

        // Send OTP to email
        try {
            Mail::to($request->email)->send(new TouristOtpMail($otp));
        } catch (\Exception $e) {
            logger('Mail failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send OTP email. Please try again.');
        }

        Session::put('pending_email', $request->email);

        return redirect()->back()->with('showOtpModal', true);
    }

    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'email' => 'required|email',
        ]);

        $data = Session::get('register_data');

        logger("Entered OTP: " . $request->otp);
        logger("Stored OTP for {$request->email}: " . ($data['otp'] ?? 'null'));

        if (!$data || $request->email !== $data['email']) {
            return back()->with([
                'showOtpModal' => true,
                'otp_error' => 'Session expired or mismatched email. Please register again.'
            ]);
        }

        if ($data['otp'] == $request->otp) {
            // ✅ Save user with otp_verified = 1
            Tourist::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'location' => $data['location'],
                'password' => $data['password'],
                'otp_verified' => 1, // ✅ mark as verified
            ]);

            Session::forget(['register_data', 'pending_email']);

            return redirect()->route('login')->with('success', 'OTP verified. Please log in.');
        }

        return redirect()->back()->with([
            'showOtpModal' => true,
            'otp_error' => 'Invalid OTP. Please try again.'
        ]);
    }
}
