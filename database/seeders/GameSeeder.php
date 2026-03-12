<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::all();

        Court::all()->each(function (Court $court) use ($users) {
            // 2 upcoming games
            Game::factory(2)->create([
                'court_id'   => $court->id,
                'creator_id' => $users->random()->id,
            ]);

            // 1 past game
            Game::factory()->past()->create([
                'court_id'   => $court->id,
                'creator_id' => $users->random()->id,
            ]);
        });
    }
}
