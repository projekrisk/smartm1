<?php

namespace App\Filament\Resources\KehadiranHarianResource\Pages;

use App\Filament\Resources\KehadiranHarianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKehadiranHarian extends EditRecord
{
    protected static string $resource = KehadiranHarianResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}