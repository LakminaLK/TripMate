<?php

use Illuminate\Support\Facades\Route;

/**
 * Controllers
 */
// Admin
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\LocationController as AdminLocationController;   // ⬅ alias
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;

// Tourist (public)
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Tourist\ExploreController as TouristExploreController;
use App\Http\Controllers\Tourist\LocationController as TouristLocationController; // ⬅ alias
use App\Http\Controllers\Tourist\HotelController as TouristHotelController;
use App\Http\Controllers\EmergencyServiceController;

// Auth (shared)
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ProfileController;

// Hotel
use App\Http\Controllers\Hotel\HotelAuthController;
use App\Http\Controllers\Hotel\HotelProfileController;
use App\Http\Controllers\Hotel\HotelManagementController;
use App\Http\Controllers\Hotel\RoomController;
use App\Http\Controllers\Hotel\BookingController;
use App\Http\Controllers\Hotel\Auth\PasswordResetController;

/* =========================================================================
|  LANDING (public)
| ========================================================================= */
Route::get('/', [HomeController::class, 'index'])->name('landing');

/* =========================================================================
|  TOURIST – PUBLIC PAGES
| ========================================================================= */
Route::prefix('explore')->name('tourist.')->group(function () {
    Route::get('/', [TouristExploreController::class, 'index'])->name('explore');

    // Activity detail
    Route::get('/activities/{activity}', [TouristExploreController::class, 'showActivity'])
        ->name('activity.show');

    // Location detail (new pretty page with description + gallery + hotels)
    Route::get('/locations/{location}', [TouristLocationController::class, 'show'])
        ->name('location.show');

    // (Optional) legacy list of hotels only for a location
    Route::get('/locations/{location}/hotels', [TouristExploreController::class, 'hotelsByLocation'])
        ->name('location.hotels');

    // Hotel details page
    Route::get('/hotels/{hotel}', [TouristHotelController::class, 'show'])
        ->name('hotels.show');
    
    // Hotel reviews endpoint
    Route::get('/hotels/{hotel}/reviews', [TouristHotelController::class, 'getAllReviews'])
        ->name('hotels.reviews');
    
    // Hotel availability checking routes
    Route::post('/hotels/{hotel}/check-availability', [TouristHotelController::class, 'checkAvailability'])
        ->name('hotels.check-availability');
    Route::get('/hotels/{hotel}/rooms/{roomType}/availability', [TouristHotelController::class, 'getRoomAvailability'])
        ->name('hotels.room-availability');
});

/* =========================================================================
|  TOURIST – AUTHENTICATED PROFILE
| ========================================================================= */
Route::middleware('auth:tourist')->group(function () {
    Route::get('/tourist/home', fn () => view('tourist.dashboard'))->name('tourist.home');

    // Tourist profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile/remove-image', [ProfileController::class, 'removeImage'])->name('profile.removeImage');
});

// Booking management - handle authentication manually to avoid redirect loops
Route::prefix('bookings')->name('tourist.bookings.')->group(function () {
    Route::get('/', [App\Http\Controllers\Tourist\BookingController::class, 'index'])->name('index');
    Route::get('/view', [App\Http\Controllers\Tourist\BookingController::class, 'viewBookings'])->name('view');
    Route::get('/{booking}', [App\Http\Controllers\Tourist\BookingController::class, 'show'])->name('show');
});

// Review routes - handle authentication manually to avoid redirect loops
Route::prefix('reviews')->name('tourist.reviews.')->group(function () {
    Route::post('/', [App\Http\Controllers\Tourist\ReviewController::class, 'store'])->name('store');
    Route::get('/booking/{bookingId}', [App\Http\Controllers\Tourist\ReviewController::class, 'show'])->name('show');
    Route::put('/{reviewId}', [App\Http\Controllers\Tourist\ReviewController::class, 'update'])->name('update');
    Route::delete('/{reviewId}', [App\Http\Controllers\Tourist\ReviewController::class, 'destroy'])->name('destroy');
});

// Payment routes - handle authentication manually to avoid redirect loops
Route::prefix('payment')->name('tourist.payment.')->group(function () {
    Route::match(['GET', 'POST'], '/create', [App\Http\Controllers\Tourist\PaymentController::class, 'create'])->name('create');
    Route::post('/process', [App\Http\Controllers\Tourist\PaymentController::class, 'process'])->name('process');
    Route::get('/success/{booking}', [App\Http\Controllers\Tourist\PaymentController::class, 'success'])->name('success');
    Route::get('/success-simple', function () {
        return view('tourist.payment-success-simple');
    })->name('success.simple');
    Route::get('/failed', [App\Http\Controllers\Tourist\PaymentController::class, 'failed'])->name('failed');
});

// OTP verify for tourist registration
Route::post('/verify-otp', [RegisteredUserController::class, 'verifyOtp'])->name('verify.otp');

/* =========================================================================
|  DEFAULT AUTH ROUTES (Breeze/Jetstream/etc)
| ========================================================================= */
require __DIR__ . '/auth.php';

