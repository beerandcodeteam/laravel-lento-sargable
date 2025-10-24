<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['new', 'paid', 'shipped', 'cancelled']),
            'total_cents' => fake()->numberBetween(100, 1000000),
            'created_at' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
