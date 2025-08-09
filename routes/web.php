<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Hotel\HotelAuthController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Landing Page (Tourist)
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('tourist.landing'))->name('landing');

/*
|--------------------------------------------------------------------------
| Tourist Registration + OTP
|--------------------------------------------------------------------------
*/
Route::post('/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('verify.otp');

// Tourist home (must be logged in as tourist role)
Route::middleware(['auth', 'role:tourist'])->group(function () {
    Route::get('/tourist/home', fn () => view('tourist.dashboard'))->name('tourist.home');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Public admin auth
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);

    // Protected admin area
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Resources
        Route::resource('activities', ActivityController::class);
        Route::resource('locations', LocationController::class);

        // Customers & Hotels pages
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
        Route::get('/hotels', [\App\Http\Controllers\Admin\HotelController::class, 'index'])->name('hotels');

        // 🔽 Image delete routes used by the Locations page (AJAX)
        Route::delete('/locations/images/{image}', [LocationController::class, 'destroyImage'])
            ->name('locations.images.destroy');
        Route::delete('/locations/{location}/main-image', [LocationController::class, 'destroyMainImage'])
            ->name('locations.main.destroy');

        // Example: delete a customer by id
        Route::delete('/customers/delete/{id}', [AdminCustomerController::class, 'destroy'])
            ->name('customers.delete');
    });
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
        Route::get('/dashboard', [HotelAuthController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [HotelAuthController::class, 'logout'])->name('logout');
    });
});

/*
|--------------------------------------------------------------------------
| Profile Routes (Common)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile/remove-image', [ProfileController::class, 'removeImage'])->name('profile.removeImage');
});

/*
|--------------------------------------------------------------------------
| Default Auth Routes (Breeze/Jetstream/etc.)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
