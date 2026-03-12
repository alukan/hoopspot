@extends('layouts.app')

@section('title', 'Sign In')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Welcome back</h1>
            <p class="text-gray-500 text-sm">Sign in to your HoopSpot account.</p>
        </div>

        <div class="bg-gray-900 border border-white/10 rounded-2xl p-8">

            <form method="POST" action="{{ route('auth.login.post') }}" class="flex flex-col gap-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="you@example.com"
                        class="w-full bg-gray-800 border @error('email') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600"
                    >
                    @error('email')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-1.5">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Your password"
                        class="w-full bg-gray-800 border border-white/10 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600"
                    >
                </div>

                {{-- Remember me --}}
                <label class="flex items-center gap-2.5 cursor-pointer">
                    <input type="checkbox" name="remember" class="accent-orange-500 w-4 h-4 rounded">
                    <span class="text-sm text-gray-400">Remember me</span>
                </label>

                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors cursor-pointer mt-1">
                    Sign in
                </button>

            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Don't have an account?
                <a href="{{ route('auth.register') }}" class="text-orange-400 hover:text-orange-300 transition-colors">Create one</a>
            </p>

        </div>
    </div>
</div>

@endsection
