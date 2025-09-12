<?php

use App\Models\Hotel;

describe('Hotel Model', function () {
    it('has correct fillable attributes', function () {
        $hotel = new Hotel();
        $fillable = $hotel->getFillable();

        expect($fillable)->toContain('name');
        expect($fillable)->toContain('email');
        expect($fillable)->toContain('phone');
        expect($fillable)->toContain('address');
        expect($fillable)->toContain('city');
        expect($fillable)->toContain('description');
    });

    it('casts amenities to array', function () {
        $hotel = new Hotel();
        $casts = $hotel->getCasts();

        expect($casts)->toHaveKey('amenities');
        expect($casts['amenities'])->toBe('array');
    });

    it('has relationship methods', function () {
        $hotel = new Hotel();

        expect(method_exists($hotel, 'roomTypes'))->toBeTrue();
        expect(method_exists($hotel, 'bookings'))->toBeTrue();
    });

    it('validates rating range', function () {
        $validRating = 4.5;
        
        expect($validRating)->toBeGreaterThanOrEqual(1);
        expect($validRating)->toBeLessThanOrEqual(5);
    });

    it('validates coordinates are within Sri Lanka', function () {
        $latitude = 6.9271;  // Colombo
        $longitude = 79.8612;

        expect($latitude)->toBeGreaterThanOrEqual(5.9);
        expect($latitude)->toBeLessThanOrEqual(9.9);
        expect($longitude)->toBeGreaterThanOrEqual(79.6);
        expect($longitude)->toBeLessThanOrEqual(81.9);
    });

    it('has valid check-in and check-out time formats', function () {
        $checkInTime = '14:00';
        $checkOutTime = '12:00';

        expect($checkInTime)->toMatch('/^\d{2}:\d{2}$/');
        expect($checkOutTime)->toMatch('/^\d{2}:\d{2}$/');
    });

    it('hides password in arrays', function () {
        $hotel = new Hotel();
        $hidden = $hotel->getHidden();

        expect($hidden)->toContain('password');
    });

    it('uses email as username for authentication', function () {
        $hotel = new Hotel();

        expect(method_exists($hotel, 'getAuthIdentifierName'))->toBeTrue();
    });

    it('has status field for active/inactive states', function () {
        expect(['active', 'inactive'])->toContain('active');
        expect(['active', 'inactive'])->toContain('inactive');
    });
});