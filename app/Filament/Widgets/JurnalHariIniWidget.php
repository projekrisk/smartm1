<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\JurnalGuru;
use Illuminate\Support\Facades\Auth;

class JurnalHariIniWidget extends BaseWidget
{
    protected static ?string $heading = 'Aktivitas Mengajar Anda Hari Ini';
    
    // Membuat tabel ini memanjang penuh (full width) ke samping
    protected int | string | array $columnSpan = 'full';

    // Widget ini HANYA BOLEH DILIHAT oleh Guru
    public static function canView(): bool
    {
        return Auth::user()->peran === 'guru';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Hanya memanggil jurnal milik guru yang login DAN tanggalnya adalah hari ini
                JurnalGuru::where('guru_id', Auth::id())
                    ->whereDate('tanggal', today())
                    ->orderBy('jam_mulai', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->time('H:i')
                    ->label('Mulai'),
                Tables\Columns\TextColumn::make('jam_selesai')
                    ->time('H:i')
                    ->label('Selesai'),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('mataPelajaran.nama_pelajaran')
                    ->label('Mata Pelajaran')
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('materi_pembahasan')
                    ->label('Materi')
                    ->limit(40),
            ])
            ->actions([
                // Tombol jalan pintas untuk langsung pergi ke halaman Absensi Pelajaran
                Tables\Actions\Action::make('isi_absen')
                    ->label('Cek Absensi')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    // PERBAIKAN MUTLAK: Menggunakan URL langsung agar tidak terjadi Error "Class Not Found"
                    ->url(fn (): string => url('/admin/absensi-pelajaran')),
            ]);
    }
}