/* =========================================================================
|  HOTEL ROUTES
| ========================================================================= */
Route::prefix('hotel')->name('hotel.')->group(function () {
    Route::get('/login', [HotelAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [HotelAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [HotelAuthController::class, 'logout'])->name('logout');
    
    // Password Reset Routes
    Route::get('/forgot-password', [PasswordResetController::class, 'showForgotForm'])
        ->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
        ->name('password.store');

    // Protected Hotel Routes
    Route::middleware('auth:hotel')->group(function () {
        Route::get('/dashboard', [HotelProfileController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [HotelProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/username', [HotelProfileController::class, 'updateUsername'])->name('profile.username.update');
        Route::put('/profile/password', [HotelProfileController::class, 'updatePassword'])->name('profile.password.update');
        
        // Hotel Management Routes
        Route::get('/management', [HotelManagementController::class, 'index'])->name('management.index');
        Route::get('/management/edit', [HotelManagementController::class, 'edit'])->name('management.edit');
        Route::put('/management', [HotelManagementController::class, 'update'])->name('management.update');
        Route::delete('/management/images/{imageId}', [HotelManagementController::class, 'deleteImage'])->name('management.image.delete');
        Route::delete('/management/main-image', [HotelManagementController::class, 'deleteMainImage'])->name('management.main-image.delete');
        Route::post('/management/toggle-status', [HotelManagementController::class, 'toggleStatus'])->name('management.toggle-status');
        
        // Room Management Routes
        Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
        Route::put('/rooms', [RoomController::class, 'updateRoomCounts'])->name('rooms.update');
        
        // Booking Management Routes
        Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
        Route::post('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    });
});

/* =========================================================================
|  EMERGENCY SERVICES
| ========================================================================= */
Route::prefix('emergency')->name('emergency-services.')->group(function () {
    Route::get('/', [EmergencyServiceController::class, 'index'])->name('index');
    Route::get('/{service}', [EmergencyServiceController::class, 'show'])->name('show');
});

/* =========================================================================
|  ADMIN
| ========================================================================= */
Route::prefix('admin')->name('admin.')->group(function () {
    // Login (guarded redirect if already logged-in)
    Route::get('/login', function () {
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return app(AdminAuthController::class)->showLoginForm();
    })->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.perform');

    // Protected admin area
    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Activities
        Route::resource('activities', ActivityController::class);
        Route::delete('/activities/{activity}/image', [ActivityController::class, 'destroyImage'])
            ->name('activities.image.destroy');

        // Locations (Admin)
        Route::resource('locations', AdminLocationController::class);
        Route::delete('/locations/{location}/main-image', [AdminLocationController::class, 'destroyMainImage'])
            ->name('locations.main.destroy');
        Route::delete('/locations/images/{image}', [AdminLocationController::class, 'destroyImage'])
            ->name('locations.images.destroy');

        // Customers
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers');
        Route::delete('/customers/delete/{id}', [AdminCustomerController::class, 'destroy'])->name('customers.delete');

        // Bookings
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');

        // Hotels (Admin CRUD)
        Route::get('/hotels', [AdminHotelController::class, 'index'])->name('hotels.index');
        Route::post('/hotels', [AdminHotelController::class, 'store'])->name('hotels.store');
        Route::put('/hotels/{hotel}', [AdminHotelController::class, 'update'])->name('hotels.update');
        Route::delete('/hotels/{hotel}', [AdminHotelController::class, 'destroy'])->name('hotels.destroy');
        Route::post('/hotels/{hotel}/reset-credentials', [AdminHotelController::class, 'resetCredentials'])
            ->name('hotels.resetCreds');

        // Admin Profile
        Route::get('/profile', [AdminProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/password', [AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
        Route::put('/profile/username', [AdminProfileController::class, 'updateUsername'])->name('profile.username.update');
    });
});

/* =========================================================================
|  HOTEL (separate guard & paths)
| ========================================================================= */
Route::prefix('hotel')->name('hotel.')->group(function () {
    // auth
    Route::get('/login', [HotelAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [HotelAuthController::class, 'login'])->name('login.perform');

    // protected area
    Route::middleware('auth:hotel')->group(function () {
        Route::get('/dashboard', [HotelAuthController::class, 'dashboard'])->name('dashboard');
        Route::post('/logout', [HotelAuthController::class, 'logout'])->name('logout');

        // Hotel profile (NOTE: prefixed path to avoid colliding with tourist /profile)
        Route::get('/profile', [HotelProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile/username', [HotelProfileController::class, 'updateUsername'])->name('profile.username.update');
        Route::put('/profile/password', [HotelProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});

/* =========================================================================
|  Misc password routes (if you’re using these explicitly)
| ========================================================================= */
Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.reset.store');
// If you keep this, ensure its name doesn’t collide with the tourist password.update above
Route::put('password', [PasswordController::class, 'update'])->name('password.change');
