<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user()->load([
            'courts' => fn ($q) => $q->with('city')->withCount('games')->orderBy('name'),
        ]);

        $upcomingGames = $user->games()->with('court')->withCount('attendees')
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->get();

        $pastGames = $user->games()->with('court')->withCount('attendees')
            ->where('scheduled_at', '<', now())
            ->orderByDesc('scheduled_at')
            ->get();

        $sentIds     = FriendRequest::where('inviter_id', $user->id)->where('status', 'accepted')->pluck('invitee_id');
        $receivedIds = FriendRequest::where('invitee_id', $user->id)->where('status', 'accepted')->pluck('inviter_id');
        $friends     = User::whereIn('id', $sentIds->merge($receivedIds))->orderBy('name')->get();

        $friendRequests = FriendRequest::where(function ($q) use ($user) {
            $q->where('inviter_id', $user->id)->orWhere('invitee_id', $user->id);
        })->where('status', 'accepted')->with('inviter', 'invitee')->get();

        $sentRequests = FriendRequest::where('inviter_id', $user->id)
            ->where('status', 'pending')
            ->with('invitee')
            ->latest()
            ->get();

        $incomingRequests = FriendRequest::where('invitee_id', $user->id)
            ->where('status', 'pending')
            ->with('inviter')
            ->latest()
            ->get();

        return view('profile.show', compact('user', 'upcomingGames', 'pastGames', 'friends', 'friendRequests', 'sentRequests', 'incomingRequests'));
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
