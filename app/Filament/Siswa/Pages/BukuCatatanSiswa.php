<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\CatatanSiswa;
use Illuminate\Support\Facades\Auth;

class BukuCatatanSiswa extends Page
{
    protected static ?string $title = 'Buku Catatan';
    protected static string $view = 'filament.siswa.pages.buku-catatan-siswa';
    
    protected static ?string $slug = 'catatan';
    
    protected static bool $shouldRegisterNavigation = false;

    public function getLayout(): string
    {
        return 'filament-panels::components.layout.simple';
    }

    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    protected function getViewData(): array
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        $catatans = collect();

        if ($siswa) {
            $catatans = CatatanSiswa::with(['pencatat', 'penindaklanjut'])
                ->where('siswa_id', $siswa->id)
                ->orderBy('tanggal', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return [
            'siswa' => $siswa,
            'catatans' => $catatans,
        ];
    }
}