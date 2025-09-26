<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Booking;
use App\Models\Review;
use App\Observers\BookingObserver;
use App\Observers\ReviewObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers
        Booking::observe(BookingObserver::class);
        Review::observe(ReviewObserver::class);
    }

    public const HOME = '/';
}
