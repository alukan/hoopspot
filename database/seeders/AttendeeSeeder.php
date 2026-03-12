<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendeeSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::all();

        Game::all()->each(function (Game $game) use ($users) {
            $attendeeIds = collect();

            // Creator is always the first attendee
            if ($game->creator_id) {
                $attendeeIds->push($game->creator_id);
            }

            // Fill remaining spots with random users, excluding the creator
            $remaining = min(rand(2, 7), $users->count());
            $users->whereNotIn('id', $attendeeIds->all())
                ->random(min($remaining, $users->count() - $attendeeIds->count()))
                ->each(fn ($user) => $attendeeIds->push($user->id));

            foreach ($attendeeIds as $userId) {
                Attendee::create([
                    'game_id' => $game->id,
                    'user_id' => $userId,
                ]);
            }
        });
    }
}
