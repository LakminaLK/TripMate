<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\Hotel\HotelAuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminProfileController;

// 👇 you referenced these later; add imports so those routes work
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\PasswordController;

/*
|---------------------------------------------------------------------- 
| Landing Page (Tourist) 
|---------------------------------------------------------------------- 
*/
Route::get('/', fn() => view('tourist.landing'))->name('landing');

/*
|---------------------------------------------------------------------- 
| Tourist Registration + OTP 
|---------------------------------------------------------------------- 
*/
Route::post('/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('verify.otp');

// Tourist home (must be logged in as tourist role)
Route::middleware(['auth', 'role:tourist'])->group(function () {
    Route::get('/tourist/home', fn() => view('tourist.dashboard'))->name('tourist.home');
});

/*
|---------------------------------------------------------------------- 
| Admin Routes 
|---------------------------------------------------------------------- 
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Login Routes
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Admin Authenticated Routes (requires admin login)
    Route::middleware('auth:admin')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        
        // Admin Activities Routes (for managing activities)
        Route::resource('activities', ActivityController::class);

        // Admin Locations Routes (for managing locations)
        Route::resource('locations', LocationController::class);

        // Admin Customers Routes (for managing customers)
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
        
        // Admin Hotels Routes (for managing hotels)
        Route::get('/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'index'])->name('hotels');
    });
});

/*
|---------------------------------------------------------------------- 
| Hotel Routes 
|---------------------------------------------------------------------- 
*/
Route::prefix('hotel')->name('hotel.')->group(function () {
    // Hotel Login Routes
    Route::get('/login', [HotelAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [HotelAuthController::class, 'login']);

    // Hotel Authenticated Routes (requires hotel login)
    Route::middleware('auth:hotel')->group(function () {
        Route::get('/dashboard', [HotelAuthController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [HotelAuthController::class, 'logout'])->name('logout');
    });
});

/*
|---------------------------------------------------------------------- 
| Profile Routes (Common) 
|---------------------------------------------------------------------- 
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile/image', [ProfileController::class, 'removeImage'])->name('profile.removeImage');
    Route::delete('/profile/remove-image', [ProfileController::class, 'removeImage'])->name('profile.removeImage');
});

Route::middleware(['auth:tourist'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile/remove-image', [ProfileController::class, 'removeImage'])->name('profile.removeImage');
});

/*
|---------------------------------------------------------------------- 
| Default Auth Routes (Laravel Breeze/Jetstream/etc) 
|---------------------------------------------------------------------- 
*/
require __DIR__ . '/auth.php';

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    // Admin Activities Routes
    Route::resource('activities', ActivityController::class);
    // routes/web.php
});
Route::delete('/admin/activities/{activity}/image', [ActivityController::class, 'destroyImage'])
        ->name('admin.activities.image.destroy');


Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.update');
Route::put('password', [PasswordController::class, 'update'])->name('password.update');
// In routes/auth.php
Route::put('/profile/password', [PasswordController::class, 'update'])->name('profile.password.update');

Route::delete('/admin/customers/delete/{id}', [AdminCustomerController::class, 'destroy'])->name('admin.customers.delete');

require __DIR__.'/auth.php';

// Landing page route for the home link in the navbar
Route::get('/', fn() => view('tourist.landing'))->name('landing');

Route::prefix('admin')->name('admin.')->middleware(['auth:admin'])->group(function () {
    Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');
    Route::put('/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
    Route::delete('/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');

    // delete one gallery image
    // NOTE: these names must NOT include "admin." because the group already prefixes it.
    Route::delete('/locations/{location}/main-image', [LocationController::class, 'destroyMainImage'])
        ->name('locations.main.destroy');
    Route::delete('/locations/images/{image}', [LocationController::class, 'destroyImage'])
        ->name('locations.images.destroy');
        // Admin Profile (view + change password)
    Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::put('/profile/username', [AdminProfileController::class, 'updateUsername'])
        ->name('profile.username.update');

});

// Admin – Hotels
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'index'])->name('hotels.index');
    Route::post('/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'store'])->name('hotels.store');
    Route::put('/hotels/{hotel}', [\App\Http\Controllers\Admin\HotelController::class, 'update'])->name('hotels.update');
    Route::delete('/hotels/{hotel}', [\App\Http\Controllers\Admin\HotelController::class, 'destroy'])->name('hotels.destroy');

    // Optional: reset credentials for a hotel (if you want a “Reset Password” later)
    Route::post('/hotels/{hotel}/reset-credentials', [\App\Http\Controllers\Admin\HotelController::class, 'resetCredentials'])
        ->name('hotels.resetCreds');
});

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ExploreController;

use App\Http\Controllers\Tourist\ExploreController as TouristExploreController;

Route::get('/explore', [TouristExploreController::class, 'index'])->name('tourist.explore');
Route::get('/explore/activities/{activity}', [TouristExploreController::class, 'showActivity'])
     ->name('tourist.activity.show');
Route::get('/explore/locations/{location}/hotels', [TouristExploreController::class, 'hotelsByLocation'])
     ->name('tourist.location.hotels');

Route::get('/', [HomeController::class, 'index'])->name('landing');







