<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Game;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Court::all()->each(function (Court $court) {
            // 2 upcoming games
            Game::factory(2)->create(['court_id' => $court->id]);

            // 1 past game
            Game::factory()->past()->create(['court_id' => $court->id]);
        });
    }
}
