<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JenisNilaiResource\Pages;
use App\Models\JenisNilai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JenisNilaiResource extends Resource
{
    protected static ?string $model = JenisNilai::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Admin';    
    protected static ?string $navigationLabel = 'Jenis Nilai';
    protected static ?int $navigationSort = 16;    
    public static function canViewAny(): bool
    {
        return auth()->user()->peran === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama Jenis Penilaian')
                    ->placeholder('Contoh: Sumatif 1')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Jenis Penilaian')
                    ->searchable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageJenisNilais::route('/'),
        ];
    }
}