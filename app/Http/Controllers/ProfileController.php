<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load([
            'games' => fn ($q) => $q->with('court')
                ->withCount('attendees')
                ->where('scheduled_at', '>=', now())
                ->orderBy('scheduled_at'),
            'courts' => fn ($q) => $q->with('city')->withCount('games')->orderBy('name'),
        ]);

        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'level' => ['nullable', 'in:' . implode(',', User::LEVELS)],
        ]);

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profile updated.');
    }
}
