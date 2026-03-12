<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\City;
use App\Models\Court;
use App\Models\FriendRequest;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $city = City::findOrFail($request->query('city'));

        $query = Game::whereHas('court', fn ($q) => $q->where('city_id', $city->id)->where('status', 'active'))
            ->with(['court'])
            ->withCount('attendees')
            ->where('scheduled_at', '>=', now());

        if ($request->filled('level')) {
            $query->whereIn('level', (array) $request->query('level'));
        }

        if ($request->filled('coverage')) {
            $query->whereHas('court', fn ($q) => $q->whereIn('coverage', (array) $request->query('coverage')));
        }

        if ($request->filled('rim_type')) {
            $query->whereHas('court', fn ($q) => $q->whereIn('rim_type', (array) $request->query('rim_type')));
        }

        $sort = $request->query('sort', 'soonest');

        if ($sort === 'popular') {
            $query->orderByDesc('attendees_count');
        } else {
            $query->orderBy('scheduled_at');
        }

        $games     = $query->get();
        $levels    = Game::LEVELS;
        $coverages = Court::COVERAGES;
        $rimTypes  = Court::RIM_TYPES;

        $friendGameCounts = collect();
        if (Auth::check() && $games->isNotEmpty()) {
            $sentIds     = FriendRequest::where('inviter_id', Auth::id())->where('status', 'accepted')->pluck('invitee_id');
            $receivedIds = FriendRequest::where('invitee_id', Auth::id())->where('status', 'accepted')->pluck('inviter_id');
            $friendIds   = $sentIds->merge($receivedIds);

            if ($friendIds->isNotEmpty()) {
                $friendGameCounts = Attendee::whereIn('game_id', $games->pluck('id'))
                    ->whereIn('user_id', $friendIds)
                    ->selectRaw('game_id, count(*) as count')
                    ->groupBy('game_id')
                    ->pluck('count', 'game_id');
            }
        }

        return view('games.index', compact('city', 'games', 'levels', 'coverages', 'rimTypes', 'sort', 'friendGameCounts'));
    }

    public function show(Game $game)
    {
        $game->load([
            'court.city',
            'attendees.user',
            'messages' => fn ($q) => $q->with('user')->oldest(),
        ]);

        $isAttendee = Auth::check() && $game->attendees->contains('user_id', Auth::id());
        $isCreator  = Auth::check() && $game->creator_id === Auth::id();

        $friendStatuses = [];
        if (Auth::check()) {
            $otherIds = $game->attendees->pluck('user_id')->reject(fn ($id) => $id === Auth::id());

            $requests = FriendRequest::where(function ($q) use ($otherIds) {
                $q->where('inviter_id', Auth::id())->whereIn('invitee_id', $otherIds);
            })->orWhere(function ($q) use ($otherIds) {
                $q->where('invitee_id', Auth::id())->whereIn('inviter_id', $otherIds);
            })->get();

            foreach ($otherIds as $id) {
                $req = $requests->first(fn ($r) => $r->inviter_id === $id || $r->invitee_id === $id);
                if (! $req) {
                    $friendStatuses[$id] = 'none';
                } elseif ($req->status === 'accepted') {
                    $friendStatuses[$id] = 'friends';
                } elseif ($req->inviter_id === Auth::id()) {
                    $friendStatuses[$id] = 'sent';
                } else {
                    $friendStatuses[$id] = 'incoming';
                }
            }
        }

        $goingFriends = $game->attendees->filter(fn ($a) => ($friendStatuses[$a->user_id] ?? '') === 'friends');

        return view('games.show', compact('game', 'isAttendee', 'isCreator', 'friendStatuses', 'goingFriends'));
    }

    public function join(Game $game)
    {
        if ($game->scheduled_at->isPast()) {
            return back()->with('error', 'This game has already passed.');
        }

        $alreadyIn = Attendee::where('game_id', $game->id)->where('user_id', Auth::id())->exists();
        if ($alreadyIn) {
            return back();
        }

        Attendee::create(['game_id' => $game->id, 'user_id' => Auth::id()]);

        return back()->with('success', 'You joined the game!');
    }

    public function leave(Game $game)
    {
        if ($game->creator_id === Auth::id()) {
            return back()->with('error', "Hosts can't leave their own game.");
        }

        Attendee::where('game_id', $game->id)->where('user_id', Auth::id())->delete();

        return back()->with('success', 'You left the game.');
    }

    public function destroy(Game $game)
    {
        abort_unless(Auth::user()->is_admin, 403);

        $court = $game->court;
        $game->delete();

        return redirect()->route('courts.show', $court)->with('success', 'Game deleted.');
    }

    public function create(Request $request)
    {
        $courtQuery = Court::where('status', 'active')->with('city')->orderBy('name');

        $city = null;
        if ($request->filled('city')) {
            $city = City::find($request->query('city'));
            if ($city) {
                $courtQuery->where('city_id', $city->id);
            }
        }

        $courts  = $courtQuery->get();
        $courtId = $request->query('court');
        $levels  = Game::LEVELS;

        return view('games.create', compact('courts', 'courtId', 'levels', 'city'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'court_id'     => ['required', 'exists:courts,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'title'        => ['nullable', 'string', 'max:100'],
            'description'  => ['nullable', 'string', 'max:500'],
            'level'        => ['nullable', 'in:' . implode(',', Game::LEVELS)],
        ]);

        $court = Court::findOrFail($data['court_id']);
        if ($court->status !== 'active') {
            abort(422);
        }

        $game = Game::create([
            ...$data,
            'creator_id' => Auth::id(),
        ]);

        Attendee::create([
            'game_id' => $game->id,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('games.show', $game)->with('success', 'Game scheduled!');
    }
}
