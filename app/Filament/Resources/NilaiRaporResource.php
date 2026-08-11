<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NilaiRaporResource\Pages;
use App\Models\NilaiRapor;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components as InfolistComponents;

class NilaiRaporResource extends Resource
{
    protected static ?string $model = NilaiRapor::class;

    protected static ?string $slug = 'nilai-rapor';
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Nilai Rapor';
    protected static ?string $pluralModelLabel = 'Data Nilai Rapor';
    protected static ?string $modelLabel = 'Nilai Rapor';
    protected static ?string $navigationGroup = 'Admin';    
    protected static ?int $navigationSort = 17;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Input Manual Nilai Rapor Siswa')
                    ->schema([
                        Forms\Components\Select::make('tahun_ajaran_id')
                            ->label('Tahun Ajaran & Semester')
                            ->relationship('tahunAjaran', 'nama_tahun')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_tahun} - {$record->semester}")
                            ->default(fn () => \App\Models\TahunAjaran::where('is_active', true)->first()?->id)
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('siswa_id')
                            ->label('Pilih Siswa')
                            ->relationship('siswa', 'nama_lengkap')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('mata_pelajaran_id')
                            ->label('Mata Pelajaran')
                            ->relationship('mataPelajaran', 'nama_pelajaran')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('nilai_akhir')
                            ->label('Nilai Angka (0-100)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->helperText('Predikat (A, B, C, D) akan terhitung otomatis oleh sistem setelah disimpan.'),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Catatan Capaian Kompetensi')
                            ->placeholder('Contoh: Sangat baik dalam memahami konsep perhitungan dasar...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistComponents\Section::make('Informasi Biodata Siswa')
                    ->schema([
                        InfolistComponents\Grid::make(4)->schema([
                            InfolistComponents\TextEntry::make('siswa.nama_lengkap')
                                ->label('Nama Lengkap')
                                ->weight('bold')
                                ->size(InfolistComponents\TextEntry\TextEntrySize::Large)
                                ->columnSpan(2),
                            InfolistComponents\TextEntry::make('siswa.kelas.nama_kelas')
                                ->label('Kelas Saat Ini')
                                ->badge()
                                ->color('success'),
                            InfolistComponents\TextEntry::make('siswa.nisn')
                                ->label('NISN / NIS')
                                ->formatStateUsing(fn ($record) => ($record->siswa->nisn ?? '-') . ' / ' . ($record->siswa->nis ?? '-')),
                        ]),
                    ]),

                InfolistComponents\Section::make('Rekapitulasi Daftar Nilai Per Semester')
                    ->description('Berikut adalah seluruh riwayat nilai rapor siswa ini. Klik pada baris semester untuk melihat atau menyembunyikan detail nilainya.')
                    ->schema([
                        InfolistComponents\ViewEntry::make('riwayat_nilai')
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->view('filament.infolists.riwayat-nilai-siswa'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction('view')
            ->modifyQueryUsing(function (Builder $query) {
                $subQuery = \App\Models\NilaiRapor::selectRaw('MIN(id)')->groupBy('siswa_id');
                
                return $query
                    ->whereIn('nilai_rapor.id', $subQuery)
                    ->select('nilai_rapor.*')
                    ->selectSub(
                        Siswa::select('nama_lengkap')->whereColumn('siswa.id', 'nilai_rapor.siswa_id')->limit(1),
                        'nama_siswa_order'
                    )
                    ->orderBy('nama_siswa_order', 'asc');
            })
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nisn')
                    ->label('NISN')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('siswa.kelas.nama_kelas')
                    ->label('Kelas Saat Ini')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_nilai_tersimpan')
                    ->label('Total Nilai Tersimpan')
                    ->badge()
                    ->color('info')
                    ->getStateUsing(fn ($record) => \App\Models\NilaiRapor::where('siswa_id', $record->siswa_id)->count() . ' Data Mapel'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->options(function () {
                        return \App\Models\Kelas::all()
                            ->sortBy('nama_kelas', SORT_NATURAL)
                            ->pluck('nama_kelas', 'id');
                    })
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $data['value']));
                        }
                    }),
            ])
            
            ->actions([
                Tables\Actions\ViewAction::make()->label('Lihat Buku Rapor'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNilaiRapors::route('/'),
            'view' => Pages\ViewNilaiRapor::route('/{record}'),
        ];
    }
}