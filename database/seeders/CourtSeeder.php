<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $cities = City::all();
        $users  = User::all();

        $cities->each(function (City $city) use ($users) {
            Court::factory(3)->create([
                'city_id'    => $city->id,
                'creator_id' => $users->random()->id,
            ]);
        });
    }
}
