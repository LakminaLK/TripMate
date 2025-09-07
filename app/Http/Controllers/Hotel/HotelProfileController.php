<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class HotelProfileController extends Controller
{
    public function dashboard()
    {
        $hotel = auth('hotel')->user();
        return view('hotel.dashboard', compact('hotel'));
    }

    public function edit()
    {
        $hotel = auth('hotel')->user();
        return view('hotel.profile', compact('hotel'));
    }

    public function updatePassword(Request $request)
    {
        $hotel = auth('hotel')->user();

        $data = $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8),
                'regex:/^(?=.*[a-z])(?=.*[A-Z]).+$/'
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase and one lowercase letter.'
        ]);

        if (! Hash::check($data['current_password'], $hotel->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $hotel->password = Hash::make($data['password']);
        $hotel->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateUsername(Request $request)
    {
        $hotel = auth('hotel')->user();

        // Custom validation for case-sensitive uniqueness
        $existingHotel = \App\Models\Hotel::whereRaw('BINARY username = ?', [$request->username])
            ->where('id', '!=', $hotel->id)
            ->first();
        
        if ($existingHotel) {
            return back()->withErrors(['username' => 'This username is already taken.']);
        }

        $data = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $hotel->username = $data['username'];
        $hotel->save();

        return back()->with('success', 'Username updated successfully.');
    }
}
