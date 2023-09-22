<?php

namespace Database\Factories;

use App\Models\Driver;
use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ride>
 */
class RideFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'driver_id' => Driver::inRandomOrder()->first(),
            'start_time' => fake()->dateTimeBetween('now', '+1 year'),
            'origin_id' => Address::inRandomOrder()->first(),
            'destination_id' => Address::inRandomOrder()->first(), // will sometimes be the same as origin_id lol
            'seats_total' => fake()->numberBetween(1, 7),
            'detours_allowed' => fake()->boolean(),
            'price_per_mile' => null,
            'description' => fake()->text(255),
        ];
    }
}
