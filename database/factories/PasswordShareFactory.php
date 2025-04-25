<?php

namespace Database\Factories;

use App\Models\PasswordShare;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PasswordShare>
 */
class PasswordShareFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $maxUses = fake()->numberBetween(1, 5);

        return [
            'id' => fake()->uuid(),
            'password' => fake()->password(),
            'max_uses' => $maxUses,
            'remaining_uses' => $maxUses,
            'expires_at' => fake()->dateTimeBetween('now', '+1 day'),
        ];
    }
}
