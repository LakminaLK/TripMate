<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Tourist;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Process payment and create booking.
     */
    public function processPayment(array $bookingData, array $paymentDetails, Tourist $tourist): Booking
    {
        DB::beginTransaction();
        
        try {
            $this->validateRoomAvailability($bookingData);
            $booking = $this->createBooking($bookingData, $paymentDetails, $tourist);
            $this->sendConfirmationEmail($booking, $tourist);
            
            DB::commit();
            Log::info('Payment processed successfully', ['booking_id' => $booking->id]);
            
            return $booking;
            
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Payment processing failed', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            throw $e;
        }
    }

    /**
     * Validate room availability for booking.
     */
    private function validateRoomAvailability(array $bookingData): void
    {
        $rooms = $bookingData['rooms'] ?? [];
        
        if (empty($rooms)) {
            throw new \Exception('No rooms selected');
        }

        $firstRoomTypeId = array_keys($rooms)[0];
        $firstRoom = $rooms[$firstRoomTypeId];
        $requestedRooms = $firstRoom['roomCount'] ?? 1;
        
        $availableRooms = Booking::getAvailableRoomCount(
            $bookingData['hotel_id'],
            $firstRoomTypeId,
            $bookingData['check_in'],
            $bookingData['check_out']
        );
        
        if ($availableRooms < $requestedRooms) {
            throw new \Exception("Insufficient rooms available. Only {$availableRooms} rooms are available for the selected dates.");
        }
    }

    /**
     * Create booking record.
     */
    private function createBooking(array $bookingData, array $paymentDetails, Tourist $tourist): Booking
    {
        $rooms = $bookingData['rooms'];
        $firstRoomTypeId = array_keys($rooms)[0];
        $firstRoom = $rooms[$firstRoomTypeId];

        return Booking::create([
            'tourist_id' => $tourist->id,
            'hotel_id' => $bookingData['hotel_id'],
            'room_type_id' => $firstRoomTypeId,
            'check_in' => $bookingData['check_in'],
            'check_out' => $bookingData['check_out'],
            'check_in_date' => $bookingData['check_in'],
            'check_out_date' => $bookingData['check_out'],
            'rooms_booked' => $firstRoom['roomCount'] ?? 1,
            'guests_count' => $bookingData['guests'] ?? 2,
            'price_per_night' => $firstRoom['pricePerNight'] ?? 0,
            'total_amount' => $bookingData['total'],
            'status' => 'pending',
            'booking_status' => 'pending',
            'payment_status' => 'paid',
            'payment_method' => 'card',
            'booking_reference' => Booking::generateBookingReference(),
            'payment_details' => json_encode($paymentDetails),
            'booking_details' => json_encode($rooms)
        ]);
    }

    /**
     * Send booking confirmation email.
     */
    private function sendConfirmationEmail(Booking $booking, Tourist $tourist): void
    {
        try {
            Mail::to($tourist->email)->send(new BookingConfirmationMail($booking));
            Log::info('Booking confirmation email sent', [
                'booking_id' => $booking->id,
                'email' => $tourist->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email', [
                'booking_id' => $booking->id,
                'error' => $e->getMessage()
            ]);
            // Don't fail the booking if email fails
        }
    }
}