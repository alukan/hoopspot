<?php

namespace App\Http\Controllers;

use App\Models\FriendRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectMessageController extends Controller
{
    public function show(FriendRequest $friendRequest)
    {
        $this->authorizeParticipant($friendRequest);

        $friendRequest->load('messages.user', 'inviter', 'invitee');

        $friend = $friendRequest->inviter_id === Auth::id()
            ? $friendRequest->invitee
            : $friendRequest->inviter;

        return view('messages.show', compact('friendRequest', 'friend'));
    }

    public function store(Request $request, FriendRequest $friendRequest)
    {
        $this->authorizeParticipant($friendRequest);

        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $friendRequest->messages()->create([
            'user_id' => Auth::id(),
            'body'    => $data['body'],
        ]);

        return back();
    }

    private function authorizeParticipant(FriendRequest $friendRequest): void
    {
        $id = Auth::id();

        if ($friendRequest->status !== 'accepted' ||
            ($friendRequest->inviter_id !== $id && $friendRequest->invitee_id !== $id)) {
            abort(403);
        }
    }
}
