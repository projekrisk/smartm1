<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriSuratResource\Pages;
use App\Models\KategoriSurat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KategoriSuratResource extends Resource
{
    protected static ?string $model = KategoriSurat::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?string $navigationLabel = 'Kategori Surat';
    protected static ?string $pluralModelLabel = 'Kategori Surat';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Nama Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('nama_kategori')
                            ->placeholder('Contoh: Kesiswaan, Kepegawaian')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Daftar Jenis Surat di Kategori Ini')
                    ->schema([
                        Forms\Components\Repeater::make('jenisSurat')
                            ->relationship()
                            ->schema([
                                Forms\Components\TextInput::make('nama_surat')
                                    ->required()->placeholder('Contoh: Surat Dispensasi'),
                                Forms\Components\TextInput::make('url_create')
                                    ->label('URL Halaman Tambah Surat')
                                    ->required()->placeholder('Contoh: /admin/surat-dispensasis/create'),
                                Forms\Components\TextInput::make('deskripsi')
                                    ->placeholder('Penjelasan singkat surat'),
                                Forms\Components\Select::make('icon')
                                    ->options([
                                        'heroicon-o-document-text' => 'Dokumen Biasa',
                                        'heroicon-o-academic-cap' => 'Akademik/Siswa',
                                        'heroicon-o-user-group' => 'Grup/Orang',
                                        'heroicon-o-envelope' => 'Amplop',
                                        'heroicon-o-paper-airplane' => 'Pesawat Kertas',
                                    ])->default('heroicon-o-document-text')->searchable(),
                            ])->columns(2)
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_kategori')->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('jenis_surat_count')->counts('jenisSurat')->label('Jumlah Format Surat')->badge(),
            ])
            ->actions([ Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make() ]);
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