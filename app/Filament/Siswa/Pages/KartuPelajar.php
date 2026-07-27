<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Auth;

class KartuPelajar extends Page
{
    protected static ?string $title = 'Kartu Pelajar Digital';
    protected static string $view = 'filament.siswa.pages.kartu-pelajar';
    protected static ?string $slug = 'kartu-pelajar';
    protected static bool $shouldRegisterNavigation = false;

    public function getLayout(): string { return 'filament-panels::components.layout.simple'; }
    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    protected function getViewData(): array
    {
        $siswa = Siswa::with('kelas')->where('user_id', Auth::id())->first();$pengaturan = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                $pengaturan = Pengaturan::first();
            }
        } catch (\Exception $e) {}

        return compact('siswa', 'pengaturan');
    }
}