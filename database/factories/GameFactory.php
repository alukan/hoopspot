<?php

namespace Database\Factories;

use App\Models\Court;
use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'court_id'    => Court::factory(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+60 days'),
            'description' => fake()->optional(0.6)->sentence(),
        ];
    }

    public function past(): static
    {
        return $this->state(fn () => [
            'scheduled_at' => fake()->dateTimeBetween('-60 days', '-1 day'),
        ]);
    }
}
