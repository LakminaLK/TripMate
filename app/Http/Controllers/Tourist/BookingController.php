<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Repositories\BookingRepository;
use App\Services\AuthService;
use App\Services\BookingAuthorizationService;
use App\Services\BookingStatsService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected BookingRepository $bookingRepository;
    protected BookingStatsService $bookingStatsService;

    public function __construct(
        BookingRepository $bookingRepository,
        BookingStatsService $bookingStatsService
    ) {
        $this->bookingRepository = $bookingRepository;
        $this->bookingStatsService = $bookingStatsService;
    }

    /**
     * Display all bookings for the authenticated tourist
     */
    public function index()
    {
        if ($redirect = $this->checkAuthentication()) {
            return $redirect;
        }

        $tourist = AuthService::getAuthenticatedTourist();
        $bookings = $this->bookingRepository->getTouristBookings($tourist->id);

        return view('tourist.bookings.index', compact('bookings'));
    }

    /**
     * Display a specific booking (redirect to new booking details)
     */
    public function show(Booking $booking)
    {
        if ($redirect = $this->checkAuthentication()) {
            return $redirect;
        }

        $tourist = AuthService::getAuthenticatedTourist();
        BookingAuthorizationService::authorizeBookingAccess($booking, $tourist);

        return redirect()->route('tourist.booking.details', $booking);
    }

    /**
     * Display booking receipt/details page
     */
    public function showReceipt(Booking $booking)
    {
        if ($redirect = $this->checkAuthentication()) {
            return $redirect;
        }

        $tourist = AuthService::getAuthenticatedTourist();
        $booking = BookingAuthorizationService::getBookingWithHotelForTouristOrFail(
            $booking->id, 
            $tourist
        );
        
        return view('tourist.booking-details', compact('booking'));
    }

    /**
     * Display all bookings for the authenticated tourist in the new booking view
     */
    public function viewBookings()
    {
        if ($redirect = $this->checkAuthentication()) {
            return $redirect;
        }

        $tourist = AuthService::getAuthenticatedTourist();
        
        $bookings = $this->getBookingsWithStats($tourist->id);
        $stats = $this->getBookingStatistics($tourist->id);

        return view('tourist.booking-view', compact('bookings', 'stats'));
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * Check if user is authenticated.
     */
    private function checkAuthentication()
    {
        return AuthService::checkTouristAuth();
    }

    /**
     * Get bookings with location data.
     */
    private function getBookingsWithStats(int $touristId)
    {
        return $this->bookingRepository->getTouristBookingsWithLocation($touristId);
    }

    /**
     * Get booking statistics for the tourist.
     */
    private function getBookingStatistics(int $touristId): array
    {
        return $this->bookingStatsService->getTouristBookingStats($touristId);
    }
}