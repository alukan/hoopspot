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
            $count   = min(rand(3, 8), $users->count());
            $players = $users->random($count);

            foreach ($players as $user) {
                Attendee::create([
                    'game_id' => $game->id,
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
