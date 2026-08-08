<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CatatanSiswaResource\Pages;
use App\Models\CatatanSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\JadwalPelajaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class CatatanSiswaResource extends Resource
{
    protected static ?string $model = CatatanSiswa::class;

    protected static ?string $slug = 'catatan-siswa';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Catatan / Kasus';
    protected static ?string $pluralModelLabel = 'Catatan Siswa';
    protected static ?string $modelLabel = 'Catatan';
    protected static ?string $navigationGroup = 'Kesiswaan';    
    protected static ?int $navigationSort = 13;

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return Auth::user()->peran === 'admin' || $record->guru_id === Auth::id();
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return Auth::user()->peran === 'admin' || $record->guru_id === Auth::id();
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        if ($user->peran === 'guru') {
            $kelasBinaanId = Kelas::where('wali_kelas_id', $user->id)->value('id');
            
            if ($kelasBinaanId) {
                $count = static::getModel()::whereHas('siswa', function ($q) use ($kelasBinaanId) {
                    $q->where('kelas_id', $kelasBinaanId);
                })
                ->where('is_read', false)
                ->count();
                
                return $count > 0 ? (string) $count : null;
            }
        }
        return null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $user = Auth::user();
        if ($user->peran === 'guru') {
            $kelasBinaanId = Kelas::where('wali_kelas_id', $user->id)->value('id');
            if ($kelasBinaanId) {
                $tugas = static::getModel()::whereHas('siswa', function ($q) use ($kelasBinaanId) {
                    $q->where('kelas_id', $kelasBinaanId);
                })->where('status_tindak_lanjut', 'Belum')->count();
                
                if ($tugas > 0) return 'danger';
            }
        }
        return 'success'; 
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user->peran !== 'admin') {
            $query->where(function ($q) use ($user) {
                $q->where('guru_id', $user->id)
                  ->orWhereHas('siswa.kelas', function ($subQ) use ($user) {
                      $subQ->where('wali_kelas_id', $user->id);
                  });
            });
        }
        $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc');

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Catatan / Pembinaan')
                    ->schema([
                        Forms\Components\Select::make('kelas_id')
                            ->label('Pilih Kelas')
                            ->options(function () {
                                return Kelas::whereHas('siswa', function ($q) {
                                    $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])
                                      ->orWhereNull('status_siswa');
                                })
                                ->orderByRaw('LENGTH(nama_kelas) ASC')
                                ->orderBy('nama_kelas', 'ASC')
                                ->pluck('nama_kelas', 'id');
                            })
                            ->live()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->dehydrated(false)
                            ->afterStateHydrated(function ($component, $record) {
                                if ($record && $record->siswa_id) {
                                    $component->state($record->siswa->kelas_id);
                                }
                            })
                            ->afterStateUpdated(fn (callable $set) => $set('siswa_id', null)),
                            
                        Forms\Components\Select::make('siswa_id')
                            ->label('Nama Siswa')
                            ->options(function (Get $get) {
                                $kelasId = $get('kelas_id');
                                if (!$kelasId) return [];
                                
                                return Siswa::where('kelas_id', $kelasId)
                                    ->where(function ($q) {
                                        $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])
                                          ->orWhereNull('status_siswa');
                                    })
                                    ->orderBy('nama_lengkap')
                                    ->pluck('nama_lengkap', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->required(),
                            
                        Forms\Components\Select::make('jenis_catatan')
                            ->label('Kategori / Jenis')
                            ->options([
                                'Negatif' => 'Negatif',
                                'Positif' => 'Positif',
                            ])
                            ->required(),
                            
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Kejadian / Tindakan')
                            ->default(now())
                            ->required(),
                            
                        Forms\Components\TextInput::make('judul_catatan')
                            ->label('Perihal / Topik')
                            ->placeholder('Contoh: Terlambat, Membantu Teman, dll.')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                            
                        Forms\Components\Textarea::make('isi_catatan')
                            ->label('Keterangan Detail & Tindak Lanjut Awal')
                            ->required()
                            ->rows(4)
                            ->columnSpanFull(),
                            
                        Forms\Components\Hidden::make('guru_id')->default(fn () => Auth::id()),
                        Forms\Components\Hidden::make('tahun_ajaran_id')->default(fn () => TahunAjaran::where('is_active', true)->first()?->id),
                    ])->columns(2),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Detail Catatan / Kasus')
                    ->schema([
                        Infolists\Components\Grid::make(2)->schema([
                            Infolists\Components\TextEntry::make('siswa.nama_lengkap')
                                ->label('Nama Siswa')
                                ->weight('bold')
                                ->size(Infolists\Components\TextEntry\TextEntrySize::Large),
                            Infolists\Components\TextEntry::make('siswa.kelas.nama_kelas')
                                ->label('Kelas Saat Ini')
                                ->badge()
                                ->color('info'),
                            Infolists\Components\TextEntry::make('jenis_catatan')
                                ->label('Kategori')
                                ->badge()
                                ->color(fn (string $state): string => match ($state) {
                                    'Positif' => 'success',
                                    'Negatif' => 'danger',
                                    'Bimbingan' => 'info',
                                    'Panggilan Ortu' => 'warning',
                                    default => 'gray',
                                }),
                            Infolists\Components\TextEntry::make('tanggal')
                                ->label('Tanggal Kejadian')
                                ->date('d M Y'),
                            Infolists\Components\TextEntry::make('pencatat.name')
                                ->label('Dicatat / Dilaporkan Oleh'),
                            Infolists\Components\TextEntry::make('created_at')
                                ->label('Waktu Dicatat Sistem')
                                ->dateTime('d M Y, H:i'),
                        ]),
                        Infolists\Components\TextEntry::make('judul_catatan')
                            ->label('Perihal / Topik')
                            ->weight('bold')
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('isi_catatan')
                            ->label('Keterangan Detail')
                            ->columnSpanFull(),
                    ]),
                
                Infolists\Components\Section::make('Riwayat Tindak Lanjut & Resolusi')
                    ->schema([
                        Infolists\Components\TextEntry::make('status_tindak_lanjut')
                            ->label('Status Laporan')
                            ->badge()
                            ->color(fn ($state) => $state === 'Sudah' ? 'success' : 'danger'),
                        
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('tindak_lanjut')
                                    ->label('Hasil Tindak Lanjut (Resolusi)')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('penindaklanjut.name')
                                    ->label('Ditindaklanjuti Oleh'),
                                Infolists\Components\TextEntry::make('tanggal_tindak_lanjut')
                                    ->label('Waktu Diselesaikan')
                                    ->dateTime('d M Y, H:i'),
                            ])
                            ->visible(fn ($record) => $record->status_tindak_lanjut === 'Sudah'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('jenis_catatan')
                ->label('Kategori')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Positif' => 'success',
                    'Negatif' => 'danger',
                    'Bimbingan' => 'info',
                    'Panggilan Ortu' => 'warning',
                    default => 'gray',
                }),
                
            Tables\Columns\IconColumn::make('is_read')
                ->label('Status Baca')
                ->boolean()
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-envelope')
                ->trueColor('success')
                ->falseColor('danger')
                ->tooltip(fn ($state) => $state ? 'Sudah dibaca oleh Wali Kelas' : 'Belum dibaca (Baru)'),

                Tables\Columns\TextColumn::make('pencatat.name')
                    ->label('Oleh')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_tindak_lanjut')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => $state === 'Sudah' ? 'success' : 'danger'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('kelas_binaan')
                    ->label('Tampilkan Data')
                    ->placeholder('Semua Data (Akses Saya)')
                    ->trueLabel('Hanya Siswa Binaan Saya')
                    ->falseLabel('Hanya Laporan Saya Sendiri')
                    ->queries(
                        true: fn (Builder $query) => $query->whereHas('siswa.kelas', fn ($q) => $q->where('wali_kelas_id', Auth::id())),
                        false: fn (Builder $query) => $query->where('guru_id', Auth::id()),
                        blank: fn (Builder $query) => $query,
                    )
                    ->visible(fn () => Auth::user()->peran === 'guru' && Kelas::where('wali_kelas_id', Auth::id())->exists()),
                
                Tables\Filters\SelectFilter::make('status_tindak_lanjut')
                    ->label('Status Resolusi')
                    ->options([
                        'Belum' => 'Belum Ditindaklanjuti',
                        'Sudah' => 'Sudah Selesai',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('tindak_lanjut')
                    ->label('Tindak Lanjut')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn ($record) => 
                        $record->status_tindak_lanjut === 'Belum' && 
                        (Auth::user()->peran === 'admin' || $record->siswa->kelas->wali_kelas_id === Auth::id())
                    )
                    ->form([
                        Forms\Components\Textarea::make('tindak_lanjut')
                            ->label('Hasil / Riwayat Tindak Lanjut')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status_tindak_lanjut' => 'Sudah',
                            'tindak_lanjut' => $data['tindak_lanjut'],
                            'tanggal_tindak_lanjut' => now(),
                            'ditindaklanjuti_oleh' => Auth::id(),
                        ]);
                    }),

                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => Auth::user()->peran === 'guru' && $record->guru_id !== Auth::id()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                ])
                ->visible(fn () => auth()->user()->peran === 'admin'),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatatanSiswas::route('/'),
            'create' => Pages\CreateCatatanSiswa::route('/create'),
            'view' => Pages\ViewCatatanSiswa::route('/{record}'),
            'edit' => Pages\EditCatatanSiswa::route('/{record}/edit'),
        ];
    }
}