<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameMessageController extends Controller
{
    public function store(Request $request, Game $game)
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:1000'],
        ]);

        $game->messages()->create([
            'user_id' => Auth::id(),
            'body'    => $data['body'],
        ]);

        return back();
    }

    public function destroy(GameMessage $message)
    {
        if ($message->user_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return back();
    }
}
