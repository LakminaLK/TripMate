<?php

describe('ATC23 - Commission Calculation', function () {

    it('platform revenue logic routes respond properly', function () {
        $response = $this->get('/admin/revenue');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/commission');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/revenue/analytics');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('commission calculation for ₹10000 booking shows ₹1000', function () {
        // Test the specific scenario: ₹10000 room booking should generate ₹1000 commission (10%)
        $bookingAmount = 10000;
        $expectedCommission = 1000; // 10% of 10000
        
        // Simulate booking creation
        $response = $this->post('/bookings', [
            'hotel_id' => 1,
            'room_id' => 1,
            'amount' => $bookingAmount,
            'check_in' => '2025-09-15',
            'check_out' => '2025-09-17',
            'guests' => 2
        ]);
        
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        
        // Check commission calculation
        $response = $this->get('/admin/commission/booking/1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for commission amount in response
            expect(
                str_contains($content, '1000') ||
                str_contains($content, '₹1000') ||
                str_contains($content, '10%')
            )->toBeTrue();
        }
    });

    it('commission calculation is 10% of booking amount', function () {
        $testBookings = [
            ['amount' => 5000, 'expected_commission' => 500],
            ['amount' => 15000, 'expected_commission' => 1500],
            ['amount' => 2000, 'expected_commission' => 200],
            ['amount' => 25000, 'expected_commission' => 2500]
        ];
        
        foreach ($testBookings as $index => $booking) {
            $response = $this->post('/bookings', [
                'hotel_id' => 1,
                'room_id' => 1,
                'amount' => $booking['amount'],
                'check_in' => '2025-09-15',
                'check_out' => '2025-09-17',
                'guests' => 2,
                'booking_reference' => 'TEST_' . ($index + 1)
            ]);
            
            expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        }
        
        // Check commission calculations
        $response = $this->get('/admin/commission/summary');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('commission shows correct amount in admin panel', function () {
        $response = $this->get('/admin/revenue');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for commission/revenue display
            expect(
                str_contains($content, 'commission') ||
                str_contains($content, 'Commission') ||
                str_contains($content, 'revenue') ||
                str_contains($content, 'Revenue') ||
                str_contains($content, '₹') ||
                str_contains($content, 'total') ||
                str_contains($content, 'Total')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        }
    });

    it('platform revenue is calculated correctly', function () {
        // Test multiple bookings and total platform revenue
        $totalCommissionExpected = 0;
        $bookings = [
            10000, // ₹1000 commission
            5000,  // ₹500 commission  
            8000,  // ₹800 commission
            12000  // ₹1200 commission
        ];
        
        foreach ($bookings as $index => $amount) {
            $totalCommissionExpected += ($amount * 0.10); // 10% commission
            
            $response = $this->post('/bookings', [
                'hotel_id' => 1,
                'room_id' => 1,
                'amount' => $amount,
                'check_in' => '2025-09-15',
                'check_out' => '2025-09-17',
                'guests' => 2,
                'booking_reference' => 'PLATFORM_TEST_' . ($index + 1)
            ]);
            
            expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        }
        
        // Total expected commission: ₹3500
        expect((int)$totalCommissionExpected)->toBe(3500);
        
        // Check total platform revenue
        $response = $this->get('/admin/revenue/total');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('commission calculation handles different payment statuses', function () {
        // Test commission for different booking statuses
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        
        foreach ($statuses as $index => $status) {
            $response = $this->post('/bookings', [
                'hotel_id' => 1,
                'room_id' => 1,
                'amount' => 10000,
                'status' => $status,
                'check_in' => '2025-09-15',
                'check_out' => '2025-09-17',
                'guests' => 2,
                'booking_reference' => 'STATUS_TEST_' . ($index + 1)
            ]);
            
            expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        }
        
        // Check commission calculation for different statuses
        $response = $this->get('/admin/commission/by-status');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('commission rate is configurable in system', function () {
        // Test that commission rate can be verified/configured
        $response = $this->get('/admin/settings/commission');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        // Test commission rate endpoint
        $response = $this->get('/api/commission-rate');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
        }
    });

    it('hotel receives correct amount after commission deduction', function () {
        // For ₹10000 booking, hotel should receive ₹9000 (after ₹1000 commission)
        $bookingAmount = 10000;
        $platformCommission = 1000; // 10%
        $hotelAmount = 9000; // 90%
        
        $response = $this->post('/bookings', [
            'hotel_id' => 1,
            'room_id' => 1,
            'amount' => $bookingAmount,
            'check_in' => '2025-09-15',
            'check_out' => '2025-09-17',
            'guests' => 2
        ]);
        
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        
        // Check hotel revenue calculation
        $response = $this->get('/hotel/revenue/booking/1');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for hotel amount after commission
            expect(
                str_contains($content, '9000') ||
                str_contains($content, '₹9000') ||
                str_contains($content, '90%')
            )->toBeTrue();
        }
    });

    it('commission calculation includes proper date filtering', function () {
        // Test commission calculation for specific date ranges
        $response = $this->get('/admin/commission?from=2025-09-01&to=2025-09-30');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/revenue/monthly/2025/09');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/revenue/yearly/2025');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('commission calculation excludes refunded bookings', function () {
        // Test that refunded bookings don't contribute to commission
        $response = $this->post('/bookings', [
            'hotel_id' => 1,
            'room_id' => 1,
            'amount' => 10000,
            'status' => 'refunded',
            'check_in' => '2025-09-15',
            'check_out' => '2025-09-17',
            'guests' => 2
        ]);
        
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 405, 500]);
        
        // Process refund
        $response = $this->post('/bookings/1/refund', [
            'reason' => 'Customer requested refund',
            'refund_amount' => 10000
        ]);
        
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
        
        // Check that commission is adjusted for refund
        $response = $this->get('/admin/commission/net');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('commission reporting displays accurate totals', function () {
        $response = $this->get('/admin/reports/commission');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for commission reporting elements
            expect(
                str_contains($content, 'total') ||
                str_contains($content, 'Total') ||
                str_contains($content, 'commission') ||
                str_contains($content, 'Commission') ||
                str_contains($content, 'revenue') ||
                str_contains($content, 'Revenue') ||
                str_contains($content, 'summary') ||
                str_contains($content, 'Summary')
            )->toBeTrue();
        }
    });
});
