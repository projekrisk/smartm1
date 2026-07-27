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

    // Mengatur lebar tabel menjadi setengah halaman agar bisa berdampingan
    protected int | string | array $columnSpan = ['md' => 1];

    // FUNGSI BARU: Membuat judul dinamis dengan nama bulan saat ini
    public function getHeading(): string
    {
        $bulanIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $namaBulan = $bulanIndo[now()->month - 1];
        
        return "Sering Absen ({$namaBulan})";
    }

    // HANYA MUNCUL UNTUK WALI KELAS
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
                    // Pastikan Widget hanya menghitung anak yang MASIH AKTIF
                    ->where(function ($q) {
                        $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])
                          ->orWhereNull('status_siswa');
                    })
                    // Menghitung khusus yang ALPA bulan ini
                    ->withCount(['kehadiranHarian as total_alpa' => function (Builder $query) use ($bulanIni, $tahunIni) {
                        $query->where('status', 'Alpa')
                            ->whereHas('rekapKehadiran', function ($q) use ($bulanIni, $tahunIni) {
                                $q->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni);
                            });
                    }])
                    // Menghitung total semua ketidakhadiran (S/I/A) bulan ini
                    ->withCount(['kehadiranHarian as total_absen' => function (Builder $query) use ($bulanIni, $tahunIni) {
                        $query->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
                            ->whereHas('rekapKehadiran', function ($q) use ($bulanIni, $tahunIni) {
                                $q->whereMonth('tanggal', $bulanIni)->whereYear('tanggal', $tahunIni);
                            });
                    }])
                    // Hanya tampilkan siswa yang punya minimal 1 absen bulan ini
                    ->having('total_absen', '>', 0)
                    ->orderByDesc('total_alpa') // Urutkan dari yang Alpa-nya paling banyak
                    ->orderByDesc('total_absen')
            )
            ->columns([
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Siswa')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('total_alpa')
                    ->label('Jml. Alpa')
                    ->badge()
                    // Jika Alpa lebih dari 2 kali, warnanya merah terang (peringatan)
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
                    // Memaksa Filament menggunakan ID secara eksplisit ($record->id)
                    ->url(fn (Siswa $record): string => \App\Filament\Resources\SiswaResource::getUrl('view', ['record' => $record->id])),
            ])
            ->paginated([5])
            ->emptyStateHeading('Kelas Kondusif')
            ->emptyStateDescription('Seluruh siswa binaan Anda belum pernah absen di bulan ini.')
            ->emptyStateIcon('heroicon-o-face-smile');
    }
}