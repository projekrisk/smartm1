<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class EditSiswa extends EditRecord
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // TOMBOL CETAK BIODATA (Hanya untuk Admin & Staf)
            Actions\Action::make('cetak_biodata')
                ->label('Cetak Biodata')
                ->icon('heroicon-o-printer')
                ->color('info')
                ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                ->url(fn (Siswa $record): string => route('cetak.biodata', $record->id))
                ->openUrlInNewTab(),
            
            // TOMBOL CETAK RIWAYAT & CATATAN (Hanya untuk Admin & Staf)
            Actions\Action::make('cetak_riwayat_catatan')
                ->label('Riwayat & Catatan')
                ->icon('heroicon-o-document-text')
                ->color('success')
                ->visible(fn () => in_array(Auth::user()->peran, ['admin', 'staf']))
                ->url(fn (Siswa $record): string => route('cetak.riwayat-catatan', $record->id))
                ->openUrlInNewTab(),

            // Tombol bawaan Hapus Data
            Actions\DeleteAction::make(),
        ];
    }
}