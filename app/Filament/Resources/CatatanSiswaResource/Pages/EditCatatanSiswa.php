<?php

namespace App\Filament\Resources\CatatanSiswaResource\Pages;

use App\Filament\Resources\CatatanSiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCatatanSiswa extends EditRecord
{
    protected static string $resource = CatatanSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}