<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MataPelajaranResource\Pages;
use App\Models\MataPelajaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MataPelajaranResource extends Resource
{
    protected static ?string $model = MataPelajaran::class;
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?string $slug = 'mata-pelajaran';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';    
    protected static ?string $navigationLabel = 'Mata Pelajaran';
    protected static ?string $pluralModelLabel = 'Mata Pelajaran';
    protected static ?string $modelLabel = 'Mata Pelajaran';
    protected static ?int $navigationSort = 6;

    public static function canViewAny(): bool
    {
        return auth()->user()->peran === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Master Data Pelajaran')
                    ->description('Data statis mata pelajaran. Siapa yang mengajar mapel ini diatur pada menu Jadwal Mengajar.')
                    ->schema([
                        Forms\Components\TextInput::make('kode_pelajaran')
                            ->label('Kode Mapel (Singkatan)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(50),
                        Forms\Components\TextInput::make('nama_pelajaran')
                            ->label('Nama Pelajaran Lengkap')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_pelajaran')
                    ->label('Kode')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_pelajaran')
                    ->label('Nama Pelajaran')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMataPelajarans::route('/'),
        ];
    }
}