<?php

use App\Http\Controllers\Hotel\Auth\PasswordResetController;

// Add these routes in your hotel routes group
Route::middleware('guest')->group(function () {
    Route::get('forgot-password', [PasswordResetController::class, 'showForgotForm'])
        ->name('hotel.password.request');

    Route::post('forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->name('hotel.password.email');

    Route::get('reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('hotel.password.reset');

    Route::post('reset-password', [PasswordResetController::class, 'resetPassword'])
        ->name('hotel.password.store');
});
