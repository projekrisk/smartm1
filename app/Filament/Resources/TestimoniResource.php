<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimoniResource\Pages;
use App\Models\Testimoni;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TestimoniResource extends Resource
{
    protected static ?string $model = Testimoni::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Ulasan & Rating';
    protected static ?string $pluralModelLabel = 'Ulasan Siswa';
    protected static ?string $navigationGroup = 'Sistem';
    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool { return false; }
    public static function canViewAny(): bool { return Auth::user()->peran === 'admin'; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')->label('Nama Siswa')->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('siswa.kelas.nama_kelas')->label('Kelas')->badge(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('Penilaian')
                    ->formatStateUsing(fn ($state) => str_repeat('⭐', $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('pesan')->label('Ulasan/Testimoni')->wrap(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([ Tables\Actions\DeleteAction::make()->label('Hapus Ulasan') ])
            ->bulkActions([ Tables\Actions\BulkActionGroup::make([ Tables\Actions\DeleteBulkAction::make() ]) ]);
    }

    public static function getPages(): array { return [ 'index' => Pages\ListTestimonis::route('/') ]; }
}