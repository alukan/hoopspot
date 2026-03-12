<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\CourtComment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourtCommentSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::all();

        Court::all()->each(function (Court $court) use ($users) {
            // 3 top-level comments per court
            $topLevel = CourtComment::factory(3)->create([
                'court_id' => $court->id,
                'user_id'  => $users->random()->id,
            ]);

            // 1-2 replies on each top-level comment
            $topLevel->each(function (CourtComment $comment) use ($users) {
                CourtComment::factory(rand(1, 2))->replyTo($comment)->create([
                    'user_id' => $users->random()->id,
                ]);
            });
        });
    }
}
