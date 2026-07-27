<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\Testimoni;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;

class TentangSiswa extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Tentang Aplikasi';
    protected static string $view = 'filament.siswa.pages.tentang-siswa';
    protected static ?string $slug = 'tentang';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    public bool $sudahMenilai = false;
    
    // FUNGSI LAZY LOADING: Menentukan batas jumlah data yang dimuat
    public int $perPage = 5;
    public int $totalTestimoni = 0;

    public function getLayout(): string { return 'filament-panels::components.layout.simple'; }
    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    public function mount(): void
    {
        // Setiap kali masuk halaman, kita reset state agar siswa bisa menilai lagi
        $this->sudahMenilai = false;
        $this->form->fill();
    }

    // Fungsi untuk tombol "Tulis Ulasan Lagi"
    public function tulisLagi(): void
    {
        $this->sudahMenilai = false;
        $this->form->fill();
    }

    // Fungsi untuk tombol "Muat Lainnya"
    public function loadMore(): void
    {
        $this->perPage += 5;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('rating')
                ->label('Penilaian (Bintang)')
                ->options([
                    '5' => '⭐⭐⭐⭐⭐ Sangat Membantu',
                    '4' => '⭐⭐⭐⭐ Cukup Bagus',
                    '3' => '⭐⭐⭐ Biasa Saja',
                    '2' => '⭐⭐ Kurang',
                    '1' => '⭐ Sangat Mengecewakan',
                ])
                ->default('5')
                ->required(),
            Forms\Components\Textarea::make('pesan')
                ->label('Ulasan / Kritik & Saran')
                ->placeholder('Tulis pengalaman Anda menggunakan aplikasi ini...')
                ->required()
                ->rows(3),
        ])->statePath('data')->columns(1);
    }

    public function kirimTestimoni(): void
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $data = $this->form->getState();

        // LOGIKA FILTER KATA KASAR
        $pengaturan = Pengaturan::first();
        if ($pengaturan && $pengaturan->filter_kata_kasar) {
            $kataTerlarang = explode(',', $pengaturan->filter_kata_kasar);
            $pesanLower = strtolower($data['pesan']);

            foreach ($kataTerlarang as $kata) {
                $kataBersih = trim(strtolower($kata));
                if ($kataBersih !== '' && str_contains($pesanLower, $kataBersih)) {
                    Notification::make()
                        ->title('Ulasan Ditolak!')
                        ->body('Mohon gunakan bahasa yang sopan. Ulasan Anda mengandung kata yang dilarang.')
                        ->danger()
                        ->send();
                    return;
                }
            }
        }

        if ($siswa) {
            Testimoni::create([
                'siswa_id' => $siswa->id,
                'rating' => $data['rating'],
                'pesan' => $data['pesan'],
            ]);

            $this->sudahMenilai = true;

            Notification::make()
                ->title('Terima Kasih!')
                ->body('Ulasan Anda telah kami terima.')
                ->success()
                ->send();
        }
    }

    protected function getViewData(): array
    {
        // Menyusun query ulasan
        $query = Testimoni::with('siswa.kelas')->orderBy('created_at', 'desc');
        
        // Hitung total seluruh ulasan di database
        $this->totalTestimoni = $query->count();
        
        // Tarik data hanya sejumlah $perPage (Awalnya 5)
        $semuaTestimoni = $query->take($this->perPage)->get();

        return [
            'semuaTestimoni' => $semuaTestimoni,
        ];
    }
}