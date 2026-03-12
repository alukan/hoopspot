@extends('layouts.app')

@section('title', 'Create Account')

@section('content')

<div class="min-h-[80vh] flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-md">

        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Create your account</h1>
            <p class="text-gray-500 text-sm">Join HoopSpot and start finding games near you.</p>
        </div>

        <div class="bg-gray-900 border border-white/10 rounded-2xl p-8">

            <form method="POST" action="{{ route('auth.register') }}" class="flex flex-col gap-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        placeholder="Your name"
                        class="w-full bg-gray-800 border @error('name') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600"
                    >
                    @error('name')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-1.5">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
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
                        autocomplete="new-password"
                        placeholder="Min. 8 characters"
                        class="w-full bg-gray-800 border @error('password') border-red-500/60 @else border-white/10 @enderror text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600"
                    >
                    @error('password')
                        <p class="text-red-400 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-1.5">Confirm password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Repeat your password"
                        class="w-full bg-gray-800 border border-white/10 text-white rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50 placeholder:text-gray-600"
                    >
                </div>

                {{-- Skill level --}}
                <div>
                    <label for="level" class="block text-sm font-medium text-gray-300 mb-1.5">
                        Skill level <span class="text-gray-600 font-normal">(optional)</span>
                    </label>
                    <select
                        id="level"
                        name="level"
                        class="w-full bg-gray-800 border border-white/10 text-white rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer focus:outline-none focus:ring-2 focus:ring-orange-500/50 focus:border-orange-500/50"
                    >
                        <option value="">Select your level…</option>
                        @foreach (\App\Models\User::LEVELS as $lvl)
                            <option value="{{ $lvl }}" {{ old('level') === $lvl ? 'selected' : '' }} class="capitalize">
                                {{ ucfirst($lvl) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-400 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors cursor-pointer mt-1">
                    Create account
                </button>

            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Already have an account?
                <a href="{{ route('auth.login') }}" class="text-orange-400 hover:text-orange-300 transition-colors">Sign in</a>
            </p>

        </div>
    </div>
</div>

@endsection
