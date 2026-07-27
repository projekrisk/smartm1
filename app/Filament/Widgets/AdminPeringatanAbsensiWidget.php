<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class AdminPeringatanAbsensiWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    // Memakan setengah layar (1 kolom dari total 2 kolom Grid) di Desktop
    protected int | string | array $columnSpan = ['md' => 1];

    public function getHeading(): string
    {
        $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $namaBulan = $bulanIndo[now()->month - 1];
        
        return "Sering Bolos/Absen ({$namaBulan})";
    }

    // Hanya untuk Admin dan Staf TU
    public static function canView(): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf']);
    }

    public function table(Table $table): Table
    {
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        return $table
            ->query(
                Siswa::with('kelas')
                    ->where(function ($q) {
                        $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])
                          ->orWhereNull('status_siswa');
                    })
                    // Hitung jumlah Alpa bulan ini
                    ->withCount(['kehadiranHarian as total_alpa' => function (Builder $query) use ($bulanIni, $tahunIni) {
                        $query->where('status', 'Alpa')
                            ->whereHas('rekapKehadiran', function ($q) use ($bulanIni, $tahunIni) {
                                $q->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni);
                            });
                    }])
                    // Hitung total semua S/I/A bulan ini
                    ->withCount(['kehadiranHarian as total_absen' => function (Builder $query) use ($bulanIni, $tahunIni) {
                        $query->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
                            ->whereHas('rekapKehadiran', function ($q) use ($bulanIni, $tahunIni) {
                                $q->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni);
                            });
                    }])
                    // Hanya tampilkan jika setidaknya pernah absen 1 kali di bulan ini
                    ->having('total_absen', '>', 0)
                    ->orderByDesc('total_alpa') // Prioritaskan yang Alpa-nya paling banyak
                    ->orderByDesc('total_absen')
            )
            ->columns([
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Siswa')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('total_alpa')
                    ->label('Alpa')
                    ->badge()
                    ->color(fn ($state) => $state >= 3 ? 'danger' : ($state > 0 ? 'warning' : 'gray')),
                Tables\Columns\TextColumn::make('total_absen')
                    ->label('Total (S/I/A)')
                    ->badge()
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('cek_data')
                    ->label('Cek')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('info')
                    ->url(fn (Siswa $record): string => \App\Filament\Resources\SiswaResource::getUrl('view', ['record' => $record->id])),
            ])
            ->paginated([10]) // Paginasi per 10 data
            ->emptyStateHeading('Semua Siswa Terkendali')
            ->emptyStateDescription('Belum ada siswa yang dilaporkan absen di bulan ini.')
            ->emptyStateIcon('heroicon-o-face-smile');
    }
}