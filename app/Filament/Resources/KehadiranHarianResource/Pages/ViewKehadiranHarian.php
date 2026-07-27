<?php

namespace App\Filament\Resources\KehadiranHarianResource\Pages;

use App\Filament\Resources\KehadiranHarianResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKehadiranHarian extends ViewRecord
{
    protected static string $resource = KehadiranHarianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Jika suatu saat butuh tombol aksi di halaman View, tambahkan di sini
        ];
    }
}