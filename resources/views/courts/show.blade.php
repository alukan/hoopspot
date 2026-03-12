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
            @auth
                @if (Auth::user()->is_admin)
                    <form method="POST" action="{{ route('courts.destroy', $court) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-500/70 hover:text-red-400 transition-colors cursor-pointer">Delete court</button>
                    </form>
                @endif
            @endauth
        </div>
    </div>

    {{-- Pending approval notice --}}
    @if ($court->status === 'pending')
        <div class="flex items-center justify-between gap-4 bg-yellow-500/5 border border-yellow-500/20 rounded-xl px-5 py-3 mb-6">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-yellow-500/10 text-yellow-400 ring-1 ring-yellow-500/20">Pending</span>
                <span class="text-sm text-gray-400">This court is awaiting admin approval and is not yet publicly visible.</span>
            </div>
            @auth
                @if (Auth::user()->is_admin)
                    <form method="POST" action="{{ route('courts.approve', $court) }}" class="shrink-0">
                        @csrf
                        <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-green-500/10 text-green-400 ring-1 ring-green-500/20 hover:bg-green-500/20 transition-colors cursor-pointer">Approve</button>
                    </form>
                @endif
            @endauth
        </div>
    @endif

    {{-- Photo gallery --}}
    @if (count($court->images ?? []) === 0)
        <div class="rounded-xl overflow-hidden mb-10 h-56 bg-gray-900 border border-white/10 flex flex-col items-center justify-center gap-2 text-gray-600">
            <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 21h18M3 3h18M3 9h18M3 15h18" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 10.5a2.25 2.25 0 114.5 0 2.25 2.25 0 01-4.5 0z" />
            </svg>
            <span class="text-sm">No photos yet</span>
        </div>

    @elseif (count($court->images ?? []) === 1)
        <div class="rounded-xl overflow-hidden mb-10 h-64">
            <img src="{{ $court->images[0] }}" alt="{{ $court->name }}" class="w-full h-full object-cover">
        </div>

    @elseif (count($court->images ?? []) === 2)
        <div class="grid grid-cols-2 gap-2 rounded-xl overflow-hidden mb-10 h-64">
            @foreach ($court->images as $img)
                <div class="overflow-hidden">
                    <img src="{{ $img }}" alt="{{ $court->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
            @endforeach
        </div>

    @else
        <div class="grid grid-cols-3 gap-2 rounded-xl overflow-hidden mb-10 h-64">
            <div class="col-span-2 overflow-hidden">
                <img src="{{ $court->images[0] }}" alt="{{ $court->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
            </div>
            <div class="flex flex-col gap-2">
                <div class="flex-1 overflow-hidden">
                    <img src="{{ $court->images[1] }}" alt="{{ $court->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                </div>
                <div class="flex-1 overflow-hidden relative">
                    <img src="{{ $court->images[2] }}" alt="{{ $court->name }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                    @if (count($court->images ?? []) > 3)
                        <div class="absolute inset-0 bg-black/60 flex items-center justify-center text-white font-semibold text-sm">
                            +{{ count($court->images ?? []) - 3 }} more
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Body: two columns --}}
    <div class="grid md:grid-cols-3 gap-8">

        {{-- Main column --}}
        <div class="md:col-span-2 flex flex-col gap-10">

            {{-- Description --}}
            @if ($court->description)
                <div>
                    <h2 class="text-lg font-semibold mb-2">About this court</h2>
                    <p class="text-gray-400 leading-relaxed">{{ $court->description }}</p>
                </div>
            @endif

            {{-- Upcoming games --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <h2 class="text-lg font-semibold">
                        Upcoming Games
                        @if ($upcomingGames->isNotEmpty())
                            <span class="ml-2 text-sm font-normal text-orange-500">{{ $upcomingGames->count() }} scheduled</span>
                        @endif
                    </h2>
                    @auth
                        <a href="{{ route('games.create', ['court' => $court->id]) }}" class="ml-auto bg-orange-500 hover:bg-orange-400 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
                            + Schedule a game
                        </a>
                    @endauth
                </div>

                @if ($upcomingGames->isEmpty())
                    <p class="text-sm text-gray-500">No upcoming games scheduled at this court.</p>
                @else
                    <div class="flex flex-col gap-3">
                        @foreach ($upcomingGames as $game)
                            <a href="{{ route('games.show', $game) }}" class="bg-gray-900 border border-white/10 rounded-xl px-5 py-4 flex items-center justify-between gap-4 hover:border-orange-500/40 transition-colors">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        @if ($game->level)
                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full capitalize {{ $levelColors[$game->level] }}">
                                                {{ $game->level }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="text-sm font-semibold text-white">
                                        @if ($game->title)
                                            {{ $game->title }}
                                        @else
                                            {{ \Carbon\Carbon::parse($game->scheduled_at)->format('D, M j') }}
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 mt-0.5">
                                        @if ($game->title){{ \Carbon\Carbon::parse($game->scheduled_at)->format('D, M j') }}&nbsp;·&nbsp;@endif{{ \Carbon\Carbon::parse($game->scheduled_at)->format('g:i A') }}
                                    </div>
                                    @if ($game->description)
                                        <div class="text-sm text-gray-400 mt-1 truncate">{{ $game->description }}</div>
                                    @endif
                                </div>
                                <div class="text-right shrink-0">
                                    <div class="text-sm font-semibold text-orange-500">{{ $game->attendees_count }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::plural('player', $game->attendees_count) }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Comments --}}
            <div>
                <h2 class="text-lg font-semibold mb-4">
                    Comments
                    @if ($court->comments->isNotEmpty())
                        <span class="ml-2 text-sm font-normal text-gray-500">{{ $court->comments->sum(fn ($c) => 1 + $c->replies->count()) }}</span>
                    @endif
                </h2>

                @if ($court->comments->isEmpty())
                    <p class="text-sm text-gray-500 mb-6">No comments yet. Be the first to leave a review.</p>
                @else
                    <div class="flex flex-col gap-6 mb-6">
                        @foreach ($court->comments as $comment)
                            <div>
                                {{-- Top-level comment --}}
                                <div class="flex gap-3">
                                    <div class="w-8 h-8 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-sm font-bold shrink-0">
                                        {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-baseline gap-2 mb-1">
                                            <span class="text-sm font-semibold text-white">{{ $comment->user->name }}</span>
                                            <span class="text-xs text-gray-600">{{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-sm text-gray-300 leading-relaxed">{{ $comment->body }}</p>
                                        <div class="flex items-center gap-3 mt-2">
                                            @auth
                                                <details>
                                                    <summary class="text-xs text-gray-500 hover:text-white transition-colors cursor-pointer list-none">Reply</summary>
                                                    <form method="POST" action="{{ route('court-comments.store', $court) }}" class="mt-2 flex gap-2">
                                                        @csrf
                                                        <input type="hidden" name="replies_to" value="{{ $comment->id }}">
                                                        <input
                                                            type="text"
                                                            name="body"
                                                            placeholder="Write a reply…"
                                                            required
                                                            maxlength="1000"
                                                            class="flex-1 bg-gray-800 border border-white/10 text-white text-sm rounded-lg px-3 py-2 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                                                        >
                                                        <button type="submit" class="shrink-0 bg-orange-500 hover:bg-orange-400 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors cursor-pointer">Post</button>
                                                    </form>
                                                </details>
                                                @if ($comment->user_id === Auth::id() || Auth::user()->is_admin)
                                                    <form method="POST" action="{{ route('court-comments.destroy', $comment) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-gray-600 hover:text-red-400 transition-colors cursor-pointer">Delete</button>
                                                    </form>
                                                @endif
                                            @endauth
                                        </div>
                                    </div>
                                </div>

                                {{-- Replies --}}
                                @if ($comment->replies->isNotEmpty())
                                    <details class="ml-11 mt-3" open>
                                        <summary class="text-xs text-gray-600 hover:text-gray-400 transition-colors cursor-pointer list-none pl-4 mb-2">
                                            ↳ {{ $comment->replies->count() }} {{ Str::plural('reply', $comment->replies->count()) }}
                                        </summary>
                                    <div class="flex flex-col gap-4 border-l-2 border-orange-500/20 pl-4">
                                        @foreach ($comment->replies as $reply)
                                            <div class="flex gap-3">
                                                <div class="w-7 h-7 rounded-full bg-gray-700 text-gray-300 flex items-center justify-center text-xs font-bold shrink-0">
                                                    {{ strtoupper(substr($reply->user->name, 0, 1)) }}
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-baseline gap-2 mb-1">
                                                        <span class="text-sm font-semibold text-white">{{ $reply->user->name }}</span>
                                                        <span class="text-xs text-gray-600">{{ $reply->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-sm text-gray-400 leading-relaxed">{{ $reply->body }}</p>
                                                    <div class="flex items-center gap-3 mt-1.5">
                                                    @auth
                                                        <details>
                                                            <summary class="text-xs text-gray-500 hover:text-white transition-colors cursor-pointer list-none">Reply</summary>
                                                            <form method="POST" action="{{ route('court-comments.store', $court) }}" class="mt-2 flex gap-2">
                                                                @csrf
                                                                <input type="hidden" name="replies_to" value="{{ $reply->id }}">
                                                                <input
                                                                    type="text"
                                                                    name="body"
                                                                    placeholder="Write a reply…"
                                                                    required
                                                                    maxlength="1000"
                                                                    class="flex-1 bg-gray-800 border border-white/10 text-white text-sm rounded-lg px-3 py-2 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                                                                >
                                                                <button type="submit" class="shrink-0 bg-orange-500 hover:bg-orange-400 text-white text-xs font-semibold px-3 py-2 rounded-lg transition-colors cursor-pointer">Post</button>
                                                            </form>
                                                        </details>
                                                        @if ($reply->user_id === Auth::id() || Auth::user()->is_admin)
                                                            <form method="POST" action="{{ route('court-comments.destroy', $reply) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-xs text-gray-600 hover:text-red-400 transition-colors cursor-pointer">Delete</button>
                                                            </form>
                                                        @endif
                                                    @endauth
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    </details>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- New comment form --}}
                @auth
                    <form method="POST" action="{{ route('court-comments.store', $court) }}" class="flex flex-col gap-2">
                        @csrf
                        <textarea
                            name="body"
                            placeholder="Leave a comment…"
                            required
                            maxlength="1000"
                            rows="3"
                            class="w-full bg-gray-900 border border-white/10 text-white text-sm rounded-xl px-4 py-3 placeholder-gray-600 resize-none focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                        ></textarea>
                        @error('body')
                            <p class="text-red-400 text-xs">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-end">
                            <button type="submit" class="bg-orange-500 hover:bg-orange-400 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors cursor-pointer">Post comment</button>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-600"><a href="{{ route('auth.login') }}" class="text-orange-500 hover:text-orange-400 transition-colors">Log in</a> to leave a comment.</p>
                @endauth
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
