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

        $bookings = Booking::with('hotel')
            ->where('tourist_id', Auth::guard('tourist')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tourist.bookings.index', compact('bookings'));
    }

    /**
     * Display a specific booking
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

        $booking->load('hotel');
        
        return view('tourist.bookings.show', compact('booking'));
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

        $bookings = Booking::with('hotel.location')
            ->where('tourist_id', Auth::guard('tourist')->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tourist.booking-view', compact('bookings'));
    }
}
