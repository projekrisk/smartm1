<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunAjaranResource\Pages;
use App\Models\TahunAjaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TahunAjaranResource extends Resource
{
    protected static ?string $model = TahunAjaran::class;

    // Ikon di menu navigasi
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    
    // Label menu dalam Bahasa Indonesia
    protected static ?string $navigationLabel = 'Tahun Ajaran';
    protected static ?string $pluralModelLabel = 'Tahun Ajaran';
    protected static ?string $slug = 'tahun-ajaran';
    protected static ?string $modelLabel = 'Tahun Ajaran';

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_tahun')
                    ->label('Nama Tahun Ajaran')
                    ->placeholder('Contoh: 2026/2027')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('semester')
                    ->label('Semester')
                    ->options([
                        'Ganjil' => 'Ganjil',
                        'Genap' => 'Genap',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->helperText('Tandai jika ini adalah tahun ajaran yang sedang berjalan.')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_tahun')
                    ->label('Tahun Ajaran')
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester'),
                
                // MENGUBAH ICON MENJADI SAKLAR (TOGGLE) YANG BISA DIKLIK LANGSUNG
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Status Aktif')
                    ->afterStateUpdated(function ($record, $state, \Livewire\Component $livewire) {
                        // Jika saklar dihidupkan, paksa tabel untuk me-refresh layar 
                        // agar saklar tahun ajaran lain yang tadinya menyala otomatis mati di layar
                        if ($state) {
                            $livewire->dispatch('$refresh');
                        }
                    }),

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
            'index' => Pages\ListTahunAjarans::route('/'),
            'create' => Pages\CreateTahunAjaran::route('/create'),
            'edit' => Pages\EditTahunAjaran::route('/{record}/edit'),
        ];
    }
}