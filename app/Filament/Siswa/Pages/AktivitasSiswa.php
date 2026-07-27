<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\JadwalPelajaran;

class AktivitasSiswa extends Page
{
    protected static ?string $title = 'Aktivitas Saya';
    
    // Jangan tampilkan di menu Sidebar bawaan (karena kita pakai Bottom Navbar)
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.siswa.pages.aktivitas-siswa';

    protected function getViewData(): array
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->first();
        
        $jadwalSeminggu = [];
        $hariUrut = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        if ($siswa && $siswa->kelas_id) {
            // Ambil semua jadwal untuk kelas siswa ini
            $jadwals = JadwalPelajaran::with(['mataPelajaran', 'guru'])
                ->where('kelas_id', $siswa->kelas_id)
                ->orderBy('jam_mulai', 'asc')
                ->get();

            // Kelompokkan berdasarkan hari
            foreach ($hariUrut as $hari) {
                $jadwalSeminggu[$hari] = $jadwals->where('hari', $hari)->values();
            }
        }

        // Cari tahu hari ini hari apa
        $hariIndo = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        $hariIni = $hariIndo[date('N')];

        return [
            'siswa' => $siswa,
            'jadwalSeminggu' => $jadwalSeminggu,
            'hariIni' => $hariIni,
        ];
    }
}