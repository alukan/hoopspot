@extends('layouts.app')

@section('title', \Carbon\Carbon::parse($game->scheduled_at)->format('M j') . ' · ' . $game->court->name)

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

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('games.index', ['city' => $game->court->city->id]) }}" class="hover:text-white transition-colors">{{ $game->court->city->name }}</a>
        <span>/</span>
        <span class="text-gray-300">{{ \Carbon\Carbon::parse($game->scheduled_at)->format('D, M j · g:i A') }}</span>
    </div>

    {{-- Header --}}
    <div class="flex flex-wrap items-start gap-4 mb-8">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                @if ($game->level)
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full capitalize {{ $levelColors[$game->level] }}">
                        {{ $game->level }}
                    </span>
                @endif
                <span @class([
                    'text-xs font-semibold px-3 py-1.5 rounded-full',
                    'bg-sky-500/10 text-sky-400 ring-1 ring-sky-500/20'       => $game->court->coverage === 'indoor',
                    'bg-green-500/10 text-green-400 ring-1 ring-green-500/20' => $game->court->coverage === 'outdoor',
                ])>{{ ucfirst($game->court->coverage) }}</span>
                <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-800 text-gray-400 ring-1 ring-white/10 capitalize">
                    {{ str_replace('_', ' ', $game->court->rim_type) }} rim
                </span>
            </div>
            <h1 class="text-3xl font-bold text-white mb-1">
                {{ \Carbon\Carbon::parse($game->scheduled_at)->format('l, F j') }}
            </h1>
            <p class="text-gray-400">{{ \Carbon\Carbon::parse($game->scheduled_at)->format('g:i A') }}</p>
            @if ($game->description)
                <p class="text-gray-400 mt-3 leading-relaxed">{{ $game->description }}</p>
            @endif
        </div>
        <div class="shrink-0 text-right pt-1">
            <div class="text-3xl font-bold text-orange-500">{{ $game->attendees->count() }}</div>
            <div class="text-sm text-gray-500">{{ Str::plural('player', $game->attendees->count()) }}</div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">

        {{-- Main column: attendees --}}
        <div class="lg:col-span-2">
            <h2 class="text-lg font-semibold mb-4">
                Players
                @if ($game->attendees->isNotEmpty())
                    <span class="ml-2 text-sm font-normal text-gray-500">{{ $game->attendees->count() }}</span>
                @endif
            </h2>

            @if ($game->attendees->isEmpty())
                <p class="text-sm text-gray-500">No players signed up yet.</p>
            @else
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach ($game->attendees as $attendee)
                        <div class="bg-gray-900 border border-white/10 rounded-xl px-4 py-3 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-sm font-bold shrink-0">
                                {{ strtoupper(substr($attendee->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-white truncate">{{ $attendee->user->name }}</div>
                                @if ($attendee->user->level)
                                    <span class="inline-block mt-0.5 text-xs font-medium px-2 py-0.5 rounded-full capitalize {{ $levelColors[$attendee->user->level] }}">
                                        {{ $attendee->user->level }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-600">No level set</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Sidebar: court details --}}
        <div class="flex flex-col gap-4">
            <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-widest mb-4">Court</h3>

                <div class="mb-4">
                    <a href="{{ route('courts.show', $game->court) }}" class="font-semibold text-white hover:text-orange-400 transition-colors leading-snug">
                        {{ $game->court->name }}
                    </a>
                    @if ($game->court->address)
                        <p class="text-sm text-gray-500 mt-0.5">{{ $game->court->address }}</p>
                    @endif
                </div>

                <div class="flex flex-col gap-2 text-sm mb-5">
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">City</span>
                        <span class="text-white font-medium">{{ $game->court->city->name }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Coverage</span>
                        <span class="text-white font-medium capitalize">{{ $game->court->coverage }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Rim type</span>
                        <span class="text-white font-medium capitalize">{{ str_replace('_', ' ', $game->court->rim_type) }}</span>
                    </div>
                </div>

                <a
                    href="{{ route('courts.show', $game->court) }}"
                    class="block w-full text-center bg-gray-800 hover:bg-gray-700 border border-white/10 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors"
                >
                    View Court →
                </a>
            </div>
        </div>

    </div>

</div>

@endsection
