<?php

namespace App\Filament\Resources\SuratDispensasiResource\Pages;

use App\Filament\Resources\SuratDispensasiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratDispensasi extends EditRecord
{
    protected static string $resource = SuratDispensasiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
