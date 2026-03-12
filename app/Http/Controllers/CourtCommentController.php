<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\CourtComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourtCommentController extends Controller
{
    public function store(Request $request, Court $court)
    {
        $data = $request->validate([
            'body'       => ['required', 'string', 'max:1000'],
            'replies_to' => ['nullable', 'exists:court_comments,id'],
        ]);

        $court->comments()->create([
            'user_id'    => Auth::id(),
            'body'       => $data['body'],
            'replies_to' => $this->resolveParent($data['replies_to'] ?? null),
        ]);

        return back()->with('success', 'Comment posted.');
    }

    public function destroy(CourtComment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted.');
    }

    private function resolveParent(?int $id): ?int
    {
        if (! $id) {
            return null;
        }

        $comment = CourtComment::find($id);

        return $comment?->replies_to ?? $id;
    }
}
