<?php

namespace App\Filament\Resources\KehadiranHarianResource\Pages;

use App\Filament\Resources\KehadiranHarianResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Siswa;
use App\Models\KehadiranHarian;

class CreateKehadiranHarian extends CreateRecord
{
    protected static string $resource = KehadiranHarianResource::class;

    protected function afterCreate(): void
    {
        $rekap = $this->record;
        
        $siswas = Siswa::where('kelas_id', $rekap->kelas_id)->orderBy('nama_lengkap')->get();
        
        $dataInsert = [];
        foreach ($siswas as $siswa) {
            $dataInsert[] = [
                'rekap_kehadiran_id' => $rekap->id,
                'siswa_id' => $siswa->id,
                'status' => 'Hadir',
                'keterangan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        KehadiranHarian::insert($dataInsert);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}