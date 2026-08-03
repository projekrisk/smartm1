<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\BukuNilai;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class NilaiSiswa extends Page
{
    protected static ?string $title = 'Nilai Akademik';
    protected static string $view = 'filament.siswa.pages.nilai-siswa';
    
    protected static ?string $slug = 'nilai';
    
    protected static bool $shouldRegisterNavigation = false;

    public function getLayout(): string
    {
        return 'filament-panels::components.layout.simple';
    }

    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    protected function getViewData(): array
    {
        $user = Auth::user();$siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();$taAktif = TahunAjaran::where('is_active', true)->first();

        $nilaiGrouped = collect();$totalNilaiMasuk = 0;

        if ($siswa &&$taAktif) {
            $nilais = BukuNilai::with(['penilaian.mataPelajaran'])
                ->where('siswa_id', $siswa->id)
                ->whereHas('penilaian', function($q) use ($taAktif) {
                    $q->where('tahun_ajaran_id',$taAktif->id);
                })
                ->whereNotNull('nilai')
                ->get()
                ->sortByDesc(fn($n) =>$n->penilaian->tanggal_penilaian);

            $totalNilaiMasuk =$nilais->count();

            $nilaiGrouped = $nilais->groupBy(function ($item) {
                return $item->penilaian->mataPelajaran->nama_pelajaran ?? 'Lainnya';
            });
            
            $nilaiGrouped =$nilaiGrouped->sortKeys();
        }

        return [
            'siswa' => $siswa,
            'taAktif' => $taAktif,
            'nilaiGrouped' => $nilaiGrouped,
            'totalNilaiMasuk' => $totalNilaiMasuk,
        ];
    }
}