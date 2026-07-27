<?php

namespace App\Filament\Resources\KehadiranHarianResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\KehadiranHarian;

class KehadiranHarianStats extends BaseWidget
{
    protected function getStats(): array
    {
        $today = today();
        
        // Menghitung status dari detail absensi yang rekapnya adalah hari ini
        $sakit = KehadiranHarian::whereHas('rekapKehadiran', function ($q) use ($today) {
            $q->whereDate('tanggal', $today);
        })->where('status', 'Sakit')->count();

        $izin = KehadiranHarian::whereHas('rekapKehadiran', function ($q) use ($today) {
            $q->whereDate('tanggal', $today);
        })->where('status', 'Izin')->count();

        $alpa = KehadiranHarian::whereHas('rekapKehadiran', function ($q) use ($today) {
            $q->whereDate('tanggal', $today);
        })->where('status', 'Alpa')->count();

        return [
            Stat::make('Sakit', $sakit)
                ->description('Siswa sakit hari ini')
                ->descriptionIcon('heroicon-m-plus-circle')
                ->color('warning'),
            Stat::make('Izin', $izin)
                ->description('Siswa izin hari ini')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),
            Stat::make('Alpa', $alpa)
                ->description('Siswa tanpa keterangan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}