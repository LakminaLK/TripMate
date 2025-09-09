<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Booking;
use App\Mail\BookingConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Remove middleware from constructor to handle it manually in payment routes
        // to avoid redirect loops during the payment process
    }

    /**
     * Show the payment form with booking details
     */
    public function create(Request $request)
    {
        // Check if user is authenticated first
        if (!Auth::guard('tourist')->check()) {
            // Store the intended payment data in session and redirect to login
            if ($request->has('booking_data')) {
                session(['intended_booking_data' => $request->booking_data]);
            }
            return redirect()->route('login')
                ->with('message', 'Please login to continue with your booking');
        }

        // Handle both GET and POST requests
        $bookingData = null;
        
        if ($request->isMethod('post')) {
            // POST request with booking data
            $bookingData = json_decode($request->booking_data, true);
        } elseif ($request->has('booking_data')) {
            // GET request with booking data in query string
            $bookingData = json_decode($request->booking_data, true);
        } elseif (session('intended_booking_data')) {
            // Get booking data from session (after login redirect)
            $bookingData = json_decode(session('intended_booking_data'), true);
            session()->forget('intended_booking_data');
        }
        
        if (!$bookingData) {
            return redirect()->back()->with('error', 'Invalid booking data');
        }

        // Validate the hotel and dates
        $hotel = Hotel::find($bookingData['hotel_id']);
        if (!$hotel) {
            return redirect()->back()->with('error', 'Hotel not found');
        }

        // Add user information
        $tourist = Auth::guard('tourist')->user();
        
        return view('tourist.payment', compact('bookingData', 'hotel', 'tourist'));
    }

    /**
     * Process the payment and create booking
     */
    public function process(Request $request)
    {
        // Debug log
        \Log::info('Payment process started', [
            'request_data' => $request->except(['card_number', 'cvv']), // Don't log sensitive data
            'has_booking_data' => $request->has('booking_data'),
            'user_authenticated' => Auth::guard('tourist')->check()
        ]);

        // Check if user is authenticated
        if (!Auth::guard('tourist')->check()) {
            \Log::warning('Payment process: User not authenticated');
            return redirect()->route('login')
                ->with('error', 'Please login to complete your booking');
        }

        $request->validate([
            'booking_data' => 'required',
            'card_number' => 'required|string|min:16|max:19',
            'card_name' => 'required|string|max:255',
            'expiry_month' => 'required|string|size:2',
            'expiry_year' => 'required|string|min:4|max:4', // Changed from size:4 to min:4|max:4
            'cvv' => 'required|string|size:3',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:100',
            'billing_postal' => 'required|string|max:20',
        ]);

        \Log::info('Payment validation passed');

        $bookingData = json_decode($request->booking_data, true);
        
        \Log::info('Booking data decoded', [
            'booking_data_exists' => !is_null($bookingData),
            'has_hotel_id' => isset($bookingData['hotel_id']),
            'has_rooms' => isset($bookingData['rooms'])
        ]);
        $tourist = Auth::guard('tourist')->user();

        try {
            DB::beginTransaction();

            // Validate room availability before creating booking
            // The booking data structure has rooms as an object with room_type_id as keys
            $rooms = $bookingData['rooms'] ?? [];
            
            if (empty($rooms)) {
                throw new \Exception('No rooms selected');
            }

            // Get the first room type and details (for now, handle single room type bookings)
            $firstRoomTypeId = array_keys($rooms)[0];
            $firstRoom = $rooms[$firstRoomTypeId];
            
            $roomTypeId = $firstRoomTypeId;
            $requestedRooms = $firstRoom['roomCount'] ?? 1;
            $pricePerNight = $firstRoom['pricePerNight'] ?? 0;
            
            $checkIn = $bookingData['check_in'];
            $checkOut = $bookingData['check_out'];
            $hotelId = $bookingData['hotel_id'];

            \Log::info('Extracted booking details', [
                'room_type_id' => $roomTypeId,
                'requested_rooms' => $requestedRooms,
                'price_per_night' => $pricePerNight
            ]);

            // Check room availability
            $availableRooms = Booking::getAvailableRoomCount($hotelId, $roomTypeId, $checkIn, $checkOut);
            
            if ($availableRooms < $requestedRooms) {
                throw new \Exception("Insufficient rooms available. Only {$availableRooms} rooms are available for the selected dates.");
            }

            // Create booking record
            $booking = Booking::create([
                'tourist_id' => $tourist->id,
                'hotel_id' => $hotelId,
                'room_type_id' => $roomTypeId,
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'check_in_date' => $checkIn, // For compatibility
                'check_out_date' => $checkOut, // For compatibility
                'rooms_booked' => $requestedRooms,
                'guests_count' => $bookingData['guests'] ?? 2,
                'price_per_night' => $pricePerNight,
                'total_amount' => $bookingData['total'],
                'status' => 'pending',  // Default status is pending
                'booking_status' => 'pending',  // Default status is pending
                'payment_status' => 'paid',
                'payment_method' => 'card',
                'booking_reference' => 'BK' . strtoupper(uniqid()),
                'payment_details' => json_encode([
                    'card_last_four' => substr($request->card_number, -4),
                    'card_name' => $request->card_name,
                    'processed_at' => now(),
                ]),
                'booking_details' => json_encode($rooms) // Store the rooms data
            ]);

            // Here you would integrate with a real payment processor like Stripe
            // For demo purposes, we'll simulate a successful payment
            
            // Send booking confirmation email
            try {
                Mail::to($tourist->email)->send(new BookingConfirmationMail($booking));
                \Log::info('Booking confirmation email sent', ['booking_id' => $booking->id, 'email' => $tourist->email]);
            } catch (\Exception $e) {
                \Log::error('Failed to send booking confirmation email', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage()
                ]);
                // Don't fail the booking if email fails
            }
            
            DB::commit();

            \Log::info('Payment successful, redirecting to simple success page', [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference
            ]);

            // Redirect to simple success page that doesn't require authentication
            return redirect()->route('tourist.payment.success.simple')
                ->with('success', 'Payment successful! Your booking has been confirmed.')
                ->with('booking_id', $booking->id);

        } catch (\Exception $e) {
            DB::rollback();
            
            \Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            
            return redirect()->back()
                ->with('error', 'Payment failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show payment success page
     */
    public function success($bookingId)
    {
        \Log::info('Payment success page accessed', [
            'booking_id' => $bookingId,
            'user_authenticated' => Auth::guard('tourist')->check(),
            'user_id' => Auth::guard('tourist')->id()
        ]);

        if (!Auth::guard('tourist')->check()) {
            \Log::warning('User not authenticated on success page');
            return redirect()->route('login')->with('message', 'Please login to view your booking confirmation.');
        }

        $booking = Booking::with('hotel')->where('id', $bookingId)
            ->where('tourist_id', Auth::guard('tourist')->id())
            ->firstOrFail();

        \Log::info('Booking found for success page', ['booking_reference' => $booking->booking_reference]);

        return view('tourist.payment-success', compact('booking'));
    }

    /**
     * Show payment failed page
     */
    public function failed()
    {
        return view('tourist.payment-failed');
    }
}
