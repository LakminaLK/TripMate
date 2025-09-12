<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Tourist;
use Illuminate\Http\RedirectResponse;

class BookingAuthorizationService
{
    /**
     * Check if tourist owns the booking.
     */
    public static function authorizeBookingAccess(Booking $booking, Tourist $tourist): ?RedirectResponse
    {
        if ($booking->tourist_id !== $tourist->id) {
            abort(403, 'Unauthorized access to booking');
        }
        
        return null;
    }

    /**
     * Check if tourist owns the booking (boolean check).
     */
    public static function touristOwnsBooking(Booking $booking, Tourist $tourist): bool
    {
        return $booking->tourist_id === $tourist->id;
    }

    /**
     * Get booking for tourist or fail.
     */
    public static function getBookingForTouristOrFail(int $bookingId, Tourist $tourist): Booking
    {
        $booking = Booking::where('id', $bookingId)
            ->where('tourist_id', $tourist->id)
            ->first();

        if (!$booking) {
            abort(404, 'Booking not found or you do not have permission to access it.');
        }

        return $booking;
    }

    /**
     * Get booking with hotel for tourist or fail.
     */
    public static function getBookingWithHotelForTouristOrFail(int $bookingId, Tourist $tourist): Booking
    {
        $booking = Booking::with('hotel')
            ->where('id', $bookingId)
            ->where('tourist_id', $tourist->id)
            ->first();

        if (!$booking) {
            abort(404, 'Booking not found or you do not have permission to access it.');
        }

        return $booking;
    }
}