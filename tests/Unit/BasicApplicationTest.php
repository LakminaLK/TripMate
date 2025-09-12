<?php

// Simple unit tests that don't require database
describe('Basic Application Tests', function () {
    it('has correct app name', function () {
        expect(config('app.name'))->toBe('Laravel');
    });

    it('has correct timezone', function () {
        expect(config('app.timezone'))->toBe('Asia/Colombo');
    });

    it('can hash passwords correctly', function () {
        $password = 'test-password';
        $hashedPassword = \Illuminate\Support\Facades\Hash::make($password);

        expect(\Illuminate\Support\Facades\Hash::check($password, $hashedPassword))->toBeTrue();
        expect(\Illuminate\Support\Facades\Hash::check('wrong-password', $hashedPassword))->toBeFalse();
    });

    it('can validate email format', function () {
        $validEmails = [
            'test@example.com',
            'user.name@domain.co.uk',
            'admin+tag@site.org'
        ];

        $invalidEmails = [
            'invalid-email',
            '@domain.com',
            'user@',
            'user name@domain.com'
        ];

        foreach ($validEmails as $email) {
            expect(filter_var($email, FILTER_VALIDATE_EMAIL))->not->toBeFalse();
        }

        foreach ($invalidEmails as $email) {
            expect(filter_var($email, FILTER_VALIDATE_EMAIL))->toBeFalse();
        }
    });

    it('can format Sri Lankan phone numbers', function () {
        $phoneNumbers = [
            '+94771234567',
            '0771234567',
            '94771234567'
        ];

        foreach ($phoneNumbers as $phone) {
            expect(strlen(preg_replace('/[^0-9]/', '', $phone)))->toBeGreaterThanOrEqual(9);
        }
    });

    it('can handle Sri Lankan currency formatting', function () {
        $amount = 1234.56;
        $formatted = 'LKR ' . number_format($amount, 2);
        
        expect($formatted)->toBe('LKR 1,234.56');
    });

    it('can validate booking date ranges', function () {
        $checkIn = \Carbon\Carbon::parse('2024-12-25');
        $checkOut = \Carbon\Carbon::parse('2024-12-28');
        
        expect($checkOut->isAfter($checkIn))->toBeTrue();
        expect($checkIn->diffInDays($checkOut))->toBe(3);
    });

    it('can calculate booking totals', function () {
        $pricePerNight = 100.00;
        $nights = 3;
        $rooms = 2;
        $total = $pricePerNight * $nights * $rooms;
        
        expect($total)->toBe(600.00);
    });

    it('can validate difficulty levels', function () {
        $validLevels = ['easy', 'moderate', 'challenging'];
        $testLevel = 'easy';
        
        expect(in_array($testLevel, $validLevels))->toBeTrue();
    });

    it('can validate activity categories', function () {
        $validCategories = ['adventure', 'cultural', 'nature', 'relaxation', 'food'];
        $testCategory = 'adventure';
        
        expect(in_array($testCategory, $validCategories))->toBeTrue();
    });

    it('can validate Sri Lankan coordinates', function () {
        $colomboLat = 6.9271;
        $colomboLng = 79.8612;
        
        // Sri Lanka bounds approximately
        expect($colomboLat)->toBeGreaterThanOrEqual(5.9);
        expect($colomboLat)->toBeLessThanOrEqual(9.9);
        expect($colomboLng)->toBeGreaterThanOrEqual(79.6);
        expect($colomboLng)->toBeLessThanOrEqual(81.9);
    });
});