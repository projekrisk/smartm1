<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Pegawai;

class ViewPegawai extends ViewRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetak_biodata_pegawai')
                ->label('Cetak Biodata')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn (Pegawai $record): string => route('cetak.biodata-pegawai', $record->id))
                ->openUrlInNewTab(),

            Actions\EditAction::make(),
        ];
    }
}