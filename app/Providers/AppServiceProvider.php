<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Memaksa Laravel agar menggunakan folder utama sebagai folder public
        $this->app->usePublicPath(base_path());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // FUNGSI AJAIB: Memaksa Laravel & Livewire selalu menggunakan HTTPS di server hosting
        if (config('app.env') === 'production' || str_contains(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }
    }
}