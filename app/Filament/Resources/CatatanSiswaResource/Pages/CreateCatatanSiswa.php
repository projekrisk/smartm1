<?php

namespace App\Filament\Resources\CatatanSiswaResource\Pages;

use App\Filament\Resources\CatatanSiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCatatanSiswa extends CreateRecord
{
    protected static string $resource = CatatanSiswaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}