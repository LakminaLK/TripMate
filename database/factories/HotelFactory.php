<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true) . ' Hotel',
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'description' => $this->faker->text(300),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'latitude' => $this->faker->latitude(5.9, 9.9), // Sri Lanka coordinates
            'longitude' => $this->faker->longitude(79.6, 81.9),
            'status' => 'active',
            'amenities' => json_encode([
                'wifi' => true,
                'parking' => true,
                'restaurant' => $this->faker->boolean(),
                'pool' => $this->faker->boolean(),
                'gym' => $this->faker->boolean(),
            ]),
            'check_in_time' => '14:00',
            'check_out_time' => '12:00',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}