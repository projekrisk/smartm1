<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JadwalPelajaranResource\Pages;
use App\Models\JadwalPelajaran;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class JadwalPelajaranResource extends Resource
{
    protected static ?string $model = JadwalPelajaran::class;
    protected static ?string $slug = 'jadwal-mengajar';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Jadwal Mengajar';
    protected static ?string $pluralModelLabel = 'Jadwal Mengajar';
    protected static ?int $navigationSort = 7;

    public static function canCreate(): bool 
    { 
        return in_array(Auth::user()->peran, ['admin', 'staf']); 
    }
    
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool 
    { 
        return in_array(Auth::user()->peran, ['admin', 'staf']); 
    }
    
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool 
    { 
        return in_array(Auth::user()->peran, ['admin', 'staf']); 
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (Auth::user()->peran === 'guru') {
            $query->where('guru_id', Auth::id());
        }
        
        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Penugasan Utama')
                    ->schema([
                        Forms\Components\Hidden::make('tahun_ajaran_id')
                            ->default(fn () => \App\Models\TahunAjaran::where('is_active', true)->first()?->id),

                        Forms\Components\Select::make('guru_id')
                            ->label('Guru Pengajar')
                            ->relationship('guru', 'name', fn ($query) => $query->where('peran', 'guru'))
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('mata_pelajaran_id')
                            ->label('Mata Pelajaran')
                            ->options(MataPelajaran::pluck('nama_pelajaran', 'id'))
                            ->searchable()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Rincian Kelas & Waktu')
                    ->schema([
                        
                        Forms\Components\Repeater::make('sesi_mengajar')
                            ->label('Jadwal Mengajar')
                            ->visible(fn (string $operation) => $operation === 'create')
                            ->schema([
                                Forms\Components\Select::make('kelas_id')
                                    ->label('Kelas')
                                    ->options(Kelas::pluck('nama_kelas', 'id'))
                                    ->searchable()->required(),
                                Forms\Components\Select::make('hari')
                                    ->options(['Senin'=>'Senin', 'Selasa'=>'Selasa', 'Rabu'=>'Rabu', 'Kamis'=>'Kamis', 'Jumat'=>'Jumat', 'Sabtu'=>'Sabtu'])
                                    ->required(),
                                Forms\Components\TimePicker::make('jam_mulai')->required(),
                                Forms\Components\TimePicker::make('jam_selesai')->required(),
                            ])->columns(4)->defaultItems(1),

                        Forms\Components\Grid::make(4)
                            ->visible(fn (string $operation) => $operation === 'edit')
                            ->schema([
                                Forms\Components\Select::make('kelas_id')
                                    ->label('Kelas')
                                    ->options(Kelas::pluck('nama_kelas', 'id'))
                                    ->searchable()->required(),
                                Forms\Components\Select::make('hari')
                                    ->options(['Senin'=>'Senin', 'Selasa'=>'Selasa', 'Rabu'=>'Rabu', 'Kamis'=>'Kamis', 'Jumat'=>'Jumat', 'Sabtu'=>'Sabtu'])
                                    ->required(),
                                Forms\Components\TimePicker::make('jam_mulai')->required(),
                                Forms\Components\TimePicker::make('jam_selesai')->required(),
                            ]),
                            
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Jadwal Pelajaran')
                    ->schema([
                        Infolists\Components\Grid::make(2)->schema([
                            Infolists\Components\TextEntry::make('guru.name')
                                ->label('Nama Guru Pengajar')
                                ->weight('bold')
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                            Infolists\Components\TextEntry::make('mataPelajaran.nama_pelajaran')
                                ->label('Mata Pelajaran')
                                ->badge()
                                ->color('primary'),
                            Infolists\Components\TextEntry::make('kelas.nama_kelas')
                                ->label('Kelas')
                                ->badge()
                                ->color('success'),
                            Infolists\Components\TextEntry::make('hari')
                                ->label('Hari')
                                ->badge()
                                ->color('warning'),
                            Infolists\Components\TextEntry::make('waktu')
                                ->label('Jam Mengajar')
                                ->getStateUsing(fn ($record) => date('H:i', strtotime($record->jam_mulai)) . ' s/d ' . date('H:i', strtotime($record->jam_selesai)))
                                ->icon('heroicon-o-clock')
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultGroup('guru.name')
            ->columns([
                Tables\Columns\TextColumn::make('mataPelajaran.nama_pelajaran')->label('Mapel')->weight('bold'),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas')->badge()->color('success'),
                Tables\Columns\TextColumn::make('hari')->label('Hari')->badge(),
                Tables\Columns\TextColumn::make('waktu')
                    ->label('Waktu')
                    ->getStateUsing(fn ($record) => date('H:i', strtotime($record->jam_mulai)) . ' - ' . date('H:i', strtotime($record->jam_selesai))),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('guru_id')
                    ->label('Filter Guru')
                    ->relationship('guru', 'name', fn ($query) => $query->where('peran', 'guru')->orderBy('name', 'asc'))
                    ->searchable()
                    ->preload()
                    ->hidden(fn () => Auth::user()->peran === 'guru'),
                
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama_kelas', fn ($query) => $query->orderByRaw('LENGTH(nama_kelas) ASC')->orderBy('nama_kelas', 'ASC'))
                    ->searchable()
                    ->preload(),
            ], layout: \Filament\Tables\Enums\FiltersLayout::AboveContent) // Filter tampil lega di atas tabel
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array 
    { 
        return [ 
            'index' => Pages\ListJadwalPelajarans::route('/'), 
            'create' => Pages\CreateJadwalPelajaran::route('/create'),
            'view' => Pages\ViewJadwalPelajaran::route('/{record}'),
            'edit' => Pages\EditJadwalPelajaran::route('/{record}/edit'),
        ]; 
    }
}