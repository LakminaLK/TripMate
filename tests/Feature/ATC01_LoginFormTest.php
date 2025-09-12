<?php

/**
 * ATC01 - Verify Login Form Rendering
 * 
 * Test Case Description: Ensures login form renders correctly
 * Steps: Visit /login → Verify form fields
 * Expected Results: Email, password, login button appear
 * 
 * Usage: php artisan test tests/Feature/ATC01_LoginFormTest.php
 */

// ATC01: Verify login form renders correctly
describe('ATC01 - Login Form Rendering', function () {
    
    it('admin login form renders with all required fields', function () {
        $response = $this->get('/admin/login');
        
        // Verify page loads successfully
        $response->assertStatus(200);
        
        // Check that username field appears (not email in your app)
        $response->assertSee('username', false);
        
        // Check that password field appears  
        $response->assertSee('password', false);
        
        // Check that login button appears
        $response->assertSee('Sign In', false);
        
        // Verify form exists
        $response->assertSee('<form', false);
        
        // Verify admin-specific elements
        $response->assertSee('Admin Login', false);
        $response->assertSee('administrative', false);
    });

    it('hotel login form renders with all required fields', function () {
        $response = $this->get('/hotel/login');
        
        // Verify page loads successfully
        $response->assertStatus(200);
        
        // Check that username field appears (not email in your app)
        $response->assertSee('username', false);
        
        // Check that password field appears  
        $response->assertSee('password', false);
        
        // Check that login button appears
        $response->assertSee('Sign In', false);
        
        // Verify form exists
        $response->assertSee('<form', false);
        
        // Verify hotel-specific elements
        $response->assertSee('Hotel Login', false);
        $response->assertSee('hotel management', false);
    });

    it('login forms have proper security features', function () {
        // Test admin login form
        $adminResponse = $this->get('/admin/login');
        $adminResponse->assertSee('_token', false); // CSRF protection
        
        // Test hotel login form  
        $hotelResponse = $this->get('/hotel/login');
        $hotelResponse->assertSee('_token', false); // CSRF protection
    });
});