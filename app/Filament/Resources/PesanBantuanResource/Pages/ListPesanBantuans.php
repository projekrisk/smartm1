<?php

namespace App\Filament\Resources\PesanBantuanResource\Pages;

use App\Filament\Resources\PesanBantuanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPesanBantuans extends ListRecords
{
    protected static string $resource = PesanBantuanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
