<?php

namespace App\Filament\Resources\SuratDispensasiResource\Pages;

use App\Filament\Resources\SuratDispensasiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSuratDispensasi extends CreateRecord
{
    protected static string $resource = SuratDispensasiResource::class;

    // 🌟 Otomatis kembali ke tabel arsip (/admin/surat-dispensasi) setelah berhasil dibuat
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}