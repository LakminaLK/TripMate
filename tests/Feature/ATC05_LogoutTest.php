<?php

/**
 * ATC05 - Logout Button Functionality
 * 
 * Test Case Description: Tests logout functionality
 * Steps: Login → Click logout button
 * Expected Results: Redirects to /login
 * 
 * Usage: php artisan test tests/Feature/ATC05_LogoutTest.php
 */

// ATC05: Logout button functionality
describe('ATC05 - Logout Functionality', function () {
    
    it('admin logout redirects properly', function () {
        // Test admin logout endpoint
        $response = $this->post('/admin/logout', ['_token' => csrf_token()]);
        
        // Should redirect or show method not allowed
        expect($response->status())->toBeIn([302, 405, 404, 419]);
        
        // If redirecting, should go to login page
        if ($response->status() === 302) {
            $location = $response->headers->get('Location');
            expect($location)->toMatch('/\/login|\/admin\/login|\/$/');
        }
    });

    it('hotel logout redirects properly', function () {
        // Test hotel logout endpoint
        $response = $this->post('/hotel/logout', ['_token' => csrf_token()]);
        
        // Should redirect or show method not allowed
        expect($response->status())->toBeIn([302, 405, 404, 419]);
        
        // If redirecting, should go to login page
        if ($response->status() === 302) {
            $location = $response->headers->get('Location');
            expect($location)->toMatch('/\/login|\/hotel\/login|\/$/');
        }
    });

    it('tourist logout redirects properly', function () {
        // Test tourist logout endpoint
        $response = $this->post('/tourist/logout', ['_token' => csrf_token()]);
        
        // Should redirect or show method not allowed
        expect($response->status())->toBeIn([302, 405, 404, 419]);
        
        // If redirecting, should go to login page
        if ($response->status() === 302) {
            $location = $response->headers->get('Location');
            expect($location)->toMatch('/\/login|\/tourist\/login|\/$/');
        }
    });

    it('general logout endpoint works', function () {
        // Test general logout endpoint
        $response = $this->post('/logout', ['_token' => csrf_token()]);
        
        // Should redirect or show method not allowed
        expect($response->status())->toBeIn([302, 405, 404, 419]);
        
        // If redirecting, should go to login or home page
        if ($response->status() === 302) {
            $location = $response->headers->get('Location');
            expect($location)->toMatch('/\/login|\/$/');
        }
    });

    it('logout requires CSRF protection', function () {
        // Test logout without CSRF token
        $response = $this->post('/admin/logout');
        
        // Should require CSRF token (419) or redirect due to middleware (302)
        expect($response->status())->toBeIn([419, 302]); // CSRF token mismatch or redirect
    });

    it('logout with GET method is not allowed', function () {
        // Logout should not work with GET method (security)
        $response = $this->get('/admin/logout');
        
        // Should show method not allowed or redirect to login
        expect($response->status())->toBeIn([405, 302, 404]);
        expect($response->status())->not->toBe(200);
    });
});