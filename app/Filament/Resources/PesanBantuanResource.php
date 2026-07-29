<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesanBantuanResource\Pages;
use App\Models\PesanBantuan;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class PesanBantuanResource extends Resource
{
    protected static ?string $model = PesanBantuan::class;
    protected static ?string $slug = 'pesan-bantuan';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationLabel = 'Pesan & Bantuan';
    protected static ?int $navigationSort = 2;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin']);
    }
    
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('is_read_admin', false)->count();
        return $count > 0 ? (string) $count : null;
    }
    public static function getNavigationBadgeColor(): ?string { return 'danger'; }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\ViewEntry::make('chat_room')
                ->hiddenLabel()
                ->columnSpanFull()
                ->view('filament.infolists.admin-chat-bantuan')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction('view')
            ->columns([
                Tables\Columns\TextColumn::make('updated_at')->label('Aktivitas Terakhir')->dateTime('d M Y, H:i')->sortable(),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')->label('Siswa Pengirim')->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('siswa.kelas.nama_kelas')->label('Kelas'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) { 'Open' => 'danger', 'Diproses' => 'warning', 'Selesai' => 'success', default => 'gray'}),
            ])
            ->defaultSort('updated_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()->label('Buka Obrolan'),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPesanBantuans::route('/'),
            'view' => Pages\ViewPesanBantuan::route('/{record}'),
        ];
    }
}