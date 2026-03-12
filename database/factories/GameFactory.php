<?php

namespace Database\Factories;

use App\Models\Court;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    public function definition(): array
    {
        return [
            'court_id'     => Court::factory(),
            'creator_id'   => User::factory(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+60 days'),
            'title'        => fake()->optional(0.5)->randomElement([
                'Friday evening run',
                '3v3 tourney',
                'Morning shootaround',
                'Sunday open run',
                'Lunch break hoops',
                'Winners stay on',
                'Half-court 2v2',
                'Pick-up game',
                'Weekend warriors',
                'Late night run',
            ]),
            'description'  => fake()->optional(0.6)->sentence(),
            'level'        => fake()->optional(0.8)->randomElement(Game::LEVELS),
        ];
    }

    public function past(): static
    {
        return $this->state(fn () => [
            'scheduled_at' => fake()->dateTimeBetween('-60 days', '-1 day'),
        ]);
    }
}
