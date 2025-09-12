<?php

use App\Models\Tourist;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Tourist Profile Management', function () {
    it('tourist can view their profile', function () {
        $tourist = Tourist::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
        
        $this->actingAs($tourist, 'tourist');
        
        $response = $this->get('/tourist/profile');
        
        $response->assertStatus(200);
        $response->assertSee('John Doe');
        $response->assertSee('john@example.com');
    });

    it('guest cannot view profile page', function () {
        $response = $this->get('/tourist/profile');
        
        $response->assertRedirect('/login');
    });

    it('tourist can update their profile', function () {
        $tourist = Tourist::factory()->create([
            'name' => 'John Doe',
            'location' => 'Colombo',
        ]);
        
        $this->actingAs($tourist, 'tourist');
        
        $response = $this->patch('/tourist/profile', [
            'name' => 'John Smith',
            'location' => 'Kandy',
            'mobile' => '+94771234567',
        ]);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('tourists', [
            'id' => $tourist->id,
            'name' => 'John Smith',
            'location' => 'Kandy',
            'mobile' => '+94771234567',
        ]);
    });

    it('validates profile update data', function () {
        $tourist = Tourist::factory()->create();
        
        $this->actingAs($tourist, 'tourist');
        
        $response = $this->patch('/tourist/profile', [
            'name' => '', // Empty name
            'email' => 'invalid-email', // Invalid email
        ]);
        
        $response->assertSessionHasErrors(['name', 'email']);
    });

    it('prevents updating email to existing one', function () {
        $tourist1 = Tourist::factory()->create(['email' => 'john@example.com']);
        $tourist2 = Tourist::factory()->create(['email' => 'jane@example.com']);
        
        $this->actingAs($tourist2, 'tourist');
        
        $response = $this->patch('/tourist/profile', [
            'name' => 'Jane Doe',
            'email' => 'john@example.com', // Already taken
        ]);
        
        $response->assertSessionHasErrors(['email']);
    });

    it('tourist can change password', function () {
        $tourist = Tourist::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);
        
        $this->actingAs($tourist, 'tourist');
        
        $response = $this->patch('/tourist/password', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        $response->assertRedirect();
        
        // Verify new password works
        $this->post('/logout');
        $loginResponse = $this->post('/login', [
            'email' => $tourist->email,
            'password' => 'newpassword123',
        ]);
        $loginResponse->assertRedirect();
    });

    it('validates current password when changing password', function () {
        $tourist = Tourist::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);
        
        $this->actingAs($tourist, 'tourist');
        
        $response = $this->patch('/tourist/password', [
            'current_password' => 'wrongpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);
        
        $response->assertSessionHasErrors(['current_password']);
    });

    it('validates new password confirmation', function () {
        $tourist = Tourist::factory()->create([
            'password' => bcrypt('oldpassword'),
        ]);
        
        $this->actingAs($tourist, 'tourist');
        
        $response = $this->patch('/tourist/password', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'differentpassword',
        ]);
        
        $response->assertSessionHasErrors(['password']);
    });

    it('tourist can upload profile photo', function () {
        $tourist = Tourist::factory()->create();
        
        $this->actingAs($tourist, 'tourist');
        
        $file = \Illuminate\Http\UploadedFile::fake()->image('profile.jpg', 300, 300);
        
        $response = $this->post('/tourist/profile/photo', [
            'profile_photo' => $file,
        ]);
        
        $response->assertRedirect();
        $tourist->refresh();
        expect($tourist->profile_photo_path)->not->toBeNull();
    });

    it('validates profile photo upload', function () {
        $tourist = Tourist::factory()->create();
        
        $this->actingAs($tourist, 'tourist');
        
        // Upload non-image file
        $file = \Illuminate\Http\UploadedFile::fake()->create('document.pdf');
        
        $response = $this->post('/tourist/profile/photo', [
            'profile_photo' => $file,
        ]);
        
        $response->assertSessionHasErrors(['profile_photo']);
    });
});