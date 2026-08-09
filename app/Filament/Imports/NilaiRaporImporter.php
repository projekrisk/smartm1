<?php

namespace App\Filament\Imports;

use App\Models\NilaiRapor;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class NilaiRaporImporter extends Importer
{
    protected static ?string $model = NilaiRapor::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nisn')
                ->label('NISN Siswa')
                ->requiredMapping()
                ->rules(['required']),
                
            ImportColumn::make('kode_pelajaran')
                ->label('Kode Mapel')
                ->requiredMapping()
                ->rules(['required']),
                
            ImportColumn::make('nilai_akhir')
                ->label('Nilai Akhir (0-100)')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer', 'min:0', 'max:100']),
                
            ImportColumn::make('deskripsi')
                ->label('Deskripsi / Catatan')
                ->rules(['nullable']),
        ];
    }

    public function resolveRecord(): ?NilaiRapor
    {
        $nisn = $this->data['nisn'] ?? null;
        $kodePelajaran = $this->data['kode_pelajaran'] ?? null;
        
        $tahunAjaranId = $this->options['tahun_ajaran_id'] ?? null;

        if (!$nisn || !$kodePelajaran || !$tahunAjaranId) {
            throw new \Exception('Data tidak lengkap. Pastikan NISN, Kode Mapel, dan Tahun Ajaran sudah diset.');
        }

        $siswa = Siswa::where('nisn', $nisn)->first();
        if (!$siswa) {
            throw new \Exception("Siswa dengan NISN $nisn tidak ditemukan di sistem.");
        }

        $mapel = MataPelajaran::where('kode_pelajaran', $kodePelajaran)->first();
        if (!$mapel) {
            throw new \Exception("Mata Pelajaran dengan kode $kodePelajaran tidak ditemukan.");
        }

        return NilaiRapor::firstOrNew([
            'siswa_id' => $siswa->id,
            'mata_pelajaran_id' => $mapel->id,
            'tahun_ajaran_id' => $tahunAjaranId,
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Impor Nilai Rapor selesai. ' . number_format($import->successful_rows) . ' baris berhasil diproses.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' Terdapat ' . number_format($failedRowsCount) . ' baris gagal (Cek file log/kesalahan).';
        }

        return $body;
    }
}