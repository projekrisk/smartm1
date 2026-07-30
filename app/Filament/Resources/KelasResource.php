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
    protected static ?string $navigationGroup = 'Data Master';    
    protected static ?string $modelLabel = 'Kelas';
    protected static ?int $navigationSort = 3;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->peran === 'admin';
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->peran === 'admin';
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->peran === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    
                Forms\Components\Select::make('wali_kelas_id')
                    ->label('Wali Kelas')
                    ->relationship('waliKelas', 'name') 
                    ->searchable()
                    ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Nama Kelas')
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('waliKelas.name')
                    ->label('Wali Kelas')
                    ->searchable()
                    ->sortable()
                    ->default('Belum Diatur'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('wali_kelas_id')
                    ->label('Filter Wali Kelas')
                    ->relationship('waliKelas', 'name'), 
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}