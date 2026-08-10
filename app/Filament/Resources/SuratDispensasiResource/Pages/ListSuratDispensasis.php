<?php

namespace App\Filament\Resources\SuratDispensasiResource\Pages;

use App\Filament\Resources\SuratDispensasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSuratDispensasis extends ListRecords
{
    protected static string $resource = SuratDispensasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Buat Surat'),
        ];
    }
}