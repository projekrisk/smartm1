<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Pengumuman;

class SiswaDashboard extends BaseDashboard
{
    protected static string $view = 'filament.siswa.pages.siswa-dashboard';
    protected static ?string $title = 'Beranda';
    protected static ?string $navigationIcon = 'heroicon-o-home';

    public function getLayout(): string
    {
        return 'filament-panels::components.layout.simple';
    }

    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    protected function getViewData(): array
    {
        $user = Auth::user();
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
        
        $pengumuman = Pengumuman::where('is_aktif', true)->latest()->take(3)->get();

        return [
            'siswa' => $siswa,
            'pengumuman' => $pengumuman,
        ];
    }

    public function keluarAplikasi()
    {
        auth()->logout();
        
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect('/');
    }
}