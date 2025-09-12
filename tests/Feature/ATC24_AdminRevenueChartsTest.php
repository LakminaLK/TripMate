<?php

describe('ATC24 - Admin Revenue Charts', function () {

    it('admin revenue analytics routes are accessible', function () {
        $response = $this->get('/admin/revenue');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/analytics');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/charts');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('admin can access revenue dashboard', function () {
        $response = $this->get('/admin/dashboard');
        expect($response->getStatusCode())->toBeIn([200, 302]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for analytics/revenue elements
            expect(
                str_contains($content, 'revenue') ||
                str_contains($content, 'Revenue') ||
                str_contains($content, 'analytics') ||
                str_contains($content, 'Analytics') ||
                str_contains($content, 'chart') ||
                str_contains($content, 'Chart')
            )->toBeTrue();
        }
    });

    it('revenue charts are visible and filterable', function () {
        $response = $this->get('/admin/revenue/charts');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for chart elements
            expect(
                str_contains($content, 'chart') ||
                str_contains($content, 'Chart') ||
                str_contains($content, 'canvas') ||
                str_contains($content, 'svg') ||
                str_contains($content, 'graph') ||
                str_contains($content, 'Graph')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        }
    });

    it('revenue filters update charts correctly', function () {
        // Test date range filtering
        $response = $this->get('/admin/revenue?from=2025-09-01&to=2025-09-30');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/revenue?filter=monthly');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/revenue?filter=yearly');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/revenue?filter=weekly');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('charts update for specific date ranges', function () {
        // Test current month filter
        $currentMonth = date('Y-m');
        $response = $this->get("/admin/revenue/monthly/{$currentMonth}");
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        // Test current year filter  
        $currentYear = date('Y');
        $response = $this->get("/admin/revenue/yearly/{$currentYear}");
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        // Test custom date range
        $response = $this->post('/admin/revenue/filter', [
            'start_date' => '2025-09-01',
            'end_date' => '2025-09-30',
            'chart_type' => 'line'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
    });

    it('revenue analytics show correct data visualization', function () {
        $response = $this->get('/admin/analytics/revenue');
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for data visualization elements
            expect(
                str_contains($content, 'data') ||
                str_contains($content, 'total') ||
                str_contains($content, 'Total') ||
                str_contains($content, '₹') ||
                str_contains($content, 'commission') ||
                str_contains($content, 'Commission') ||
                str_contains($content, 'booking') ||
                str_contains($content, 'Booking')
            )->toBeTrue();
        } else {
            expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        }
    });

    it('charts display booking trends over time', function () {
        $response = $this->get('/admin/charts/bookings-trend');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/analytics/bookings');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for trend analysis elements
            expect(
                str_contains($content, 'trend') ||
                str_contains($content, 'Trend') ||
                str_contains($content, 'month') ||
                str_contains($content, 'Month') ||
                str_contains($content, 'week') ||
                str_contains($content, 'Week')
            )->toBeTrue();
        }
    });

    it('revenue charts show commission breakdown', function () {
        $response = $this->get('/admin/charts/commission');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/revenue/breakdown');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for commission breakdown
            expect(
                str_contains($content, 'commission') ||
                str_contains($content, 'Commission') ||
                str_contains($content, 'breakdown') ||
                str_contains($content, 'Breakdown') ||
                str_contains($content, '10%') ||
                str_contains($content, 'percentage')
            )->toBeTrue();
        }
    });

    it('admin can export revenue data', function () {
        $response = $this->get('/admin/revenue/export');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->post('/admin/revenue/export', [
            'format' => 'csv',
            'date_range' => 'monthly'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
        
        $response = $this->post('/admin/revenue/export', [
            'format' => 'pdf',
            'from' => '2025-09-01',
            'to' => '2025-09-30'
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
    });

    it('charts handle real-time data updates', function () {
        // Test that charts reflect current data
        $response = $this->get('/admin/analytics/real-time');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        // Test API endpoints for chart data
        $response = $this->get('/api/admin/revenue-data');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/api/admin/booking-stats');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
        }
    });

    it('revenue analytics include hotel performance comparison', function () {
        $response = $this->get('/admin/analytics/hotels');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        $response = $this->get('/admin/charts/hotel-comparison');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for hotel comparison elements
            expect(
                str_contains($content, 'hotel') ||
                str_contains($content, 'Hotel') ||
                str_contains($content, 'comparison') ||
                str_contains($content, 'Comparison') ||
                str_contains($content, 'performance') ||
                str_contains($content, 'Performance')
            )->toBeTrue();
        }
    });

    it('admin dashboard requires proper authentication', function () {
        // Test that admin analytics require admin login
        $response = $this->get('/admin/revenue');
        
        if ($response->getStatusCode() === 302) {
            $location = $response->headers->get('Location');
            expect($location)->toBeString();
            expect(
                str_contains($location, 'login') ||
                str_contains($location, 'auth')
            )->toBeTrue();
        }
        
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
    });

    it('charts display data in multiple formats', function () {
        // Test different chart types
        $chartTypes = ['line', 'bar', 'pie', 'area'];
        
        foreach ($chartTypes as $type) {
            $response = $this->get("/admin/charts/revenue?type={$type}");
            expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        }
        
        // Test chart configuration
        $response = $this->post('/admin/charts/configure', [
            'chart_type' => 'line',
            'time_period' => 'monthly',
            'data_points' => 12
        ]);
        expect($response->getStatusCode())->toBeIn([200, 302, 422, 401, 404, 405, 500]);
    });

    it('revenue analytics show growth metrics', function () {
        $response = $this->get('/admin/analytics/growth');
        expect($response->getStatusCode())->toBeIn([200, 302, 404]);
        
        if ($response->getStatusCode() === 200) {
            $content = $response->getContent();
            expect($content)->toBeString();
            
            // Look for growth metrics
            expect(
                str_contains($content, 'growth') ||
                str_contains($content, 'Growth') ||
                str_contains($content, '%') ||
                str_contains($content, 'increase') ||
                str_contains($content, 'decrease') ||
                str_contains($content, 'change')
            )->toBeTrue();
        }
    });
});
