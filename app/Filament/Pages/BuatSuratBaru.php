<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\KategoriSurat;

class BuatSuratBaru extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static string $view = 'filament.pages.buat-surat-baru';
    protected static ?string $navigationGroup = 'Persuratan';
    protected static ?string $title = 'Pilih Jenis Surat';
    protected static ?string $navigationLabel = 'Buat Surat Baru';
    protected static ?int $navigationSort = 2;

    protected function getViewData(): array
    {
        return [
            // Mengambil semua kategori beserta jenis suratnya
            'kategoris' => KategoriSurat::with('jenisSurat')->get()
        ];
    }
}