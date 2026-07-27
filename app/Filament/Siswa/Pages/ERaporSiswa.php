<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\NilaiRapor;
use Illuminate\Support\Facades\Auth;

class EraporSiswa extends Page
{
    protected static ?string $title = 'E-Rapor Akademik';
    protected static string $view = 'filament.siswa.pages.erapor-siswa';
    
    // Custom URL untuk halaman ini
    protected static ?string $slug = 'e-rapor';
    
    // Sembunyikan dari sidebar kiri
    protected static bool $shouldRegisterNavigation = false;

    public function getLayout(): string
    {
        return 'filament-panels::components.layout.simple';
    }

    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    protected function getViewData(): array
    {
        $user = Auth::user();$siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();$raporGrouped = collect();

        if ($siswa) {
            // Tarik seluruh data nilai rapor siswa tersebut
            $nilais = NilaiRapor::with(['mataPelajaran', 'tahunAjaran'])
                ->where('siswa_id', $siswa->id)
                ->get();

            // Kelompokkan berdasarkan Tahun Ajaran & Semester
            $raporGrouped = $nilais->groupBy(function ($item) {
                return $item->tahunAjaran->nama_tahun . ' - Smt ' .$item->tahunAjaran->semester;
            })->sortKeysDesc(); // Urutkan dari semester terbaru (Z ke A / Descending)
        }

        return [
            'siswa' => $siswa,
            'raporGrouped' => $raporGrouped,
        ];
    }
}