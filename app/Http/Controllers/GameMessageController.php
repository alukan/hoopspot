<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameMessageController extends Controller
{
    public function store(Request $request, Game $game)
    {
        if (! $game->attendees()->where('user_id', Auth::id())->exists()) {
            abort(403);
        }

        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $game->messages()->create([
            'user_id' => Auth::id(),
            'body'    => $data['body'],
        ]);

        return back();
    }

    public function destroy(Message $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return back();
    }
}
