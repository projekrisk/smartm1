<?php

namespace App\Filament\Resources\PegawaiResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pegawai;

class PegawaiStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Keseluruhan
        $total = Pegawai::count();
        $totalL = Pegawai::where('jenis_kelamin', 'Laki-laki')->count();
        $totalP = Pegawai::where('jenis_kelamin', 'Perempuan')->count();
        
        // 2. Kepala Sekolah
        $kepsek = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->count();
        $kepsekL = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->where('jenis_kelamin', 'Laki-laki')->count();
        $kepsekP = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->where('jenis_kelamin', 'Perempuan')->count();

        // 3. Guru
        $guru = Pegawai::where('jenis_ptk', 'Guru')->count();
        $guruL = Pegawai::where('jenis_ptk', 'Guru')->where('jenis_kelamin', 'Laki-laki')->count();
        $guruP = Pegawai::where('jenis_ptk', 'Guru')->where('jenis_kelamin', 'Perempuan')->count();

        // 4. Tenaga Kependidikan
        $tendik = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->count();
        $tendikL = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->where('jenis_kelamin', 'Laki-laki')->count();
        $tendikP = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->where('jenis_kelamin', 'Perempuan')->count();

        // Status Kepegawaian (tetap dipertahankan)
        $pns = Pegawai::where('status_kepegawaian', 'PNS')->count();
        $pppk = Pegawai::where('status_kepegawaian', 'PPPK')->count();
        $honorer = Pegawai::where('status_kepegawaian', 'Honorer')->count();
        $gty = Pegawai::where('status_kepegawaian', 'GTY/PTY')->count();

        return [
            // Baris 1: Detail per Jenis PTK & Gender
            Stat::make('Total Seluruh Pegawai', $total . ' Orang')
                ->description("Laki-laki: $totalL | Perempuan: $totalP")
                ->icon('heroicon-o-users')
                ->color('primary'),
                
            Stat::make('Guru / Pendidik', $guru . ' Orang')
                ->description("Laki-laki: $guruL | Perempuan: $guruP")
                ->icon('heroicon-o-book-open')
                ->color('info'),
                
            Stat::make('Tenaga Kependidikan', $tendik . ' Orang')
                ->description("Laki-laki: $tendikL | Perempuan: $tendikP")
                ->icon('heroicon-o-briefcase')
                ->color('warning'),

            // Baris 2: Kepsek & ASN / Non-ASN
            Stat::make('Kepala Sekolah', $kepsek . ' Orang')
                ->description("Laki-laki: $kepsekL | Perempuan: $kepsekP")
                ->icon('heroicon-o-academic-cap')
                ->color('success'),
                
            Stat::make('Pegawai ASN', ($pns + $pppk) . ' Orang')
                ->description("PNS: $pns Orang | PPPK: $pppk Orang")
                ->icon('heroicon-o-check-badge')
                ->color('success'),
                
            Stat::make('Pegawai Non-ASN', ($honorer + $gty) . ' Orang')
                ->description("Honorer: $honorer Orang | GTY/PTY: $gty Orang")
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}