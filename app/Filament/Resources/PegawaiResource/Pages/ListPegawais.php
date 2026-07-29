<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use App\Filament\Resources\PegawaiResource\Widgets\PegawaiStatsWidget;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPegawais extends ListRecords
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PegawaiStatsWidget::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pegawai Baru'),
        ];
    }
}