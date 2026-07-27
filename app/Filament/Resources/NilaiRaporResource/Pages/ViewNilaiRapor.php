<?php

namespace App\Filament\Resources\NilaiRaporResource\Pages;

use App\Filament\Resources\NilaiRaporResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewNilaiRapor extends ViewRecord
{
    protected static string $resource = NilaiRaporResource::class;

    // Mengubah judul halaman di atas agar sesuai dengan nama siswa
    public function getTitle(): string
    {
        return 'Detail Buku Rapor: ' . ($this->record->siswa->nama_lengkap ?? '-');
    }

    protected function getHeaderActions(): array
    {
        return [
            // TOMBOL BARU: CETAK BUKU RAPOR
            Actions\Action::make('cetak_rapor')
                ->label('Cetak Buku Rapor')
                ->icon('heroicon-o-printer')
                ->color('success')
                // Mengambil ID siswa untuk dilempar ke halaman cetak
                ->url(fn (): string => url('/cetak/buku-rapor/' . $this->record->siswa_id))
                ->openUrlInNewTab(),

            Actions\Action::make('kembali')
                ->label('Kembali ke Daftar')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),
        ];
    }
}