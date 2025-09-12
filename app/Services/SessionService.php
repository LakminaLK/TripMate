<?php

namespace App\Services;

class SessionService
{
    /**
     * Clear all payment-related session data.
     */
    public static function clearPaymentData(): void
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
     * Store booking data in session.
     */
    public static function storeBookingData(string $bookingData): void
    {
        self::clearPaymentData();
        
        session([
            'payment_booking_data' => $bookingData,
            'payment_from_checkout' => true,
            'payment_page_loaded' => true
        ]);
    }

    /**
     * Check if user has completed bookings.
     */
    public static function hasCompletedBookings(): bool
    {
        return session('completed_bookings') !== null;
    }

    /**
     * Get booking data from session.
     */
    public static function getBookingData(): ?array
    {
        $bookingData = session('payment_booking_data') ?? session('intended_booking_data');
        
        if ($bookingData) {
            return json_decode($bookingData, true);
        }
        
        return null;
    }

    /**
     * Mark booking as completed and clear payment session.
     */
    public static function completeBooking(int $bookingId): void
    {
        session()->forget([
            'payment_booking_data', 
            'payment_from_checkout',
            'intended_booking_data'
        ]);

        session(['completed_bookings' => [$bookingId]]);
    }
}