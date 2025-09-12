<?php

namespace Database\Factories;

use App\Models\Activity;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityFactory extends Factory
{
    protected $model = Activity::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->text(200),
            'price' => $this->faker->randomFloat(2, 10, 200),
            'duration' => $this->faker->numberBetween(1, 8) . ' hours',
            'difficulty_level' => $this->faker->randomElement(['easy', 'moderate', 'challenging']),
            'category' => $this->faker->randomElement(['adventure', 'cultural', 'nature', 'relaxation', 'food']),
            'image' => 'activities/' . $this->faker->word() . '.jpg',
            'location_id' => Location::factory(),
            'status' => 'active',
            'min_participants' => $this->faker->numberBetween(1, 2),
            'max_participants' => $this->faker->numberBetween(10, 50),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}