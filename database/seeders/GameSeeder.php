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
        $users    = User::all();
        $testUser = User::where('email', 'test@example.com')->first();

        Court::all()->each(function (Court $court) use ($users, $testUser) {
            // 2 upcoming games
            Game::factory(2)->create([
                'court_id'   => $court->id,
                'creator_id' => $users->random()->id,
            ]);

            // 3 past games — test user is creator of one per court
            Game::factory()->past()->create([
                'court_id'   => $court->id,
                'creator_id' => $testUser->id,
            ]);

            Game::factory(2)->past()->create([
                'court_id'   => $court->id,
                'creator_id' => $users->random()->id,
            ]);
        });
    }
}
