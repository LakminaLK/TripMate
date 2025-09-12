<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BookingRepository
{
    /**
     * Get paginated bookings for a tourist.
     */
    public function getTouristBookings(int $touristId, int $perPage = 10): LengthAwarePaginator
    {
        return Booking::with(['hotel', 'review'])
            ->where('tourist_id', $touristId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get detailed bookings with location for a tourist.
     */
    public function getTouristBookingsWithLocation(int $touristId, int $perPage = 10): LengthAwarePaginator
    {
        return Booking::with(['hotel.location', 'review'])
            ->where('tourist_id', $touristId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get total bookings count for a tourist.
     */
    public function getTotalBookingsCount(int $touristId): int
    {
        return Booking::where('tourist_id', $touristId)->count();
    }

    /**
     * Get bookings count by status for a tourist.
     */
    public function getBookingsCountByStatus(int $touristId, string $status): int
    {
        return Booking::where('tourist_id', $touristId)
            ->where('status', $status)
            ->count();
    }

    /**
     * Get all booking counts by status for a tourist.
     */
    public function getAllStatusCounts(int $touristId): array
    {
        $baseQuery = Booking::where('tourist_id', $touristId);
        
        return [
            'total' => $baseQuery->count(),
            'confirmed' => (clone $baseQuery)->where('status', 'confirmed')->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'completed' => (clone $baseQuery)->where('status', 'completed')->count(),
            'cancelled' => (clone $baseQuery)->where('status', 'cancelled')->count(),
        ];
    }

    /**
     * Find booking by ID and tourist ID.
     */
    public function findBookingForTourist(int $bookingId, int $touristId): ?Booking
    {
        return Booking::where('id', $bookingId)
            ->where('tourist_id', $touristId)
            ->first();
    }

    /**
     * Find booking with hotel details for tourist.
     */
    public function findBookingWithHotelForTourist(int $bookingId, int $touristId): ?Booking
    {
        return Booking::with('hotel')
            ->where('id', $bookingId)
            ->where('tourist_id', $touristId)
            ->first();
    }
}