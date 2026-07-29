<?php

namespace App\Filament\Resources\PegawaiResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pegawai;

class PegawaiStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Pegawai::count();
        
        $kepsek = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->count();
        $guru = Pegawai::where('jenis_ptk', 'Guru')->count();
        $tendik = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->count();

        $pns = Pegawai::where('status_kepegawaian', 'PNS')->count();
        $pppk = Pegawai::where('status_kepegawaian', 'PPPK')->count();
        $honorer = Pegawai::where('status_kepegawaian', 'Honorer')->count();
        $gty = Pegawai::where('status_kepegawaian', 'GTY/PTY')->count();

        return [
            Stat::make('Total Seluruh Pegawai', $total)
                ->description("Kepsek: $kepsek | Guru: $guru | Tendik: $tendik")
                ->icon('heroicon-o-users')
                ->color('primary'),
                
            Stat::make('Pegawai ASN', $pns + $pppk)
                ->description("PNS: $pns Orang | PPPK: $pppk Orang")
                ->icon('heroicon-o-check-badge')
                ->color('success'),
                
            Stat::make('Pegawai Non-ASN', $honorer + $gty)
                ->description("Honorer: $honorer Orang | GTY/PTY: $gty Orang")
                ->icon('heroicon-o-briefcase')
                ->color('warning'),
        ];
    }
}