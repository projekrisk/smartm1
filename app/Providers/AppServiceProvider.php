<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->usePublicPath(base_path());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (config('app.env') === 'production' || str_contains(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }

//        FilamentView::registerRenderHook(
//            PanelsRenderHook::BODY_END,
//           fn (): \Illuminate\Contracts\View\View => view('components.pwa-prompt'),
//        );
    }
}