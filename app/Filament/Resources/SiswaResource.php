<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiswaResource\Pages;
use App\Models\Siswa;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use App\Filament\Resources\SiswaResource\RelationManagers;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;
    
    protected static ?string $slug = 'siswa';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Data Siswa';
    protected static ?string $pluralModelLabel = 'Data Siswa';
    protected static ?string $navigationGroup = 'Data Master';    
    protected static ?string $modelLabel = 'Siswa';
    protected static ?int $navigationSort = 6;

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama_lengkap', 'nis', 'nisn', 'kelas.nama_kelas'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string | \Illuminate\Contracts\Support\Htmlable
    {
        $nama = e($record->nama_lengkap);
        $nis = e($record->nis);
        $kelas = e($record->kelas->nama_kelas ?? 'Tanpa Kelas');
        
        return new HtmlString("
            <div class='flex flex-col'>
                <span class='font-bold text-gray-900 dark:text-white uppercase'>{$nama}</span>
                <div class='flex gap-2 mt-1'>
                    <span class='inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-primary-50 text-primary-700 ring-1 ring-inset ring-primary-600/20'>
                        NIS: {$nis}
                    </span>
                    <span class='inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-success-50 text-success-700 ring-1 ring-inset ring-success-600/20'>
                        Kelas {$kelas}
                    </span>
                </div>
            </div>
        ");
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'NISN' => $record->nisn ?? '-',
            'Status' => $record->status_siswa ?? 'Aktif',
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return static::getUrl('view', ['record' => $record]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['kelas']);
    }
    
    public static function canViewAny(): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf', 'guru']);
    }
    
    public static function canView(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf', 'guru']);
    }
    
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
        return Auth::user()->peran === 'admin';
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (Auth::user()->peran === 'guru') {
            $query->whereHas('kelas', function (Builder $q) {
                $q->where('wali_kelas_id', Auth::id());
            })->where(function (Builder $q) {
                $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])
                  ->orWhereNull('status_siswa');
            });
        }

        return $query;
    }
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Data Siswa')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Identitas')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('nis')
                                        ->label('NIS')
                                        ->required()
                                        ->unique(ignoreRecord: true),
                                    Forms\Components\TextInput::make('nisn')
                                        ->label('NISN')
                                        ->unique(ignoreRecord: true)
                                        ->numeric(),
                                    Forms\Components\TextInput::make('nama_lengkap')
                                        ->label('Nama Lengkap')
                                        ->required()
                                        ->columnSpanFull(),
                                    Forms\Components\Select::make('jenis_kelamin')
                                        ->label('Jenis Kelamin')
                                        ->options([
                                            'Laki-laki' => 'Laki-laki', 
                                            'Perempuan' => 'Perempuan'
                                        ])
                                        ->required(),
                                    Forms\Components\Select::make('agama')
                                        ->label('Agama')
                                        ->options([
                                            'Islam' => 'Islam', 
                                            'Kristen' => 'Kristen', 
                                            'Katolik' => 'Katolik', 
                                            'Hindu' => 'Hindu', 
                                            'Buddha' => 'Buddha', 
                                            'Konghucu' => 'Konghucu'
                                        ]),
                                    Forms\Components\TextInput::make('tempat_lahir')
                                        ->label('Tempat Lahir'),
                                    Forms\Components\DatePicker::make('tanggal_lahir')
                                        ->label('Tanggal Lahir'),
                                    Forms\Components\TextInput::make('nik')
                                        ->label('NIK (No. KTP)')
                                        ->numeric()
                                        ->unique(ignoreRecord: true),
                                    Forms\Components\TextInput::make('no_kk')
                                        ->label('No. Kartu Keluarga')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('telepon')
                                        ->label('No. HP / Telepon')
                                        ->tel(),
                                    Forms\Components\TextInput::make('email')
                                        ->label('Email Siswa')
                                        ->email(),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Alamat')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Forms\Components\Textarea::make('alamat')
                                    ->label('Alamat Jalan / Dusun')
                                    ->columnSpanFull(),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('rt')
                                        ->label('RT')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('rw')
                                        ->label('RW')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('kelurahan')
                                        ->label('Kelurahan / Desa'),
                                    Forms\Components\TextInput::make('kecamatan')
                                        ->label('Kecamatan'),
                                    Forms\Components\TextInput::make('kabupaten')
                                        ->label('Kabupaten / Kota'),
                                ]),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('lintang')
                                        ->label('Garis Lintang (Latitude)'),
                                    Forms\Components\TextInput::make('bujur')
                                        ->label('Garis Bujur (Longitude)'),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Orang Tua')
                            ->icon('heroicon-o-users')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('nama_ayah')
                                        ->label('Nama Ayah'),
                                    Forms\Components\TextInput::make('telepon_ayah')
                                        ->label('No. HP Ayah')
                                        ->tel(),
                                    Forms\Components\TextInput::make('nama_ibu')
                                        ->label('Nama Ibu'),
                                    Forms\Components\TextInput::make('telepon_ibu')
                                        ->label('No. HP Ibu')
                                        ->tel(),
                                    Forms\Components\TextInput::make('nama_wali')
                                        ->label('Nama Wali (Opsional)'),
                                    Forms\Components\TextInput::make('telepon_wali')
                                        ->label('No. HP Wali')
                                        ->tel(),
                                ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Akademik')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('kelas_id')
                                        ->label('Kelas Saat Ini')
                                        ->relationship('kelas', 'nama_kelas')
                                        ->required(fn (\Filament\Forms\Get $get) => in_array($get('status_siswa'), ['Aktif', 'Mutasi Masuk']))
                                        ->searchable()
                                        ->preload()
                                        ->helperText('Kosongkan jika siswa sedang dalam masa transisi kenaikan kelas.'),
                                    
                                    Forms\Components\Select::make('status_siswa')
                                        ->label('Status Siswa')
                                        ->options([
                                            'Aktif' => 'Aktif',
                                            'Lulus' => 'Lulus',
                                            'Mutasi Keluar' => 'Mutasi Keluar / Pindah',
                                            'Dikeluarkan' => 'Dikeluarkan',
                                            'Wafat' => 'Wafat',
                                            'Mutasi Masuk' => 'Mutasi Masuk',
                                        ])
                                        ->default('Aktif')
                                        ->live()
                                        ->afterStateUpdated(function (string $state, callable $set) {
                                            if ($state === 'Aktif') {
                                                $set('tanggal_status', null);
                                                $set('keterangan_status', null);
                                            }
                                        })
                                        ->required(),
                                        
                                    Forms\Components\DatePicker::make('tanggal_status')
                                        ->label('Tanggal Kejadian / Perubahan Status')
                                        ->required()
                                        ->hidden(fn (\Filament\Forms\Get $get) => in_array($get('status_siswa'), ['Aktif', null])),
                                        
                                    Forms\Components\Textarea::make('keterangan_status')
                                        ->label('Keterangan / Alasan')
                                        ->placeholder('Contoh: Pindah mengikuti dinas orang tua...')
                                        ->required()
                                        ->hidden(fn (\Filament\Forms\Get $get) => in_array($get('status_siswa'), ['Aktif', null])),
                                        
                                    Forms\Components\Select::make('jalur_masuk')
                                        ->label('Jalur Masuk')
                                        ->options([
                                            'Siswa Baru' => 'Siswa Baru',
                                            'Mutasi Masuk' => 'Mutasi Masuk',
                                        ])
                                        ->default('Siswa Baru')
                                        ->required(),
                                    
                                    Forms\Components\DatePicker::make('tanggal_masuk')
                                        ->label('Tanggal Masuk')
                                        ->default(now()),
                                ]),
                                Forms\Components\TextInput::make('sekolah_asal')
                                    ->label('Asal Sekolah (SMP/MTs)'),
                                
                                Forms\Components\Toggle::make('is_sekretaris')
                                    ->label('Jadikan Sekretaris Kelas')
                                    ->helperText('Siswa ini akan mendapatkan akses ekstra di portal siswa untuk mengisi Absensi Harian kelasnya.')
                                    ->default(false)
                                    ->columnSpanFull(),
                                
                                Forms\Components\FileUpload::make('foto')
                                    ->label('Foto Profil Siswa')
                                    ->disk('publik_upload') 
                                    ->directory('foto-siswa')
                                    ->image()
                                    ->avatar() 
                                    ->columnSpanFull(),
                                    
                                Forms\Components\Select::make('user_id')
                                    ->label('Akun Login (User ID)')
                                    ->relationship('user', 'name')
                                    ->disabled() 
                                    ->helperText('Akun login akan dibuatkan otomatis oleh sistem.'),
                            ]),
                    ])
                    ->columnSpanFull() 
            ]);
    }
    
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Profil Siswa')
                    ->schema([
                        Infolists\Components\Split::make([
                            Infolists\Components\Grid::make(1)->schema([
                                Infolists\Components\ImageEntry::make('foto')
                                    ->hiddenLabel()
                                    ->disk('publik_upload')
                                    ->circular()
                                    ->defaultImageUrl(url('/images/default-avatar.png'))
                                    ->size(150),
                            ])->grow(false),

                            Infolists\Components\Grid::make(2)->schema([
                                Infolists\Components\TextEntry::make('nama_lengkap')
                                    ->label('Nama Lengkap')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                                    ->weight('bold')
                                    ->columnSpanFull(),
                                Infolists\Components\TextEntry::make('nis')
                                    ->label('NIS / NISN')
                                    ->formatStateUsing(fn ($record) => $record->nis . ' / ' . ($record->nisn ?? '-'))
                                    ->icon('heroicon-m-identification'),
                                Infolists\Components\TextEntry::make('kelas.nama_kelas')
                                    ->label('Kelas Saat Ini')
                                    ->badge()
                                    ->color(fn ($state) => $state ? 'success' : 'gray')
                                    ->default('Lepas Kelas (Transisi)')
                                    ->icon('heroicon-m-building-office-2'),
                                Infolists\Components\TextEntry::make('status_siswa')
                                    ->label('Status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'Aktif' => 'success',
                                        'Mutasi Masuk' => 'success',
                                        'Lulus' => 'primary',
                                        'Mutasi Keluar' => 'warning',
                                        'Dikeluarkan' => 'danger',
                                        'Wafat' => 'gray',
                                        default => 'primary',
                                    }),
                            ]),
                        ])->from('md'),
                    ]),

                Infolists\Components\Tabs::make('Data Detail Siswa')
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make('Identitas')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Infolists\Components\Grid::make(3)->schema([
                                    Infolists\Components\TextEntry::make('jenis_kelamin')
                                        ->label('Jenis Kelamin'),
                                    Infolists\Components\TextEntry::make('agama')
                                        ->label('Agama')
                                        ->default('-'),
                                    Infolists\Components\TextEntry::make('tempat_lahir')
                                        ->label('Tempat, Tanggal Lahir')
                                        ->formatStateUsing(function ($record) {
                                            $tempat = $record->tempat_lahir ?? '-';
                                            $tgl = $record->tanggal_lahir;
                                            if (empty($tgl) || $tgl === '-') return $tempat . ', -';
                                            try {
                                                return $tempat . ', ' . \Carbon\Carbon::parse($tgl)->isoFormat('D MMMM Y');
                                            } catch (\Exception $e) {
                                                return $tempat . ', -';
                                            }
                                        }),
                                    
                                    Infolists\Components\TextEntry::make('nik')
                                        ->label('NIK (No. KTP)')
                                        ->default('-')
                                        ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf'])),
                                        
                                    Infolists\Components\TextEntry::make('no_kk')
                                        ->label('No. Kartu Keluarga')
                                        ->default('-')
                                        ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf'])),
                                        
                                    Infolists\Components\TextEntry::make('telepon')
                                        ->label('No. HP / Telepon')
                                        ->default('-')
                                        ->icon('heroicon-m-phone'),
                                    Infolists\Components\TextEntry::make('email')
                                        ->label('Email Siswa')
                                        ->default('-')
                                        ->icon('heroicon-m-envelope'),
                                ]),
                            ]),

                        Infolists\Components\Tabs\Tab::make('Alamat')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Infolists\Components\TextEntry::make('alamat')
                                    ->label('Alamat Jalan / Dusun')
                                    ->columnSpanFull()
                                    ->default('-'),
                                Infolists\Components\Grid::make(3)->schema([
                                    Infolists\Components\TextEntry::make('rt_rw')
                                        ->label('RT / RW')
                                        ->getStateUsing(fn ($record) => ($record->rt ?? '-') . ' / ' . ($record->rw ?? '-')),
                                    Infolists\Components\TextEntry::make('kelurahan')
                                        ->label('Kelurahan / Desa')
                                        ->default('-'),
                                    Infolists\Components\TextEntry::make('kecamatan')
                                        ->label('Kecamatan')
                                        ->default('-'),
                                    Infolists\Components\TextEntry::make('kabupaten')
                                        ->label('Kabupaten / Kota')
                                        ->default('-'),
                                    Infolists\Components\TextEntry::make('koordinat')
                                        ->label('Titik Koordinat')
                                        ->getStateUsing(fn ($record) => 'Lat: ' . ($record->lintang ?? '-') . ' | Long: ' . ($record->bujur ?? '-')),
                                        
                                    Infolists\Components\TextEntry::make('jarak_ke_sekolah')
                                        ->label('Radius Jarak ke Sekolah')
                                        ->getStateUsing(function ($record) {
                                            if (!$record->lintang || !$record->bujur) return 'Titik siswa belum diatur';
                                            
                                            if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                                                $pengaturan = \App\Models\Pengaturan::first();
                                                if (!$pengaturan || !$pengaturan->lintang || !$pengaturan->bujur) {
                                                    return 'Titik sekolah belum diatur';
                                                }
                                                
                                                $lat1 = (float) $record->lintang;
                                                $lon1 = (float) $record->bujur;
                                                $lat2 = (float) $pengaturan->lintang;
                                                $lon2 = (float) $pengaturan->bujur;

                                                $earthRadius = 6371; 
                                                $dLat = deg2rad($lat2 - $lat1);
                                                $dLon = deg2rad($lon2 - $lon1);
                                                
                                                $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
                                                $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                                                $jarak = $earthRadius * $c;

                                                return number_format($jarak, 2, ',', '.') . ' KM (Garis Lurus)';
                                            }
                                            return 'Modul pengaturan error';
                                        })
                                        ->badge()
                                        ->color(fn ($state) => str_contains($state, 'KM') ? 'success' : 'gray')
                                        ->icon(fn ($state) => str_contains($state, 'KM') ? 'heroicon-m-arrows-right-left' : 'heroicon-m-exclamation-triangle'),
                                        
                                    Infolists\Components\TextEntry::make('peta_lokasi')
                                        ->label('Peta Lokasi (Google Maps)')
                                        ->columnSpanFull()
                                        ->getStateUsing(fn ($record) => $record->lintang && $record->bujur ? 'Buka Peta di Google Maps' : 'Data koordinat belum lengkap')
                                        ->badge()
                                        ->color(fn ($record) => $record->lintang && $record->bujur ? 'info' : 'gray')
                                        ->icon(fn ($record) => $record->lintang && $record->bujur ? 'heroicon-m-map' : 'heroicon-m-x-circle')
                                        ->url(fn ($record) => $record->lintang && $record->bujur ? "https://maps.google.com/maps?q={$record->lintang},{$record->bujur}&hl=id&z=15" : null)
                                        ->openUrlInNewTab(),
                                ]),
                            ]),

                        Infolists\Components\Tabs\Tab::make('Orang Tua')
                            ->icon('heroicon-o-users')
                            ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                            ->schema([
                                Infolists\Components\Grid::make(3)->schema([
                                    Infolists\Components\TextEntry::make('nama_ayah')
                                        ->label('Nama Ayah')
                                        ->default('-'),
                                    Infolists\Components\TextEntry::make('telepon_ayah')
                                        ->label('No. HP Ayah')
                                        ->default('-')
                                        ->icon('heroicon-m-phone'),
                                    Infolists\Components\TextEntry::make('')
                                        ->label(''), 
                                    
                                    Infolists\Components\TextEntry::make('nama_ibu')
                                        ->label('Nama Ibu')
                                        ->default('-'),
                                    Infolists\Components\TextEntry::make('telepon_ibu')
                                        ->label('No. HP Ibu')
                                        ->default('-')
                                        ->icon('heroicon-m-phone'),
                                    Infolists\Components\TextEntry::make('')
                                        ->label(''), 
                                    
                                    Infolists\Components\TextEntry::make('nama_wali')
                                        ->label('Nama Wali')
                                        ->default('-'),
                                    Infolists\Components\TextEntry::make('telepon_wali')
                                        ->label('No. HP Wali')
                                        ->default('-')
                                        ->icon('heroicon-m-phone'),
                                ]),
                            ]),

                        Infolists\Components\Tabs\Tab::make('Akademik')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Infolists\Components\Grid::make(3)->schema([
                                    Infolists\Components\TextEntry::make('jalur_masuk')
                                        ->label('Jalur Masuk')
                                        ->default('Siswa Baru'),
                                    Infolists\Components\TextEntry::make('sekolah_asal')
                                        ->label('Asal Sekolah (SMP/MTs)')
                                        ->default('-'),
                                    Infolists\Components\TextEntry::make('tanggal_masuk')
                                        ->label('Tanggal Masuk')
                                        ->formatStateUsing(function ($state) {
                                            if (empty($state) || $state === '-') return '-';
                                            try { return \Carbon\Carbon::parse($state)->isoFormat('D MMMM Y'); } catch (\Exception $e) { return '-'; }
                                        })
                                        ->default('-'),
                                    
                                    Infolists\Components\TextEntry::make('tanggal_status')
                                        ->label('Tanggal Perubahan Status')
                                        ->formatStateUsing(function ($state) {
                                            if (empty($state) || $state === '-') return '-';
                                            try { return \Carbon\Carbon::parse($state)->isoFormat('D MMMM Y'); } catch (\Exception $e) { return '-'; }
                                        })
                                        ->visible(fn ($record) => !in_array($record->status_siswa, ['Aktif', null])),
                                    Infolists\Components\TextEntry::make('keterangan_status')
                                        ->label('Keterangan Keluar/Mutasi')
                                        ->columnSpan(2)
                                        ->visible(fn ($record) => !in_array($record->status_siswa, ['Aktif', null])),
                                ]),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }
    
    public static function table(Table $table): Table
    {
        return $table
            ->recordAction('view') 
            ->columns([
                Tables\Columns\ImageColumn::make('foto')
                    ->label('Foto')
                    ->disk('publik_upload') 
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->default('Transisi/Lepas')
                    ->badge(),
                Tables\Columns\TextColumn::make('status_siswa')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Aktif' => 'success',
                        'Mutasi Masuk' => 'success',
                        'Lulus' => 'info',
                        'Mutasi Keluar' => 'warning',
                        'Dikeluarkan' => 'danger',
                        'Wafat' => 'gray',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('jenis_kelamin')
                    ->label('L/P'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->searchable()
                    ->preload()
                    ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf'])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(), 
                Tables\Actions\EditAction::make()->hidden(fn () => Auth::user()->peran === 'guru'),
                
                Tables\Actions\Action::make('aktifkan_kembali')
                    ->label('Aktifkan Kembali')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                    ->modalHeading('Aktifkan Kembali Siswa')
                    ->modalDescription('Apakah Anda yakin ingin mengubah status siswa ini kembali menjadi Aktif? Data tanggal keluar/lulus dan keterangannya akan dihapus.')
                    ->modalSubmitActionLabel('Ya, Aktifkan')
                    ->hidden(fn (\App\Models\Siswa $record): bool => in_array($record->status_siswa, ['Aktif', 'Mutasi Masuk', null]))
                    ->action(function (\App\Models\Siswa $record): void {
                        $record->update([
                            'status_siswa' => 'Aktif',
                            'tanggal_status' => null,
                            'keterangan_status' => null,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Status Berhasil Diubah')
                            ->body("Siswa {$record->nama_lengkap} sekarang kembali Aktif.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    
                    Tables\Actions\BulkAction::make('lepas_kelas')
                        ->label('Lepas Kelas (Kenaikan/Lulus)')
                        ->icon('heroicon-o-arrow-up-on-square')
                        ->color('warning')
                        ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\TextInput::make('status_riwayat')
                                ->label('Keterangan Riwayat Lama (Dicatat di Buku Induk)')
                                ->placeholder('Contoh: Naik Kelas XI / Lulus / Pindah')
                                ->required(),
                            Forms\Components\Toggle::make('jadikan_lulus')
                                ->label('Tandai sebagai Alumni (Lulus)')
                                ->helperText('Nyalakan jika siswa lulus. Status akan berubah jadi "Lulus" dan hilang dari daftar aktif.')
                                ->default(false)
                                ->live(),
                            Forms\Components\DatePicker::make('tanggal_lulus')
                                ->label('Tanggal Kelulusan / Tanggal Keluar')
                                ->default(now())
                                ->visible(fn (\Filament\Forms\Get $get) => $get('jadikan_lulus'))
                                ->required(fn (\Filament\Forms\Get $get) => $get('jadikan_lulus')),
                        ])
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records, array $data): void {
                            $tahunAktif = \App\Models\TahunAjaran::where('is_active', true)->first();

                            foreach ($records as $record) {
                                if ($record->kelas_id && $tahunAktif) {
                                    \App\Models\RiwayatKelasSiswa::create([
                                        'siswa_id' => $record->id,
                                        'kelas_id' => $record->kelas_id,
                                        'tahun_ajaran_id' => $tahunAktif->id,
                                        'status_riwayat' => $data['status_riwayat'],
                                    ]);
                                }

                                $updateData = ['kelas_id' => null];
                                
                                if ($data['jadikan_lulus']) {
                                    $updateData['status_siswa'] = 'Lulus';
                                    $updateData['tanggal_status'] = $data['tanggal_lulus'];
                                    $updateData['keterangan_status'] = $data['status_riwayat'];
                                }

                                $record->update($updateData);
                            }
                            
                            \Filament\Notifications\Notification::make()
                                ->title('Berhasil Diproses')
                                ->body($records->count() . ' siswa telah dilepas dari kelasnya.')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->peran === 'admin'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RiwayatKelasRelationManager::class,
            RelationManagers\CatatanRelationManager::class,
            RelationManagers\KehadiranHarianRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'view' => Pages\ViewSiswa::route('/{record}'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}