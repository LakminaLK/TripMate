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
});

// Landing page route for the home link in the navbar
Route::get('/', fn() => view('tourist.landing'))->name('landing');


