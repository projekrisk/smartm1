<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class ViewSiswa extends ViewRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetak_biodata')
                ->label('Cetak')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                ->url(fn (Siswa $record): string => route('cetak.biodata', $record->id))
                ->openUrlInNewTab(),
            
            Actions\Action::make('cetak_riwayat_catatan')
                ->label('Riwayat')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                ->url(fn (Siswa $record): string => route('cetak.riwayat-catatan', $record->id))
                ->openUrlInNewTab(),

            // Tombol bawaan Edit Data
            Actions\EditAction::make(),
        ];
    }
}