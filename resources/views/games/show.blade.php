@extends('layouts.app')

@section('title', \Carbon\Carbon::parse($game->scheduled_at)->format('M j') . ' · ' . $game->court->name)

@section('content')

<div class="max-w-5xl mx-auto px-6 py-10">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-white transition-colors">Home</a>
        <span>/</span>
        <a href="{{ route('games.index', ['city' => $game->court->city->id]) }}" class="hover:text-white transition-colors">{{ $game->court->city->name }}</a>
        <span>/</span>
        <a href="{{ route('courts.show', $game->court) }}" class="hover:text-white transition-colors">{{ $game->court->name }}</a>
        <span>/</span>
        <span class="text-gray-300">{{ \Carbon\Carbon::parse($game->scheduled_at)->format('M j') }}</span>
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
                @if ($game->title)
                    {{ $game->title }}
                @else
                    {{ \Carbon\Carbon::parse($game->scheduled_at)->format('l, F j') }}
                @endif
            </h1>
            <p class="text-gray-400 text-sm">
                @if ($game->title){{ \Carbon\Carbon::parse($game->scheduled_at)->format('l, F j') }}&nbsp;·&nbsp;@endif{{ \Carbon\Carbon::parse($game->scheduled_at)->format('g:i A') }}
                &nbsp;·&nbsp;
                <a href="{{ route('courts.show', $game->court) }}" class="hover:text-white transition-colors">{{ $game->court->name }}</a>
            </p>
            @if ($game->description)
                <p class="text-gray-400 mt-3 leading-relaxed">{{ $game->description }}</p>
            @endif
        </div>
        <div class="shrink-0 text-right pt-1">
            <div class="text-3xl font-bold text-orange-500">{{ $game->attendees->count() }}</div>
            <div class="text-sm text-gray-500 mb-3">{{ Str::plural('player', $game->attendees->count()) }}</div>

            @if ($game->scheduled_at->isFuture())
                @auth
                    @if ($isCreator)
                        <span class="inline-block text-xs font-semibold px-3 py-1.5 rounded-full bg-orange-500/15 text-orange-400 ring-1 ring-orange-500/30">
                            You're hosting
                        </span>
                    @elseif ($isAttendee)
                        <form method="POST" action="{{ route('games.leave', $game) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm font-semibold px-4 py-2 rounded-lg border border-white/10 bg-gray-800 hover:bg-gray-700 text-white transition-colors cursor-pointer">
                                Leave game
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('games.join', $game) }}">
                            @csrf
                            <button type="submit" class="text-sm font-semibold px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-400 text-white transition-colors cursor-pointer">
                                Join game
                            </button>
                        </form>
                    @endif
                @else
                    <a href="{{ route('auth.login') }}" class="inline-block text-sm font-semibold px-4 py-2 rounded-lg bg-orange-500 hover:bg-orange-400 text-white transition-colors">
                        Join game
                    </a>
                @endauth
            @endif
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-8">

        {{-- Main column: attendees --}}
        <div class="md:col-span-2">
            <h2 class="text-lg font-semibold mb-4">
                Players
                @if ($game->attendees->isNotEmpty())
                    <span class="ml-2 text-sm font-normal text-gray-500">{{ $game->attendees->count() }}</span>
                @endif
            </h2>

            @if ($game->attendees->isEmpty())
                <p class="text-sm text-gray-500">No players signed up yet.</p>
            @else
                @if ($goingFriends->isNotEmpty())
                    <div class="bg-sky-500/5 border border-sky-500/15 rounded-xl px-4 py-3 mb-4">
                        <p class="text-xs font-semibold text-sky-400 uppercase tracking-widest mb-2.5">Friends going</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($goingFriends as $attendee)
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-sky-500/20 text-sky-400 flex items-center justify-center text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($attendee->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-white">{{ $attendee->user->name }}</span>
                                    @if ($attendee->user->level)
                                        <span class="text-xs font-medium px-2 py-0.5 rounded-full capitalize {{ $levelColors[$attendee->user->level] }}">{{ $attendee->user->level }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="grid sm:grid-cols-2 gap-3">
                    @foreach ($game->attendees as $attendee)
                        <div class="bg-gray-900 border border-white/10 rounded-xl px-4 py-3 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-sm font-bold shrink-0">
                                {{ strtoupper(substr($attendee->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold text-white truncate">{{ $attendee->user->name }}</span>
                                    @if ($attendee->user_id === $game->creator_id)
                                        <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full bg-orange-500/15 text-orange-400 ring-1 ring-orange-500/30">Host</span>
                                    @endif
                                    @if (isset($friendStatuses[$attendee->user_id]) && $friendStatuses[$attendee->user_id] === 'friends')
                                        <span class="shrink-0 text-xs font-semibold px-2 py-0.5 rounded-full bg-sky-500/10 text-sky-400 ring-1 ring-sky-500/20">Friend</span>
                                    @endif
                                </div>
                                @if ($attendee->user->level)
                                    <span class="inline-block mt-0.5 text-xs font-medium px-2 py-0.5 rounded-full capitalize {{ $levelColors[$attendee->user->level] }}">
                                        {{ $attendee->user->level }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-600">No level set</span>
                                @endif
                            </div>
                            @auth
                                @if ($attendee->user_id !== Auth::id())
                                    @php $fs = $friendStatuses[$attendee->user_id] ?? 'none'; @endphp
                                    @if ($fs === 'sent')
                                        <span class="shrink-0 text-xs text-gray-500">Requested</span>
                                    @elseif ($fs === 'incoming')
                                        <form method="POST" action="{{ route('friends.toggle', $attendee->user) }}" class="shrink-0">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-green-500/10 text-green-400 ring-1 ring-green-500/20 hover:bg-green-500/20 transition-colors cursor-pointer">Accept</button>
                                        </form>
                                    @elseif ($fs === 'none')
                                        <form method="POST" action="{{ route('friends.toggle', $attendee->user) }}" class="shrink-0">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-gray-800 text-gray-400 ring-1 ring-white/10 hover:text-white transition-colors cursor-pointer">+ Add</button>
                                        </form>
                                    @endif
                                @endif
                            @endauth
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Chat --}}
            <div class="mt-8">
            <h2 class="text-lg font-semibold mb-4">
                Chat
                @if ($game->messages->isNotEmpty())
                    <span class="ml-2 text-sm font-normal text-gray-500">{{ $game->messages->count() }}</span>
                @endif
            </h2>

            @if ($game->messages->isEmpty())
                <p class="text-sm text-gray-500 mb-6">No messages yet.</p>
            @else
                <div class="flex flex-col gap-4 mb-6">
                    @foreach ($game->messages as $message)
                        <div class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-sm font-bold shrink-0">
                                {{ strtoupper(substr($message->user->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-baseline gap-2 mb-1">
                                    <span class="text-sm font-semibold text-white">{{ $message->user->name }}</span>
                                    <span class="text-xs text-gray-600">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-300 leading-relaxed">{{ $message->body }}</p>
                                @auth
                                    @if ($message->user_id === Auth::id())
                                        <form method="POST" action="{{ route('game-messages.destroy', $message) }}" class="mt-1.5">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-gray-600 hover:text-red-400 transition-colors cursor-pointer">Delete</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @auth
                <form method="POST" action="{{ route('game-messages.store', $game) }}" class="flex gap-2">
                    @csrf
                    <input
                        type="text"
                        name="body"
                        placeholder="Send a message…"
                        required
                        maxlength="1000"
                        class="flex-1 bg-gray-900 border border-white/10 text-white text-sm rounded-xl px-4 py-3 placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                    >
                    <button type="submit" class="shrink-0 bg-orange-500 hover:bg-orange-400 text-white text-sm font-semibold px-4 py-3 rounded-xl transition-colors cursor-pointer">Send</button>
                </form>
                @error('body')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            @else
                <p class="text-sm text-gray-600"><a href="{{ route('auth.login') }}" class="text-orange-500 hover:text-orange-400 transition-colors">Log in</a> to send a message.</p>
            @endauth
            </div>
        </div>

        {{-- Sidebar: court details --}}
        <div class="flex flex-col gap-4">
            <div class="bg-gray-900 border border-white/10 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-300 uppercase tracking-widest mb-4">Court</h3>

                <a href="{{ route('courts.show', $game->court) }}" class="block font-semibold text-white hover:text-orange-400 transition-colors leading-snug mb-1">
                    {{ $game->court->name }}
                </a>
                @if ($game->court->address)
                    <p class="text-sm text-gray-500 mb-4">{{ $game->court->address }}, {{ $game->court->city->name }}</p>
                @else
                    <p class="text-sm text-gray-500 mb-4">{{ $game->court->city->name }}</p>
                @endif

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
