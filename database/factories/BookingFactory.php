<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Tourist;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkIn = $this->faker->dateTimeBetween('now', '+30 days');
        $checkOut = $this->faker->dateTimeBetween($checkIn, '+7 days');
        $pricePerNight = $this->faker->randomFloat(2, 50, 500);
        $roomsBooked = $this->faker->numberBetween(1, 3);
        $nights = $checkIn->diff($checkOut)->days;
        
        return [
            'tourist_id' => Tourist::factory(),
            'hotel_id' => Hotel::factory(),
            'room_type_id' => RoomType::factory(),
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'check_in_date' => $checkIn,
            'check_out_date' => $checkOut,
            'rooms_booked' => $roomsBooked,
            'guests_count' => $this->faker->numberBetween(1, 4),
            'price_per_night' => $pricePerNight,
            'total_amount' => $pricePerNight * $nights * $roomsBooked,
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'booking_status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['pending', 'completed', 'failed', 'refunded']),
            'payment_method' => $this->faker->randomElement(['card', 'bank_transfer', 'cash']),
            'booking_reference' => strtoupper($this->faker->lexify('BK??????')),
            'special_requests' => $this->faker->optional()->text(100),
            'booking_details' => json_encode([
                'room_preferences' => $this->faker->optional()->text(50),
                'arrival_time' => $this->faker->time(),
            ]),
            'payment_details' => json_encode([
                'transaction_id' => $this->faker->uuid(),
                'payment_date' => $this->faker->dateTime(),
            ]),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'booking_status' => 'pending',
            'payment_status' => 'pending',
        ]);
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'confirmed',
            'booking_status' => 'confirmed',
            'payment_status' => 'completed',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'cancelled',
            'booking_status' => 'cancelled',
        ]);
    }
}