<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratDispensasiResource\Pages;
use App\Models\SuratDispensasi;
use App\Models\Pegawai;
use App\Models\Siswa;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SuratDispensasiResource extends Resource
{
    protected static ?string $model = \App\Models\SuratDispensasi::class;

    protected static ?string $slug = 'surat-dispensasi';
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Surat Dispensasi';
    protected static ?string $pluralModelLabel = 'Surat Dispensasi';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?int $navigationSort = 15;

    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf']);
    }

    public static function form(Form $form): Form
    {
        $getPenandatangan = function () {
            return \App\Models\Pegawai::all()->filter(function ($pegawai) {
                $jenisPtk = strtolower((string) $pegawai->jenis_ptk);
                if (str_contains($jenisPtk, 'kepala sekolah')) return true;
                
                $tugas = strtolower(json_encode($pegawai->tugas_tambahan));
                if (str_contains($tugas, 'kesiswaan') || str_contains($tugas, 'kepala sekolah')) return true;
                
                return false;
            });
        };

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat')
                    ->schema([
                        Forms\Components\TextInput::make('nomor_urut')
                            ->label('Nomor Surat')
                            ->required()
                            ->numeric()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::generateNomorSurat($get, $set);
                            }),

                        Forms\Components\TextInput::make('nomor_surat_lengkap')
                            ->label('Format Lengkap (Otomatis)')
                            ->disabled()
                            ->dehydrated()
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Kegiatan')
                    ->schema([
                        Forms\Components\TextInput::make('nama_kegiatan')->required()->columnSpanFull(),
                        Forms\Components\TextInput::make('penyelenggara')->required(),
                        Forms\Components\TextInput::make('tempat')->required(),
                        Forms\Components\DatePicker::make('tanggal_mulai')->required(),
                        Forms\Components\DatePicker::make('tanggal_selesai')->required(),
                        Forms\Components\DatePicker::make('tanggal_surat')->default(now())->required(),
                        
                        Forms\Components\Select::make('penandatangan_id')
                            ->label('Ditandatangani Oleh')
                            ->options(function () use ($getPenandatangan) {
                                return $getPenandatangan()->pluck('nama', 'id');
                            })
                            ->default(function () use ($getPenandatangan) {
                                $pejabat = $getPenandatangan()->first();
                                return $pejabat ? $pejabat->id : null;
                            })
                            ->required()
                            ->searchable(),
                    ])->columns(2),

                Forms\Components\Section::make('Peserta Didik (Lampiran)')
                    ->schema([
                        Forms\Components\Select::make('siswa')
                            ->multiple()
                            ->relationship('siswa', 'nama_lengkap')
                            ->getOptionLabelFromRecordUsing(function ($record) {
                                $kelas = $record->kelas ? $record->kelas->nama_kelas : 'Tanpa Kelas';
                                return "{$record->nama_lengkap} - (Kelas: {$kelas})";
                            })
                            ->preload()
                            ->searchable()
                            ->label('Pilih Siswa yang Diberi Dispensasi')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function generateNomorSurat(Get $get, Set $set)
    {
        $nomor = $get('nomor_urut');

        if ($nomor) {
            $tahun = date('Y');
            $kodePrefix = '400.03.08'; 
            $kodeSuffix = 'SMA.01-MLP';

            $nomorLengkap = "{$kodePrefix}/{$nomor}/{$kodeSuffix}/{$tahun}";
            $set('nomor_surat_lengkap', $nomorLengkap);
        }
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor_surat_lengkap')->label('No. Surat')->searchable(),
                Tables\Columns\TextColumn::make('nama_kegiatan')->limit(30),
                Tables\Columns\TextColumn::make('tanggal_mulai')->date('d M Y'),
                Tables\Columns\TextColumn::make('siswa_count')
                    ->counts('siswa')
                    ->label('Jml Siswa')
                    ->badge(),
            ])
            ->actions([
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('cetak.dispensasi', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSuratDispensasis::route('/'),
            'create' => Pages\CreateSuratDispensasi::route('/create'),
            'edit' => Pages\EditSuratDispensasi::route('/{record}/edit'),
        ];
    }
}