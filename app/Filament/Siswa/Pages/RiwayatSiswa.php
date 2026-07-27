<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\CatatanSiswa;
use App\Models\SuratPanggilan;
use Illuminate\Support\Facades\Auth;

class RiwayatSiswa extends Page
{
    protected static ?string $title = 'Riwayat Kesiswaan';
    protected static string $view = 'filament.siswa.pages.riwayat-siswa';
    protected static ?string $slug = 'riwayat';
    protected static bool $shouldRegisterNavigation = false;

    public function getLayout(): string { return 'filament-panels::components.layout.simple'; }
    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    protected function getViewData(): array
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        
        $catatans = collect();$panggilans = collect();

        if ($siswa) {
            $catatans = CatatanSiswa::with('pencatat')->where('siswa_id',$siswa->id)->orderBy('tanggal', 'desc')->get();
            $panggilans = SuratPanggilan::where('siswa_id',$siswa->id)->orderBy('tanggal_surat', 'desc')->get();
        }

        return compact('siswa', 'catatans', 'panggilans');
    }
}