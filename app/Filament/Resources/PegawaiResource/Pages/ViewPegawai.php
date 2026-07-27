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
            // TOMBOL CETAK BIODATA PEGAWAI
            Actions\Action::make('cetak_biodata_pegawai')
                ->label('Cetak Biodata')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->url(fn (Pegawai $record): string => route('cetak.biodata-pegawai', $record->id))
                ->openUrlInNewTab(),

            // Tombol edit hanya akan muncul di halaman view ini jika user tersebut memiliki hak Edit (Admin/Staf)
            Actions\EditAction::make(),
        ];
    }
}