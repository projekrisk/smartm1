<?php

namespace App\Filament\Resources\BukuNilaiResource\Pages;

use App\Filament\Resources\BukuNilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListBukuNilais extends ListRecords
{
    protected static string $resource = BukuNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // TOMBOL INI YANG BIKIN ERROR, HAPUS ATAU JADIKAN KOMENTAR:
            // Actions\Action::make('pantau')
            //     ->label('Pantau Pengumpulan')
            //     ->url(fn (): string => BukuNilaiResource::getUrl('pantau')),
            
            Actions\Action::make('input_massal')
                ->label('Input Nilai Massal')
                ->url(fn (): string => BukuNilaiResource::getUrl('input-massal')),
                
            Actions\CreateAction::make(),
        ];
    }
}