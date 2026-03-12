<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::share('levelColors', [
            'beginner'     => 'bg-green-500/10 text-green-400 ring-1 ring-green-500/20',
            'intermediate' => 'bg-blue-500/10 text-blue-400 ring-1 ring-blue-500/20',
            'advanced'     => 'bg-orange-500/10 text-orange-400 ring-1 ring-orange-500/20',
            'pro'          => 'bg-purple-500/10 text-purple-400 ring-1 ring-purple-500/20',
        ]);
    }
}
