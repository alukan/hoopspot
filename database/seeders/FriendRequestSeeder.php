<?php

namespace Database\Seeders;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendRequestSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $users = User::all();
        $pairs = collect();

        // Generate 30 unique directional pairs
        $attempts = 0;
        while ($pairs->count() < 30 && $attempts < 300) {
            $attempts++;
            [$a, $b] = $users->random(2)->values();
            $key = $a->id . '-' . $b->id;

            if ($pairs->has($key)) {
                continue;
            }

            $pairs->put($key, true);

            FriendRequest::create([
                'inviter_id' => $a->id,
                'invitee_id' => $b->id,
                'status'     => fake()->randomElement(['pending', 'accepted', 'rejected']),
            ]);
        }
    }
}
