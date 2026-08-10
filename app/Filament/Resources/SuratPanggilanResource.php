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
    protected static ?string $modelLabel = 'Surat Panggilan';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        
        if ($user->peran === 'guru') {
            $kelasIds = Kelas::where('wali_kelas_id', $user->id)->pluck('id')->toArray();
            
            if (!empty($kelasIds)) {
                $count = static::getModel()::where('status', 'Dibuat')
                    ->whereHas('siswa', function ($q) use ($kelasIds) {
                        $q->whereIn('kelas_id', $kelasIds);
                    })->count();
                return $count > 0 ? (string) $count : null;
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

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();
        
        if ($user->peran === 'guru') {
            $kelasIds = Kelas::where('wali_kelas_id', $user->id)->pluck('id')->toArray();
            
            if (!empty($kelasIds)) {
                $query->whereHas('siswa', function ($q) use ($kelasIds) {
                    $q->whereIn('kelas_id', $kelasIds);
                });
            } else {
                $query->where('id', 0);
            }
        }
        
        return $query->orderBy('created_at', 'desc');
    }

    public static function form(Form $form): Form
    {
        $isGuru = Auth::user()->peran === 'guru';

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat')
                    ->schema([
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
                                ->dehydrated(false),

                            Forms\Components\TextInput::make('nomor_surat')
                                ->label('Format Lengkap (Otomatis)')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->disabled()
                                ->dehydrated(),
                        ]),

                        Forms\Components\Grid::make(3)->schema([
                            
                            Forms\Components\Select::make('siswa_id')
                                ->label('Nama Siswa')
                                ->relationship('siswa', 'nama_lengkap', fn (Builder $query) => $query->with('kelas'))
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nama_lengkap} (Kelas {$record->kelas?->nama_kelas})")
                                ->searchable(['nama_lengkap', 'nis', 'nisn'])
                                ->preload()
                                ->required()
                                ->disabled($isGuru)
                                ->live()
                                ->afterStateUpdated(function (Set $set, $state) {
                                    if ($state) {
                                        $siswa = Siswa::with('kelas')->find($state);
                                        // Mengisi ID Penandatangan secara otomatis di balik layar
                                        if ($siswa && $siswa->kelas && $siswa->kelas->wali_kelas_id) {
                                            $set('penandatangan_id', $siswa->kelas->wali_kelas_id);
                                        } else {
                                            $set('penandatangan_id', null);
                                        }
                                    }
                                })
                                ->columnSpan(2),

                            Forms\Components\Hidden::make('penandatangan_id'),

                            Forms\Components\Select::make('status')
                                ->label('Status Surat')
                                ->options([
                                    'Dibuat' => 'Baru Dibuat',
                                    'Selesai' => 'Selesai (Pertemuan Terjadi)',
                                    'Dibatalkan' => 'Dibatalkan',
                                ])
                                ->default('Dibuat')
                                ->required()
                                ->columnSpan(1),
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

    public static function generateNomorSurat(Get $get, Set $set)
    {
        $urut = $get('nomor_urut');
        
        if ($urut) {
            $indexStatis = '421.3'; 
            $tahun = date('Y');
            $set('nomor_surat', "{$indexStatis}/{$urut}/SMA.01-MLP/{$tahun}");
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')
                    ->label('Siswa')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
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
            ])
            ->defaultSort('created_at', 'desc');
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