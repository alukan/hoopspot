<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Court;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $city = City::findOrFail($request->query('city'));

        $query = Game::whereHas('court', fn ($q) => $q->where('city_id', $city->id))
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

        return view('games.index', compact('city', 'games', 'levels', 'coverages', 'rimTypes', 'sort'));
    }

    public function show(Game $game)
    {
        $game->load(['court.city', 'attendees.user']);

        return view('games.show', compact('game'));
    }
}
