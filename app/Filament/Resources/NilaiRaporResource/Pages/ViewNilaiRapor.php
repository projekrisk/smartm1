<?php

namespace App\Filament\Resources\NilaiRaporResource\Pages;

use App\Filament\Resources\NilaiRaporResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNilaiRapor extends ViewRecord
{
    protected static string $resource = NilaiRaporResource::class;

    public function getTitle(): string
    {
        return 'Detail Buku Rapor: ' . ($this->record->siswa->nama_lengkap ?? '-');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetak_rapor')
                ->label('Cetak Buku Rapor')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn (): string => url('/cetak/buku-rapor/' . $this->record->siswa_id))
                ->openUrlInNewTab(),

            Actions\Action::make('kembali')
                ->label('Kembali ke Daftar')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];
    }
}