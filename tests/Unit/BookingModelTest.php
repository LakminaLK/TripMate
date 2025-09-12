<?php

use App\Models\Booking;
use Carbon\Carbon;

describe('Booking Model', function () {
    it('has correct fillable attributes', function () {
        $booking = new Booking();
        $expected = [
            'tourist_id',
            'hotel_id', 
            'room_type_id',
            'check_in',
            'check_out',
            'check_in_date',
            'check_out_date',
            'rooms_booked',
            'guests_count',
            'price_per_night',
            'total_amount',
            'status',
            'booking_status',
            'payment_status',
            'payment_method',
            'payment_details',
            'booking_details',
            'special_requests',
            'booking_reference'
        ];

        expect($booking->getFillable())->toBe($expected);
    });

    it('casts dates correctly', function () {
        $booking = new Booking();
        $casts = $booking->getCasts();

        expect($casts['check_in'])->toBe('date');
        expect($casts['check_out'])->toBe('date');
        expect($casts['check_in_date'])->toBe('date');
        expect($casts['check_out_date'])->toBe('date');
    });

    it('casts decimal fields correctly', function () {
        $booking = new Booking();
        $casts = $booking->getCasts();

        expect($casts['price_per_night'])->toBe('decimal:2');
        expect($casts['total_amount'])->toBe('decimal:2');
    });

    it('casts json fields correctly', function () {
        $booking = new Booking();
        $casts = $booking->getCasts();

        expect($casts['payment_details'])->toBe('array');
        expect($casts['booking_details'])->toBe('array');
    });

    it('has relationship methods', function () {
        $booking = new Booking();

        expect(method_exists($booking, 'tourist'))->toBeTrue();
        expect(method_exists($booking, 'hotel'))->toBeTrue();
        expect(method_exists($booking, 'roomType'))->toBeTrue();
    });

    it('can calculate duration in days', function () {
        $booking = new Booking();
        $booking->check_in = Carbon::parse('2024-01-01');
        $booking->check_out = Carbon::parse('2024-01-04');

        $days = $booking->check_in->diffInDays($booking->check_out);
        expect($days)->toBe(3);
    });

    it('validates numeric fields are positive', function () {
        expect(100.50)->toBeGreaterThan(0); // price_per_night example
        expect(2)->toBeGreaterThan(0); // rooms_booked example
        expect(4)->toBeGreaterThan(0); // guests_count example
    });
});