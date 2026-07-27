<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PegawaiResource\Pages;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\PegawaiResource\RelationManagers; 

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;
    
    protected static ?string $slug = 'pegawai';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?string $pluralModelLabel = 'Data Pegawai';
    protected static ?string $modelLabel = 'Pegawai';
    protected static ?int $navigationSort = 4;

    // 1. IZINKAN GURU & STAF UNTUK MELIHAT MENU DATA PEGAWAI
    // 1. IZINKAN GURU, STAF, & ADMIN UNTUK MELIHAT MENU DATA PEGAWAI
    public static function canViewAny(): bool
    {
        return in_array(auth()->user()->peran, ['admin', 'staf', 'guru']);
    }

    // 2. JIKA GURU ATAU STAF, HANYA TAMPILKAN DATANYA SENDIRI DI TABEL
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        
        // Guru dan Staf hanya melihat datanya sendiri, Admin melihat semua
        if (in_array(auth()->user()->peran, ['guru', 'staf'])) {
            $query->where('user_id', auth()->id());
        }
        
        return $query;
    }

    // 3. HANYA ADMIN YANG BISA MEMBUAT DATA PEGAWAI BARU
    public static function canCreate(): bool
    {
        return auth()->user()->peran === 'admin';
    }

    // 4. HANYA ADMIN YANG BISA MENGEDIT DATA POKOK
    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->peran === 'admin';
    }

    // 5. HANYA ADMIN YANG BISA MENGHAPUS PEGAWAI
    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->peran === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Data Pegawai')
                    ->tabs([
                        // TAB 1: IDENTITAS PRIBADI
                        Forms\Components\Tabs\Tab::make('Identitas Pribadi')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('nama')
                                        ->label('Nama Lengkap (Beserta Gelar)')
                                        ->required()
                                        ->columnSpanFull(),
                                    Forms\Components\TextInput::make('nik')
                                        ->label('NIK (Nomor Induk Kependudukan)')
                                        ->required()
                                        ->numeric()
                                        ->unique(ignoreRecord: true),
                                    Forms\Components\Select::make('jenis_kelamin')
                                        ->label('Jenis Kelamin')
                                        ->options([
                                            'Laki-laki' => 'Laki-laki',
                                            'Perempuan' => 'Perempuan',
                                        ])
                                        ->required(),
                                    Forms\Components\TextInput::make('tempat_lahir')
                                        ->label('Tempat Lahir'),
                                    Forms\Components\DatePicker::make('tanggal_lahir')
                                        ->label('Tanggal Lahir'),
                                    Forms\Components\TextInput::make('telepon')
                                        ->label('Nomor Telepon / WhatsApp')
                                        ->tel(),
                                    Forms\Components\TextInput::make('email')
                                        ->label('Email Aktif (Untuk Login)')
                                        ->email()
                                        ->required()
                                        ->unique(ignoreRecord: true),
                                ]),
                            ]),

                        // TAB 2: DATA KEPEGAWAIAN
                        Forms\Components\Tabs\Tab::make('Data Kepegawaian')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('status_kepegawaian')
                                        ->label('Status Kepegawaian')
                                        ->options([
                                            'Guru' => 'Guru',
                                            'Staf' => 'Staf / Tata Usaha',
                                            'Keamanan' => 'Keamanan / Satpam',
                                            'Lainnya' => 'Lainnya',
                                        ])
                                        ->required(),
                                    Forms\Components\TextInput::make('tugas_utama')
                                        ->label('Tugas Utama')
                                        ->placeholder('Contoh: Guru Matematika / Pembantu Pelaksana')
                                        ->required(),
                                    Forms\Components\TextInput::make('nip')
                                        ->label('NIP (Kosongkan jika honorer)'),
                                    Forms\Components\TextInput::make('nuptk')
                                        ->label('NUPTK'),
                                    Forms\Components\TextInput::make('pangkat_golongan')
                                        ->label('Pangkat / Gol. Ruang')
                                        ->placeholder('Contoh: Penata Muda, III/a'),
                                    Forms\Components\TextInput::make('jabatan')
                                        ->label('Jabatan')
                                        ->placeholder('Contoh: Guru Ahli Pertama'),
                                    
                                    Forms\Components\Placeholder::make('status_tugas_saat_ini')
                                        ->label('Status Tugas Saat Ini (Termasuk Wali Kelas)')
                                        ->content(function ($record) {
                                            if (!$record) return 'Belum ada data.';
                                            $tugas = $record->daftar_tugas_tambahan; 
                                            return empty($tugas) ? 'Tidak ada tugas tambahan.' : implode(', ', (array) $tugas);
                                        })
                                        ->columnSpanFull(),

                                    Forms\Components\TagsInput::make('tugas_tambahan')
                                        ->label('Input Tugas Tambahan Manual')
                                        ->placeholder('Ketik lalu tekan Enter (Contoh: Wakasek Humas, Bendahara)')
                                        ->helperText('Catatan: Jangan ketik "Wali Kelas" di sini. Tugas Wali Kelas otomatis terdeteksi.')
                                        ->columnSpanFull(),
                                ]),
                            ]),

                        // TAB 3: RIWAYAT TMT & MASA KERJA
                        Forms\Components\Tabs\Tab::make('Riwayat Pengangkatan')
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\DatePicker::make('tmt_cpns_honorer')
                                        ->label('TMT CPNS / Honorer Awal')
                                        ->helperText('Tanggal mulai bertugas pertama kali.'),
                                    Forms\Components\DatePicker::make('tmt_pns_pppk')
                                        ->label('TMT PNS / PPPK')
                                        ->helperText('Tanggal diangkat menjadi pegawai tetap (jika ada).'),
                                    Forms\Components\DatePicker::make('tmt_golongan_terakhir')
                                        ->label('TMT Golongan Terakhir')
                                        ->helperText('Tanggal kenaikan pangkat terakhir.'),
                                ]),
                                Forms\Components\Placeholder::make('kalkulasi_masa_kerja')
                                    ->label('Masa Kerja Terhitung (Otomatis)')
                                    ->content(fn ($record) => $record ? 
                                        "Masa Kerja Golongan: " . intval($record->masa_kerja_golongan) . " Tahun | Keseluruhan: " . intval($record->masa_kerja_keseluruhan) . " Tahun" 
                                        : 'Akan terhitung otomatis setelah data disimpan.')
                                    ->columnSpanFull(),
                            ]),

                        // TAB 4: PENDIDIKAN
                        Forms\Components\Tabs\Tab::make('Pendidikan Terakhir')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\Select::make('pendidikan_ijazah')
                                        ->label('Tingkat Ijazah')
                                        ->options([
                                            'SMA/SMK' => 'SMA / SMK Sederajat',
                                            'D3' => 'Diploma 3 (D3)',
                                            'S1' => 'Strata 1 (S1) / D4',
                                            'S2' => 'Strata 2 (S2)',
                                            'S3' => 'Strata 3 (S3)',
                                        ]),
                                    Forms\Components\TextInput::make('pendidikan_tahun')
                                        ->label('Tahun Lulus')
                                        ->numeric(),
                                    Forms\Components\TextInput::make('pendidikan_jurusan')
                                        ->label('Fakultas / Jurusan')
                                        ->placeholder('Contoh: S.Pd Pendidikan Bahasa Inggris')
                                        ->columnSpanFull(),
                                ]),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Tabs::make('Data Pegawai')
                    ->tabs([
                        Infolists\Components\Tabs\Tab::make('Identitas')
                            ->icon('heroicon-o-user')
                            ->schema([
                                Infolists\Components\Grid::make(2)->schema([
                                    Infolists\Components\TextEntry::make('nama')->label('Nama Lengkap')->weight('bold')->size(Infolists\Components\TextEntry\TextEntrySize::Large)->columnSpanFull(),
                                    Infolists\Components\TextEntry::make('nik')->label('NIK (Nomor Kependudukan)'),
                                    Infolists\Components\TextEntry::make('jenis_kelamin')->label('Jenis Kelamin'),
                                    Infolists\Components\TextEntry::make('tempat_lahir')->label('Tempat Lahir')->default('-'),
                                    // FUNGSI AJAIB: Mencegah error jika tanggal di database berisi strip (-)
                                    Infolists\Components\TextEntry::make('tanggal_lahir')
                                        ->label('Tanggal Lahir')
                                        ->getStateUsing(fn ($record) => $record->tanggal_lahir && strtotime($record->tanggal_lahir) ? \Carbon\Carbon::parse($record->tanggal_lahir)->isoFormat('D MMMM Y') : '-'),
                                    Infolists\Components\TextEntry::make('telepon')->label('Nomor Telepon')->default('-'),
                                    Infolists\Components\TextEntry::make('email')->label('Email Aktif')->default('-'),
                                ]),
                            ]),
                        Infolists\Components\Tabs\Tab::make('Kepegawaian')
                            ->icon('heroicon-o-briefcase')
                            ->schema([
                                Infolists\Components\Grid::make(2)->schema([
                                    Infolists\Components\TextEntry::make('status_kepegawaian')
                                        ->label('Status Kepegawaian')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'Guru' => 'success',
                                            'Staf' => 'info',
                                            'Keamanan' => 'warning',
                                            default => 'gray',
                                        }),
                                    Infolists\Components\TextEntry::make('tugas_utama')->label('Tugas Utama'),
                                    Infolists\Components\TextEntry::make('nip')->label('NIP')->default('-'),
                                    Infolists\Components\TextEntry::make('nuptk')->label('NUPTK')->default('-'),
                                    Infolists\Components\TextEntry::make('pangkat_golongan')->label('Pangkat / Gol. Ruang')->default('-'),
                                    Infolists\Components\TextEntry::make('jabatan')->label('Jabatan')->default('-'),
                                    Infolists\Components\TextEntry::make('status_tugas_saat_ini')
                                        ->label('Status Tugas Saat Ini (Termasuk Wali Kelas)')
                                        ->getStateUsing(function ($record) {
                                            $tugas = $record->daftar_tugas_tambahan;
                                            return empty($tugas) ? 'Tidak ada tugas tambahan.' : implode(', ', (array) $tugas);
                                        })
                                        ->columnSpanFull(),
                                ]),
                            ]),
                        Infolists\Components\Tabs\Tab::make('Riwayat')
                            ->icon('heroicon-o-calendar-days')
                            ->schema([
                                Infolists\Components\Grid::make(2)->schema([
                                    Infolists\Components\TextEntry::make('tmt_cpns_honorer')
                                        ->label('TMT CPNS / Honorer Awal')
                                        ->getStateUsing(fn ($record) => $record->tmt_cpns_honorer && strtotime($record->tmt_cpns_honorer) ? \Carbon\Carbon::parse($record->tmt_cpns_honorer)->isoFormat('D MMMM Y') : '-'),
                                    Infolists\Components\TextEntry::make('tmt_pns_pppk')
                                        ->label('TMT PNS / PPPK')
                                        ->getStateUsing(fn ($record) => $record->tmt_pns_pppk && strtotime($record->tmt_pns_pppk) ? \Carbon\Carbon::parse($record->tmt_pns_pppk)->isoFormat('D MMMM Y') : '-'),
                                    Infolists\Components\TextEntry::make('tmt_golongan_terakhir')
                                        ->label('TMT Golongan Terakhir')
                                        ->getStateUsing(fn ($record) => $record->tmt_golongan_terakhir && strtotime($record->tmt_golongan_terakhir) ? \Carbon\Carbon::parse($record->tmt_golongan_terakhir)->isoFormat('D MMMM Y') : '-'),
                                ]),
                                Infolists\Components\TextEntry::make('kalkulasi_masa_kerja')
                                    ->label('Masa Kerja Terhitung (Otomatis)')
                                    ->getStateUsing(fn ($record) => "Masa Kerja Golongan: " . intval($record->masa_kerja_golongan) . " Tahun | Keseluruhan: " . intval($record->masa_kerja_keseluruhan) . " Tahun")
                                    ->columnSpanFull(),
                            ]),
                        Infolists\Components\Tabs\Tab::make('Pendidikan')
                            ->icon('heroicon-o-academic-cap')
                            ->schema([
                                Infolists\Components\Grid::make(2)->schema([
                                    Infolists\Components\TextEntry::make('pendidikan_ijazah')->label('Tingkat Ijazah')->default('-'),
                                    Infolists\Components\TextEntry::make('pendidikan_tahun')->label('Tahun Lulus')->default('-'),
                                    Infolists\Components\TextEntry::make('pendidikan_jurusan')->label('Fakultas / Jurusan')->columnSpanFull()->default('-'),
                                ]),
                            ]),
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordAction('view') // KLIK BARIS AKAN MEMBUKA HALAMAN VIEW, BUKAN EDIT
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Pegawai')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('status_kepegawaian')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Guru' => 'success',
                        'Staf' => 'info',
                        'Keamanan' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('tugas_utama')
                    ->label('Tugas Utama')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('masa_kerja_keseluruhan')
                    ->label('Masa Kerja')
                    ->badge()
                    ->color('primary')
                    ->getStateUsing(fn ($record) => intval($record->masa_kerja_keseluruhan) . ' Tahun'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_kepegawaian')
                    ->label('Filter Status')
                    ->options([
                        'Guru' => 'Guru',
                        'Staf' => 'Staf / Tata Usaha',
                        'Keamanan' => 'Keamanan / Satpam',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('unduh_template')
                    ->label('Unduh Template')
                    ->color('info')
                    ->icon('heroicon-o-document-arrow-down')
                    ->visible(fn () => auth()->user()->peran === 'admin') 
                    ->action(function () {
                        $headers = ['nama', 'nik', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'telepon', 'email', 'status_kepegawaian', 'tugas_utama', 'nip', 'nuptk', 'pangkat_golongan', 'jabatan', 'tmt_cpns_honorer', 'tmt_pns_pppk', 'tmt_golongan_terakhir', 'pendidikan_ijazah', 'pendidikan_tahun', 'pendidikan_jurusan'];
                        $csvData = implode(',', $headers) . "\n";
                        
                        return response()->streamDownload(function () use ($csvData) {
                            echo $csvData;
                        }, 'template_impor_pegawai.csv', ['Content-Type' => 'text/csv']);
                    }),

                Tables\Actions\Action::make('impor_csv')
                    ->label('Impor Excel (CSV)')
                    ->color('success')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->visible(fn () => auth()->user()->peran === 'admin') 
                    ->form([
                        Forms\Components\FileUpload::make('file')
                            ->label('Upload File CSV')
                            ->disk('local')
                            ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values'])
                            ->required()
                            ->helperText('Pastikan judul kolom (baris ke-1) sama persis dengan template.'),
                    ])
                    ->action(function (array $data) {
                        set_time_limit(0);
                        ini_set('memory_limit', '512M');

                        $filePath = \Illuminate\Support\Facades\Storage::disk('local')->path($data['file']);
                        if (!file_exists($filePath)) {
                            \Filament\Notifications\Notification::make()->title('Gagal: File tidak ditemukan')->danger()->send();
                            return;
                        }

                        $file = fopen($filePath, 'r');
                        $headers = fgetcsv($file);
                        if (isset($headers[0])) $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);
                        $headers = array_map(function($val) { return strtolower(trim($val)); }, $headers);

                        $berhasil = 0; $gagal = 0; $nikTerbaca = [];
                        \Illuminate\Support\Facades\DB::beginTransaction();

                        try {
                            while (($row = fgetcsv($file)) !== false) {
                                if (count($headers) !== count($row)) { $gagal++; continue; }
                                $rowData = array_combine($headers, $row);
                                $nik = trim($rowData['nik'] ?? '');
                                if (empty($nik) || in_array($nik, $nikTerbaca)) { $gagal++; continue; }
                                $nikTerbaca[] = $nik;

                                $jkRaw = strtolower(trim($rowData['jenis_kelamin'] ?? ''));
                                $jkFix = null;
                                if (in_array($jkRaw, ['l', 'laki-laki', 'laki laki', 'laki'])) $jkFix = 'Laki-laki';
                                elseif (in_array($jkRaw, ['p', 'perempuan', 'wanita'])) $jkFix = 'Perempuan';

                                \App\Models\Pegawai::updateOrCreate(
                                    ['nik' => $nik],
                                    [
                                        'nama' => $rowData['nama'] ?? null,
                                        'jenis_kelamin' => $jkFix,
                                        'tempat_lahir' => $rowData['tempat_lahir'] ?? null,
                                        'tanggal_lahir' => empty($rowData['tanggal_lahir']) ? null : date('Y-m-d', strtotime($rowData['tanggal_lahir'])),
                                        'telepon' => $rowData['telepon'] ?? null,
                                        'email' => $rowData['email'] ?? null,
                                        'status_kepegawaian' => $rowData['status_kepegawaian'] ?? 'Guru',
                                        'tugas_utama' => $rowData['tugas_utama'] ?? 'Guru',
                                        'nip' => $rowData['nip'] ?? null,
                                        'nuptk' => $rowData['nuptk'] ?? null,
                                        'pangkat_golongan' => $rowData['pangkat_golongan'] ?? null,
                                        'jabatan' => $rowData['jabatan'] ?? null,
                                        'tmt_cpns_honorer' => empty($rowData['tmt_cpns_honorer']) ? null : date('Y-m-d', strtotime($rowData['tmt_cpns_honorer'])),
                                        'tmt_pns_pppk' => empty($rowData['tmt_pns_pppk']) ? null : date('Y-m-d', strtotime($rowData['tmt_pns_pppk'])),
                                        'tmt_golongan_terakhir' => empty($rowData['tmt_golongan_terakhir']) ? null : date('Y-m-d', strtotime($rowData['tmt_golongan_terakhir'])),
                                        'pendidikan_ijazah' => $rowData['pendidikan_ijazah'] ?? null,
                                        'pendidikan_tahun' => $rowData['pendidikan_tahun'] ?? null,
                                        'pendidikan_jurusan' => $rowData['pendidikan_jurusan'] ?? null,
                                    ]
                                );
                                $berhasil++;
                            }
                            \Illuminate\Support\Facades\DB::commit();

                        } catch (\Exception $e) {
                            \Illuminate\Support\Facades\DB::rollBack();
                            \Filament\Notifications\Notification::make()->title('Gagal Impor')->body('Kesalahan: ' . $e->getMessage())->danger()->send();
                            fclose($file); return;
                        }
                        fclose($file);
                        \Filament\Notifications\Notification::make()->title('Impor Selesai')->body("Berhasil memproses $berhasil data. Ditolak/Dilewati: $gagal data.")->success()->send();
                    })
                    ->modalHeading('Impor Data Pegawai (CSV)')
                    ->modalSubmitActionLabel('Mulai Impor'),

                Tables\Actions\ExportAction::make()
                    ->exporter(\App\Filament\Exports\PegawaiExporter::class)
                    ->label('Ekspor Data')
                    ->color('warning')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->visible(fn () => auth()->user()->peran === 'admin') 
                    ->columnMapping(false),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BerkasRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'view' => Pages\ViewPegawai::route('/{record}'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}