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
use App\Filament\Siswa\Pages\Auth\SiswaLogin; // <-- TAMBAHKAN INI
use App\Http\Middleware\ForceStudentPasswordChange; // <-- TAMBAHKAN INI
use App\Filament\Siswa\Pages\SiswaDashboard; // <-- TAMBAHKAN INI
use App\Filament\Siswa\Pages\AbsensiSekretaris;

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
            // TAMBAHKAN DUA BARIS INI UNTUK MEMBUANG SEMUA HEADER FILAMENT
            ->topNavigation(false) 
            ->sidebarCollapsibleOnDesktop(false)
            ->brandName('Portal Siswa')
            ->discoverResources(in: app_path('Filament/Siswa/Resources'), for: 'App\\Filament\\Siswa\\Resources')
            ->discoverPages(in: app_path('Filament/Siswa/Pages'), for: 'App\\Filament\\Siswa\\Pages')
            ->pages([
                SiswaDashboard::class, // <-- GANTI DEFAULT DASHBOARD DENGAN KUSTOM
                AbsensiSekretaris::class, // <-- 2. DAFTARKAN HALAMANNYA DI SINI
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
                ForceStudentPasswordChange::class, // <-- PASANG MIDDLEWARE DI SINI
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => Blade::render('@include("pwa-head")')
            );
    }
}