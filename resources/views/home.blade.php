@extends('layouts.app')

@section('title', 'Find Pickup Games Near You')

@section('content')

{{-- Hero --}}
<section class="py-24 px-6">
    <div class="max-w-2xl mx-auto text-center">

        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-500/10 ring-1 ring-orange-500/30 mb-6">
            <svg class="w-8 h-8 text-orange-500" viewBox="0 0 24 24" fill="currentColor">
                <circle cx="12" cy="12" r="10"/>
                <path d="M12 2a10 10 0 0 1 0 20M12 2a10 10 0 0 0 0 20M2 12h20M12 2c2.5 2.5 4 6 4 10s-1.5 7.5-4 10M12 2C9.5 4.5 8 8 8 12s1.5 7.5 4 10" stroke="currentColor" stroke-width="1.2" fill="none"/>
            </svg>
        </div>

        <h1 class="text-5xl font-bold tracking-tight mb-4">
            Find your next <span class="text-orange-500">run</span>
        </h1>

        <p class="text-lg text-gray-400 mb-3 leading-relaxed">
            HoopSpot connects basketball players with pickup games happening right in their city.
        </p>
        <p class="text-gray-500 mb-12 leading-relaxed">
            Discover courts, join games, leave reviews, and play with people in your neighbourhood.
            No memberships. No sign-ups required to browse. Just hoop.
        </p>

        {{-- City picker --}}
        <div class="bg-gray-900 border border-white/10 rounded-2xl p-6 max-w-md mx-auto shadow-xl">
            <p class="text-sm text-gray-400 mb-3 text-left font-medium">Select your city</p>

            <select
                id="city-select"
                class="w-full bg-gray-800 border border-white/10 text-white rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 mb-4"
            >
                <option value="" disabled selected>Choose a city…</option>
                <option value="1">New York</option>
                <option value="2">Los Angeles</option>
                <option value="3">Chicago</option>
                <option value="4">Houston</option>
                <option value="5">Phoenix</option>
                <option value="6">Philadelphia</option>
                <option value="7">San Antonio</option>
                <option value="8">Dallas</option>
                <option value="9">Toronto</option>
                <option value="10">Miami</option>
            </select>

            <button
                type="button"
                class="w-full bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors cursor-pointer"
            >
                See Courts
            </button>
        </div>

    </div>
</section>

{{-- Features strip --}}
<section class="border-t border-white/10 py-16 px-6">
    <div class="max-w-4xl mx-auto grid sm:grid-cols-3 gap-8 text-center">

        <div>
            <div class="text-2xl font-bold text-orange-500 mb-1">Courts</div>
            <p class="text-sm text-gray-500">Browse and rate outdoor and indoor courts across your city.</p>
        </div>

        <div>
            <div class="text-2xl font-bold text-orange-500 mb-1">Games</div>
            <p class="text-sm text-gray-500">See scheduled pickup games and join with one tap.</p>
        </div>

        <div>
            <div class="text-2xl font-bold text-orange-500 mb-1">Friends</div>
            <p class="text-sm text-gray-500">Follow people you know and see where they're playing.</p>
        </div>

    </div>
</section>

@endsection
