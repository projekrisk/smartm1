<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RiwayatKelasRelationManager extends RelationManager
{
    protected static string $relationship = 'riwayatKelas';
    protected static ?string $title = 'Riwayat';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'nama_tahun')
                    // MODIFIKASI: Menampilkan nama tahun & semester di dropdown
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_tahun} ({$record->semester})")
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('kelas_id')
                    ->label('Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->required(),
                Forms\Components\TextInput::make('status_riwayat')
                    ->label('Status (Contoh: Naik Kelas / Mutasi)')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status_riwayat')
            ->columns([
                Tables\Columns\TextColumn::make('tahunAjaran.nama_tahun')->label('Tahun Ajaran')->sortable(),
                // TAMBAHAN: Memunculkan Kolom Semester
                Tables\Columns\TextColumn::make('tahunAjaran.semester')->label('Semester')->badge()->color('info')->sortable(),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas')->sortable(),
                Tables\Columns\TextColumn::make('status_riwayat')->label('Status')->badge(),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Dicatat')->dateTime('d M Y')->sortable(),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Riwayat Manual'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}