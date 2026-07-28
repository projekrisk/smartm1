<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use App\Filament\Siswa\Pages\Auth\SiswaLogin;
use App\Http\Middleware\ForceStudentPasswordChange;
use App\Filament\Siswa\Pages\SiswaDashboard;
use App\Filament\Siswa\Pages\AbsensiSekretaris;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\HtmlString;

class SiswaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('siswa')
            ->path('siswa')
            ->login(SiswaLogin::class)
            ->colors(['primary' => Color::Blue])
            ->favicon(url('/uploads/' . \App\Models\Pengaturan::first()?->logo_sekolah))
            ->topNavigation()
            ->brandName('Portal Siswa')
            ->discoverResources(in: app_path('Filament/Siswa/Resources'), for: 'App\\Filament\\Siswa\\Resources')
            ->discoverPages(in: app_path('Filament/Siswa/Pages'), for: 'App\\Filament\\Siswa\\Pages')
            ->pages([
                SiswaDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Siswa/Widgets'), for: 'App\\Filament\\Siswa\\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                ForceStudentPasswordChange::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('@include("pwa-head")')
            )
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => '
                    <style>
                        .fi-topbar, nav.fi-topbar, header.fi-topbar, .fi-topbar-user-menu, .fi-user-menu { 
                            display: none !important; 
                            opacity: 0 !important; 
                            visibility: hidden !important; 
                            pointer-events: none !important; 
                            height: 0 !important; 
                            width: 0 !important;
                            overflow: hidden !important;
                        }
                        html, body {
                            margin: 0 !important;
                            padding: 0 !important;
                            overflow: hidden !important; /* Cegah scroll ganda di luar aplikasi */
                            background-color: #e2e8f0 !important;
                        }                        
                        .dark body {
                            background-color: #020617 !important;
                        }
                        .fi-layout, .fi-simple-layout, .fi-main, .fi-simple-main, .fi-simple-page, main, .fi-page-simple-page {
                            padding: 0 !important;
                            margin: 0 !important;
                            min-height: 0 !important;
                            display: block !important; /* Menghilangkan flexbox centering penyebab gap */
                        }
                        .android-app-container {
                            position: fixed !important;
                            top: 0 !important;
                            left: 0 !important;
                            right: 0 !important;
                            bottom: 0 !important;
                            margin: 0 auto !important;
                            z-index: 99999 !important;
                            width: 100% !important;
                            max-width: 414px !important;
                            height: 100vh !important;
                            height: 100dvh !important;
                        }
                    </style>
                '
            );
    }
}