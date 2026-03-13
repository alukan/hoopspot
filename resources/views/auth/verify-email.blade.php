@extends('layouts.app')

@section('title', 'Verify your email')

@section('content')

<div class="max-w-md mx-auto px-6 py-16 text-center">

    <div class="w-16 h-16 rounded-full bg-orange-500/10 flex items-center justify-center mx-auto mb-6">
        <svg class="w-8 h-8 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
        </svg>
    </div>

    <h1 class="text-2xl font-bold text-white mb-3">Check your inbox</h1>
    <p class="text-gray-400 mb-8">
        We sent a verification link to <span class="text-white font-medium">{{ Auth::user()->email }}</span>.
        Click the link to activate your account.
    </p>

    @if (session('status'))
        <div class="bg-green-500/10 border border-green-500/30 text-green-400 text-sm rounded-xl px-4 py-3 mb-6">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="w-full bg-gray-800 hover:bg-gray-700 border border-white/10 text-white font-semibold rounded-xl px-6 py-3 text-sm transition-colors cursor-pointer">
            Resend verification email
        </button>
    </form>

    <form method="POST" action="{{ route('auth.logout') }}" class="mt-3">
        @csrf
        <button type="submit" class="text-sm text-gray-600 hover:text-gray-400 transition-colors cursor-pointer">
            Log out
        </button>
    </form>

</div>

@endsection
