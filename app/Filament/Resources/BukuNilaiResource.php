<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BukuNilaiResource\Pages;
use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class BukuNilaiResource extends Resource
{
    protected static ?string $model = Penilaian::class;
    protected static ?string $slug = 'buku-nilai';
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Buku Nilai';
    protected static ?string $navigationGroup = 'Akademik';    
    protected static ?int $navigationSort = 9;

    public static function canViewAny(): bool { return in_array(auth()->user()->peran, ['admin', 'guru']); }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $query->withCount(['bukuNilai' => function (Builder $q) { $q->whereNotNull('nilai'); }]);

        if (auth()->user()->peran === 'guru') {
            $guruId = auth()->id();
            $query->whereExists(function($q) use ($guruId) {
                $q->select(DB::raw(1))
                  ->from('jadwal_pelajaran') 
                  ->whereColumn('jadwal_pelajaran.mata_pelajaran_id', 'penilaian.mata_pelajaran_id')
                  ->whereColumn('jadwal_pelajaran.kelas_id', 'penilaian.kelas_id')
                  ->where('jadwal_pelajaran.guru_id', $guruId);
            });
        }

        return $query->orderBy('tanggal_penilaian', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('1. Informasi Kegiatan Penilaian')
                    ->schema([
                        Forms\Components\Hidden::make('tahun_ajaran_id')
                            ->default(fn () => \App\Models\TahunAjaran::where('is_active', true)->first()?->id),

                        Forms\Components\Select::make('mata_pelajaran_id')
                            ->label('Mata Pelajaran')
                            ->options(function () {
                                if (auth()->user()->peran === 'admin') return MataPelajaran::pluck('nama_pelajaran', 'id');
                                $mapelIds = \App\Models\JadwalPelajaran::where('guru_id', auth()->id())->pluck('mata_pelajaran_id');
                                return MataPelajaran::whereIn('id', $mapelIds)->pluck('nama_pelajaran', 'id');
                            })
                            ->required()
                            ->live()
                            ->disabled(fn (string $operation) => $operation !== 'create')
                            ->afterStateUpdated(function ($state, callable $set, string $operation) {
                                if ($operation !== 'create') return;
                                $set('kelas_id', null);
                                $set('bukuNilai', []);
                            }),

                        Forms\Components\Select::make('jenis_nilai')
                            ->label('Jenis Nilai')
                            ->options([ 'Tugas' => 'Tugas', 'Ulangan Harian' => 'Ulangan Harian', 'UTS' => 'UTS', 'UAS' => 'UAS', 'Sikap' => 'Sikap' ])
                            ->required(),

                        Forms\Components\DatePicker::make('tanggal_penilaian')
                            ->label('Tanggal Pelaksanaan')
                            ->default(now())
                            ->required(),

                        Forms\Components\Select::make('kelas_id')
                            ->label('Pilih Kelas')
                            ->options(function (callable $get) {
                                $mapelId = $get('mata_pelajaran_id');
                                if (!$mapelId) return [];
                                
                                $query = \App\Models\JadwalPelajaran::with('kelas')->where('mata_pelajaran_id', $mapelId);
                                if (auth()->user()->peran === 'guru') $query->where('guru_id', auth()->id());
                                return $query->get()->pluck('kelas.nama_kelas', 'kelas_id');
                            })
                            ->required()
                            ->live()
                            ->disabled(fn (string $operation) => $operation !== 'create')
                            ->afterStateUpdated(function ($state, callable $set, string $operation) {
                                if ($operation !== 'create' || !$state) { $set('bukuNilai', []); return; }
                                $siswas = Siswa::where('kelas_id', $state)->orderBy('nama_lengkap')->get();
                                $daftar = [];
                                foreach ($siswas as $siswa) {
                                    $daftar[(string) Str::uuid()] = [ 'siswa_id' => $siswa->id, 'nilai' => null, 'catatan_guru' => null ];
                                }
                                $set('bukuNilai', $daftar); 
                            }),
                            
                        Forms\Components\TextInput::make('materi')
                            ->label('Materi / Pembahasan (Topik)')
                            ->placeholder('Contoh: Persamaan Kuadrat / Bab 2')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                            
                    ])->columns(2),

                Forms\Components\Section::make('2. Daftar Siswa & Input Nilai')
                    ->description('Silakan isi kolom nilai. Siswa yang dibiarkan kosong tidak akan tersimpan.')
                    ->schema([
                        Forms\Components\Placeholder::make('header_tabel')
                            ->hiddenLabel()
                            ->content(new HtmlString('
                                <style>
                                    /* Trik CSS Super Padat: Merampingkan baris repeater persis seperti baris tabel */
                                    .tabel-repeater .fi-rep-item { box-shadow: none !important; border-radius: 0 !important; border: none !important; border-bottom: 1px solid #e5e7eb !important; margin: 0 !important; }
                                    .tabel-repeater .fi-rep-item > div { padding: 0.25rem 0.5rem !important; background-color: transparent !important; }
                                </style>
                            ')),

                        Forms\Components\Repeater::make('bukuNilai')
                            ->relationship('bukuNilai') 
                            ->hiddenLabel()
                            ->extraAttributes(['class' => 'tabel-repeater']) // Dipasangkan dengan CSS di atas
                            ->schema([
                                Forms\Components\Select::make('siswa_id')
                                    ->hiddenLabel()
                                    ->options(Siswa::pluck('nama_lengkap', 'id'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('nilai')
                                    ->hiddenLabel()
                                    ->placeholder('Input Nilai...')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('catatan_guru')
                                    ->hiddenLabel()
                                    ->placeholder('Ketik catatan...')
                                    ->columnSpan(2),
                            ])
                            ->columns(5)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                    ])->hidden(fn (\Filament\Forms\Get $get) => !$get('kelas_id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_penilaian')->label('Tanggal')->date('d M Y'),
                Tables\Columns\TextColumn::make('mataPelajaran.nama_pelajaran')->label('Mata Pelajaran')->weight('bold'),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas')->badge()->color('success'),
                Tables\Columns\TextColumn::make('jenis_nilai')->label('Jenis')->badge()->color('info'),
                Tables\Columns\TextColumn::make('materi')->label('Materi / Topik')->limit(20)->searchable(),
                Tables\Columns\TextColumn::make('buku_nilai_count')->label('Siswa Dinilai')->badge()->color('primary')->formatStateUsing(fn ($state) => $state . ' Orang'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tahun_ajaran_id')
                    ->label('Filter Tahun Ajaran')
                    ->options(fn () => \App\Models\TahunAjaran::orderBy('id', 'desc')->get()->mapWithKeys(fn ($ta) => [$ta->id => $ta->nama_tahun . ' (' . $ta->semester . ')']))
                    ->default(fn () => \App\Models\TahunAjaran::where('is_active', true)->first()?->id),
            ])
            ->actions([ 
                Tables\Actions\EditAction::make()->label('Ubah Nilai'),
                
                Tables\Actions\Action::make('cetak_rekap')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('cetak.penilaian', $record->id))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListBukuNilais::route('/'),
            'create' => Pages\CreateBukuNilai::route('/create'),
            'edit' => Pages\EditBukuNilai::route('/{record}/edit'),
            
            'pantau' => Pages\PantauPengumpulan::route('/pantau-pengumpulan'),
            'input-massal' => Pages\InputNilaiMassal::route('/input-massal'),
        ];
    }
}