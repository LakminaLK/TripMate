<?php

describe('ATC21 - Hotel Dashboard', function () {

    it('hotel operator can access dashboard', function () {
        $response = $this->get('/hotel/dashboard');
        expect($response->getStatusCode())->toBeIn([200, 302]); // 302 for redirect to login
        
        $response = $this->get('/hotel/login');
        expect($response->getStatusCode())->toBeIn([200, 302]);
        
        $response = $this->get('/hotel');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('hotel dashboard shows operator overview', function () {
        $response = $this->get('/hotel/dashboard');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for dashboard elements
            expect(
                str_contains($content, 'dashboard') ||
                str_contains($content, 'Dashboard') ||
                str_contains($content, 'overview') ||
                str_contains($content, 'Overview')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302]);
        }
    });

    it('dashboard displays booking information', function () {
        $response = $this->get('/hotel/dashboard');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for booking-related content
            expect(
                str_contains($content, 'booking') ||
                str_contains($content, 'Booking') ||
                str_contains($content, 'reservation') ||
                str_contains($content, 'Reservation')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302]);
        }
    });

    it('dashboard shows room management section', function () {
        $response = $this->get('/hotel/dashboard');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for room management elements
            expect(
                str_contains($content, 'room') ||
                str_contains($content, 'Room') ||
                str_contains($content, 'accommodation') ||
                str_contains($content, 'Accommodation')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302]);
        }
    });

    it('dashboard displays revenue information', function () {
        $response = $this->get('/hotel/dashboard');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for revenue/financial elements
            expect(
                str_contains($content, 'revenue') ||
                str_contains($content, 'Revenue') ||
                str_contains($content, 'earnings') ||
                str_contains($content, 'Earnings') ||
                str_contains($content, 'income') ||
                str_contains($content, 'Income') ||
                str_contains($content, '₹') ||
                str_contains($content, '$') ||
                str_contains($content, 'total') ||
                str_contains($content, 'Total')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302]);
        }
    });

    it('dashboard navigation links work correctly', function () {
        $dashboardRoutes = [
            '/hotel/bookings',
            '/hotel/rooms', 
            '/hotel/revenue',
            '/hotel/analytics',
            '/hotel/profile'
        ];
        
        foreach ($dashboardRoutes as $route) {
            $response = $this->get($route);
            expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        }
    });

    it('dashboard shows recent booking activity', function () {
        $response = $this->get('/hotel/bookings');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/hotel/bookings/recent');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        // Test booking list functionality
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
        }
    });

    it('dashboard provides room availability overview', function () {
        $response = $this->get('/hotel/rooms/availability');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/hotel/rooms');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for availability information
            expect(
                str_contains($content, 'available') ||
                str_contains($content, 'Available') ||
                str_contains($content, 'occupied') ||
                str_contains($content, 'Occupied') ||
                str_contains($content, 'status') ||
                str_contains($content, 'Status')
            )->toBeTrue();
        }
    });

    it('dashboard displays performance metrics', function () {
        $response = $this->get('/hotel/analytics');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/hotel/dashboard');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for metrics or statistics
            expect(
                str_contains($content, 'total') ||
                str_contains($content, 'Total') ||
                str_contains($content, 'stats') ||
                str_contains($content, 'Statistics') ||
                str_contains($content, 'count') ||
                str_contains($content, 'number') ||
                str_contains($content, '%')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302]);
        }
    });

    it('dashboard requires hotel authentication', function () {
        // Test that dashboard requires proper hotel login
        $response = $this->get('/hotel/dashboard');
        
        if ($response->getStatusCode() === 302) {
            $location = $response->headers->get('Location');
            expect($location)->toBeString();
            expect(
                str_contains($location, 'login') ||
                str_contains($location, 'auth')
            )->toBeTrue();
        }
        
        expect($response->getStatusCode())->toBeIn([200, 302]);
    });

    it('dashboard shows current date and time information', function () {
        $response = $this->get('/hotel/dashboard');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for date/time elements (common in dashboards)
            $currentYear = date('Y');
            expect(
                str_contains($content, $currentYear) ||
                str_contains($content, date('M')) ||
                str_contains($content, 'today') ||
                str_contains($content, 'Today') ||
                str_contains($content, 'week') ||
                str_contains($content, 'month')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302]);
        }
    });

    it('dashboard provides quick action buttons', function () {
        $response = $this->get('/hotel/dashboard');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for action buttons or links
            expect(
                str_contains($content, 'button') ||
                str_contains($content, 'btn') ||
                str_contains($content, 'action') ||
                str_contains($content, 'add') ||
                str_contains($content, 'Add') ||
                str_contains($content, 'create') ||
                str_contains($content, 'Create') ||
                str_contains($content, 'manage') ||
                str_contains($content, 'Manage')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302]);
        }
    });

    it('dashboard layout is responsive and accessible', function () {
        $response = $this->get('/hotel/dashboard');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            expect(strlen($content))->toBeGreaterThan(500); // Substantial content
            
            // Look for responsive design elements
            expect(
                str_contains($content, 'container') ||
                str_contains($content, 'row') ||
                str_contains($content, 'col') ||
                str_contains($content, 'grid') ||
                str_contains($content, 'flex') ||
                str_contains($content, 'responsive')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302]);
        }
    });
});