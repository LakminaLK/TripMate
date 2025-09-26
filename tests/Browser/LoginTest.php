<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test tourist login functionality
     */
    public function test_tourist_can_login()
    {
        // Create a test user
        $user = User::factory()->create([
            'email' => 'tourist@test.com',
            'password' => bcrypt('password123'),
            'role' => 'tourist'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->assertSee('Login')
                    ->type('email', $user->email)
                    ->type('password', 'password123')
                    ->press('Login')
                    ->waitForLocation('/dashboard')
                    ->assertPathIs('/dashboard')
                    ->assertAuthenticated();
        });
    }

    /**
     * Test admin login functionality
     */
    public function test_admin_can_login()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                    ->assertSee('Admin Login')
                    ->type('username', 'admin')
                    ->type('password', 'admin123')
                    ->press('Sign In')
                    ->waitForLocation('/admin/dashboard')
                    ->assertPathIs('/admin/dashboard');
        });
    }

    /**
     * Test hotel booking process
     */
    public function test_user_can_book_hotel()
    {
        $user = User::factory()->create([
            'email' => 'tourist@test.com',
            'password' => bcrypt('password123'),
            'role' => 'tourist'
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/hotels')
                    ->assertSee('Available Hotels')
                    ->clickLink('View Details') // Click first hotel
                    ->waitForText('Book Now')
                    ->press('Book Now')
                    ->waitForLocation('/booking')
                    ->type('check_in', '2025-12-01')
                    ->type('check_out', '2025-12-05')
                    ->select('guests', '2')
                    ->press('Continue to Payment')
                    ->waitForText('Payment Details');
        });
    }
}