<?php

declare(strict_types=1);


namespace Tests\Acceptance;

use Tests\Support\AcceptanceTester;

final class HotelBookingCest
{
    public function _before(AcceptanceTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function testTouristHotelBooking(AcceptanceTester $I): void
    {
        $I->wantTo('Test complete hotel booking flow as a tourist');
        
        // Navigate to login page
        $I->amOnPage('/login');
        $I->see('Login');
        
        // Fill login form
        $I->fillField('email', 'tourist@test.com');
        $I->fillField('password', 'password123');
        $I->click('Login');
        
        // Should be redirected to dashboard
        $I->seeCurrentUrlMatches('/dashboard');
        
        // Navigate to hotels
        $I->click('Hotels');
        $I->see('Available Hotels');
        
        // Select first hotel
        $I->click('View Details');
        $I->see('Book Now');
        
        // Fill booking form
        $I->click('Book Now');
        $I->fillField('check_in', '2025-12-01');
        $I->fillField('check_out', '2025-12-05');
        $I->selectOption('guests', '2');
        
        // Proceed to payment
        $I->click('Continue to Payment');
        $I->see('Payment Details');
    }

    public function testAdminDashboardAccess(AcceptanceTester $I): void
    {
        $I->wantTo('Test admin login and dashboard access');
        
        // Navigate to admin login
        $I->amOnPage('/admin/login');
        $I->see('Admin Login');
        
        // Fill admin login form
        $I->fillField('username', 'admin');
        $I->fillField('password', 'admin123');
        $I->click('Sign In');
        
        // Should be on admin dashboard
        $I->seeCurrentUrlMatches('/admin/dashboard');
        $I->see('Admin Dashboard');
        
        // Test navigation to hotel management
        $I->click('Hotels');
        $I->see('Hotel Management');
    }
}
