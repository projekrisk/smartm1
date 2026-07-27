<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\KehadiranHarian;
use Illuminate\Support\Facades\Auth;

class AdminAbsensiHariIniWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    // Memakan setengah layar (1 kolom dari total 2 kolom Grid) di Desktop
    protected int | string | array $columnSpan = ['md' => 1];

    public function getHeading(): string
    {
        // Hitung total absen hari ini
        $jumlahAbsen = KehadiranHarian::whereHas('rekapKehadiran', function ($q) {
            $q->whereDate('tanggal', today());
        })->whereIn('status', ['Sakit', 'Izin', 'Alpa'])->count();

        return "Absen Hari Ini ({$jumlahAbsen} Siswa)";
    }

    // Hanya untuk Admin dan Staf TU
    public static function canView(): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                KehadiranHarian::with(['siswa.kelas', 'rekapKehadiran'])
                    ->whereHas('rekapKehadiran', function ($q) {
                        $q->whereDate('tanggal', today());
                    })
                    ->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
            )
            ->columns([
                Tables\Columns\TextColumn::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Nama Siswa')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sakit' => 'warning',
                        'Izin' => 'info',
                        'Alpa' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated([10]) // Paginasi per 10 data
            ->emptyStateHeading('Semua Hadir / Belum Diabsen')
            ->emptyStateDescription('Belum ada laporan ketidakhadiran dari Wali Kelas/TU untuk hari ini.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}