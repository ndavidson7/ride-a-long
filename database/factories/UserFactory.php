<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => fake()->regexify('[a-z]{2,3}[1-9][a-z]{2,3}@virginia\.edu'),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'password' => 'password',
            'phone' => fake()->regexify('[1-9]\d{9}'),
            // 'email_verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
