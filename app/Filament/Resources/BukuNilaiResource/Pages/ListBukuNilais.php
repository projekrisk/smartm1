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
        $actions = [];

        if (in_array(Auth::user()->peran, ['admin', 'staf'])) {
            $actions[] = Actions\Action::make('pantau_ujian')
                ->label('Pantau Pengumpulan (UTS/UAS)')
                ->icon('heroicon-o-eye')
                ->color('warning')
                ->url(fn (): string => BukuNilaiResource::getUrl('pantau'));
        }

        $actions[] = Actions\CreateAction::make()->label('Input Nilai Kelas');

        return $actions;
    }
}