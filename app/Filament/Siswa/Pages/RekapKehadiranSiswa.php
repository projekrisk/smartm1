<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\KehadiranHarian;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;

class RekapKehadiranSiswa extends Page
{
    protected static ?string $title = 'Rekap Kehadiran';
    protected static string $view = 'filament.siswa.pages.rekap-kehadiran-siswa';
    
    // FUNGSI BARU: Mengubah URL rute bawaan Filament
    protected static ?string $slug = 'rekap-absensi';
    
    // Sembunyikan dari sidebar karena akan diakses lewat icon di Dashboard
    protected static bool $shouldRegisterNavigation = false;

    // Gunakan layout simple agar header bawaan admin hilang
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
        $ta = TahunAjaran::where('is_active', true)->first();

        $absenSemester = 0;
        $absenBulan = 0;
        $listAbsen = collect();

        if ($siswa && $ta) {
            $absenSemester = KehadiranHarian::where('siswa_id', $siswa->id)
                ->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
                ->whereHas('rekapKehadiran', function($q) use ($ta) {
                    $q->where('tahun_ajaran_id', $ta->id);
                })->count();

            $queryBulan = KehadiranHarian::with('rekapKehadiran')
                ->where('siswa_id', $siswa->id)
                ->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
                ->whereHas('rekapKehadiran', function($q) {
                    $q->whereMonth('tanggal', now()->month)
                      ->whereYear('tanggal', now()->year);
                });
            
            $absenBulan = $queryBulan->count();
            $listAbsen = $queryBulan->get()->sortByDesc('rekapKehadiran.tanggal');
        }

        return [
            'siswa' => $siswa,
            'ta' => $ta,
            'absenSemester' => $absenSemester,
            'absenBulan' => $absenBulan,
            'listAbsen' => $listAbsen,
            'bulanTahun' => now()->isoFormat('MMMM YYYY'),
        ];
    }
}