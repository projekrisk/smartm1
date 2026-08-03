<?php

namespace App\Filament\Resources\JenisNilaiResource\Pages;

use App\Filament\Resources\JenisNilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageJenisNilais extends ManageRecords
{
    protected static string $resource = JenisNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
