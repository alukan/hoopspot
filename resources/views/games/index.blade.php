@extends('layouts.app')

@section('title', 'Games in ' . $city->name)

@section('content')

@php
$levelColors = [
    'beginner'     => 'bg-green-500/10 text-green-400 ring-1 ring-green-500/20',
    'intermediate' => 'bg-blue-500/10 text-blue-400 ring-1 ring-blue-500/20',
    'advanced'     => 'bg-orange-500/10 text-orange-400 ring-1 ring-orange-500/20',
    'pro'          => 'bg-purple-500/10 text-purple-400 ring-1 ring-purple-500/20',
];
@endphp

<div class="max-w-5xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-white transition-colors text-sm">← Cities</a>
        <span class="text-gray-700">/</span>
        <h1 class="text-2xl font-bold">Games in <span class="text-orange-500">{{ $city->name }}</span></h1>
        <div class="ml-auto flex items-center gap-3">
            <span class="text-sm text-gray-500">{{ $games->count() }} {{ Str::plural('game', $games->count()) }}</span>
            @auth
                <a href="{{ route('games.create') }}" class="bg-orange-500 hover:bg-orange-400 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                    + Schedule a game
                </a>
            @endauth
        </div>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('games.index') }}" class="bg-gray-900 border border-white/10 rounded-xl p-5 mb-8">
        <input type="hidden" name="city" value="{{ $city->id }}">
        <input type="hidden" name="sort" value="{{ $sort }}">

        <div class="flex flex-wrap gap-8">

            {{-- Skill level --}}
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-widest mb-3 font-semibold">Skill Level</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($levels as $option)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                name="level[]"
                                value="{{ $option }}"
                                {{ in_array($option, (array) request('level', [])) ? 'checked' : '' }}
                                class="accent-orange-500 w-4 h-4 rounded"
                            >
                            <span class="text-sm text-gray-300 capitalize">{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Coverage --}}
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-widest mb-3 font-semibold">Coverage</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($coverages as $option)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                name="coverage[]"
                                value="{{ $option }}"
                                {{ in_array($option, (array) request('coverage', [])) ? 'checked' : '' }}
                                class="accent-orange-500 w-4 h-4 rounded"
                            >
                            <span class="text-sm text-gray-300 capitalize">{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Rim type --}}
            <div>
                <p class="text-xs text-gray-400 uppercase tracking-widest mb-3 font-semibold">Rim Type</p>
                <div class="flex flex-wrap gap-2">
                    @foreach ($rimTypes as $option)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="checkbox"
                                name="rim_type[]"
                                value="{{ $option }}"
                                {{ in_array($option, (array) request('rim_type', [])) ? 'checked' : '' }}
                                class="accent-orange-500 w-4 h-4 rounded"
                            >
                            <span class="text-sm text-gray-300 capitalize">{{ str_replace('_', ' ', $option) }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-end gap-2 ml-auto">
                @if (request('level') || request('coverage') || request('rim_type'))
                    <a href="{{ route('games.index', ['city' => $city->id, 'sort' => $sort]) }}" class="text-sm text-gray-400 hover:text-white transition-colors px-4 py-2">
                        Clear
                    </a>
                @endif
                <button type="submit" class="bg-orange-500 hover:bg-orange-400 text-white text-sm font-semibold px-5 py-2 rounded-lg transition-colors cursor-pointer">
                    Apply
                </button>
            </div>

        </div>
    </form>

    {{-- Sort tabs --}}
    <div class="flex items-center gap-2 mb-6">
        <span class="text-xs text-gray-500 uppercase tracking-widest font-semibold mr-1">Sort by</span>
        <a
            href="{{ request()->fullUrlWithQuery(['sort' => 'soonest']) }}"
            @class([
                'text-sm px-4 py-1.5 rounded-full font-medium transition-colors',
                'bg-orange-500 text-white'        => $sort === 'soonest',
                'bg-gray-800 text-gray-400 hover:text-white' => $sort !== 'soonest',
            ])
        >Soonest</a>
        <a
            href="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}"
            @class([
                'text-sm px-4 py-1.5 rounded-full font-medium transition-colors',
                'bg-orange-500 text-white'        => $sort === 'popular',
                'bg-gray-800 text-gray-400 hover:text-white' => $sort !== 'popular',
            ])
        >Most Popular</a>
    </div>

    {{-- Games list --}}
    @if ($games->isEmpty())
        <div class="text-center py-20 text-gray-500">
            <p class="text-lg mb-2">No upcoming games match your filters.</p>
            <a href="{{ route('games.index', ['city' => $city->id]) }}" class="text-orange-500 hover:text-orange-400 text-sm transition-colors">Clear filters</a>
        </div>
    @else
        <div class="flex flex-col gap-3">
            @foreach ($games as $game)
                <a href="{{ route('games.show', $game) }}" class="bg-gray-900 border border-white/10 rounded-xl px-5 py-4 flex items-center gap-5 hover:border-orange-500/40 transition-colors">

                    {{-- Date block --}}
                    <div class="shrink-0 w-14 text-center">
                        <div class="text-xl font-bold text-white leading-none">
                            {{ \Carbon\Carbon::parse($game->scheduled_at)->format('j') }}
                        </div>
                        <div class="text-xs text-gray-500 uppercase tracking-wide mt-0.5">
                            {{ \Carbon\Carbon::parse($game->scheduled_at)->format('M') }}
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            {{ \Carbon\Carbon::parse($game->scheduled_at)->format('g:i A') }}
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div class="w-px self-stretch bg-white/10 shrink-0"></div>

                    {{-- Main info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1.5">
                            @if ($game->level)
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full capitalize {{ $levelColors[$game->level] }}">
                                    {{ $game->level }}
                                </span>
                            @endif
                            <span @class([
                                'text-xs font-medium px-2.5 py-1 rounded-full',
                                'bg-sky-500/10 text-sky-400 ring-1 ring-sky-500/20'       => $game->court->coverage === 'indoor',
                                'bg-green-500/10 text-green-400 ring-1 ring-green-500/20' => $game->court->coverage === 'outdoor',
                            ])>{{ ucfirst($game->court->coverage) }}</span>
                            <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-800 text-gray-400 ring-1 ring-white/10 capitalize">
                                {{ str_replace('_', ' ', $game->court->rim_type) }} rim
                            </span>
                        </div>

                        <span class="font-semibold text-white leading-snug">
                            {{ $game->court->name }}
                        </span>
                        @if ($game->court->address)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $game->court->address }}</p>
                        @endif
                        @if ($game->description)
                            <p class="text-sm text-gray-400 mt-1 leading-snug">{{ $game->description }}</p>
                        @endif
                    </div>

                    {{-- Attendees --}}
                    <div class="shrink-0 text-right">
                        <div class="text-lg font-bold text-orange-500">{{ $game->attendees_count }}</div>
                        <div class="text-xs text-gray-500">{{ Str::plural('player', $game->attendees_count) }}</div>
                    </div>

                </a>
            @endforeach
        </div>
    @endif

</div>

@endsection
