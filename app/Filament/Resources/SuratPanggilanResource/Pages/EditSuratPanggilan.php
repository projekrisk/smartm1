<?php

namespace App\Filament\Resources\SuratPanggilanResource\Pages;

use App\Filament\Resources\SuratPanggilanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSuratPanggilan extends EditRecord
{
    protected static string $resource = SuratPanggilanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
