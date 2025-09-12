<?php

namespace App\Http\Controllers\Tourist;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Hotel;
use App\Models\Booking;
use App\Services\AuthService;
use App\Services\PaymentService;
use App\Services\SessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Show the payment form with booking details
     */
    public function create(Request $request)
    {
        if ($redirect = $this->checkAuthentication()) {
            return $redirect;
        }

        if ($redirect = $this->handleCompletedBookings()) {
            return $redirect;
        }

        $bookingData = $this->handleBookingDataRequest($request);
        if (!$bookingData) {
            return $this->redirectToLanding();
        }

        $hotel = $this->getHotelData($bookingData['hotel_id']);
        $tourist = AuthService::getAuthenticatedTourist();
        $fromProceedToCheckout = $request->isMethod('post') && $request->has('booking_data');
        
        return view('tourist.payment', compact('bookingData', 'hotel', 'tourist', 'fromProceedToCheckout'));
    }

    /**
     * Process the payment and create booking
     */
    public function process(PaymentRequest $request)
    {
        $this->logPaymentStart($request);

        if ($redirect = $this->checkAuthentication()) {
            return $redirect;
        }

        try {
            $bookingData = $request->getBookingData();
            $paymentDetails = $request->getPaymentDetails();
            $tourist = AuthService::getAuthenticatedTourist();

            $booking = $this->paymentService->processPayment($bookingData, $paymentDetails, $tourist);
            
            SessionService::completeBooking($booking->id);

            return $this->buildSuccessResponse($request, $booking);

        } catch (\Exception $e) {
            return $this->handlePaymentError($request, $e);
        }
    }

    /**
     * Show payment success page
     */
    public function success($bookingId)
    {
        Log::info('Payment success page accessed', [
            'booking_id' => $bookingId,
            'user_authenticated' => AuthService::isTouristAuthenticated(),
            'user_id' => AuthService::getAuthenticatedTourist()?->id
        ]);

        if ($redirect = $this->checkAuthentication()) {
            return $redirect;
        }

        $booking = $this->getBookingForTourist($bookingId);
        
        return view('tourist.payment-success', compact('booking'));
    }

    /**
     * Show simple success page (accessible without authentication)
     */
    public function successSimple()
    {
        $bookingId = session('booking_id');
        
        if (!$bookingId) {
            return redirect()->route('landing')
                ->with('info', 'No booking information found.');
        }

        session()->forget('booking_id');
        
        return view('tourist.payment-success-simple', compact('bookingId'));
    }

    /**
     * Clear payment session and redirect
     */
    public function clearSession()
    {
        SessionService::clearPaymentData();
        
        return redirect()->route('landing')
            ->with('info', 'Booking session cleared. Please start your booking process again.');
    }

    // ========================================
    // PRIVATE HELPER METHODS
    // ========================================

    /**
     * Check if user is authenticated.
     */
    private function checkAuthentication()
    {
        if (!AuthService::isTouristAuthenticated()) {
            SessionService::clearPaymentData();
            return redirect()->route('login')
                ->with('message', 'Please login to continue with your booking');
        }
        return null;
    }

    /**
     * Handle completed bookings redirect.
     */
    private function handleCompletedBookings()
    {
        if (SessionService::hasCompletedBookings()) {
            SessionService::clearPaymentData();
            return redirect()->route('landing')
                ->with('info', 'You have already completed your booking. Starting a new booking process.');
        }
        return null;
    }

    /**
     * Handle booking data from request or session.
     */
    private function handleBookingDataRequest(Request $request): ?array
    {
        if ($request->isMethod('post') && $request->has('booking_data')) {
            $bookingData = json_decode($request->booking_data, true);
            SessionService::storeBookingData($request->booking_data);
            return $bookingData;
        }

        if (session('intended_booking_data')) {
            $bookingData = json_decode(session('intended_booking_data'), true);
            SessionService::storeBookingData(session('intended_booking_data'));
            session()->forget('intended_booking_data');
            return $bookingData;
        }

        return SessionService::getBookingData();
    }

    /**
     * Get hotel data with validation.
     */
    private function getHotelData(int $hotelId): Hotel
    {
        $hotel = Hotel::with('location')->find($hotelId);
        
        if (!$hotel) {
            SessionService::clearPaymentData();
            throw new \Exception('Hotel not found. Please start your booking process again.');
        }
        
        return $hotel;
    }

    /**
     * Get booking for authenticated tourist.
     */
    private function getBookingForTourist(int $bookingId): Booking
    {
        return Booking::with('hotel')
            ->where('id', $bookingId)
            ->where('tourist_id', AuthService::getAuthenticatedTourist()->id)
            ->firstOrFail();
    }

    /**
     * Redirect to landing page with error.
     */
    private function redirectToLanding()
    {
        SessionService::clearPaymentData();
        return redirect()->route('landing')
            ->with('error', 'Invalid booking data. Please start your booking process again.');
    }

    /**
     * Log payment process start.
     */
    private function logPaymentStart(PaymentRequest $request): void
    {
        Log::info('Payment process started', [
            'request_data' => $request->except(['card_number', 'cvv']),
            'has_booking_data' => $request->has('booking_data'),
            'user_authenticated' => AuthService::isTouristAuthenticated()
        ]);
    }

    /**
     * Build success response for payment.
     */
    private function buildSuccessResponse(PaymentRequest $request, Booking $booking)
    {
        Log::info('Payment successful', [
            'booking_id' => $booking->id,
            'booking_reference' => $booking->booking_reference
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment successful! Your booking has been confirmed.',
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference
            ]);
        }

        return redirect()->route('tourist.payment.success.simple')
            ->with('success', 'Payment successful! Your booking has been confirmed.')
            ->with('booking_id', $booking->id);
    }

    /**
     * Handle payment processing errors.
     */
    private function handlePaymentError(PaymentRequest $request, \Exception $e)
    {
        Log::error('Payment processing failed', [
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
        
        return redirect()->back()
            ->with('error', 'Payment failed: ' . $e->getMessage())
            ->withInput();
    }
}