<?php

namespace App\Filament\Resources\BukuNilaiResource\Pages;

use App\Filament\Resources\BukuNilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBukuNilai extends CreateRecord
{
    protected static string $resource = BukuNilaiResource::class;

    // Mengarahkan ke halaman Daftar Penilaian (Tabel Index) setelah berhasil simpan
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}