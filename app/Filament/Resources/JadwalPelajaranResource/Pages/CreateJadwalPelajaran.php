<?php

namespace App\Filament\Resources\JadwalPelajaranResource\Pages;

use App\Filament\Resources\JadwalPelajaranResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateJadwalPelajaran extends CreateRecord
{
    protected static string $resource = JadwalPelajaranResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $sesiMengajar = $data['sesi_mengajar'] ?? [];
        $firstRecord = null;
        
        foreach ($sesiMengajar as $sesi) {
            $record = static::getModel()::create([
                'guru_id' => $data['guru_id'],
                'mata_pelajaran_id' => $data['mata_pelajaran_id'],
                'tahun_ajaran_id' => \App\Models\TahunAjaran::where('is_active', true)->first()?->id,
                'kelas_id' => $sesi['kelas_id'],
                'hari' => $sesi['hari'],
                'jam_mulai' => $sesi['jam_mulai'],
                'jam_selesai' => $sesi['jam_selesai'],
            ]);
            if (!$firstRecord) $firstRecord = $record;
        }
        return $firstRecord;
    }
}