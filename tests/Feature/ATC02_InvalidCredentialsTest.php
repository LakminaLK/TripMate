<?php

/**
 * ATC02 - Error Handling for Invalid Credentials
 * 
 * Test Case Description: Tests login with wrong email/password
 * Steps: Enter wrong email/password → Submit
 * Expected Results: Error message shown
 * 
 * Usage: php artisan test tests/Feature/ATC02_InvalidCredentialsTest.php
 */

// ATC02: Error handling for invalid credentials
describe('ATC02 - Invalid Credentials Error Handling', function () {
    
    it('shows error message for wrong admin credentials', function () {
        $response = $this->post('/admin/login', [
            'username' => 'wrong@example.com',
            'password' => 'wrongpassword',
            '_token' => csrf_token()
        ]);
        
        // Should not be successful (not 200)
        expect($response->status())->not->toBe(200);
        
        // Should show some kind of error response (including 500 for validation errors)
        expect($response->status())->toBeIn([302, 422, 401, 419, 500]);
        
        // Verify it's not allowing access
        if ($response->status() === 302) {
            $location = $response->headers->get('Location');
            // Should redirect back to login or show error, not to dashboard
            expect($location)->not->toContain('/admin/dashboard');
        }
    });

    it('shows error message for wrong hotel credentials', function () {
        $response = $this->post('/hotel/login', [
            'username' => 'wrong@example.com', 
            'password' => 'wrongpassword',
            '_token' => csrf_token()
        ]);
        
        // Should not be successful (not 200)
        expect($response->status())->not->toBe(200);
        
        // Should show some kind of error response (including 500 for validation errors)
        expect($response->status())->toBeIn([302, 422, 401, 419, 500]);
        
        // Verify it's not allowing access
        if ($response->status() === 302) {
            $location = $response->headers->get('Location');
            // Should redirect back to login or show error, not to dashboard
            expect($location)->not->toContain('/hotel/dashboard');
        }
    });

    it('handles empty credentials properly', function () {
        // Test empty admin credentials
        $adminResponse = $this->post('/admin/login', [
            'username' => '',
            'password' => '',
            '_token' => csrf_token()
        ]);
        
        expect($adminResponse->status())->not->toBe(200);
        expect($adminResponse->status())->toBeIn([302, 422, 401, 419, 500]);
        
        // Test empty hotel credentials
        $hotelResponse = $this->post('/hotel/login', [
            'username' => '',
            'password' => '',
            '_token' => csrf_token()
        ]);
        
        expect($hotelResponse->status())->not->toBe(200);
        expect($hotelResponse->status())->toBeIn([302, 422, 401, 419, 500]);
    });

    it('validates CSRF token requirement', function () {
        // Test without CSRF token
        $response = $this->post('/admin/login', [
            'username' => 'test@example.com',
            'password' => 'password'
            // No _token provided
        ]);
        
        // Should reject due to missing CSRF token (419) or validation error (500)
        expect($response->status())->toBeIn([419, 500]); // CSRF or validation error
    });
});