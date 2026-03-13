@extends('layouts.app')

@section('title', 'My Profile')

@section('content')


<div class="max-w-5xl mx-auto px-6 py-10">

    {{-- Profile header --}}
    <div class="flex items-start gap-5 mb-10">
        <div class="w-16 h-16 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-2xl font-bold shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <h1 class="text-2xl font-bold text-white">{{ $user->name }}</h1>
            <div class="flex items-center gap-3 mt-1.5">
                @if ($user->level)
                    <span class="text-xs font-semibold px-3 py-1 rounded-full capitalize {{ $levelColors[$user->level] }}">
                        {{ $user->level }}
                    </span>
                @else
                    <span class="text-xs text-gray-600">No level set</span>
                @endif
                <span class="text-sm text-gray-500">Member since {{ $user->created_at->format('M Y') }}</span>
            </div>
        </div>
        <a href="{{ route('profile.edit') }}" class="shrink-0 bg-gray-800 hover:bg-gray-700 border border-white/10 text-white text-sm font-semibold px-4 py-2 rounded-lg transition-colors">
            Edit profile
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-8">

        {{-- Upcoming games --}}
        <div class="lg:col-span-2">
            <h2 class="text-lg font-semibold mb-4">
                Upcoming Games
                @if ($upcomingGames->isNotEmpty())
                    <span class="ml-2 text-sm font-normal text-orange-500">{{ $upcomingGames->count() }}</span>
                @endif
            </h2>

            @if ($upcomingGames->isEmpty())
                <p class="text-sm text-gray-500">You haven't joined any upcoming games yet.</p>
            @else
                <div class="flex flex-col gap-3">
                    @foreach ($upcomingGames as $game)
                        <a href="{{ route('games.show', $game) }}" class="bg-gray-900 border border-white/10 rounded-xl px-5 py-4 flex items-center gap-5 hover:border-orange-500/40 transition-colors">

                            {{-- Date block --}}
                            <div class="shrink-0 w-12 text-center">
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

                            <div class="w-px self-stretch bg-white/10 shrink-0"></div>

                            <div class="flex-1 min-w-0">
                                @if ($game->level)
                                    <span class="inline-block mb-1 text-xs font-medium px-2.5 py-0.5 rounded-full capitalize {{ $levelColors[$game->level] }}">
                                        {{ $game->level }}
                                    </span>
                                @endif
                                @if ($game->title)
                                    <div class="font-semibold text-white text-sm leading-snug">{{ $game->title }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $game->court->name }}</div>
                                @else
                                    <div class="font-semibold text-white text-sm leading-snug">{{ $game->court->name }}</div>
                                @endif
                                @if ($game->court->address)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $game->court->address }}</p>
                                @endif
                            </div>

                            <div class="shrink-0 text-right">
                                <div class="text-sm font-bold text-orange-500">{{ $game->attendees_count }}</div>
                                <div class="text-xs text-gray-500">{{ Str::plural('player', $game->attendees_count) }}</div>
                            </div>

                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Past games --}}
            @if ($pastGames->isNotEmpty())
                <h2 class="text-lg font-semibold mt-8 mb-4">
                    Past Games
                    <span class="ml-2 text-sm font-normal text-gray-500">{{ $pastGames->count() }}</span>
                </h2>
                <div class="flex flex-col gap-3">
                    @foreach ($pastGames as $game)
                        <a href="{{ route('games.show', $game) }}" class="bg-gray-900 border border-white/10 rounded-xl px-5 py-4 flex items-center gap-5 hover:border-white/20 transition-colors opacity-60">

                            <div class="shrink-0 w-12 text-center">
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

                            <div class="w-px self-stretch bg-white/10 shrink-0"></div>

                            <div class="flex-1 min-w-0">
                                @if ($game->level)
                                    <span class="inline-block mb-1 text-xs font-medium px-2.5 py-0.5 rounded-full capitalize {{ $levelColors[$game->level] }}">
                                        {{ $game->level }}
                                    </span>
                                @endif
                                @if ($game->title)
                                    <div class="font-semibold text-white text-sm leading-snug">{{ $game->title }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $game->court->name }}</div>
                                @else
                                    <div class="font-semibold text-white text-sm leading-snug">{{ $game->court->name }}</div>
                                @endif
                                @if ($game->court->address)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $game->court->address }}</p>
                                @endif
                            </div>

                            <div class="shrink-0 text-right">
                                <div class="text-sm font-bold text-gray-400">{{ $game->attendees_count }}</div>
                                <div class="text-xs text-gray-500">{{ Str::plural('player', $game->attendees_count) }}</div>
                            </div>

                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Courts created --}}
        <div>
            <h2 class="text-lg font-semibold mb-4">
                Courts Added
                @if ($user->courts->isNotEmpty())
                    <span class="ml-2 text-sm font-normal text-gray-500">{{ $user->courts->count() }}</span>
                @endif
            </h2>

            @if ($user->courts->isEmpty())
                <p class="text-sm text-gray-500">You haven't added any courts yet.</p>
            @else
                <div class="flex flex-col gap-2">
                    @foreach ($user->courts as $court)
                        <a href="{{ route('courts.show', $court) }}" class="bg-gray-900 border border-white/10 rounded-xl px-4 py-3 hover:border-orange-500/40 transition-colors">
                            <div class="font-semibold text-sm text-white leading-snug">{{ $court->name }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $court->city->name }}</div>
                            <div class="text-xs text-gray-600 mt-1">{{ $court->games_count }} {{ Str::plural('game', $court->games_count) }}</div>
                        </a>
                    @endforeach
                </div>
            @endif

            {{-- Incoming requests --}}
            @if ($incomingRequests->isNotEmpty())
                <h2 class="text-lg font-semibold mt-8 mb-4">
                    Friend Requests
                    <span class="ml-2 text-sm font-normal text-orange-500">{{ $incomingRequests->count() }}</span>
                </h2>
                <div class="flex flex-col gap-2">
                    @foreach ($incomingRequests as $req)
                        <div class="bg-gray-900 border border-white/10 rounded-xl px-4 py-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-sm font-bold shrink-0">
                                {{ strtoupper(substr($req->inviter->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-white truncate">{{ $req->inviter->name }}</div>
                                @if ($req->inviter->level)
                                    <span class="text-xs text-gray-500 capitalize">{{ $req->inviter->level }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="{{ route('friends.toggle', $req->inviter) }}">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-green-500/10 text-green-400 ring-1 ring-green-500/20 hover:bg-green-500/20 transition-colors cursor-pointer">Accept</button>
                                </form>
                                <form method="POST" action="{{ route('friends.destroy', $req->inviter) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-gray-800 text-gray-400 ring-1 ring-white/10 hover:text-red-400 hover:border-red-400/30 transition-colors cursor-pointer">Decline</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Friends --}}
            <h2 class="text-lg font-semibold mt-8 mb-4">
                Friends
                @if ($friendRequests->isNotEmpty())
                    <span class="ml-2 text-sm font-normal text-gray-500">{{ $friendRequests->count() }}</span>
                @endif
            </h2>

            @if ($friendRequests->isEmpty())
                <p class="text-sm text-gray-500">No friends yet. Join a game to meet players!</p>
            @else
                <div class="flex flex-col gap-2">
                    @foreach ($friendRequests as $fr)
                        @php $friend = $fr->inviter_id === Auth::id() ? $fr->invitee : $fr->inviter; @endphp
                        <div class="bg-gray-900 border border-white/10 rounded-xl px-4 py-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-sm font-bold shrink-0">
                                {{ strtoupper(substr($friend->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-white truncate">{{ $friend->name }}</div>
                                @if ($friend->level)
                                    <span class="text-xs text-gray-500 capitalize">{{ $friend->level }}</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ route('messages.show', $fr) }}" class="text-xs font-semibold px-2.5 py-1 rounded-lg bg-orange-500/10 text-orange-400 ring-1 ring-orange-500/20 hover:bg-orange-500/20 transition-colors">Message</a>
                                <form method="POST" action="{{ route('friends.destroy', $friend) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-gray-600 hover:text-red-400 transition-colors cursor-pointer">Remove</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Sent requests --}}
            @if ($sentRequests->isNotEmpty())
                <h2 class="text-lg font-semibold mt-8 mb-4">
                    Sent Requests
                    <span class="ml-2 text-sm font-normal text-gray-500">{{ $sentRequests->count() }}</span>
                </h2>
                <div class="flex flex-col gap-2">
                    @foreach ($sentRequests as $req)
                        <div class="bg-gray-900 border border-white/10 rounded-xl px-4 py-3 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-gray-700 text-gray-300 flex items-center justify-center text-sm font-bold shrink-0">
                                {{ strtoupper(substr($req->invitee->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-semibold text-white truncate">{{ $req->invitee->name }}</div>
                                <span class="text-xs text-gray-600">Pending</span>
                            </div>
                            <form method="POST" action="{{ route('friends.destroy', $req->invitee) }}" class="shrink-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-gray-600 hover:text-red-400 transition-colors cursor-pointer">Cancel</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

</div>

@endsection
