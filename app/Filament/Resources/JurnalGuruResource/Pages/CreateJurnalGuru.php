<?php

namespace App\Filament\Resources\JurnalGuruResource\Pages;

use App\Filament\Resources\JurnalGuruResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Siswa;
use App\Models\KehadiranPelajaran;

class CreateJurnalGuru extends CreateRecord
{
    protected static string $resource = JurnalGuruResource::class;

    protected function afterCreate(): void
    {
        $jurnal = $this->record;
        
        $siswas = Siswa::where('kelas_id', $jurnal->kelas_id)
            ->where(function ($q) {
                $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
            })->get();
        
        $dataInsert = [];
        foreach ($siswas as $siswa) {
            $dataInsert[] = [
                'jurnal_guru_id' => $jurnal->id,
                'siswa_id' => $siswa->id,
                'status' => 'Hadir',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        if (!empty($dataInsert)) {
            KehadiranPelajaran::insert($dataInsert);
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}