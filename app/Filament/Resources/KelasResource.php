<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KelasResource\Pages;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;
    protected static ?string $slug = 'kelas';
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';    
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $pluralModelLabel = 'Kelas';
    protected static ?string $modelLabel = 'Kelas';
    protected static ?int $navigationSort = 5;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Kelas')
                    ->schema([
                        Forms\Components\Select::make('tingkat_id')
                            ->label('Tingkat Kelas')
                            ->relationship('tingkat', 'nama_tingkat')
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('nama_kelas')
                            ->label('Nama Kelas')
                            ->placeholder('Contoh: X IPA 1 / X-E1')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('wali_kelas_id')
                            ->label('Wali Kelas')
                            ->relationship('waliKelas', 'name', fn ($query) => $query->where('peran', 'guru'))
                            ->searchable()
                            ->preload()
                            ->nullable(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tingkat.nama_tingkat')
                    ->label('Tingkat')
                    ->badge()
                    ->color('primary')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waliKelas.name')
                    ->label('Wali Kelas')
                    ->searchable()
                    ->sortable()
                    ->default('Belum diatur'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tingkat_id')
                    ->label('Filter Tingkat')
                    ->relationship('tingkat', 'nama_tingkat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}