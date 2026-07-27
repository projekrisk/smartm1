<?php

namespace App\Filament\Resources\SuratPanggilanResource\Pages;

use App\Filament\Resources\SuratPanggilanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratPanggilans extends ListRecords
{
    protected static string $resource = SuratPanggilanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
