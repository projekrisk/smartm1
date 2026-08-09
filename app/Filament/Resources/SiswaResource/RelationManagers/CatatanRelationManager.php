<?php

namespace App\Filament\Resources\SiswaResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use App\Models\TahunAjaran;

class CatatanRelationManager extends RelationManager
{
    protected static string $relationship = 'catatan';
    protected static ?string $title = 'Catatan';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenis_catatan')
                    ->label('Kategori / Jenis')
                    ->options([
                        'Negatif' => 'Pelanggaran / Kasus',
                        'Positif' => 'Prestasi / Apresiasi',
                        'Bimbingan' => 'Bimbingan / Pembinaan Khusus',
                        'Panggilan Ortu' => 'Tindak Lanjut & Panggilan Ortu',
                        'Biasa' => 'Catatan Biasa / Informasi',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('judul_catatan')
                    ->label('Judul / Perihal')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('tanggal')
                    ->label('Tanggal Kejadian')
                    ->default(now())
                    ->required(),
                Forms\Components\Textarea::make('isi_catatan')
                    ->label('Keterangan & Tindak Lanjut')
                    ->required()
                    ->columnSpanFull(),
                
                Forms\Components\Hidden::make('guru_id')->default(fn () => Auth::id()),
                Forms\Components\Hidden::make('tahun_ajaran_id')->default(fn () => TahunAjaran::where('is_active', true)->first()?->id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('judul_catatan')
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\TextColumn::make('jenis_catatan')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Positif' => 'success',
                        'Negatif' => 'danger',
                        'Bimbingan' => 'info',
                        'Panggilan Ortu' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('judul_catatan')->label('Judul / Perihal'),
                Tables\Columns\TextColumn::make('pencatat.name')->label('Dilaporkan Oleh'),
                Tables\Columns\TextColumn::make('status_tindak_lanjut')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 'Sudah' ? 'success' : 'danger'),
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Tambah Catatan Baru'),
            ])
            ->actions([
                Tables\Actions\Action::make('tindak_lanjut')
                    ->label('Tindak Lanjut')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => 
                        $record->status_tindak_lanjut === 'Belum' && 
                        (Auth::user()->peran === 'admin' || $record->siswa->kelas->wali_kelas_id === Auth::id())
                    )
                    ->form([
                        Forms\Components\Textarea::make('tindak_lanjut')
                            ->label('Hasil Tindak Lanjut')
                            ->required()->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status_tindak_lanjut' => 'Sudah',
                            'tindak_lanjut' => $data['tindak_lanjut'],
                            'tanggal_tindak_lanjut' => now(),
                            'ditindaklanjuti_oleh' => Auth::id(),
                        ]);
                    }),

                Tables\Actions\ViewAction::make()
                    ->url(fn ($record): string => \App\Filament\Resources\CatatanSiswaResource::getUrl('view', ['record' => $record])),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->peran === 'admin'),
                ]),
            ]);
    }
}