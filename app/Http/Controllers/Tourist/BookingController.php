<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function __construct()
    {
        // Remove middleware to avoid redirect loops
        // $this->middleware('auth:tourist');
    }

    /**
     * Display all bookings for the authenticated tourist
     */
    public function index()
    {
        // Check authentication manually to avoid redirect loops
        if (!Auth::guard('tourist')->check()) {
            return redirect()->route('login')->with('message', 'Please login to view your bookings.');
        }

        $bookings = Booking::with(['hotel', 'review'])
            ->where('tourist_id', Auth::guard('tourist')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tourist.bookings.index', compact('bookings'));
    }

    /**
     * Display a specific booking (redirect to new booking details)
     */
    public function show(Booking $booking)
    {
        // Check authentication manually to avoid redirect loops
        if (!Auth::guard('tourist')->check()) {
            return redirect()->route('login')->with('message', 'Please login to view your bookings.');
        }

        // Ensure the booking belongs to the authenticated tourist
        if ($booking->tourist_id !== Auth::guard('tourist')->id()) {
            abort(403, 'Unauthorized access to booking');
        }

        // Redirect to the new booking details page
        return redirect()->route('tourist.booking.details', $booking);
    }

    /**
     * Display booking receipt/details page
     */
    public function showReceipt(Booking $booking)
    {
        // Check authentication manually to avoid redirect loops
        if (!Auth::guard('tourist')->check()) {
            return redirect()->route('login')->with('message', 'Please login to view your booking details.');
        }

        // Ensure the booking belongs to the authenticated tourist
        if ($booking->tourist_id !== Auth::guard('tourist')->id()) {
            abort(403, 'Unauthorized access to booking');
        }

        $booking->load('hotel');
        
        return view('tourist.booking-details', compact('booking'));
    }

    /**
     * Display all bookings for the authenticated tourist in the new booking view
     */
    public function viewBookings()
    {
        // Check authentication manually to avoid redirect loops
        if (!Auth::guard('tourist')->check()) {
            return redirect()->route('login')->with('message', 'Please login to view your bookings.');
        }

        $userId = Auth::guard('tourist')->id();

        // Get paginated bookings
        $bookings = Booking::with(['hotel.location', 'review'])
            ->where('tourist_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get total counts for all statuses
        $totalBookings = Booking::where('tourist_id', $userId)->count();
        $confirmedCount = Booking::where('tourist_id', $userId)->where('status', 'confirmed')->count();
        $pendingCount = Booking::where('tourist_id', $userId)->where('status', 'pending')->count();
        $completedCount = Booking::where('tourist_id', $userId)->where('status', 'completed')->count();
        $cancelledCount = Booking::where('tourist_id', $userId)->where('status', 'cancelled')->count();

        // Create stats array
        $stats = [
            'total' => $totalBookings,
            'confirmed' => $confirmedCount,
            'pending' => $pendingCount,
            'completed' => $completedCount,
            'cancelled' => $cancelledCount
        ];

        return view('tourist.booking-view', compact('bookings', 'stats'));
    }
}
