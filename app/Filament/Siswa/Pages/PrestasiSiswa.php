<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Prestasi;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;

class PrestasiSiswa extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'PrestasiKu';
    protected static string $view = 'filament.siswa.pages.prestasi-siswa';
    protected static ?string $slug = 'prestasi';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public bool $isCreatingNew = false;

    public function getLayout(): string { return 'filament-panels::components.layout.simple'; }
    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function buatPengajuanBaru(): void
    {
        $this->isCreatingNew = true;
        $this->form->fill();
    }

    public function kembaliKeList(): void
    {
        $this->isCreatingNew = false;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama_prestasi')
                ->label('Judul Prestasi / Lomba')
                ->placeholder('Contoh: Juara 1 Lomba Puisi')
                ->required(),
                
            Forms\Components\Select::make('juara')
                ->label('Peringkat / Juara')
                ->options(['Juara 1' => 'Juara 1', 'Juara 2' => 'Juara 2', 'Juara 3' => 'Juara 3', 'Harapan 1' => 'Harapan 1', 'Harapan 2' => 'Harapan 2', 'Lainnya' => 'Lainnya'])
                ->required(),
                
            Forms\Components\Select::make('jenis')
                ->label('Jenis Lomba')
                ->options(['Individu' => 'Individu / Perorangan', 'Beregu' => 'Beregu / Kelompok'])
                ->required(),
                
            Forms\Components\Select::make('kategori')
                ->label('Kategori')
                ->options(['Akademik' => 'Akademik', 'Non-Akademik' => 'Non-Akademik'])
                ->required(),
                
            Forms\Components\Select::make('tingkat')
                ->label('Tingkat')
                ->options(['Sekolah' => 'Tingkat Sekolah', 'Kecamatan' => 'Tingkat Kecamatan', 'Kabupaten/Kota' => 'Tingkat Kabupaten/Kota', 'Provinsi' => 'Tingkat Provinsi', 'Nasional' => 'Tingkat Nasional', 'Internasional' => 'Tingkat Internasional'])
                ->required(),
            
            Forms\Components\DatePicker::make('tanggal_perolehan')
                ->label('Tanggal Diperoleh')
                ->default(now())
                ->required(),
                
            Forms\Components\TextInput::make('penyelenggara')
                ->label('Penyelenggara (Opsional)')
                ->placeholder('Contoh: Kemendikbud'),
                
            Forms\Components\FileUpload::make('bukti_file')
                ->label('Upload Bukti (Sertifikat / Piagam / Foto Trofi)')
                ->disk('publik_upload')->directory('prestasi')
                ->image()
                ->maxSize(2048)
                ->required()
                ->helperText('Wajib mengunggah 1 bukti foto/scan asli (Maks 2MB).'),
        ])->statePath('data')->columns(1); // PAKSA MURNI 1 KOLOM UNTUK APLIKASI SISWA
    }

    public function kirimPengajuan(): void
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $data = $this->form->getState();

        if ($siswa) {
            Prestasi::create(array_merge($data, [
                'siswa_id' => $siswa->id,
                'status' => 'Menunggu',
                'diajukan_oleh' => 'Siswa',
            ]));

            Notification::make()
                ->title('Pengajuan Berhasil!')
                ->body('Prestasi Anda sedang diverifikasi oleh staf/admin.')
                ->success()
                ->send();

            $this->isCreatingNew = false;
            $this->form->fill();
        }
    }

    protected function getViewData(): array
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $prestasis = collect();

        if ($siswa) {
            $prestasis = Prestasi::where('siswa_id', $siswa->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return ['prestasis' => $prestasis];
    }
}