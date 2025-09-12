<?php

namespace Database\Factories;

use App\Models\Tourist;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TouristFactory extends Factory
{
    protected $model = Tourist::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'mobile' => $this->faker->phoneNumber(),
            'password' => Hash::make('password'),
            'location' => $this->faker->city(),
            'otp' => null,
            'otp_verified' => true,
            'remember_token' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'otp_verified' => false,
            'otp' => $this->faker->numberBetween(100000, 999999),
        ]);
    }
}