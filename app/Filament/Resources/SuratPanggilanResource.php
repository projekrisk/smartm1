<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratPanggilanResource\Pages;
use App\Models\SuratPanggilan;
use App\Models\Pegawai;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class SuratPanggilanResource extends Resource
{
    protected static ?string $model = SuratPanggilan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';    
    protected static ?string $slug = 'surat-panggilan';
    protected static ?string $navigationLabel = 'Surat Panggilan';
    protected static ?string $pluralModelLabel = 'Surat Panggilan';
    protected static ?string $navigationGroup = 'Kesiswaan';    
    protected static ?string $modelLabel = 'Surat Panggilan';
    protected static ?int $navigationSort = 14;

    // 🌟 BADGE NOTIFIKASI SIDEBAR
    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        
        if ($user->peran === 'guru') {
            $pegawaiId = Pegawai::where('user_id', $user->id)->value('id');
            if ($pegawaiId) {
                $kelasIds = Kelas::where('wali_kelas_id', $pegawaiId)->pluck('id');
                $siswaIds = Siswa::whereIn('kelas_id', $kelasIds)->pluck('id');
                
                if ($siswaIds->isNotEmpty()) {
                    $count = static::getModel()::where('status', 'Dibuat')
                        ->whereIn('siswa_id', $siswaIds)
                        ->count();
                    return $count > 0 ? (string) $count : null;
                }
            }
            return null;
        }

        if (in_array($user->peran, ['admin', 'staf'])) {
            $count = static::getModel()::where('status', 'Dibuat')->count();
            return $count > 0 ? (string) $count : null;
        }

        return null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    // 🌟 PENGATURAN HAK AKSES
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf', 'guru']);
    }

    public static function canCreate(): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf']);
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf', 'guru']);
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf']);
    }

    // 🌟 FILTER DATA (Hanya Tampilkan Siswa Binaan Wali Kelas ybs)
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();
        
        if ($user->peran === 'guru') {
            $pegawaiId = Pegawai::where('user_id', $user->id)->value('id');
            if ($pegawaiId) {
                $kelasIds = Kelas::where('wali_kelas_id', $pegawaiId)->pluck('id');
                $siswaIds = Siswa::whereIn('kelas_id', $kelasIds)->pluck('id');
                
                if ($siswaIds->isNotEmpty()) {
                    $query->whereIn('siswa_id', $siswaIds);
                } else {
                    $query->where('id', 0); 
                }
            } else {
                $query->where('id', 0);
            }
        }
        
        return $query->orderBy('created_at', 'desc');
    }

    // 🌟 FORM INPUT
    public static function form(Form $form): Form
    {
        $isGuru = Auth::user()->peran === 'guru';

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat')
                    ->schema([
                        // Baris 1: 3 Kolom
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\DatePicker::make('tanggal_surat')
                                ->label('Tanggal Surat Dibuat')
                                ->default(now())
                                ->required()
                                ->disabled($isGuru),

                            Forms\Components\TextInput::make('nomor_urut')
                                ->label('Nomor Surat')
                                ->numeric()
                                ->placeholder('Contoh: 123')
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Get $get, Set $set) {
                                    self::generateNomorSurat($get, $set);
                                })
                                ->disabled($isGuru)
                                ->dehydrated(false), // Tidak dimasukkan ke DB

                            Forms\Components\TextInput::make('nomor_surat')
                                ->label('Format Lengkap (Otomatis)')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->disabled()
                                ->dehydrated(), // Wajib disave ke DB
                        ]),

                        // Baris 2: 3 Kolom (Siswa, Penandatangan, Status)
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('siswa_id')
                                ->label('Nama Siswa')
                                ->relationship('siswa', 'nama_lengkap')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disabled($isGuru),

                            Forms\Components\Select::make('penandatangan_id')
                                ->label('Penandatangan Surat')
                                ->relationship('penandatangan', 'nama')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->disabled($isGuru),

                            Forms\Components\Select::make('status')
                                ->label('Status Surat')
                                ->options([
                                    'Dibuat' => 'Baru Dibuat',
                                    'Selesai' => 'Selesai (Pertemuan Terjadi)',
                                    'Dibatalkan' => 'Dibatalkan',
                                ])
                                ->default('Dibuat')
                                ->required(),
                        ]),

                        Forms\Components\Hidden::make('dibuat_oleh')
                            ->default(fn () => Auth::id()),
                    ]),

                Forms\Components\Section::make('Detail Pemanggilan')
                    ->schema([
                        Forms\Components\DatePicker::make('tanggal_panggilan')
                            ->label('Tanggal Pertemuan')
                            ->required()
                            ->disabled($isGuru),

                        Forms\Components\TimePicker::make('waktu_panggilan')
                            ->label('Waktu Pertemuan')
                            ->required()
                            ->disabled($isGuru),

                        Forms\Components\TextInput::make('tempat_pertemuan')
                            ->label('Tempat Pertemuan')
                            ->placeholder('Contoh: Ruang Bimbingan Konseling (BK)')
                            ->required()
                            ->maxLength(255)
                            ->disabled($isGuru),

                        Forms\Components\Textarea::make('alasan_panggilan')
                            ->label('Maksud / Alasan Pemanggilan')
                            ->placeholder('Jelaskan secara singkat alasan pemanggilan...')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull()
                            ->disabled($isGuru),
                    ])->columns(3),
            ]);
    }

    // 🌟 FUNGSI PENOMORAN (Index Statis + Otomatis Tahun)
    public static function generateNomorSurat(Get $get, Set $set)
    {
        $urut = $get('nomor_urut');
        
        if ($urut) {
            $indexStatis = '421.3'; 
            $tahun = date('Y');
            // Hasil: 421.3/123/SMA.01-MLP/2026
            $set('nomor_surat', "{$indexStatis}/{$urut}/SMA.01-MLP/{$tahun}");
        }
    }

    // 🌟 TABEL DATA
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat')
                    ->label('No. Surat')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_panggilan')
                    ->label('Tgl. Pertemuan')
                    ->date('d M Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_panggilan')
                    ->label('Waktu')
                    ->time('H:i'),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Dibuat' => 'warning',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
                        default => 'primary',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'Dibuat' => 'Baru Dibuat',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label(fn() => Auth::user()->peran === 'guru' ? 'Ubah Status' : 'Edit'),
                
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('cetak.panggilan', $record->id))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf'])),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuratPanggilans::route('/'),
            'create' => Pages\CreateSuratPanggilan::route('/create'),
            'edit' => Pages\EditSuratPanggilan::route('/{record}/edit'),
        ];
    }
}