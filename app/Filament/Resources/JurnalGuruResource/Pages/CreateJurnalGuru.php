<?php

namespace App\Filament\Resources\JurnalGuruResource\Pages;

use App\Filament\Resources\JurnalGuruResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Siswa;
use App\Models\KehadiranPelajaran;
use Carbon\Carbon;

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
        
        $tanggalJurnal = Carbon::parse($jurnal->tanggal)->format('Y-m-d');
        
        $dataInsert = [];
        
        foreach ($siswas as $siswa) {
            $status = 'Hadir';
            $keterangan = null;

            $adaDispensasi = $siswa->suratDispensasi()
                ->where('tanggal_mulai', '<=', $tanggalJurnal)
                ->where('tanggal_selesai', '>=', $tanggalJurnal)
                ->first();

            if ($adaDispensasi) {
                $status = 'Dispensasi';
                $keterangan = "Surat No: " . $adaDispensasi->nomor_surat_lengkap;
            }

            $dataInsert[] = [
                'jurnal_guru_id' => $jurnal->id,
                'siswa_id' => $siswa->id,
                'status' => $status,
                'keterangan' => $keterangan,
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