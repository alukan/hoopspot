@extends('layouts.app')

@section('title', $court->name)

@section('content')

<div class="max-w-5xl mx-auto px-6 py-10">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('courts.index', ['city' => $court->city_id]) }}" class="hover:text-white transition-colors">{{ $court->city->name }}</a>
        <span>/</span>
        <span class="text-gray-300">{{ $court->name }}</span>
    </div>

    {{-- Title row --}}
    <div class="flex flex-wrap items-start gap-4 mb-6">
        <div class="flex-1 min-w-0">
            <h1 class="text-3xl font-bold text-white mb-1">{{ $court->name }}</h1>
            @if ($court->address)
                <p class="text-gray-400 text-sm">{{ $court->address }}, {{ $court->city->name }}</p>
            @endif
        </div>
        <div class="flex items-center gap-2 shrink-0 pt-1">
            <span @class([
                'text-xs font-semibold px-3 py-1.5 rounded-full',
                'bg-sky-500/10 text-sky-400 ring-1 ring-sky-500/20'       => $court->coverage === 'indoor',
                'bg-green-500/10 text-green-400 ring-1 ring-green-500/20' => $court->coverage === 'outdoor',
            ])>{{ ucfirst($court->coverage) }}</span>
            <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-gray-800 text-gray-400 ring-1 ring-white/10 capitalize">
                {{ str_replace('_', ' ', $court->rim_type) }} rim
            </span>
        </div>
    </div>

    {{-- Photo gallery --}}
    <div class="grid grid-cols-3 gap-2 rounded-xl overflow-hidden mb-10 h-64">
        <div class="col-span-2 overflow-hidden">
            <img
                src="https://picsum.photos/seed/court{{ $court->id }}a/800/520"
                alt="{{ $court->name }}"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
            >
        </div>
        <div class="flex flex-col gap-2">
            <div class="flex-1 overflow-hidden">
                <img
                    src="https://picsum.photos/seed/court{{ $court->id }}b/400/260"
                    alt="{{ $court->name }}"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                >
            </div>
            <div class="flex-1 overflow-hidden">
                <img
                    src="https://picsum.photos/seed/court{{ $court->id }}c/400/260"
                    alt="{{ $court->name }}"
                    class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                >
            </div>
        </div>
    </div>

    {{-- Body: two columns --}}
    <div class="grid lg:grid-cols-3 gap-8">

        {{-- Main column --}}
        <div class="lg:col-span-2 flex flex-col gap-10">

            {{-- Description --}}
            @if ($court->description)
                <div>
                    <h2 class="text-lg font-semibold mb-2">About this court</h2>
                    <p class="text-gray-400 leading-relaxed">{{ $court->description }}</p>
                </div>
            @endif

            {{-- Upcoming games --}}
            <div>
                <h2 class="text-lg font-semibold mb-4">
                    Upcoming Games
                    @if ($upcomingGames->isNotEmpty())
                        <span class="ml-2 text-sm font-normal text-orange-500">{{ $upcomingGames->count() }} scheduled</span>
                    @endif
                </h2>

                @if ($upcomingGames->isEmpty())
                    <p class="text-sm text-gray-500">No upcoming games scheduled at this court.</p>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach ($upcomingGames as $game)
                            <div class="bg-gray-900 border border-white/10 rounded-xl px-5 py-4 flex items-center justify-between gap-4">
                                <div>
                                    <div class="text-sm font-semibold text-white">
                                        {{ \Carbon\Carbon::parse($game->scheduled_at)->format('D, M j') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        {{ \Carbon\Carbon::parse($game->scheduled_at)->format('g:i A') }}
                                    </div>
                                    @if ($game->description)
                                        <div class="text-sm text-gray-400 mt-1">{{ $game->description }}</div>
                                    @endif
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-sm font-semibold text-orange-500">{{ $game->attendees_count }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::plural('player', $game->attendees_count) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Comments --}}
            <div>
                <h2 class="text-lg font-semibold mb-4">
                    Comments
                    @if ($court->comments->isNotEmpty())
                        <span class="ml-2 text-sm font-normal text-gray-500">{{ $court->comments->count() }}</span>
                    @endif
                </h2>

                @if ($court->comments->isEmpty())
                    <p class="text-sm text-gray-500">No comments yet. Be the first to leave a review.</p>
                @else
                    <div class="flex flex-col gap-6">
                        @foreach ($court->comments as $comment)
                            <div>
                                {{-- Top-level comment --}}
                                <div class="flex gap-3">
                                    <div class="w-8 h-8 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-sm font-bold shrink-0">
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-baseline gap-2 mb-1">
                                            <span class="text-sm font-semibold text-white">{{ $comment->user->name }}</span>
                                            <span class="text-xs text-gray-600">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-300 leading-relaxed">{{ $comment->body }}</p>
                                    </div>
                                </div>

                                {{-- Replies --}}
                                @if ($comment->replies->isNotEmpty())
                                    <div class="ml-11 mt-4 flex flex-col gap-4 border-l border-white/10 pl-4">
                                        @foreach ($comment->replies as $reply)
                                            <div class="flex gap-3">
                                                <div class="w-7 h-7 rounded-full bg-gray-700 text-gray-300 flex items-center justify-center text-xs font-bold shrink-0">
                                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-baseline gap-2 mb-1">
                                                        <span class="text-sm font-semibold text-white">{{ $reply->user->name }}</span>
                                                        <span class="text-xs text-gray-600">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-400 leading-relaxed">{{ $reply->body }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="flex flex-col gap-4">

            <div class="bg-gray-900 border border-white/10 rounded-xl p-5 flex flex-col gap-4">
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-widest">Details</h3>

                <div class="flex flex-col gap-3 text-sm">
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">City</span>
                        <span class="text-white font-medium text-right">{{ $court->city->name }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Coverage</span>
                        <span class="text-white font-medium capitalize">{{ $court->coverage }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Rim type</span>
                        <span class="text-white font-medium capitalize">{{ str_replace('_', ' ', $court->rim_type) }}</span>
                    </div>
                    @if ($pastGames->isNotEmpty())
                        <div class="flex justify-between gap-2">
                            <span class="text-gray-500">Past games</span>
                            <span class="text-white font-medium">{{ $pastGames->count() }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Added by</span>
                        <span class="text-white font-medium text-right">{{ $court->creator->name }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-gray-500">Added</span>
                        <span class="text-white font-medium">{{ $court->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
