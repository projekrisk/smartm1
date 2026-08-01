<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Pegawai;
use Livewire\Attributes\Url;

class DirektoriGuru extends Page
{
    protected static ?string $title = 'Direktori Guru';
    protected static string $view = 'filament.siswa.pages.direktori-guru';
    protected static ?string $slug = 'direktori-guru';
    
    protected static bool $shouldRegisterNavigation = false; 

    public function getLayout(): string { return 'filament-panels::components.layout.simple'; }
    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    // =========================================================
    // FITUR PENCARIAN & FILTER REAL-TIME ala APLIKASI
    // =========================================================
    #[Url]
    public string $search = '';

    #[Url]
    public string $kategori = 'Semua'; 

    public function setKategori($kat)
    {
        $this->kategori = $kat;
    }

    protected function getViewData(): array
    {
        $query = Pegawai::query();

        // 1. Logika Filter Kategori (Tab)
        if ($this->kategori !== 'Semua') {
            $query->where('jenis_ptk', $this->kategori);
        }

        // 2. Logika Pencarian Nama/Tugas
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('tugas_utama', 'like', '%' . $this->search . '%');
            });
        }

        return [
            'pegawais' => $query->orderBy('nama', 'asc')->get(),
            'kategori' => $this->kategori, // <--- TAMBAHKAN BARIS INI
        ];
    }
}