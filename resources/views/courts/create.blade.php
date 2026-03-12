@extends('layouts.app')

@section('title', 'Add a Court')

@section('content')

<div class="max-w-2xl mx-auto px-6 py-10">

    <div class="flex items-center gap-3 mb-8">
        <a href="{{ url()->previous() }}" class="text-gray-500 hover:text-white transition-colors text-sm">← Back</a>
        <span class="text-gray-700">/</span>
        <h1 class="text-2xl font-bold">
            Add a court
            @if ($cityId && ($lockedCity = $cities->find($cityId)))
                <span class="text-orange-500">in {{ $lockedCity->name }}</span>
            @endif
        </h1>
    </div>

    <div class="bg-gray-900 border border-white/10 rounded-2xl p-8">

        <p class="text-sm text-gray-500 mb-6">
            Your submission will be reviewed by an admin before it appears publicly.
        </p>

        <form method="POST" action="{{ route('courts.store') }}" class="flex flex-col gap-5">
            @csrf

            {{-- City --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-1.5">City</label>
                @if ($cityId && ($lockedCity = $cities->find($cityId)))
                    <input type="hidden" name="city_id" value="{{ $lockedCity->id }}">
                    <div class="w-full bg-gray-800/50 border border-white/10 text-gray-400 rounded-xl px-4 py-3 text-sm">
                        {{ $lockedCity->name }}
                    </div>
                @else
                    <select
                        id="city_id"
                        name="city_id"
                        required
                        class="w-full bg-gray-800 border @error('city_id') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                    >
                        <option value="">Select a city…</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ (old('city_id') == $city->id) ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Court name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    placeholder="e.g. Riverside Park Courts"
                    class="w-full bg-gray-800 border @error('name') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600"
                >
                @error('name')
                    <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            {{-- Address --}}
            <div>
                <label for="address" class="block text-sm font-medium text-gray-300 mb-1.5">Address</label>
                <input
                    type="text"
                    id="address"
                    name="address"
                    value="{{ old('address') }}"
                    required
                    placeholder="e.g. 123 Main St"
                    class="w-full bg-gray-800 border @error('address') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600"
                >
                @error('address')
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
                    placeholder="Parking nearby, lit at night, free to use…"
                    class="w-full bg-gray-800 border border-white/10 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600 resize-none"
                >{{ old('description') }}</textarea>
            </div>

            {{-- Coverage + Rim type --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="coverage" class="block text-sm font-medium text-gray-300 mb-1.5">Coverage</label>
                    <select
                        id="coverage"
                        name="coverage"
                        required
                        class="w-full bg-gray-800 border @error('coverage') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                    >
                        <option value="">Select…</option>
                        @foreach (\App\Models\Court::COVERAGES as $option)
                            <option value="{{ $option }}" {{ old('coverage') === $option ? 'selected' : '' }} class="capitalize">
                                {{ ucfirst($option) }}
                            </option>
                        @endforeach
                    </select>
                    @error('coverage')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rim_type" class="block text-sm font-medium text-gray-300 mb-1.5">Rim type</label>
                    <select
                        id="rim_type"
                        name="rim_type"
                        required
                        class="w-full bg-gray-800 border @error('rim_type') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                    >
                        <option value="">Select…</option>
                        @foreach (\App\Models\Court::RIM_TYPES as $option)
                            <option value="{{ $option }}" {{ old('rim_type') === $option ? 'selected' : '' }} class="capitalize">
                                {{ ucfirst(str_replace('_', ' ', $option)) }}
                            </option>
                        @endforeach
                    </select>
                    @error('rim_type')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3 pt-1">
                <a href="{{ url()->previous() }}" class="flex-1 text-center bg-gray-800 hover:bg-gray-700 border border-white/10 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors">
                    Cancel
                </a>
                <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors cursor-pointer">
                    Submit court
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
