<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratPanggilanResource\Pages;
use App\Models\SuratPanggilan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SuratPanggilanResource extends Resource
{
    protected static ?string $model = SuratPanggilan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';    
    protected static ?string $slug = 'surat-panggilan';
    protected static ?string $navigationLabel = 'Surat Panggilan';
    protected static ?string $pluralModelLabel = 'Surat Panggilan';
    protected static ?string $navigationGroup = 'Kesiswaan';    
    protected static ?string $modelLabel = 'Surat Panggilan';
    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_surat')
                            ->label('Nomor Surat')
                            ->placeholder('Contoh: 001/SP/SMK/2026')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        Forms\Components\Select::make('siswa_id')
                            ->label('Nama Siswa')
                            ->relationship('siswa', 'nama_lengkap')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('tanggal_surat')
                            ->label('Tanggal Surat Dibuat')
                            ->default(now())
                            ->required(),
                            
                        Forms\Components\Hidden::make('dibuat_oleh')
                            ->default(fn () => Auth::id()),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Pemanggilan')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_panggilan')
                            ->label('Tanggal Pertemuan')
                            ->required(),
                        Forms\Components\TimePicker::make('waktu_panggilan')
                            ->label('Waktu Pertemuan')
                            ->required(),
                        Forms\Components\TextInput::make('tempat_pertemuan')
                            ->label('Tempat Pertemuan')
                            ->placeholder('Contoh: Ruang Bimbingan Konseling (BK)')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('alasan_panggilan')
                            ->label('Maksud / Alasan Pemanggilan')
                            ->placeholder('Jelaskan secara singkat alasan pemanggilan orang tua/siswa...')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->label('Status Surat')
                            ->options([
                                'Dibuat' => 'Baru Dibuat',
                                'Selesai' => 'Selesai (Pertemuan Terjadi)',
                                'Dibatalkan' => 'Dibatalkan',
                            ])
                            ->default('Dibuat')
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_panggilan')
                    ->label('Tgl. Pertemuan')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_panggilan')
                    ->label('Waktu')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Dibuat' => 'warning',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'primary',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'Dibuat' => 'Baru Dibuat',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratPanggilans::route('/'),
            'create' => Pages\CreateSuratPanggilan::route('/create'),
            'edit' => Pages\EditSuratPanggilan::route('/{record}/edit'),
        ];
    }
}