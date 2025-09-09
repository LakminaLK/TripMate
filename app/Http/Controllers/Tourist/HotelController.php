<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Booking;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HotelController extends Controller
{
    /**
     * Display hotel details for tourists
     */
    public function show(Hotel $hotel)
    {
        // Ensure hotel is active
        if ($hotel->status !== 'Active') {
            abort(404, 'Hotel not found or not available');
        }

        // Load related data
        $hotel->load([
            'location',
            'images',
            'facilities',
            'hotelRooms.roomType'
        ]);

        // Get available rooms (without date filtering initially)
        $availableRooms = $hotel->hotelRooms()
            ->where('is_available', true)
            ->where('room_count', '>', 0)
            ->with('roomType')
            ->get();

        // Get approved reviews for this hotel (limit to 3 for display)
        $reviews = \App\Models\Review::with('tourist')
            ->where('hotel_id', $hotel->id)
            ->where('is_approved', true)
            ->latest()
            ->limit(3)
            ->get();

        // Get total review count and average rating
        $totalReviews = \App\Models\Review::where('hotel_id', $hotel->id)
            ->where('is_approved', true)
            ->count();

        $averageRating = \App\Models\Review::where('hotel_id', $hotel->id)
            ->where('is_approved', true)
            ->avg('rating');

        return view('tourist.hotel-details', [
            'hotel' => $hotel,
            'availableRooms' => $availableRooms,
            'reviews' => $reviews,
            'totalReviews' => $totalReviews,
            'averageRating' => round($averageRating, 1)
        ]);
    }

    /**
     * Get all reviews for a hotel (AJAX endpoint)
     */
    public function getAllReviews(Hotel $hotel)
    {
        $reviews = \App\Models\Review::with('tourist')
            ->where('hotel_id', $hotel->id)
            ->where('is_approved', true)
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'reviews' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'total' => $reviews->total()
            ]
        ]);
    }

    /**
     * Check room availability for specific dates
     */
    public function checkAvailability(Request $request, Hotel $hotel)
    {
        try {
            // Log the incoming request for debugging
            \Log::info('Availability check request', [
                'hotel_id' => $hotel->id,
                'request_data' => $request->all()
            ]);

            $request->validate([
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in'
            ]);

            $checkIn = Carbon::parse($request->check_in);
            $checkOut = Carbon::parse($request->check_out);

            \Log::info('Parsed dates', [
                'check_in' => $checkIn->format('Y-m-d'),
                'check_out' => $checkOut->format('Y-m-d')
            ]);

            // Get all room types for this hotel
            $hotelRooms = $hotel->hotelRooms()
                ->where('is_available', true)
                ->where('room_count', '>', 0)
                ->with('roomType')
                ->get();

            \Log::info('Hotel rooms found', ['count' => $hotelRooms->count()]);

            $availabilityResults = [];

            foreach ($hotelRooms as $hotelRoom) {
                // Get overlapping bookings for this room type in the date range
                $overlappingBookings = Booking::getOverlappingBookings(
                    $hotel->id,
                    $hotelRoom->room_type_id, 
                    $checkIn->format('Y-m-d'),
                    $checkOut->format('Y-m-d')
                );
                
                // Calculate total booked rooms for this period
                $bookedRooms = $overlappingBookings->sum('rooms_booked');
                
                // Calculate available rooms
                $availableCount = max(0, $hotelRoom->room_count - $bookedRooms);

                \Log::info('Room availability calculation', [
                    'room_type_id' => $hotelRoom->room_type_id,
                    'room_type_name' => $hotelRoom->roomType->name,
                    'total_rooms' => $hotelRoom->room_count,
                    'booked_rooms' => $bookedRooms,
                    'available_rooms' => $availableCount,
                    'overlapping_bookings_count' => $overlappingBookings->count()
                ]);

                $availabilityResults[] = [
                    'room_type_id' => $hotelRoom->room_type_id,
                    'room_type_name' => $hotelRoom->roomType->name,
                    'room_type_description' => $hotelRoom->roomType->description ?? '',
                    'total_rooms' => $hotelRoom->room_count,
                    'booked_rooms' => $bookedRooms,
                    'available_rooms' => $availableCount,
                    'price_per_night' => (float) $hotelRoom->price_per_night,
                    'room_type_features' => [
                        'max_occupancy' => $hotelRoom->roomType->max_occupancy ?? 2,
                        'icon' => $hotelRoom->roomType->icon ?? 'fas fa-bed'
                    ]
                ];
            }

            \Log::info('Availability results', ['results_count' => count($availabilityResults)]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'check_in' => $checkIn->format('Y-m-d'),
                    'check_out' => $checkOut->format('Y-m-d'),
                    'nights' => $checkIn->diffInDays($checkOut),
                    'availability' => $availabilityResults,
                    'hotel_name' => $hotel->name,
                    'hotel_id' => $hotel->id
                ]);
            }

            // For non-AJAX requests, return view with availability data
            $hotel->load([
                'location',
                'images', 
                'facilities',
                'hotelRooms.roomType'
            ]);

            return view('tourist.hotel-details', [
                'hotel' => $hotel,
                'availableRooms' => $hotelRooms,
                'availabilityCheck' => [
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'nights' => $checkIn->diffInDays($checkOut),
                    'results' => $availabilityResults
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Availability check error: ' . $e->getMessage(), [
                'hotel_id' => $hotel->id ?? null,
                'request_data' => $request->all(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to check availability. Please try again.',
                    'debug_message' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
            
            return back()->withErrors(['error' => 'Failed to check availability. Please try again.']);
        }
    }

    /**
     * Get availability for a specific room type
     */
    public function getRoomAvailability(Request $request, Hotel $hotel, $roomTypeId)
    {
        $request->validate([
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in'
        ]);

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);

        $hotelRoom = $hotel->hotelRooms()
            ->where('room_type_id', $roomTypeId)
            ->where('is_available', true)
            ->with('roomType')
            ->first();

        if (!$hotelRoom) {
            return response()->json(['error' => 'Room type not found'], 404);
        }

        $bookedRooms = Booking::getOverlappingBookings(
            $hotel->id,
            $roomTypeId,
            $checkIn->format('Y-m-d'),
            $checkOut->format('Y-m-d')
        )->sum('rooms_booked');

        $availableCount = max(0, $hotelRoom->room_count - $bookedRooms);

        return response()->json([
            'success' => true,
            'room_type_name' => $hotelRoom->roomType->name,
            'total_rooms' => $hotelRoom->room_count,
            'booked_rooms' => $bookedRooms,
            'available_rooms' => $availableCount,
            'price_per_night' => $hotelRoom->price_per_night,
            'check_in' => $checkIn->format('Y-m-d'),
            'check_out' => $checkOut->format('Y-m-d'),
            'nights' => $checkIn->diffInDays($checkOut)
        ]);
    }
}
