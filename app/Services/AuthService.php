<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthService
{
    /**
     * Check if tourist is authenticated.
     */
    public static function checkTouristAuth(): ?RedirectResponse
    {
        if (!Auth::guard('tourist')->check()) {
            return redirect()->route('login')
                ->with('message', 'Please login to continue with your booking');
        }
        
        return null;
    }

    /**
     * Get authenticated tourist.
     */
    public static function getAuthenticatedTourist()
    {
        return Auth::guard('tourist')->user();
    }

    /**
     * Check if user is authenticated (boolean).
     */
    public static function isTouristAuthenticated(): bool
    {
        return Auth::guard('tourist')->check();
    }

    /**
     * Clear payment-related session data.
     */
    public static function clearPaymentSession(): void
    {
        session()->forget([
            'payment_booking_data', 
            'payment_from_checkout', 
            'payment_page_loaded',
            'intended_booking_data',
            'booking_summary',
            'selected_rooms',
            'booking_details',
            'completed_bookings'
        ]);
    }

    /**
     * Mark booking as completed in session.
     */
    public static function markBookingCompleted(int $bookingId): void
    {
        session()->forget([
            'payment_booking_data', 
            'payment_from_checkout',
            'intended_booking_data'
        ]);

        session(['completed_bookings' => [$bookingId]]);
    }
}