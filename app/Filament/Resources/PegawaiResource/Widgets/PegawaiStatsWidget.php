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
        $pns = Pegawai::where('status_kepegawaian', 'PNS')->count();
        $pppk = Pegawai::where('status_kepegawaian', 'PPPK')->count();
        $honorer = Pegawai::where('status_kepegawaian', 'Honorer')->count();
        $gty = Pegawai::where('status_kepegawaian', 'GTY/PTY')->count();

        return [
            Stat::make('Total Seluruh Pegawai', $total . ' Orang')
                ->icon('heroicon-o-users')
                ->color('primary'),
                
            Stat::make('Pegawai ASN', ($pns + $pppk) . ' Orang')
                ->description("PNS: $pns Orang | PPPK: $pppk Orang")
                ->icon('heroicon-o-check-badge')
                ->color('success'),
                
            Stat::make('Pegawai Non-ASN', ($honorer + $gty) . ' Orang')
                ->description("Honorer: $honorer Orang | GTY/PTY: $gty Orang")
                ->icon('heroicon-o-briefcase')
                ->color('warning'),
        ];
    }
}