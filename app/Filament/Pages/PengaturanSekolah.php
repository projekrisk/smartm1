<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms;
use App\Models\Pengaturan;
use Filament\Actions\Action;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class PengaturanSekolah extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationLabel = 'Pengaturan';
    protected static ?string $title = 'Pengaturan';    
    protected static string $view = 'filament.pages.pengaturan-sekolah';
    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return Auth::user()->peran === 'admin';
    }

    public function mount(): void
    {
        $pengaturan = Pengaturan::first() ?? Pengaturan::create();
        $this->form->fill($pengaturan->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Pengaturan')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Identitas Sekolah')
                            ->icon('heroicon-o-building-library')
                            ->schema([
                                Forms\Components\TextInput::make('nama_sekolah')
                                    ->label('Nama Sekolah (Ditampilkan di Kop Surat & Cetak)')
                                    ->required()
                                    ->columnSpanFull(),
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('nama_kepala_sekolah')
                                        ->label('Nama Kepala Sekolah'),
                                    Forms\Components\TextInput::make('nip_kepala_sekolah')
                                        ->label('NIP Kepala Sekolah'),
                                ]),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Logo & Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\FileUpload::make('logo_sekolah')
                                        ->label('Logo Sekolah')
                                        ->disk('publik_upload') 
                                        ->directory('pengaturan') 
                                        ->image()
                                        ->maxSize(2048),
                                    Forms\Components\FileUpload::make('logo_dinas')
                                        ->label('Logo Dinas / Yayasan')
                                        ->disk('publik_upload')
                                        ->directory('pengaturan')
                                        ->image()
                                        ->maxSize(2048),
                                ]),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Lokasi Peta')
                            ->icon('heroicon-o-map-pin')
                            ->schema([
                                Forms\Components\Grid::make(2)->schema([
                                    Forms\Components\TextInput::make('lintang')
                                        ->label('Garis Lintang (Latitude)')
                                        ->placeholder('Contoh: -6.123456'),
                                    Forms\Components\TextInput::make('bujur')
                                        ->label('Garis Bujur (Longitude)')
                                        ->placeholder('Contoh: 106.123456'),
                                ]),
                            ]),
                            
                        Forms\Components\Tabs\Tab::make('Keamanan & Filter')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Textarea::make('filter_kata_kasar')
                                    ->label('Daftar Kata Terlarang (Filter Testimoni)')
                                    ->placeholder('bodoh, anjing, babi, jelek, dll')
                                    ->helperText('Pisahkan setiap kata dengan KOMA (,). Sistem akan menolak testimoni siswa yang mengandung kata-kata ini.')
                                    ->rows(4)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function simpan(): void
    {
        $pengaturan = Pengaturan::first();
        $pengaturan->update($this->form->getState());

        Notification::make()
            ->title('Berhasil Disimpan')
            ->body('Pengaturan identitas sekolah telah diperbarui.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reset_data')
                ->label('Reset Data Sistem')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Peringatan: Reset Data Sistem')
                ->modalDescription('Tindakan ini sangat berbahaya dan TIDAK DAPAT DIBATALKAN. Pastikan Anda telah mengunduh (Backup/Ekspor) data penting sebelum melanjutkan.')
                ->modalSubmitActionLabel('Eksekusi Reset')
                ->form([
                    Forms\Components\Select::make('tipe_reset')
                        ->label('Pilih Jenis Reset')
                        ->options([
                            'transaksi' => '1. Reset Transaksi Saja (Hapus Nilai, Absensi, Kasus & Jurnal. Data Siswa AMAN)',
                            'total' => '2. Reset Kuras Habis (Hapus Siswa, Kelas, Mapel, Pegawai. Aplikasi Seperti Baru)',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('konfirmasi')
                        ->label('Ketik "RESET" untuk mengonfirmasi')
                        ->placeholder('Ketik huruf kapital: RESET')
                        ->required()
                        ->rules(['in:RESET'])
                        ->validationMessages([
                            'in' => 'Kata konfirmasi salah! Ketik "RESET" tanpa tanda kutip.',
                        ]),
                ])
                ->action(function (array $data) {
                    if ($data['konfirmasi'] !== 'RESET') return;

                    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

                    if ($data['tipe_reset'] === 'transaksi') {
                        DB::table('buku_nilai')->truncate();
                        DB::table('penilaian')->truncate();
                        DB::table('nilai_rapor')->truncate();
                        DB::table('kehadiran_harian')->truncate();
                        DB::table('rekap_kehadiran')->truncate();
                        DB::table('kehadiran_pelajaran')->truncate();
                        DB::table('jurnal_guru')->truncate();
                        DB::table('catatan_siswa')->truncate();
                        DB::table('surat_panggilan')->truncate();
                        
                    } elseif ($data['tipe_reset'] === 'total') {
                        DB::table('buku_nilai')->truncate();
                        DB::table('penilaian')->truncate();
                        DB::table('nilai_rapor')->truncate();
                        DB::table('kehadiran_harian')->truncate();
                        DB::table('rekap_kehadiran')->truncate();
                        DB::table('kehadiran_pelajaran')->truncate();
                        DB::table('jurnal_guru')->truncate();
                        DB::table('catatan_siswa')->truncate();
                        DB::table('surat_panggilan')->truncate();
                        DB::table('riwayat_kelas_siswa')->truncate();
                        DB::table('siswa')->truncate();
                        DB::table('tahun_ajaran')->truncate();
                        DB::table('testimoni')->truncate();
                        
                        DB::table('jadwal_pelajaran')->truncate(); 
                        
                        DB::table('mata_pelajaran')->truncate();
                        DB::table('kelas')->truncate();
                        
                        DB::table('users')->where('peran', '!=', 'admin')->delete();
                        DB::table('pegawai')->truncate();
                        DB::table('pengumuman')->truncate();
                        DB::table('pesan_bantuan')->truncate();
                    }

                    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

                    Notification::make()
                        ->title('Sistem Berhasil Direset')
                        ->body('Data telah dibersihkan sesuai instruksi Anda.')
                        ->success()
                        ->send();
                }),
        ];
    }
}