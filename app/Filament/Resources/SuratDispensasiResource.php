<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuratDispensasiResource\Pages;
use App\Filament\Resources\SuratDispensasiResource\RelationManagers;
use App\Models\SuratDispensasi;
use App\Models\Pegawai;
use App\Models\KategoriSurat;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SuratDispensasiResource extends Resource
{
    protected static ?string $model = \App\Models\SuratDispensasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?string $navigationLabel = 'Arsip Dispensasi';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        $getPenandatangan = function () {
            return \App\Models\Pegawai::all()->filter(function ($pegawai) {
                
                // Cek 1: Kepala Sekolah dari kolom jenis_ptk
                $jenisPtk = strtolower((string) $pegawai->jenis_ptk);
                if (str_contains($jenisPtk, 'kepala sekolah')) {
                    return true;
                }
                
                // Cek 2: Wakasek / Kepala Sekolah dari tugas_tambahan
                // Trik: Ubah apapun formatnya (Array/JSON/String) menjadi satu baris teks biasa
                $tugas = json_encode($pegawai->tugas_tambahan);
                $tugas = strtolower($tugas);
                
                if (str_contains($tugas, 'kesiswaan') || str_contains($tugas, 'kepala sekolah')) {
                    return true;
                }
                
                return false;
            });
        };

        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Surat')
                    ->schema([
                        Forms\Components\Select::make('kategori_surat_id')
                            ->label('Jenis Surat')
                            ->relationship('kategori', 'nama_kategori')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Get $get, Set $set) {
                                self::generateNomorSurat($get, $set);
                            }),
                            
                        Forms\Components\TextInput::make('nomor_urut')
                            ->label('Nomor Surat (Input Manual)')
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
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Kegiatan')
                    ->schema([
                        Forms\Components\TextInput::make('nama_kegiatan')->required()->columnSpanFull(),
                        Forms\Components\TextInput::make('penyelenggara')->required(),
                        Forms\Components\TextInput::make('tempat')->required(),
                        Forms\Components\DatePicker::make('tanggal_mulai')->required(),
                        Forms\Components\DatePicker::make('tanggal_selesai')->required(),
                        Forms\Components\DatePicker::make('tanggal_surat')->default(now())->required(),
                        
                        // 2. FORM SELECT PENANDATANGAN
                        Forms\Components\Select::make('penandatangan_id')
                            ->label('Ditandatangani Oleh (Kepala Sekolah / Wakasek)')
                            ->options(function () use ($getPenandatangan) {
                                // Akan memunculkan opsi pegawai yang lolos seleksi di atas
                                return $getPenandatangan()->pluck('nama', 'id');
                            })
                            ->default(function () use ($getPenandatangan) {
                                // Otomatis memilih pejabat pertama yang ditemukan
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
                            ->preload()
                            ->searchable()
                            ->label('Pilih Siswa yang Diberi Dispensasi')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    // Fungsi canggih pembuat nomor urut
    public static function generateNomorSurat(Get $get, Set $set)
    {
        $kategoriId = $get('kategori_surat_id');
        $nomor = $get('nomor_urut');

        if ($kategoriId && $nomor) {
            $kategori = KategoriSurat::find($kategoriId);
            $tahun = date('Y');
            // Hasil: 400.03.08/123/SMA.01-MLP/2026
            $nomorLengkap = "{$kategori->kode_prefix}/{$nomor}/{$kategori->kode_suffix}/{$tahun}";
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
                // Tombol Cetak HTML
                Tables\Actions\Action::make('cetak')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('cetak.dispensasi', $record->id))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
