<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('q');
        $statusFilter = $request->get('status', 'all');
        
        $query = Booking::with(['tourist', 'hotel']);

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
                  })
                  ->orWhereHas('hotel', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%");
                  });
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $bookings->getCollection()->transform(function ($booking) {
            return (object)[
                'id' => $booking->id,
                'booking_id' => 'BK' . str_pad($booking->id, 4, '0', STR_PAD_LEFT),
                'booking_reference' => $booking->booking_reference ?: 'N/A',
                'customer_id' => 'C' . str_pad($booking->tourist_id, 3, '0', STR_PAD_LEFT),
                'tourist_name' => $booking->tourist ? $booking->tourist->name : 'Unknown',
                'tourist_email' => $booking->tourist ? $booking->tourist->email : 'N/A',
                'tourist_id' => $booking->tourist_id,
                'hotel_display_id' => 'H' . str_pad($booking->hotel_id, 3, '0', STR_PAD_LEFT),
                'hotel_name' => $booking->hotel ? $booking->hotel->name : 'Unknown Hotel',
                'hotel_id' => $booking->hotel_id,
                'check_in' => $booking->check_in ? $booking->check_in->format('M d, Y') : ($booking->check_in_date ? $booking->check_in_date->format('M d, Y') : 'N/A'),
                'check_out' => $booking->check_out ? $booking->check_out->format('M d, Y') : ($booking->check_out_date ? $booking->check_out_date->format('M d, Y') : 'N/A'),
                'booking_date' => $booking->created_at->format('M d, Y H:i'),
                'total_amount' => number_format($booking->total_amount, 2),
                'status' => $booking->status ?: $booking->booking_status ?: 'pending',
                'payment_status' => $booking->payment_status ?: 'pending',
                'rooms_booked' => $booking->rooms_booked ?: 1,
                'guests_count' => $booking->guests_count ?: 1,
            ];
        });

        return view('admin.bookings', compact('bookings'));
    }
}
