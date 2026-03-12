@extends('layouts.app')

@section('title', 'Schedule a Game')

@section('content')

<div class="max-w-2xl mx-auto px-6 py-10">

    <div class="flex items-center gap-3 mb-8">
        <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-white transition-colors text-sm">← Back</a>
        <span class="text-gray-700">/</span>
        <h1 class="text-2xl font-bold">
            Schedule a game
            @if ($city)
                <span class="text-orange-500">in {{ $city->name }}</span>
            @endif
        </h1>
    </div>

    <div class="bg-gray-900 border border-white/10 rounded-2xl p-8">

        <form method="POST" action="{{ route('games.store') }}" class="flex flex-col gap-5">
            @csrf

            {{-- Title --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-1.5">
                    Title <span class="text-gray-600 font-normal">(optional)</span>
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="{{ old('title') }}"
                    maxlength="100"
                    placeholder="Friday evening run, 3v3 tourney…"
                    class="w-full bg-gray-800 border @error('title') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600"
                >
                @error('title')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Court --}}
            <div>
                <label for="court_id" class="block text-sm font-medium text-gray-300 mb-1.5">Court</label>
                <select
                    id="court_id"
                    name="court_id"
                    required
                    class="w-full bg-gray-800 border @error('court_id') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                >
                    <option value="">Select a court…</option>
                    @foreach ($courts as $court)
                        <option value="{{ $court->id }}" {{ (old('court_id', $courtId) == $court->id) ? 'selected' : '' }}>
                            {{ $court->name }}{{ $city ? '' : ' — ' . $court->city->name }}
                        </option>
                    @endforeach
                </select>
                @error('court_id')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Date & time --}}
            <div>
                <label for="scheduled_at" class="block text-sm font-medium text-gray-300 mb-1.5">Date &amp; time</label>
                <input
                    type="datetime-local"
                    id="scheduled_at"
                    name="scheduled_at"
                    value="{{ old('scheduled_at') }}"
                    required
                    class="w-full bg-gray-800 border @error('scheduled_at') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 [color-scheme:dark]"
                >
                @error('scheduled_at')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Level --}}
            <div>
                <label for="level" class="block text-sm font-medium text-gray-300 mb-1.5">
                    Skill level <span class="text-gray-600 font-normal">(optional)</span>
                </label>
                <select
                    id="level"
                    name="level"
                    class="w-full bg-gray-800 border @error('level') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                >
                    <option value="">Any level</option>
                    @foreach ($levels as $option)
                        <option value="{{ $option }}" {{ old('level') === $option ? 'selected' : '' }} class="capitalize">
                            {{ ucfirst($option) }}
                        </option>
                    @endforeach
                </select>
                @error('level')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-1.5">
                    Description <span class="text-gray-600 font-normal">(optional)</span>
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    placeholder="Bring good vibes, all levels welcome…"
                    class="w-full bg-gray-800 border border-white/10 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600 resize-none"
                >{{ old('description') }}</textarea>
            </div>

            <div class="flex gap-3 pt-1">
                <a href="{{ url()->previous() }}" class="flex-1 text-center bg-gray-800 hover:bg-gray-700 border border-white/10 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors">
                    Cancel
                </a>
                <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors cursor-pointer">
                    Schedule game
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
