@extends('layouts.app')

@section('title', 'Chat with ' . $friend->name)

@section('content')

<div class="max-w-2xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('profile.show') }}" class="text-gray-500 hover:text-white transition-colors">
            ← Back
        </a>
        <div class="w-10 h-10 rounded-full bg-orange-500/20 text-orange-400 flex items-center justify-center text-sm font-bold shrink-0">
            {{ strtoupper(substr($friend->name, 0, 1)) }}
        </div>
        <div>
            <h1 class="text-lg font-bold text-white leading-tight">{{ $friend->name }}</h1>
            @if ($friend->level)
                <span class="text-xs text-gray-500 capitalize">{{ $friend->level }}</span>
            @endif
        </div>
    </div>

    {{-- Messages --}}
    @if ($friendRequest->messages->isEmpty())
        <p class="text-sm text-gray-500 mb-6">No messages yet. Say hi!</p>
    @else
        <div class="flex flex-col gap-4 mb-6">
            @foreach ($friendRequest->messages as $message)
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
                        @if ($message->user_id === Auth::id())
                            <form method="POST" action="{{ route('game-messages.destroy', $message) }}" class="mt-1.5">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-gray-600 hover:text-red-400 transition-colors cursor-pointer">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Send form --}}
    <form method="POST" action="{{ route('messages.store', $friendRequest) }}" class="flex gap-2">
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

</div>

@endsection
