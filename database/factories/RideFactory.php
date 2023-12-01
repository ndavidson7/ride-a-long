<?php

namespace Database\Factories;

use App\Models\Driver;
use Musonza\Chat\Facades\ChatFacade as Chat;
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
        $addresses = Address::inRandomOrder()->limit(2)->get();
        $driver = Driver::inRandomOrder()->first();

        return [
            'driver_id' => $driver->id,
            'start_time' => fake()->dateTimeBetween('now', '+2 months'),
            'origin_id' => $addresses->first(),
            'destination_id' => $addresses->last(),
            'seats_total' => fake()->numberBetween(1, 7),
            'detours_allowed' => fake()->boolean(),
            'price_per_mile' => null,
            'description' => fake()->text(255),
            'conversation_id' => Chat::createConversation([$driver->user])->makePrivate()->id,
        ];
    }
}
