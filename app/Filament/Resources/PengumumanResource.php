<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengumumanResource\Pages;
use App\Models\Pengumuman;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PengumumanResource extends Resource
{
    protected static ?string $model = Pengumuman::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'Pengumuman';
    protected static ?string $slug = 'pengumuman';
    protected static ?int $navigationSort = 16;
    
    public static function canViewAny(): bool
    {
        return Auth::user()->peran === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Tulis Pengumuman')
                    ->schema([
                        Forms\Components\TextInput::make('judul')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\RichEditor::make('isi')
                            ->label('Isi / Detail Pengumuman')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Toggle::make('is_aktif')
                            ->label('Tampilkan di Dasbor')
                            ->default(true),
                        Forms\Components\Hidden::make('dibuat_oleh')
                            ->default(fn () => Auth::id()),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('judul')->searchable()->weight('bold'),
                Tables\Columns\IconColumn::make('is_aktif')->label('Status Tampil')->boolean(),
                Tables\Columns\TextColumn::make('pembuat.name')->label('Penulis'),
                Tables\Columns\TextColumn::make('created_at')->label('Diterbitkan')->dateTime('d M Y, H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPengumuman::route('/'),
            'create' => Pages\CreatePengumuman::route('/create'),
            'edit' => Pages\EditPengumuman::route('/{record}/edit'),
        ];
    }
}