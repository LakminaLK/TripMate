<?php

use App\Models\Tourist;
use App\Models\Hotel;
use App\Models\RoomType;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Hotel Booking Flow', function () {
    it('can view available hotels', function () {
        Hotel::factory()->count(3)->create(['status' => 'active']);
        
        $response = $this->get('/tourist/explore');
        
        $response->assertStatus(200);
        $response->assertSee('Hotels');
    });

    it('can view hotel details', function () {
        $hotel = Hotel::factory()->create([
            'name' => 'Grand Palace Hotel',
            'status' => 'active',
        ]);
        
        $response = $this->get("/tourist/hotel/{$hotel->id}");
        
        $response->assertStatus(200);
        $response->assertSee('Grand Palace Hotel');
    });

    it('authenticated tourist can create booking', function () {
        $tourist = Tourist::factory()->create();
        $hotel = Hotel::factory()->create();
        $roomType = RoomType::factory()->create(['hotel_id' => $hotel->id]);
        
        $this->actingAs($tourist, 'tourist');
        
        $bookingData = [
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'check_in' => '2024-12-25',
            'check_out' => '2024-12-28',
            'rooms_booked' => 1,
            'guests_count' => 2,
        ];
        
        $response = $this->post('/tourist/booking', $bookingData);
        
        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'tourist_id' => $tourist->id,
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
        ]);
    });

    it('guest cannot create booking', function () {
        $hotel = Hotel::factory()->create();
        $roomType = RoomType::factory()->create(['hotel_id' => $hotel->id]);
        
        $bookingData = [
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'check_in' => '2024-12-25',
            'check_out' => '2024-12-28',
            'rooms_booked' => 1,
            'guests_count' => 2,
        ];
        
        $response = $this->post('/tourist/booking', $bookingData);
        
        $response->assertRedirect('/login');
    });

    it('validates booking dates', function () {
        $tourist = Tourist::factory()->create();
        $hotel = Hotel::factory()->create();
        $roomType = RoomType::factory()->create(['hotel_id' => $hotel->id]);
        
        $this->actingAs($tourist, 'tourist');
        
        // Check-out before check-in
        $bookingData = [
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'check_in' => '2024-12-28',
            'check_out' => '2024-12-25',
            'rooms_booked' => 1,
            'guests_count' => 2,
        ];
        
        $response = $this->post('/tourist/booking', $bookingData);
        
        $response->assertSessionHasErrors();
    });

    it('tourist can view their bookings', function () {
        $tourist = Tourist::factory()->create();
        $bookings = Booking::factory()->count(3)->create(['tourist_id' => $tourist->id]);
        
        $this->actingAs($tourist, 'tourist');
        
        $response = $this->get('/tourist/bookings');
        
        $response->assertStatus(200);
        $response->assertSee('My Bookings');
    });

    it('tourist can cancel their booking', function () {
        $tourist = Tourist::factory()->create();
        $booking = Booking::factory()->create([
            'tourist_id' => $tourist->id,
            'status' => 'confirmed',
        ]);
        
        $this->actingAs($tourist, 'tourist');
        
        $response = $this->patch("/tourist/booking/{$booking->id}/cancel");
        
        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);
    });

    it('tourist cannot cancel others booking', function () {
        $tourist1 = Tourist::factory()->create();
        $tourist2 = Tourist::factory()->create();
        $booking = Booking::factory()->create([
            'tourist_id' => $tourist2->id,
            'status' => 'confirmed',
        ]);
        
        $this->actingAs($tourist1, 'tourist');
        
        $response = $this->patch("/tourist/booking/{$booking->id}/cancel");
        
        $response->assertStatus(403);
    });

    it('calculates booking total correctly', function () {
        $tourist = Tourist::factory()->create();
        $hotel = Hotel::factory()->create();
        $roomType = RoomType::factory()->create([
            'hotel_id' => $hotel->id,
            'price_per_night' => 100.00,
        ]);
        
        $this->actingAs($tourist, 'tourist');
        
        $bookingData = [
            'hotel_id' => $hotel->id,
            'room_type_id' => $roomType->id,
            'check_in' => '2024-12-25',
            'check_out' => '2024-12-28', // 3 nights
            'rooms_booked' => 2,
            'guests_count' => 4,
        ];
        
        $response = $this->post('/tourist/booking', $bookingData);
        
        $booking = Booking::where('tourist_id', $tourist->id)->first();
        expect($booking->total_amount)->toBe(600.00); // 100 * 3 nights * 2 rooms
    });
});