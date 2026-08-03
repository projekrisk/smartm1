<?php

namespace App\Filament\Resources\KehadiranHarianResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class DaftarHadirRelationManager extends RelationManager
{
    protected static string $relationship = 'kehadiranHarian';
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
                    ])
                    ->required(),
                Forms\Components\TextInput::make('keterangan')
                    ->label('Keterangan Tambahan')
                    ->placeholder('Contoh: Surat dari Dokter / Dispensasi Lomba'),
            ])->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->recordAction('edit') 
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir' => 'success',
                        'Sakit' => 'warning',
                        'Izin' => 'info',
                        'Alpa' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30)
                    ->default('-'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Ubah Status')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading(fn ($record) => 'Kehadiran: ' . ($record->siswa->nama_lengkap ?? '-'))
                    ->modalWidth('md'),
            ])
            ->paginated(false);
    }
}