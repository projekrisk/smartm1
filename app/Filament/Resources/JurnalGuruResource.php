<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JurnalGuruResource\Pages;
use App\Models\JurnalGuru;
use App\Models\JadwalPelajaran;
use App\Models\TahunAjaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class JurnalGuruResource extends Resource
{
    protected static ?string $model = JurnalGuru::class;
    protected static ?string $slug = 'jurnal-mengajar';
    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $navigationLabel = 'Jurnal & Absensi';
    protected static ?string $pluralModelLabel = 'Jurnal Mengajar & Absensi';
    protected static ?string $navigationGroup = 'Akademik';    
    protected static ?int $navigationSort = 8;

    public static function canViewAny(): bool 
    { 
        return in_array(Auth::user()->peran, ['admin', 'guru']); 
    }

    public static function canCreate(): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'guru']);  
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'guru']); 
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'guru']); 
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        if (Auth::user()->peran === 'guru') {
            $query->where('guru_id', Auth::id());
        }
        
        $taAktifId = TahunAjaran::where('is_active', true)->value('id');
        if ($taAktifId) {
            $query->where('tahun_ajaran_id', $taAktifId);
        }
        
        $query->withCount([
            'kehadiranPelajaran as siswa_absen_count' => function (Builder $q) {
                $q->where('status', '!=', 'Hadir');
            }
        ]);
        
        return $query->orderBy('tanggal', 'desc')->orderBy('jam_mulai', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pelaksanaan')
                    ->schema([
                        Forms\Components\Hidden::make('tahun_ajaran_id')
                            ->default(fn () => TahunAjaran::where('is_active', true)->first()?->id),
                        
                        Forms\Components\Hidden::make('guru_id')
                            ->default(fn () => Auth::id())
                            ->visible(fn () => Auth::user()->peran === 'guru'),

                        Forms\Components\Select::make('guru_id')
                            ->label('Pilih Guru Pengajar (Mode Admin)')
                            ->options(\App\Models\User::where('peran', 'guru')->pluck('name', 'id'))
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('mata_pelajaran_id', null);
                                $set('kelas_id', null);
                                $set('waktu_jadwal', null);
                                $set('jam_mulai', null);
                                $set('jam_selesai', null);
                            })
                            ->required()
                            ->columnSpanFull()
                            ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf'])),

                        Forms\Components\Select::make('mata_pelajaran_id')
                            ->label('1. Pilih Mata Pelajaran')
                            ->options(function (callable $get) {
                                $guruId = Auth::user()->peran === 'guru' ? Auth::id() : $get('guru_id');
                                if (!$guruId) return [];
                                
                                return JadwalPelajaran::with('mataPelajaran')
                                    ->where('guru_id', $guruId)
                                    ->get()
                                    ->pluck('mataPelajaran.nama_pelajaran', 'mata_pelajaran_id')
                                    ->unique();
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('kelas_id', null);
                                $set('waktu_jadwal', null);
                                $set('jam_mulai', null);
                                $set('jam_selesai', null);
                            })
                            ->required(),

                        Forms\Components\Select::make('kelas_id')
                            ->label('2. Pilih Kelas')
                            ->options(function (callable $get) {
                                $guruId = Auth::user()->peran === 'guru' ? Auth::id() : $get('guru_id');
                                $mapelId = $get('mata_pelajaran_id');
                                
                                if (!$guruId || !$mapelId) return [];
                                
                                return JadwalPelajaran::with('kelas')
                                    ->where('guru_id', $guruId)
                                    ->where('mata_pelajaran_id', $mapelId)
                                    ->get()
                                    ->sortBy('kelas.nama_kelas')
                                    ->pluck('kelas.nama_kelas', 'kelas_id')
                                    ->unique();
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $set('waktu_jadwal', null);
                                $set('jam_mulai', null);
                                $set('jam_selesai', null);
                            })
                            ->required(),

                        Forms\Components\Select::make('waktu_jadwal')
                            ->label('3. Pilih Waktu Jadwal')
                            ->placeholder('Pilih hari dan jam...')
                            ->options(function (callable $get) {
                                $guruId = Auth::user()->peran === 'guru' ? Auth::id() : $get('guru_id');
                                $kelasId = $get('kelas_id');
                                $mapelId = $get('mata_pelajaran_id');
                                
                                if (!$guruId || !$kelasId || !$mapelId) return [];

                                $jadwals = JadwalPelajaran::where('guru_id', $guruId)
                                    ->where('kelas_id', $kelasId)
                                    ->where('mata_pelajaran_id', $mapelId)
                                    ->orderBy('hari')
                                    ->orderBy('jam_mulai')
                                    ->get();

                                return $jadwals->mapWithKeys(function ($j) {
                                    $waktu = date('H:i', strtotime($j->jam_mulai)) . ' s/d ' . date('H:i', strtotime($j->jam_selesai));
                                    return [$j->id => "Hari {$j->hari} ({$waktu})"];
                                });
                            })
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (!$state) return;
                                $jadwal = JadwalPelajaran::find($state);
                                if ($jadwal) {
                                    $set('jam_mulai', $jadwal->jam_mulai);
                                    $set('jam_selesai', $jadwal->jam_selesai);
                                }
                            })
                            ->dehydrated(false) 
                            ->helperText('Otomatis mengisi jam masuk & keluar di bawah.'),

                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Pelaksanaan')
                            ->default(now())
                            ->required(),

                        Forms\Components\TimePicker::make('jam_mulai')
                            ->label('Jam Masuk')
                            ->disabled()
                            ->dehydrated()
                            ->required(),

                        Forms\Components\TimePicker::make('jam_selesai')
                            ->label('Jam Keluar')
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                            
                    ])->columns(3),

                Forms\Components\Section::make('Laporan Mengajar')
                    ->description('Silakan isi materi yang diajarkan, lalu klik Simpan. Daftar absensi siswa akan otomatis muncul setelahnya.')
                    ->schema([
                        Forms\Components\TextInput::make('materi_pembahasan')->required()->columnSpanFull(),
                        Forms\Components\Textarea::make('catatan_kejadian')
                            ->label('Catatan Kejadian di Kelas (Opsional)')
                            ->placeholder('Kosongkan jika kelas berjalan kondusif.')
                            ->rows(3)->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y'),
                Tables\Columns\TextColumn::make('jam_mulai')
                    ->label('Waktu')
                    ->formatStateUsing(fn ($record) => date('H:i', strtotime($record->jam_mulai)) . ' - ' . date('H:i', strtotime($record->jam_selesai))),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')->label('Kelas')->badge()->color('success'),
                Tables\Columns\TextColumn::make('mataPelajaran.nama_pelajaran')->label('Pelajaran')->weight('bold'),
                Tables\Columns\TextColumn::make('materi_pembahasan')->label('Materi')->limit(20),
                
                Tables\Columns\TextColumn::make('siswa_absen_count')
                    ->label('Siswa Absen')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'gray')
                    ->formatStateUsing(fn ($state) => $state > 0 ? $state . ' Orang' : 'Nihil'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Cek Absensi & Edit'),
                
                Tables\Actions\Action::make('cetak_jurnal')
                    ->label('Cetak Jurnal')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn (\App\Models\JurnalGuru $record) => route('cetak.jurnal', $record->id))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\JurnalGuruResource\RelationManagers\KehadiranRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJurnalGurus::route('/'),
            'create' => Pages\CreateJurnalGuru::route('/create'),
            'edit' => Pages\EditJurnalGuru::route('/{record}/edit'),
        ];
    }
}