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
    protected static ?string $navigationLabel = 'Nilai Rapor Semester';
    protected static ?string $pluralModelLabel = 'Data Nilai Rapor Akhir';
    protected static ?string $modelLabel = 'Nilai Rapor';
    protected static ?string $navigationGroup = 'Akademik';    
    protected static ?int $navigationSort = 10;

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
            ->headerActions([
                // ======================================================================================
                // PERBAIKAN: EKSPOR LEGER RAPOR (EXCEL HTML STREAMING)
                // ======================================================================================
                Tables\Actions\Action::make('ekspor_leger_cepat')
                    ->label('Download Leger Rapor')
                    ->color('success')
                    ->icon('heroicon-o-table-cells')
                    ->action(function () {
                        set_time_limit(0);
                        ini_set('memory_limit', '-1');

                        return response()->streamDownload(function () {
                            // Ambil Master Data berdasar Tahun & Semester
                            $daftarTahun = \App\Models\TahunAjaran::select('nama_tahun')->distinct()->orderBy('nama_tahun')->pluck('nama_tahun');
                            $semuaTA = \App\Models\TahunAjaran::all();
                            $mapels = MataPelajaran::orderBy('id')->get();
                            
                            $siswas = Siswa::with(['kelas', 'riwayatKelas.kelas'])
                                ->orderBy('kelas_id')
                                ->orderBy('nama_lengkap')
                                ->get();

                            // Tarik seluruh nilai akhir siswa dari database ke memory
                            $semuaNilai = NilaiRapor::select('siswa_id', 'tahun_ajaran_id', 'mata_pelajaran_id', 'nilai_akhir')
                                ->get()
                                ->groupBy('siswa_id');

                            echo '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
                            echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>';
                            echo '<body>';
                            echo '<table border="1" style="border-collapse: collapse; font-family: Calibri, sans-serif; font-size: 11pt;">';
                            
                            // BARIS 1: HEADER UTAMA
                            echo '<tr style="background-color: #e2efda; font-weight: bold; text-align: center;">';
                            echo '<th rowspan="3" style="vertical-align: middle;">No</th>';
                            echo '<th rowspan="3" style="vertical-align: middle;">NIS</th>';
                            echo '<th rowspan="3" style="vertical-align: middle;">NISN</th>';
                            echo '<th rowspan="3" style="vertical-align: middle; width: 250px;">Nama Lengkap</th>';
                            echo '<th rowspan="3" style="vertical-align: middle;">Kelas Saat Ini</th>';
                            echo '<th colspan="'.$daftarTahun->count().'">Riwayat Kelas (Sesuai Tahun Ajaran)</th>';
                            
                            foreach($mapels as $mapel) {
                                $namaMapel = $mapel->nama_pelajaran ?? 'MAPEL';
                                echo '<th colspan="'.($daftarTahun->count() * 2).'">'.strtoupper($namaMapel).'</th>';
                            }
                            echo '</tr>';

                            // BARIS 2: TAHUN AJARAN
                            echo '<tr style="background-color: #e2efda; font-weight: bold; text-align: center;">';
                            foreach($daftarTahun as $tahun) {
                                echo '<th rowspan="2" style="vertical-align: middle;">TA. '.$tahun.'</th>';
                            }
                            foreach($mapels as $mapel) {
                                foreach($daftarTahun as $tahun) {
                                    echo '<th colspan="2">'.$tahun.'</th>';
                                }
                            }
                            echo '</tr>';

                            // BARIS 3: SEMESTER
                            echo '<tr style="background-color: #e2efda; font-weight: bold; text-align: center;">';
                            foreach($mapels as $mapel) {
                                foreach($daftarTahun as $tahun) {
                                    echo '<th>Smt Ganjil</th><th>Smt Genap</th>';
                                }
                            }
                            echo '</tr>';

                            $currentKelas = null;
                            $no = 1;

                            // PENGISIAN DATA SISWA
                            foreach($siswas as $siswa) {
                                $namaKelas = $siswa->kelas ? $siswa->kelas->nama_kelas : 'Belum Ada Kelas';
                                
                                if ($currentKelas !== $namaKelas) {
                                    $currentKelas = $namaKelas;
                                    $no = 1; 
                                    $totalCols = 5 + $daftarTahun->count() + ($mapels->count() * $daftarTahun->count() * 2);
                                    echo '<tr style="background-color: #d9e1f2; font-weight: bold;">';
                                    echo '<td colspan="'.$totalCols.'">KELOMPOK KELAS: '.$currentKelas.'</td>';
                                    echo '</tr>';
                                }

                                echo '<tr>';
                                echo '<td style="text-align: center;">'.$no++.'</td>';
                                echo '<td style="text-align: center;">="'.($siswa->nis ?? '').'"</td>';
                                echo '<td style="text-align: center;">="'.($siswa->nisn ?? '').'"</td>';
                                echo '<td>'.$siswa->nama_lengkap.'</td>';
                                echo '<td style="text-align: center;">'.$namaKelas.'</td>';

                                foreach($daftarTahun as $tahun) {
                                    $taIds = $semuaTA->where('nama_tahun', $tahun)->pluck('id')->toArray();
                                    $riwayat = $siswa->riwayatKelas->whereIn('tahun_ajaran_id', $taIds)->first();
                                    echo '<td style="text-align: center;">'.($riwayat && $riwayat->kelas ? $riwayat->kelas->nama_kelas : '-').'</td>';
                                }

                                $nilaiSiswa = $semuaNilai->get($siswa->id, collect());
                                
                                foreach($mapels as $mapel) {
                                    foreach($daftarTahun as $tahun) {
                                        $taGanjil = $semuaTA->where('nama_tahun', $tahun)->where('semester', 'Ganjil')->first();
                                        $taGenap = $semuaTA->where('nama_tahun', $tahun)->where('semester', 'Genap')->first();

                                        $nGanjil = $taGanjil ? $nilaiSiswa->where('mata_pelajaran_id', $mapel->id)->where('tahun_ajaran_id', $taGanjil->id)->first() : null;
                                        $nGenap = $taGenap ? $nilaiSiswa->where('mata_pelajaran_id', $mapel->id)->where('tahun_ajaran_id', $taGenap->id)->first() : null;
                                        
                                        echo '<td style="text-align: center;">'.($nGanjil ? $nGanjil->nilai_akhir : '').'</td>';
                                        echo '<td style="text-align: center;">'.($nGenap ? $nGenap->nilai_akhir : '').'</td>';
                                    }
                                }
                                echo '</tr>';
                            }

                            echo '</table></body></html>';
                        }, 'Leger_Rapor_' . date('Y-m-d_H-i') . '.xls', [
                            'Content-Type' => 'application/vnd.ms-excel',
                        ]);
                    }),

                // ======================================================================================
                // TOMBOL IMPOR ANDA TETAP UTUH & TIDAK ADA YANG DIHAPUS
                // ======================================================================================
                Tables\Actions\Action::make('impor_matriks')
                    ->label('Impor')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        Forms\Components\Select::make('tahun_ajaran_id')
                            ->label('Masukkan Nilai Untuk Tahun Ajaran & Semester Apa?')
                            ->options(\App\Models\TahunAjaran::all()->mapWithKeys(function ($ta) {
                                return [$ta->id => "{$ta->nama_tahun} - {$ta->semester}"];
                            }))
                            ->default(fn () => \App\Models\TahunAjaran::where('is_active', true)->first()?->id)
                            ->required(),
                            
                        Forms\Components\FileUpload::make('file')
                            ->label('Upload File CSV')
                            ->disk('local')
                            ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values'])
                            ->required()
                            ->helperText('Contoh Judul Kolom: nisn, MTK, INDO, INGG. Sistem akan membaca Kode Mapel secara otomatis.'),
                    ])
                    ->action(function (array $data) {
                        set_time_limit(0);
                        
                        $filePath = Storage::disk('local')->path($data['file']);
                        if (!file_exists($filePath)) {
                            Notification::make()->title('File tidak ditemukan')->danger()->send();
                            return;
                        }

                        $file = fopen($filePath, 'r');
                        $headers = fgetcsv($file);
                        
                        if (isset($headers[0])) {
                            $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);
                        }
                        
                        $headers = array_map(function($val) { return strtoupper(trim($val)); }, $headers);
                        
                        if ($headers[0] !== 'NISN') {
                            Notification::make()->title('Gagal: Kolom pertama wajib bernama "NISN"')->danger()->send();
                            fclose($file);
                            return;
                        }

                        $tahunAjaranId = $data['tahun_ajaran_id'];
                        
                        $mapelMap = [];
                        $mapelTidakDitemukan = [];
                        for ($i = 1; $i < count($headers); $i++) {
                            $kode = $headers[$i];
                            $mapel = MataPelajaran::where('kode_pelajaran', $kode)->first();
                            
                            if ($mapel) {
                                $mapelMap[$i] = $mapel->id;
                            } else {
                                $mapelTidakDitemukan[] = $kode;
                            }
                        }

                        $berhasilDisimpan = 0;
                        $siswaTidakDitemukan = 0;

                        DB::beginTransaction();
                        try {
                            while (($row = fgetcsv($file)) !== false) {
                                if (count($row) !== count($headers)) continue;
                                
                                $nisn = trim($row[0] ?? '');
                                if (!$nisn) continue;

                                $siswa = Siswa::where('nisn', $nisn)->first();
                                if (!$siswa) {
                                    $siswaTidakDitemukan++;
                                    continue;
                                }

                                for ($i = 1; $i < count($row); $i++) {
                                    if (!isset($mapelMap[$i])) continue;
                                    
                                    $nilai = trim($row[$i] ?? '');
                                    
                                    if ($nilai !== '' && is_numeric($nilai) && $nilai >= 0 && $nilai <= 100) {
                                        NilaiRapor::updateOrCreate(
                                            [
                                                'siswa_id' => $siswa->id,
                                                'mata_pelajaran_id' => $mapelMap[$i],
                                                'tahun_ajaran_id' => $tahunAjaranId,
                                            ],
                                            [
                                                'nilai_akhir' => $nilai,
                                            ]
                                        );
                                        $berhasilDisimpan++;
                                    }
                                }
                            }
                            DB::commit();
                            
                            $pesan = "Sebanyak $berhasilDisimpan nilai berhasil direkam/diperbarui.";
                            if (!empty($mapelTidakDitemukan)) {
                                $pesan .= " PERINGATAN: Kolom kode (".implode(', ', $mapelTidakDitemukan).") dilewati karena tidak ada di sistem.";
                            }
                            if ($siswaTidakDitemukan > 0) {
                                $pesan .= " $siswaTidakDitemukan baris NISN siswa tidak ditemukan.";
                            }

                            Notification::make()
                                ->title('Impor Matriks Selesai')
                                ->body($pesan)
                                ->success()
                                ->send();

                        } catch (\Exception $e) {
                            DB::rollBack();
                            Notification::make()
                                ->title('Terjadi Kesalahan')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }

                        fclose($file);
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