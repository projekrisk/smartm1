<?php

namespace App\Filament\Resources\JurnalGuruResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class KehadiranRelationManager extends RelationManager
{
    protected static string $relationship = 'kehadiranPelajaran';
    protected static ?string $title = 'Daftar Hadir Siswa';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('status')
                    ->label('Ubah Status Kehadiran')
                    ->options([
                        'Hadir' => 'Hadir',
                        'Sakit' => 'Sakit',
                        'Izin' => 'Izin',
                        'Alpa' => 'Alpa',
                        'Terlambat' => 'Terlambat',
                        'Dispensasi' => 'Dispensasi', // Tambahkan Dispensasi di form
                    ])
                    ->required()
                    // 🌟 KUNCI OPSI JIKA STATUSNYA DISPENSASI
                    ->disabled(fn ($record) => $record && $record->status === 'Dispensasi'),
                    
                Forms\Components\TextInput::make('keterangan')
                    ->label('Keterangan Tambahan')
                    ->placeholder('Contoh: Surat di meja piket')
                    // 🌟 KUNCI JUGA KETERANGANNYA JIKA DISPENSASI
                    ->disabled(fn ($record) => $record && $record->status === 'Dispensasi'),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query
                ->join('siswa', 'kehadiran_pelajaran.siswa_id', '=', 'siswa.id')
                ->orderBy('siswa.nama_lengkap', 'asc')
                ->select('kehadiran_pelajaran.*')
            )
            
            ->recordTitleAttribute('id')
            ->recordAction('edit')
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    // Beri penanda visual berupa gembok jika siswa tersebut Dispensasi
                    ->description(fn ($record) => $record->status === 'Dispensasi' ? 'Terkunci (Dispensasi)' : null),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Sakit' => 'warning',
                        'Izin' => 'info',
                        'Alpa' => 'danger',
                        'Terlambat' => 'purple',
                        'Dispensasi' => 'gray', // Beri warna beda untuk Dispensasi
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->default('-'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading(fn ($record) => 'Kehadiran: ' . ($record->siswa->nama_lengkap ?? '-'))
                    ->modalWidth('md')
                    // 🌟 SEMBUNYIKAN TOMBOL "UBAH" JIKA DISPENSASI
                    ->hidden(fn ($record) => $record->status === 'Dispensasi'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('ubah_status_massal')
                        ->label('Ubah Status Massal')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary')
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options(['Hadir'=>'Hadir', 'Sakit'=>'Sakit', 'Izin'=>'Izin', 'Alpa'=>'Alpa', 'Terlambat'=>'Terlambat'])
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data) {
                            foreach ($records as $record) { 
                                // 🌟 CEGAH UBAH MASSAL JIKA DISPENSASI
                                if($record->status !== 'Dispensasi') {
                                    $record->update(['status' => $data['status']]); 
                                }
                            }
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->paginated(false);
    }
}