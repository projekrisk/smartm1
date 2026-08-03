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
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::user()->peran === 'guru';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
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
                Tables\Actions\Action::make('isi_absen')
                    ->label('Cek Absensi')
                    ->icon('heroicon-o-check-circle')
                    ->color('primary')
                    ->url(fn (): string => url('/admin/absensi-pelajaran')),
            ]);
    }
}