@extends('layouts.app')

@section('title', 'Courts in ' . $city->name)

@section('content')

<div class="max-w-5xl mx-auto px-6 py-10">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-8">
        <a href="{{ route('home') }}" class="text-gray-500 hover:text-white transition-colors text-sm">← Cities</a>
        <span class="text-gray-700">/</span>
        <h1 class="text-2xl font-bold">Courts in <span class="text-orange-500">{{ $city->name }}</span></h1>
        <span class="ml-auto text-sm text-gray-500">{{ $courts->count() }} {{ Str::plural('court', $courts->count()) }}</span>
    </div>

    {{-- Filters --}}
    <form method="GET" action="{{ route('courts.index') }}" class="bg-gray-900 border border-white/10 rounded-xl p-5 mb-8">
        <input type="hidden" name="city" value="{{ $city->id }}">

        <div class="flex flex-wrap gap-8">

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
                @if (request('coverage') || request('rim_type'))
                    <a href="{{ route('courts.index', ['city' => $city->id]) }}" class="text-sm text-gray-400 hover:text-white transition-colors px-4 py-2">
                        Clear
                    </a>
                @endif
                <button type="submit" class="bg-orange-500 hover:bg-orange-400 text-white text-sm font-semibold px-5 py-2 rounded-lg transition-colors cursor-pointer">
                    Apply
                </button>
            </div>

        </div>
    </form>

    {{-- Courts grid --}}
    @if ($courts->isEmpty())
        <div class="text-center py-20 text-gray-500">
            <p class="text-lg mb-2">No courts match your filters.</p>
            <a href="{{ route('courts.index', ['city' => $city->id]) }}" class="text-orange-500 hover:text-orange-400 text-sm transition-colors">Clear filters</a>
        </div>
    @else
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($courts as $court)
                <div class="bg-gray-900 border border-white/10 rounded-xl p-5 flex flex-col gap-3 hover:border-orange-500/30 transition-colors">

                    {{-- Badges --}}
                    <div class="flex items-center gap-2">
                        <span @class([
                            'text-xs font-medium px-2.5 py-1 rounded-full',
                            'bg-sky-500/10 text-sky-400 ring-1 ring-sky-500/20'   => $court->coverage === 'indoor',
                            'bg-green-500/10 text-green-400 ring-1 ring-green-500/20' => $court->coverage === 'outdoor',
                        ])>
                            {{ ucfirst($court->coverage) }}
                        </span>
                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-800 text-gray-400 ring-1 ring-white/10 capitalize">
                            {{ str_replace('_', ' ', $court->rim_type) }} rim
                        </span>
                    </div>

                    {{-- Name & address --}}
                    <div>
                        <h2 class="font-semibold text-white leading-snug">{{ $court->name }}</h2>
                        @if ($court->address)
                            <p class="text-sm text-gray-500 mt-0.5">{{ $court->address }}</p>
                        @endif
                    </div>

                    {{-- Description --}}
                    @if ($court->description)
                        <p class="text-sm text-gray-400 leading-relaxed line-clamp-2">{{ $court->description }}</p>
                    @endif

                </div>
            @endforeach
        </div>
    @endif

</div>

@endsection
