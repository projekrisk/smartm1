<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\JadwalPelajaran;
use Illuminate\Support\Facades\Auth;

class JadwalHariIniPengingatWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): string
    {
        $hariIndo = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        
        $hariIni = $hariIndo[date('N')];
        
        return "Pengingat Jadwal Mengajar Anda - Hari " . $hariIni;
    }

    public static function canView(): bool
    {
        return Auth::user()->peran === 'guru';
    }

    public function table(Table $table): Table
    {
        $hariIndo = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 
            5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'
        ];
        $hariIni = $hariIndo[date('N')];

        return $table
            ->query(
                JadwalPelajaran::where('guru_id', Auth::id())
                    ->where('hari', $hariIni)
                    ->orderBy('jam_mulai', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Jam Mengajar')
                    ->formatStateUsing(fn ($record) => date('H:i', strtotime($record->jam_mulai)) . ' s/d ' . date('H:i', strtotime($record->jam_selesai)))
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-clock'),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Di Kelas')
                    ->badge()
                    ->color('success')
                    ->size(Tables\Columns\TextColumn\TextColumnSize::Large),
                Tables\Columns\TextColumn::make('mataPelajaran.nama_pelajaran')
                    ->label('Mata Pelajaran')
                    ->weight('bold'),
            ])
            ->paginated(false)
            ->emptyStateHeading('Tidak Ada Jadwal Hari Ini')
            ->emptyStateDescription('Anda sedang tidak ada jadwal mengajar di kelas manapun hari ini.')
            ->emptyStateIcon('heroicon-o-face-smile');
    }
}