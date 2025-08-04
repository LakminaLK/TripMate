<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Controllers
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\Hotel\HotelAuthController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Admin\AdminCustomerController;

/*
|--------------------------------------------------------------------------
| Landing Page (Tourist)
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('tourist.landing'))->name('landing');

/*
|--------------------------------------------------------------------------
| Tourist Registration + OTP
|--------------------------------------------------------------------------
*/
Route::post('/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('verify.otp');

// Tourist home (must be logged in as tourist role)
Route::middleware(['auth', RoleMiddleware::class . ':tourist'])->group(function () {
    Route::get('/tourist/home', fn() => view('tourist.dashboard'))->name('tourist.home');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
    Route::get('/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'index'])->name('hotels');

});

/*
|--------------------------------------------------------------------------
| Hotel Routes
|--------------------------------------------------------------------------
*/
Route::prefix('hotel')->name('hotel.')->group(function () {
    Route::get('/login', [HotelAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [HotelAuthController::class, 'login']);

    Route::middleware('auth:hotel')->group(function () {
        // ✅ FIX: Changed to use HotelAuthController instead of missing HotelDashboardController
        Route::get('/dashboard', [HotelAuthController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [HotelAuthController::class, 'logout'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Tourist Logout (default guard)
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthenticatedSessionController::class, 'destroy'])->name('login');

/*
|--------------------------------------------------------------------------
| Profile Routes (Common)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Default Auth Routes (Laravel Breeze/Jetstream/etc)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

