<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Kelas;
use App\Models\RekapKehadiran;
use App\Models\KehadiranHarian;
use Illuminate\Support\Facades\Auth;

class AbsensiKelasHariIniWidget extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = ['md' => 1];

    public function getHeading(): string
    {
        $kelasId = Kelas::where('wali_kelas_id', Auth::id())->value('id');
        $rekapHariIni = RekapKehadiran::where('kelas_id', $kelasId)->whereDate('tanggal', today())->first();
        
        $jumlahAbsen = 0;
        if ($rekapHariIni) {
            $jumlahAbsen = KehadiranHarian::where('rekap_kehadiran_id', $rekapHariIni->id)
                ->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
                ->count();
        }

        return "Tidak Hadir Hari Ini ({$jumlahAbsen})";
    }

    public static function canView(): bool
    {
        if (Auth::user()->peran !== 'guru') return false;
        return Kelas::where('wali_kelas_id', Auth::id())->exists();
    }

    public function table(Table $table): Table
    {
        $kelasId = Kelas::where('wali_kelas_id', Auth::id())->value('id');
        
        $rekapHariIni = RekapKehadiran::where('kelas_id', $kelasId)->whereDate('tanggal', today())->first();
        $rekapId = $rekapHariIni ? $rekapHariIni->id : 0;

        return $table
            ->query(
                KehadiranHarian::where('rekap_kehadiran_id', $rekapId)
                    ->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
            )
            ->columns([
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
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(20)
                    ->default('-'),
            ])
            ->paginated([5])
            ->emptyStateHeading('Semua Siswa Hadir')
            ->emptyStateDescription('Belum ada laporan absen dari TU untuk hari ini, atau seluruh siswa hadir.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}