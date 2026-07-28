<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TingkatResource\Pages;
use App\Models\Tingkat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TingkatResource extends Resource
{
    protected static ?string $model = Tingkat::class;
    protected static ?string $navigationGroup = 'Manajemen Kelas';
    protected static ?string $slug = 'tingkat';
    protected static ?string $navigationIcon = 'heroicon-o-numbered-list';
    protected static ?string $navigationLabel = 'Tingkat';
    protected static ?string $pluralModelLabel = 'Tingkat';
    protected static ?string $modelLabel = 'Tingkat';
    protected static ?int $navigationSort = 4;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_tingkat')
                    ->label('Nama Tingkat')
                    ->placeholder('Contoh: 10, 11, atau 12')
                    ->required()
                    ->numeric()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_tingkat')
                    ->label('Nama Tingkat')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTingkats::route('/'),
            'create' => Pages\CreateTingkat::route('/create'),
            'edit' => Pages\EditTingkat::route('/{record}/edit'),
        ];
    }
}