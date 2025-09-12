<?php

use App\Models\Tourist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Tourist Authentication', function () {
    it('can view registration page', function () {
        $response = $this->get('/register');
        
        $response->assertStatus(200);
        $response->assertSee('Register');
    });

    it('can register a new tourist', function () {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'mobile' => '+94771234567',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'location' => 'Colombo',
        ];

        $response = $this->post('/register', $userData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('tourists', [
            'email' => 'john@example.com',
            'name' => 'John Doe',
        ]);
    });

    it('can login with valid credentials', function () {
        $tourist = Tourist::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect();
        $this->assertAuthenticatedAs($tourist, 'tourist');
    });

    it('cannot login with invalid credentials', function () {
        Tourist::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest('tourist');
    });

    it('can logout', function () {
        $tourist = Tourist::factory()->create();
        
        $this->actingAs($tourist, 'tourist');
        
        $response = $this->post('/logout');
        
        $response->assertRedirect();
        $this->assertGuest('tourist');
    });

    it('validates required fields during registration', function () {
        $response = $this->post('/register', []);
        
        $response->assertSessionHasErrors(['name', 'email', 'password']);
    });

    it('validates email format during registration', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        $response->assertSessionHasErrors(['email']);
    });

    it('validates password confirmation during registration', function () {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'different_password',
        ]);
        
        $response->assertSessionHasErrors(['password']);
    });

    it('prevents duplicate email registration', function () {
        Tourist::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/register', [
            'name' => 'John Doe',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        
        $response->assertSessionHasErrors(['email']);
    });
});