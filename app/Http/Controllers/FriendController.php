<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    public function toggle(User $user)
    {
        $authId = Auth::id();

        if ($user->id === $authId) {
            return back();
        }

        // Accept an incoming pending request
        $incoming = FriendRequest::where('inviter_id', $user->id)
            ->where('invitee_id', $authId)
            ->where('status', 'pending')
            ->first();

        if ($incoming) {
            $incoming->update(['status' => 'accepted']);
            return back()->with('success', "You are now friends with {$user->name}.");
        }

        // Already sent or already friends — do nothing
        $existing = FriendRequest::where('inviter_id', $authId)
            ->where('invitee_id', $user->id)
            ->first();

        if ($existing) {
            return back();
        }

        FriendRequest::create([
            'inviter_id' => $authId,
            'invitee_id' => $user->id,
            'status'     => 'pending',
        ]);

        return back()->with('success', "Friend request sent to {$user->name}.");
    }

    public function destroy(User $user)
    {
        $authId = Auth::id();

        FriendRequest::where(function ($q) use ($authId, $user) {
            $q->where('inviter_id', $authId)->where('invitee_id', $user->id);
        })->orWhere(function ($q) use ($authId, $user) {
            $q->where('inviter_id', $user->id)->where('invitee_id', $authId);
        })->delete();

        return back()->with('success', "Removed {$user->name} from friends.");
    }
}
