<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $cities = [
            'New York',
            'Los Angeles',
            'Chicago',
            'Houston',
            'Philadelphia',
            'Toronto',
            'Miami',
            'Dallas',
            'Atlanta',
            'Phoenix',
        ];

        foreach ($cities as $name) {
            City::create(['name' => $name]);
        }
    }
}
