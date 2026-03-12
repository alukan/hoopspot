<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HoopSpot – @yield('title', 'Find Pickup Games')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-950 text-white min-h-screen flex flex-col font-sans antialiased">

    <nav class="border-b border-white/10 px-6 py-4">
        <div class="max-w-5xl mx-auto flex items-center justify-between">
            <a href="{{ route('home') }}" class="text-orange-500 font-bold text-xl tracking-tight hover:text-orange-400 transition-colors">HoopSpot</a>
            <div class="flex items-center gap-4 text-sm text-gray-400">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Games</a>
                @auth
                    <a href="{{ route('profile.show') }}" class="hover:text-white transition-colors">
                        {{ Auth::user()->name }}
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" class="hover:text-white transition-colors cursor-pointer">Sign out</button>
                    </form>
                @else
                    <a href="{{ route('auth.login') }}" class="hover:text-white transition-colors">Sign in</a>
                    <a href="{{ route('auth.register') }}" class="bg-orange-500 hover:bg-orange-400 text-white font-semibold px-4 py-1.5 rounded-lg transition-colors">Sign up</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="border-t border-white/10 px-6 py-6 text-center text-sm text-gray-600">
        &copy; {{ date('Y') }} HoopSpot. All rights reserved.
    </footer>

</body>
</html>
