<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Dokumen;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class DokumenSiswa extends Page
{
    protected static ?string $title = 'E-Dokumen & Arsip';
    protected static string $view = 'filament.siswa.pages.dokumen-siswa';
    protected static ?string $slug = 'dokumen';
    protected static bool $shouldRegisterNavigation = false;

    public function getLayout(): string { return 'filament-panels::components.layout.simple'; }
    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    protected function getViewData(): array
    {
        $user = Auth::user();
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
        
        // Tarik dokumen yang ditujukan untuk Siswa atau Semua
        $dokumens = Dokumen::whereIn('target_audience', ['Semua', 'Siswa'])
            ->orderBy('created_at', 'desc')
            ->get();

        return [
            'siswa' => $siswa,
            'dokumens' => $dokumens,
        ];
    }
}