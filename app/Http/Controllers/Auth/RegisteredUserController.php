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
use App\Mail\RegistrationSuccessEmail;


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

        // Generate OTP (6-digit random number)
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

        // Store email in session for later comparison
        Session::put('pending_email', $request->email);

        // Return to the registration page with the OTP modal
        return redirect()->back()->with('showOtpModal', true);
    }

    public function verifyOtp(Request $request): RedirectResponse
{
    // Validate OTP input
    $request->validate([
        'otp' => 'required|digits:6',
        'email' => 'required|email',
    ]);

    // Retrieve the stored registration data from the session
    $data = Session::get('register_data');

    if (!$data || $request->email !== $data['email']) {
        return back()->with([
            'showOtpModal' => true,
            'otp_error' => 'Session expired or mismatched email. Please register again.'
        ]);
    }

    // Validate OTP
    if ($data['otp'] == $request->otp) {
        // Save the user after OTP verification
        $user = Tourist::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
            'location' => $data['location'],
            'password' => $data['password'],
            'otp_verified' => 1, // Mark as verified
        ]);

        // Send registration success email to the user
        try {
            // Send the success email
            Mail::to($user->email)->send(new RegistrationSuccessEmail($user));  // Send email to the user

            // Log success message
            logger('Registration success email sent to: ' . $user->email);
        } catch (\Exception $e) {
            // Log email failure
            logger('Failed to send registration email: ' . $e->getMessage());
        }

        // Clear session data
        Session::forget(['register_data', 'pending_email']);

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'OTP verified and registration complete. A confirmation email has been sent.');
    }

    // OTP is invalid, return back with error
    return back()->with([
        'showOtpModal' => true,
        'otp_error' => 'Invalid OTP. Please try again.'
    ]);
}


}
