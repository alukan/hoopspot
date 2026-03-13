<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Court;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
    public function index(Request $request)
    {
        $city = City::findOrFail($request->query('city'));

        $query = Court::where('city_id', $city->id)->where('status', 'active')->with('city');

        if ($request->filled('coverage')) {
            $query->whereIn('coverage', (array) $request->query('coverage'));
        }

        if ($request->filled('rim_type')) {
            $query->whereIn('rim_type', (array) $request->query('rim_type'));
        }

        $courts    = $query->orderBy('name')->get();
        $coverages = Court::COVERAGES;
        $rimTypes  = Court::RIM_TYPES;

        $pendingCourts = collect();
        if (Auth::user()?->is_admin) {
            $pendingCourts = Court::where('city_id', $city->id)
                ->where('status', 'pending')
                ->orderBy('name')
                ->get();
        }

        return view('courts.index', compact('city', 'courts', 'coverages', 'rimTypes', 'pendingCourts'));
    }

    public function create(Request $request)
    {
        $cities = City::orderBy('name')->get();
        $cityId = $request->query('city');

        return view('courts.create', compact('cities', 'cityId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'city_id'     => ['required', 'exists:cities,id'],
            'name'        => ['required', 'string', 'max:255'],
            'address'     => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'coverage'    => ['required', 'in:' . implode(',', Court::COVERAGES)],
            'rim_type'    => ['required', 'in:' . implode(',', Court::RIM_TYPES)],
            'images'      => ['nullable', 'array', 'max:5'],
            'images.*'    => ['image', 'mimes:jpeg,png,webp', 'max:4096'],
        ]);

        $court = Court::create([
            'city_id'     => $data['city_id'],
            'name'        => $data['name'],
            'address'     => $data['address'],
            'description' => $data['description'] ?? null,
            'coverage'    => $data['coverage'],
            'rim_type'    => $data['rim_type'],
            'creator_id'  => auth()->id(),
        ]);

        $court->status = 'pending';

        if ($request->hasFile('images')) {
            $disk = config('filesystems.disks.s3.bucket') ? 's3' : 'public';
            $urls = [];
            foreach ($request->file('images') as $file) {
                $path = $file->store("courts/{$court->id}", $disk);
                if ($path) {
                    $urls[] = Storage::disk($disk)->url($path);
                }
            }
            $court->images = $urls ?: null;
        }

        $court->save();

        return redirect()->route('home')
            ->with('success', 'Court submitted! It will appear once approved by an admin.');
    }

    public function show(Court $court)
    {
        if ($court->status === 'pending' && auth()->id() !== $court->creator_id && ! Auth::user()?->is_admin) {
            abort(404);
        }

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

    public function destroy(Court $court)
    {
        abort_unless(Auth::user()->is_admin, 403);

        $cityId = $court->city_id;
        $court->delete();

        return redirect()->route('courts.index', ['city' => $cityId])->with('success', 'Court deleted.');
    }

    public function approve(Court $court)
    {
        abort_unless(Auth::user()->is_admin, 403);

        $court->status = 'active';
        $court->save();

        return back()->with('success', 'Court approved.');
    }
}
