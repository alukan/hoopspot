<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Court;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function index(Request $request)
    {
        $city = City::findOrFail($request->query('city'));

        $query = Court::where('city_id', $city->id)->with('city');

        if ($request->filled('coverage')) {
            $query->whereIn('coverage', (array) $request->query('coverage'));
        }

        if ($request->filled('rim_type')) {
            $query->whereIn('rim_type', (array) $request->query('rim_type'));
        }

        $courts    = $query->orderBy('name')->get();
        $coverages = Court::COVERAGES;
        $rimTypes  = Court::RIM_TYPES;

        return view('courts.index', compact('city', 'courts', 'coverages', 'rimTypes'));
    }

    public function show(Court $court)
    {
        $court->load([
            'city',
            'creator',
            'games' => fn ($q) => $q->withCount('attendees')->orderBy('scheduled_at'),
            'comments' => fn ($q) => $q->whereNull('replies_to')->with(['user', 'replies.user'])->latest(),
        ]);

        $upcomingGames = $court->games->where('scheduled_at', '>=', now())->values();
        $pastGames     = $court->games->where('scheduled_at', '<', now())->values();

        return view('courts.show', compact('court', 'upcomingGames', 'pastGames'));
    }
}
