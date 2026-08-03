<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class PeringatanAbsensiWidget extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = ['md' => 1];

    public function getHeading(): string
    {
        $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $namaBulan = $bulanIndo[now()->month - 1];
        
        return "Sering Absen ({$namaBulan})";
    }

    public static function canView(): bool
    {
        if (Auth::user()->peran !== 'guru') return false;
        return Kelas::where('wali_kelas_id', Auth::id())->exists();
    }

    public function table(Table $table): Table
    {
        $kelasId = Kelas::where('wali_kelas_id', Auth::id())->value('id');
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        return $table
            ->query(
                Siswa::where('kelas_id', $kelasId)
                    ->where(function ($q) {
                        $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])
                          ->orWhereNull('status_siswa');
                    })
                    ->withCount(['kehadiranHarian as total_alpa' => function (Builder $query) use ($bulanIni, $tahunIni) {
                        $query->where('status', 'Alpa')
                            ->whereHas('rekapKehadiran', function ($q) use ($bulanIni, $tahunIni) {
                                $q->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni);
                            });
                    }])
                    ->withCount(['kehadiranHarian as total_absen' => function (Builder $query) use ($bulanIni, $tahunIni) {
                        $query->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
                            ->whereHas('rekapKehadiran', function ($q) use ($bulanIni, $tahunIni) {
                                $q->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni);
                            });
                    }])
                    ->having('total_absen', '>', 0)
                    ->orderByDesc('total_alpa')
                    ->orderByDesc('total_absen')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Siswa')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('total_alpa')
                    ->label('Jml. Alpa')
                    ->badge()
                    ->color(fn ($state) => $state >= 3 ? 'danger' : ($state > 0 ? 'warning' : 'gray')),
                Tables\Columns\TextColumn::make('total_absen')
                    ->label('Total (S/I/A)')
                    ->badge()
                    ->color('gray'),
            ])
            ->actions([
                Tables\Actions\Action::make('tindak_lanjut')
                    ->label('Cek Riwayat')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('info')
                    ->url(fn (Siswa $record): string => \App\Filament\Resources\SiswaResource::getUrl('view', ['record' => $record->id])),
            ])
            ->paginated([5])
            ->emptyStateHeading('Kelas Kondusif')
            ->emptyStateDescription('Seluruh siswa binaan Anda belum pernah absen di bulan ini.')
            ->emptyStateIcon('heroicon-o-face-smile');
    }
}