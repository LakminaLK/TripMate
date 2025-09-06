<?php

namespace App\Http\Controllers\Hotel\Auth;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\HotelPasswordResetMail;

class PasswordResetController extends Controller
{
    public function showForgotForm()
    {
        return view('hotel.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:hotels,email',
        ]);

        // Generate password reset token
        $token = Str::random(64);
        
        // Store token in database
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        \DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now()
        ]);

        // Send reset email
        $hotel = Hotel::where('email', $request->email)->first();
        Mail::to($request->email)->send(new HotelPasswordResetMail($hotel, $token));

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Password reset link has been sent to your email!',
            'duration' => 3000
        ]);
    }

    public function showResetForm(Request $request, $token)
    {
        return view('hotel.auth.reset-password', ['request' => $request]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:hotels,email',
            'password' => 'required|confirmed|min:8',
        ]);

        // Verify token
        $tokenData = \DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData || !Hash::check($request->token, $tokenData->token)) {
            return back()->withErrors(['email' => 'Invalid or expired password reset link.']);
        }

        // Update password
        $hotel = Hotel::where('email', $request->email)->first();
        $hotel->update([
            'password' => Hash::make($request->password)
        ]);

        // Delete used token
        \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('hotel.login')
            ->with('status', 'Your password has been reset successfully!');
    }
}
