<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KehadiranHarianRelationManager extends RelationManager
{
    // Mengacu ke relasi yang ada di model Siswa
    protected static string $relationship = 'kehadiranHarian';
    protected static ?string $title = 'Riwayat Absensi';

    // Memastikan hanya Admin, Staf, dan Guru/Wali Kelas yang bisa melihat tab ini
    public static function canViewForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf', 'guru']);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            // FITUR AJAIB: Buang status "Hadir" agar yang tampil hanya yang abstain saja
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', '!=', 'Hadir'))
            ->columns([
                Tables\Columns\TextColumn::make('rekapKehadiran.tanggal')
                    ->label('Tanggal Absensi')
                    ->date('d F Y')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status Kehadiran')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Sakit' => 'warning',
                        'Izin' => 'info',
                        'Alpa' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan Tambahan')
                    ->default('-'),
            ])
            ->filters([
                // FILTER KHUSUS BULAN
                Tables\Filters\SelectFilter::make('bulan')
                    ->label('Filter Bulan')
                    ->options([
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ])
                    ->default(now()->format('m')) // TAMBAHAN: Otomatis mengunci ke bulan saat ini
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            // Mencari lewat relasi rekapKehadiran
                            $query->whereHas('rekapKehadiran', function ($q) use ($data) {
                                $q->whereMonth('tanggal', $data['value']);
                            });
                        }
                    }),
                    
                // FILTER KHUSUS TAHUN
                Tables\Filters\SelectFilter::make('tahun')
                    ->label('Filter Tahun')
                    ->options(function () {
                        $years = [];
                        $currentYear = now()->year;
                        // Tampilkan pilihan tahun ini dan 2 tahun ke belakang
                        for ($i = $currentYear - 2; $i <= $currentYear; $i++) {
                            $years[$i] = $i;
                        }
                        return $years;
                    })
                    ->default(now()->year) // Otomatis filter ke tahun saat ini
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('rekapKehadiran', function ($q) use ($data) {
                                $q->whereYear('tanggal', $data['value']);
                            });
                        }
                    })
            ])
            ->groups([
                // Trik ajaib untuk menampilkan "JUMLAH" otomatis ke layar pengguna
                Tables\Grouping\Group::make('status')
                    ->label('Jumlah Berdasarkan Status')
                    ->collapsible(),
            ])
            ->defaultGroup('status') // Tabel otomatis terkelompok saat baru dibuka
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}