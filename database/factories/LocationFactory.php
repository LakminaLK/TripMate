<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->text(300),
            'latitude' => $this->faker->latitude(5.9, 9.9), // Sri Lanka coordinates
            'longitude' => $this->faker->longitude(79.6, 81.9),
            'image' => 'locations/' . $this->faker->word() . '.jpg',
            'featured' => $this->faker->boolean(30), // 30% chance of being featured
            'status' => 'active',
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}