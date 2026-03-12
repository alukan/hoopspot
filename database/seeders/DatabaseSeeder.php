<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CitySeeder::class,
            CourtSeeder::class,
            GameSeeder::class,
            AttendeeSeeder::class,
            FriendRequestSeeder::class,
            CourtCommentSeeder::class,
        ]);
    }
}
