<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriSuratResource\Pages;
use App\Filament\Resources\KategoriSuratResource\RelationManagers;
use App\Models\KategoriSurat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KategoriSuratResource extends Resource
{
    protected static ?string $model = KategoriSurat::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?string $navigationLabel = 'Kategori Surat';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_kategori')->required()->placeholder('Cth: Surat Kesiswaan'),
            Forms\Components\TextInput::make('kode_prefix')->required()->placeholder('Cth: 400.03.08'),
            Forms\Components\TextInput::make('kode_suffix')->default('SMA.01-MLP')->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nama_kategori')->searchable(),
            Tables\Columns\TextColumn::make('kode_prefix'),
            Tables\Columns\TextColumn::make('kode_suffix'),
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
            'index' => Pages\ListKategoriSurats::route('/'),
            'create' => Pages\CreateKategoriSurat::route('/create'),
            'edit' => Pages\EditKategoriSurat::route('/{record}/edit'),
        ];
    }
}
