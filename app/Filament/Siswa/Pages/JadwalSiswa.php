<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;

class JadwalSiswa extends Page
{
    protected static ?string $title = 'Jadwal Pelajaran';
    protected static string $view = 'filament.siswa.pages.jadwal-siswa';
    
    // Custom URL untuk halaman ini
    protected static ?string $slug = 'jadwal';
    
    // Sembunyikan dari sidebar karena kita panggil dari Dashboard
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
        $siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();
        
        $jadwalGrouped = collect();

        if ($siswa && $siswa->kelas_id) {
            // Urutan hari standar Indonesia
            $hariOrder = [
                'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3, 
                'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6
            ];

            // Menarik jadwal khusus untuk kelas siswa ini
            $jadwals = JadwalPelajaran::with(['mataPelajaran', 'guru'])
                ->where('kelas_id', $siswa->kelas_id)
                ->orderBy('jam_mulai')
                ->get()
                ->sortBy(function($item) use ($hariOrder) {
                    return $hariOrder[$item->hari] ?? 7;
                });

            // Kelompokkan data berdasarkan Hari
            $jadwalGrouped = $jadwals->groupBy('hari');
        }

        return [
            'siswa' => $siswa,
            'jadwalGrouped' => $jadwalGrouped,
        ];
    }
}