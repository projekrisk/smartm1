<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PrestasiResource\Pages;
use App\Models\Prestasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class PrestasiResource extends Resource
{
    protected static ?string $model = Prestasi::class;

    protected static ?string $slug = 'prestasi-siswa';
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?string $navigationLabel = 'Prestasi Siswa';
    protected static ?string $pluralModelLabel = 'Daftar Prestasi Siswa';
    protected static ?string $navigationGroup = 'Kesiswaan';    
    protected static ?int $navigationSort = 12;

    public static function isValidator(): bool
    {
        $user = Auth::user();
        
        if ($user->peran === 'admin') {
            return true;
        }

        if ($user->pegawai && $user->pegawai->tugas_tambahan) {
            $tugas = $user->pegawai->tugas_tambahan;
            
            if (is_array($tugas)) {
                foreach ($tugas as $t) {
                    if (stripos((string) $t, 'kesiswaan') !== false) return true;
                }
            } elseif (is_string($tugas)) {
                if (stripos($tugas, 'kesiswaan') !== false) return true;
            }
        }

        return false;
    }

    public static function canViewAny(): bool
    {
        return true; 
    }

    public static function getNavigationBadge(): ?string
    {
        if (static::isValidator()) {
            $count = static::getModel()::where('status', 'Menunggu')->count();
            return $count > 0 ? (string) $count : null;
        }
        return null;
    }

    public static function getNavigationBadgeColor(): ?string { return 'danger'; }
    
    public static function canCreate(): bool
    {
        return true;
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        if (static::isValidator()) return true;
        
        return $record->status === 'Menunggu';
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        if (static::isValidator()) return true;
        return $record->status === 'Menunggu';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Prestasi')
                    ->schema([
                        Forms\Components\Select::make('siswa_id')
                            ->label('Pilih Siswa')
                            ->relationship('siswa', 'nama_lengkap')
                            ->searchable()->preload()->required()
                            ->disabled(fn (string $operation) => $operation === 'edit'),
                            
                        Forms\Components\TextInput::make('nama_prestasi')
                            ->label('Nama/Judul Prestasi')
                            ->placeholder('Contoh: Juara 1 Pencak Silat')
                            ->required()->maxLength(255),
                            
                        Forms\Components\Select::make('juara')
                            ->label('Peringkat / Juara')
                            ->options(['Juara 1' => 'Juara 1', 'Juara 2' => 'Juara 2', 'Juara 3' => 'Juara 3', 'Harapan 1' => 'Harapan 1', 'Harapan 2' => 'Harapan 2', 'Lainnya' => 'Lainnya'])
                            ->required(),
                            
                        Forms\Components\Select::make('jenis')
                            ->label('Jenis Lomba')
                            ->options(['Individu' => 'Individu / Perorangan', 'Beregu' => 'Beregu / Kelompok'])
                            ->required(),
                            
                        Forms\Components\Select::make('kategori')
                            ->options(['Akademik' => 'Akademik', 'Non-Akademik' => 'Non-Akademik'])
                            ->required(),
                            
                        Forms\Components\Select::make('tingkat')
                            ->options(['Sekolah' => 'Tingkat Sekolah', 'Kecamatan' => 'Tingkat Kecamatan', 'Kabupaten/Kota' => 'Tingkat Kabupaten/Kota', 'Provinsi' => 'Tingkat Provinsi', 'Nasional' => 'Tingkat Nasional', 'Internasional' => 'Tingkat Internasional'])
                            ->required(),
                            
                        Forms\Components\TextInput::make('penyelenggara')
                            ->label('Penyelenggara (Opsional)')
                            ->placeholder('Contoh: Kemdikbud / Universitas XYZ'),
                            
                        Forms\Components\DatePicker::make('tanggal_perolehan')
                            ->label('Tanggal Prestasi')
                            ->required(),
                            
                        Forms\Components\FileUpload::make('bukti_file')
                            ->label('Bukti Sertifikat / Foto / Piagam')
                            ->disk('publik_upload')->directory('prestasi')
                            ->image()->openable()->downloadable()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Verifikasi & Validasi')
                    ->visible(fn () => \App\Filament\Resources\PrestasiResource::isValidator())
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Keputusan Status')
                            ->options([
                                'Menunggu' => 'Menunggu Validasi',
                                'Disetujui' => 'Disetujui (Tampil di Profil Siswa)',
                                'Ditolak' => 'Ditolak (Kembalikan ke Siswa)',
                            ])
                            ->default('Disetujui')
                            ->required()
                            ->live(),
                            
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan Penolakan / Feedback (Opsional)')
                            ->placeholder('Contoh: Mohon maaf, sertifikat tidak terbaca, mohon difoto ulang.')
                            ->visible(fn (\Filament\Forms\Get $get) => $get('status') === 'Ditolak')
                            ->required(fn (\Filament\Forms\Get $get) => $get('status') === 'Ditolak'),
                            
                        Forms\Components\Hidden::make('diajukan_oleh')->default(fn () => Auth::user()->name),
                        Forms\Components\Hidden::make('validator_id')->default(fn () => Auth::id()),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByRaw("FIELD(status, 'Menunggu', 'Disetujui', 'Ditolak')")->orderBy('created_at', 'desc'))
            ->columns([
                Tables\Columns\TextColumn::make('siswa.nama_lengkap')->label('Siswa')->weight('bold')->searchable(),
                Tables\Columns\TextColumn::make('nama_prestasi')->label('Prestasi')->searchable()->limit(30),
                Tables\Columns\TextColumn::make('tingkat')->badge()->color('info'),
                Tables\Columns\TextColumn::make('kategori')->badge()->color('gray'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match($state){ 'Menunggu'=>'warning', 'Disetujui'=>'success', 'Ditolak'=>'danger' }),
                Tables\Columns\TextColumn::make('tanggal_perolehan')->label('Tanggal')->date('d M Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['Menunggu'=>'Menunggu', 'Disetujui'=>'Disetujui', 'Ditolak'=>'Ditolak']),
            ])
            ->actions([
                Tables\Actions\Action::make('validasi_cepat')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'Menunggu' && \App\Filament\Resources\PrestasiResource::isValidator())
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Prestasi')
                    ->modalDescription('Anda yakin ingin menyetujui prestasi ini? Sertifikat akan dianggap valid dan masuk ke profil siswa.')
                    ->action(function ($record) {
                        $record->update([
                            'status' => 'Disetujui',
                            'validator_id' => Auth::id(),
                        ]);
                    }),

                Tables\Actions\EditAction::make()->label(fn () => \App\Filament\Resources\PrestasiResource::isValidator() ? 'Cek & Validasi' : 'Edit'),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrestasis::route('/'),
            'edit' => Pages\EditPrestasi::route('/{record}/edit'),
        ];
    }
}