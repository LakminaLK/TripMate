<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class AdminProfileController extends Controller
{
    public function edit()
    {
        $admin = auth('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $admin = auth('admin')->user();

        if (! Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        $admin->password = Hash::make($request->password);
        $admin->save();

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateUsername(Request $request)
    {
        $admin = auth('admin')->user();

        $validated = $request->validate([
            'username' => [
                'required','string','max:255',
                Rule::unique('admins','username')->ignore($admin->id),
            ],
        ]);

        $admin->username = $validated['username'];
        $admin->save();

        return back()->with('success', 'Username updated successfully.');
    }
}
