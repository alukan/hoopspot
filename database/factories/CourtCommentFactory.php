<?php

namespace Database\Factories;

use App\Models\Court;
use App\Models\CourtComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CourtComment>
 */
class CourtCommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'court_id'   => Court::factory(),
            'replies_to' => null,
            'body'       => fake()->realText(120),
        ];
    }

    public function replyTo(CourtComment $comment): static
    {
        return $this->state(fn () => [
            'court_id'   => $comment->court_id,
            'replies_to' => $comment->id,
        ]);
    }
}
