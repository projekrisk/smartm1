<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\MenuItem; // <-- Wajib ditambahkan
use App\Filament\Pages\ProfilPegawai; // <-- Wajib ditambahkan
use App\Filament\Pages\Auth\CustomAdminLogin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $namaSekolah = 'SmartM1';
        $faviconUrl = null;

        // Mengambil data pengaturan sekolah secara aman
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                $pengaturan = \App\Models\Pengaturan::first();
                if ($pengaturan) {
                    $namaSekolah = $pengaturan->nama_sekolah ?? $namaSekolah;
                    $faviconUrl = $pengaturan->logo_sekolah ? url('/uploads/' . $pengaturan->logo_sekolah) : null;
                }
            }
        } catch (\Exception $e) {
            // Abaikan jika tabel belum siap (saat migrasi awal)
        }

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(CustomAdminLogin::class)
            ->brandName('SmartM1')
            ->favicon($faviconUrl)
            
            // FUNGSI BARU: Menyematkan halaman kustom ke Menu Dropdown Pengguna (Kanan Atas)
            ->userMenuItems([
                MenuItem::make()
                    ->label('Profil Saya')
                    ->url(fn (): string => ProfilPegawai::getUrl())
                    ->icon('heroicon-o-user-circle'),
            ])
            
            // Sidebar utama bisa diciutkan menjadi ikon saja di Desktop
            ->sidebarCollapsibleOnDesktop()
            
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                \App\Filament\Widgets\PengumumanWidget::class,
                \App\Filament\Widgets\AdminAbsensiHariIniWidget::class,
                \App\Filament\Widgets\AdminPeringatanAbsensiWidget::class,
            ])
            // MENYUNTIKKAN KODE PWA KE DALAM PANEL ADMIN (Agar Guru juga bisa Install)
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => \Illuminate\Support\Facades\Blade::render('@include("pwa-head")')
            )
            // PENGATURAN KELOMPOK MENU: Terbuka secara default, tapi bisa diciutkan (collapsible)
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Manajemen Kelas')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Akademik')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Kehadiran Siswa')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Kesiswaan')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Kepegawaian')
                    ->collapsible(),
                NavigationGroup::make()
                    ->label('Sistem')
                    ->collapsible(),
            ])
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}