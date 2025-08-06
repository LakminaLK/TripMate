<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the profile edit form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => Auth::guard('tourist')->user(),
        ]);
    }

    /**
     * Update profile info (name + profile image).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::guard('tourist')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;

        // Handle profile image upload
        if ($request->hasFile('profile_image')) {
    if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
        Storage::delete('public/' . $user->profile_image);
    }

    $path = $request->file('profile_image')->store('profile_images', 'public');
    $user->profile_image = $path;
}


        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = Auth::guard('tourist')->user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::guard('tourist')->user();

        Auth::guard('tourist')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

public function removeImage(): RedirectResponse
{
    $user = Auth::guard('tourist')->user();

    if ($user->profile_image && Storage::exists('public/' . $user->profile_image)) {
        Storage::delete('public/' . $user->profile_image);
    }

    $user->profile_image = null;
    $user->save();

    return redirect()->route('profile.edit')->with('status', 'profile-image-removed');
}



}
