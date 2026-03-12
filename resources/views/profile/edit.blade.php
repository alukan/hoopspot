@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">

        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('profile.show') }}" class="text-gray-500 hover:text-white transition-colors text-sm">← Profile</a>
            <span class="text-gray-700">/</span>
            <h1 class="text-2xl font-bold">Edit profile</h1>
        </div>

        <div class="bg-gray-900 border border-white/10 rounded-2xl p-8">

            <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col gap-5">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $user->name) }}"
                        required
                        autocomplete="name"
                        class="w-full bg-gray-800 border @error('name') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                    >
                    @error('name')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Skill level --}}
                <div>
                    <label for="level" class="block text-sm font-medium text-gray-300 mb-1.5">Skill level</label>
                    <select
                        id="level"
                        name="level"
                        class="w-full bg-gray-800 border border-white/10 text-white rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                    >
                        <option value="">Not set</option>
                        @foreach (\App\Models\User::LEVELS as $lvl)
                            <option value="{{ $lvl }}" {{ old('level', $user->level) === $lvl ? 'selected' : '' }} class="capitalize">
                                {{ ucfirst($lvl) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-3 pt-1">
                    <a href="{{ route('profile.show') }}" class="flex-1 text-center bg-gray-800 hover:bg-gray-700 border border-white/10 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors cursor-pointer">
                        Save changes
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
