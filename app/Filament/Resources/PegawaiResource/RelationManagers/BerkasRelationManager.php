<?php

namespace App\Filament\Resources\PegawaiResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class BerkasRelationManager extends RelationManager
{
    protected static string $relationship = 'berkas';
    protected static ?string $title = 'Berkas & Dokumen Pegawai';

    // FUNGSI AJAIB: Memaksa tabel ini tetap bisa diedit/ditambah meskipun berada di Halaman View
    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_berkas')
                    ->label('Nama File / Judul Dokumen')
                    ->placeholder('Contoh: Ijazah S1')
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\Select::make('jenis_berkas')
                    ->label('Jenis Berkas')
                    ->options([
                        'KTP' => 'KTP',
                        'KK' => 'Kartu Keluarga',
                        'Ijazah' => 'Ijazah',
                        'Sertifikat' => 'Sertifikat',
                        'SK Pengangkatan' => 'SK Pengangkatan',
                        'Lainnya' => 'Lainnya',
                    ])
                    ->required(),
                
                Forms\Components\FileUpload::make('file_path')
                    ->label('Upload File (PDF / JPG)')
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/jpg'])
                    ->disk('publik_upload') 
                    ->directory('berkas-pegawai')
                    ->downloadable()
                    ->openable()
                    ->maxSize(1024) 
                    ->required()
                    ->helperText('Hanya file berformat JPG atau PDF dengan ukuran maksimal 1 MB.')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nama_berkas')
            ->columns([
                Tables\Columns\TextColumn::make('nama_berkas')
                    ->label('Nama File')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jenis_berkas')
                    ->label('Jenis File')
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Upload')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jenis_berkas')
                    ->label('Filter Jenis'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Upload Berkas')
                    ->icon('heroicon-o-arrow-up-tray'),
            ])
            ->actions([
                Tables\Actions\Action::make('download')
                    ->label('Lihat / Unduh')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn ($record) => url('/uploads/' . $record->file_path))
                    ->openUrlInNewTab(),
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