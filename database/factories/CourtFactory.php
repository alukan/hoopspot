<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Court;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Court>
 */
class CourtFactory extends Factory
{
    public function definition(): array
    {
        $suffixes = ['Court', 'Park', 'Playground', 'Rec Center', 'Hardwood', 'Courts'];

        return [
            'city_id'     => City::factory(),
            'creator_id'  => User::factory(),
            'name'        => fake()->lastName() . ' ' . fake()->randomElement($suffixes),
            'address'     => fake()->streetAddress(),
            'description' => fake()->optional(0.7)->sentence(),
        ];
    }
}
