<?php

namespace Database\Factories;

use App\Models\RoomType;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomTypeFactory extends Factory
{
    protected $model = RoomType::class;

    public function definition(): array
    {
        return [
            'hotel_id' => Hotel::factory(),
            'name' => $this->faker->randomElement(['Standard Room', 'Deluxe Room', 'Suite', 'Family Room', 'Executive Room']),
            'description' => $this->faker->paragraph(2),
            'price_per_night' => $this->faker->randomFloat(2, 50, 500),
            'max_occupancy' => $this->faker->numberBetween(1, 6),
            'room_size' => $this->faker->numberBetween(20, 80) . ' sq m',
            'bed_type' => $this->faker->randomElement(['Single', 'Double', 'Queen', 'King', 'Twin']),
            'amenities' => json_encode([
                'air_conditioning' => true,
                'wifi' => true,
                'tv' => true,
                'minibar' => $this->faker->boolean(),
                'balcony' => $this->faker->boolean(),
                'bathroom' => 'private',
            ]),
            'total_rooms' => $this->faker->numberBetween(5, 30),
            'available_rooms' => $this->faker->numberBetween(1, 30),
        ];
    }
}