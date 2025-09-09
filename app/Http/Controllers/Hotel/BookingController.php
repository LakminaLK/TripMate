<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Mail\BookingConfirmedMail;
use App\Mail\TourCompletedMail;
use App\Mail\BookingCancelledMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $hotel = Auth::guard('hotel')->user();
        $search = $request->get('q');
        $statusFilter = $request->get('status', 'all');
        
        $query = Booking::with(['tourist', 'roomType'])
            ->where('hotel_id', $hotel->id);

        // Apply status filter
        if ($statusFilter !== 'all') {
            $query->where(function($q) use ($statusFilter) {
                $q->where('status', $statusFilter)
                  ->orWhere('booking_status', $statusFilter);
            });
        }

        // Apply search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%$search%")
                  ->orWhere('id', 'like', "%$search%")
                  ->orWhere('total_amount', 'like', "%$search%")
                  ->orWhereHas('tourist', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(10);

        // Transform bookings data for display
        $bookings->getCollection()->transform(function ($booking) {
            $booking->formatted_id = 'B' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);
            $booking->nights = $booking->check_in && $booking->check_out ? 
                $booking->check_in->diffInDays($booking->check_out) : 0;
            
            // Parse booking details to get room information
            $booking->room_details = $this->parseRoomDetails($booking);
            
            return $booking;
        });

        // Get booking statistics
        $stats = [
            'total' => Booking::where('hotel_id', $hotel->id)->count(),
            'pending' => Booking::where('hotel_id', $hotel->id)
                ->where(function($q) {
                    $q->where('status', 'pending')->orWhere('booking_status', 'pending');
                })->count(),
            'confirmed' => Booking::where('hotel_id', $hotel->id)
                ->where(function($q) {
                    $q->where('status', 'confirmed')->orWhere('booking_status', 'confirmed');
                })->count(),
            'completed' => Booking::where('hotel_id', $hotel->id)
                ->where(function($q) {
                    $q->where('status', 'completed')->orWhere('booking_status', 'completed');
                })->count(),
            'cancelled' => Booking::where('hotel_id', $hotel->id)
                ->where(function($q) {
                    $q->where('status', 'cancelled')->orWhere('booking_status', 'cancelled');
                })->count(),
        ];

        return view('hotel.bookings.index', compact('bookings', 'stats', 'search', 'statusFilter'));
    }

    public function show(Booking $booking)
    {
        $hotel = Auth::guard('hotel')->user();
        
        // Ensure the booking belongs to this hotel
        if ($booking->hotel_id !== $hotel->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        $booking->load(['tourist', 'roomType', 'hotel']);
        $booking->formatted_id = 'B' . str_pad($booking->id, 3, '0', STR_PAD_LEFT);
        $booking->nights = $booking->check_in && $booking->check_out ? 
            $booking->check_in->diffInDays($booking->check_out) : 0;

        // Use the same parseRoomDetails method as in index
        $roomDetails = $this->parseRoomDetails($booking);
        $booking->room_details = $roomDetails;

        return response()->json([
            'booking' => $booking,
            'tourist' => $booking->tourist,
            'roomType' => $booking->roomType,
            'nights' => $booking->nights,
            'room_details' => $roomDetails
        ]);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $hotel = Auth::guard('hotel')->user();
        
        // Ensure the booking belongs to this hotel
        if ($booking->hotel_id !== $hotel->id) {
            abort(403, 'Unauthorized access to booking.');
        }

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled'
        ]);

        $oldStatus = $booking->status;
        $newStatus = $request->status;

        $booking->update([
            'status' => $newStatus,
            'booking_status' => $newStatus
        ]);

        // Send appropriate email based on status change
        try {
            if ($oldStatus !== $newStatus) {
                switch ($newStatus) {
                    case 'confirmed':
                        Mail::to($booking->tourist->email)->send(new BookingConfirmedMail($booking));
                        \Log::info('Booking confirmed email sent', ['booking_id' => $booking->id]);
                        break;
                        
                    case 'completed':
                        Mail::to($booking->tourist->email)->send(new TourCompletedMail($booking));
                        \Log::info('Tour completed email sent', ['booking_id' => $booking->id]);
                        break;
                        
                    case 'cancelled':
                        Mail::to($booking->tourist->email)->send(new BookingCancelledMail($booking));
                        \Log::info('Booking cancelled email sent', ['booking_id' => $booking->id]);
                        break;
                }
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send status update email', [
                'booking_id' => $booking->id,
                'status' => $newStatus,
                'error' => $e->getMessage()
            ]);
            // Don't fail the status update if email fails
        }

        return response()->json([
            'success' => true,
            'message' => 'Booking status updated successfully.',
            'status' => $request->status
        ]);
    }

    /**
     * Parse booking details to extract room information
     */
    private function parseRoomDetails($booking)
    {
        $roomDetails = [];
        
        // Try to parse booking_details JSON first (new format)
        if ($booking->booking_details) {
            $bookingDetails = is_string($booking->booking_details) ? 
                json_decode($booking->booking_details, true) : 
                $booking->booking_details;
            
            if ($bookingDetails && is_array($bookingDetails)) {
                foreach ($bookingDetails as $roomTypeId => $roomData) {
                    $roomType = \App\Models\RoomType::find($roomTypeId);
                    $roomDetails[] = [
                        'room_type_name' => $roomType ? $roomType->name : "Room Type {$roomTypeId}",
                        'room_count' => $roomData['roomCount'] ?? $roomData['quantity'] ?? 1,
                        'price_per_night' => $roomData['pricePerNight'] ?? $roomData['price_per_night'] ?? 0
                    ];
                }
            }
        }
        
        // Fallback to single room type (legacy format)
        if (empty($roomDetails)) {
            $roomDetails[] = [
                'room_type_name' => $booking->roomType ? $booking->roomType->name : 'N/A',
                'room_count' => $booking->rooms_booked ?? 1,
                'price_per_night' => $booking->price_per_night ?? 0
            ];
        }
        
        return $roomDetails;
    }
}
