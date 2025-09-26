<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tourist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
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
        logger('Registration request started', [
            'email' => $request->email,
            'name' => $request->name,
            'has_mobile' => !empty($request->mobile)
        ]);

        // Check if email or mobile exists before validating
        if (Tourist::where('email', $request->email)->exists()) {
            return back()->withInput()->with('error', 'This email is already registered. Please use a different email or try logging in.');
        }

        if (Tourist::where('mobile', $request->mobile)->exists()) {
            return back()->withInput()->with('error', 'This mobile number is already registered. Please use a different number.');
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

        // Initialize OTP attempts counter
        Session::put('otp_attempts', 0);

        // Send OTP to email
        try {
            Mail::to($request->email)->send(new TouristOtpMail($otp));
        } catch (\Exception $e) {
            logger('Mail failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send OTP email. Please try again.');
        }

        // Store email in session for later comparison
        Session::put('pending_email', $request->email);

        logger('Registration completed, showing OTP modal', [
            'email' => $request->email,
            'otp' => $otp,
            'session_keys' => array_keys(Session::all())
        ]);

        // Return to the registration page with the OTP modal
        return redirect()->back()->with('showOtpModal', true);
    }

    public function verifyOtp(Request $request): RedirectResponse
{
    logger('OTP Verification started', [
        'email' => $request->email,
        'otp' => $request->otp,
        'session_email' => Session::get('pending_email'),
        'session_data_exists' => Session::has('register_data')
    ]);

    // Validate OTP input with custom messages
    $validator = Validator::make($request->all(), [
        'otp' => 'required|digits:6',
        'email' => 'required|email',
    ], [
        'otp.required' => 'Please enter the OTP code.',
        'otp.digits' => 'The OTP must be exactly 6 digits.',
    ]);

    if ($validator->fails()) {
        logger('OTP Validation failed', ['errors' => $validator->errors()]);
        return back()->with([
            'showOtpModal' => true,
            'otp_error' => $validator->errors()->first(),
            'otp_attempts' => Session::get('otp_attempts', 0)
        ]);
    }

    // Initialize or get current attempt count
    $attempts = Session::get('otp_attempts', 0);
    $maxAttempts = 5;

    // Check if maximum attempts exceeded
    if ($attempts >= $maxAttempts) {
        // Clear session data after max attempts
        Session::forget(['register_data', 'pending_email', 'otp_attempts']);
        
        return redirect()->route('register')->with([
            'error' => 'Maximum OTP attempts exceeded. Please register again.',
            'showOtpModal' => false
        ]);
    }

    // Retrieve the stored registration data from the session
    $data = Session::get('register_data');

    if (!$data || $request->email !== $data['email']) {
        return back()->with([
            'showOtpModal' => true,
            'otp_error' => 'Session expired or mismatched email. Please register again.',
            'otp_attempts' => $attempts
        ]);
    }

    // Increment attempt count
    $attempts++;
    Session::put('otp_attempts', $attempts);

    // Validate OTP
    logger("OTP Verification: Expected = {$data['otp']}, Received = {$request->otp}");
    
    if ($data['otp'] == $request->otp) {
        logger('OTP Verification successful, creating user');
        
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
        Session::forget(['register_data', 'pending_email', 'otp_attempts']);

        // Redirect to login page with success message
        return redirect()->route('login')->with('success', 'OTP verified and registration complete. A confirmation email has been sent.');
    }

    // OTP is invalid, check remaining attempts
    $remainingAttempts = $maxAttempts - $attempts;
    
    if ($remainingAttempts <= 0) {
        // Clear session data after max attempts
        Session::forget(['register_data', 'pending_email', 'otp_attempts']);
        
        return redirect()->route('register')->with([
            'error' => 'Maximum OTP attempts exceeded. Please register again.',
            'showOtpModal' => false
        ]);
    }

    // Provide specific error message based on remaining attempts
    $errorMessage = $remainingAttempts == 1 
        ? 'Invalid OTP. This is your last attempt!' 
        : "Invalid OTP. You have {$remainingAttempts} attempts remaining.";

    logger('OTP Verification failed', [
        'error_message' => $errorMessage,
        'attempts' => $attempts,
        'remaining' => $remainingAttempts
    ]);

    // OTP is invalid, return back with error
    return back()->with([
        'showOtpModal' => true,
        'otp_error' => $errorMessage,
        'otp_attempts' => $attempts
    ]);
}

public function resendOtp(Request $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
    ]);

    // Check if there's a resend cooldown
    $lastResend = Session::get('otp_last_resend', 0);
    $cooldownTime = 60; // 60 seconds = 1 minute
    $timeElapsed = time() - $lastResend;

    if ($timeElapsed < $cooldownTime) {
        $remainingTime = $cooldownTime - $timeElapsed;
        return back()->with([
            'showOtpModal' => true,
            'otp_error' => "Please wait {$remainingTime} seconds before requesting a new OTP.",
            'otp_attempts' => Session::get('otp_attempts', 0)
        ]);
    }

    // Retrieve the stored registration data from the session
    $data = Session::get('register_data');

    if (!$data || $request->email !== $data['email']) {
        return back()->with([
            'showOtpModal' => true,
            'otp_error' => 'Session expired or mismatched email. Please register again.',
            'otp_attempts' => Session::get('otp_attempts', 0)
        ]);
    }

    // Generate new OTP
    $otp = rand(100000, 999999);

    // Update the OTP in session
    $data['otp'] = $otp;
    Session::put('register_data', $data);

    // Send new OTP to email
    try {
        Mail::to($request->email)->send(new TouristOtpMail($otp));
        
        // Update the last resend time
        Session::put('otp_last_resend', time());
        
        return back()->with([
            'showOtpModal' => true,
            'success' => 'New OTP has been sent to your email.',
            'otp_attempts' => Session::get('otp_attempts', 0)
        ]);
    } catch (\Exception $e) {
        logger('Mail failed during resend: ' . $e->getMessage());
        return back()->with([
            'showOtpModal' => true,
            'otp_error' => 'Failed to send OTP email. Please try again.',
            'otp_attempts' => Session::get('otp_attempts', 0)
        ]);
    }
}

}
