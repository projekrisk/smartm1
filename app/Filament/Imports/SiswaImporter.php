<?php

namespace App\Filament\Imports;

use App\Models\Siswa;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Validation\ValidationException;

class SiswaImporter extends Importer
{
    protected static ?string $model = Siswa::class;

    protected static array $nisnTerbaca = [];

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nis')->label('NIS')->rules(['required', 'max:255']),
            ImportColumn::make('nisn')->label('NISN')->requiredMapping()->rules(['required', 'max:255']),
            ImportColumn::make('nama_lengkap')->label('Nama Lengkap')->rules(['required', 'max:255']),
            ImportColumn::make('jenis_kelamin')->label('Jenis Kelamin')->rules(['required']),
            ImportColumn::make('agama')->label('Agama'),
            ImportColumn::make('tempat_lahir')->label('Tempat Lahir'),
            ImportColumn::make('tanggal_lahir')->label('Tanggal Lahir')
                ->castStateUsing(fn (string $state): ?string => blank($state) ? null : date('Y-m-d', strtotime($state))),
            ImportColumn::make('nik')->label('NIK'),
            ImportColumn::make('no_kk')->label('No KK'),
            ImportColumn::make('telepon')->label('Telepon'),
            ImportColumn::make('email')->label('Email'),
            ImportColumn::make('alamat')->label('Alamat'),
            ImportColumn::make('rt')->label('RT'),
            ImportColumn::make('rw')->label('RW'),
            ImportColumn::make('kelurahan')->label('Kelurahan'),
            ImportColumn::make('kecamatan')->label('Kecamatan'),
            ImportColumn::make('kabupaten')->label('Kabupaten'),
            ImportColumn::make('lintang')->label('Lintang'),
            ImportColumn::make('bujur')->label('Bujur'),
            ImportColumn::make('nama_ayah')->label('Nama Ayah'),
            ImportColumn::make('telepon_ayah')->label('Telepon Ayah'),
            ImportColumn::make('nama_ibu')->label('Nama Ibu'),
            ImportColumn::make('telepon_ibu')->label('Telepon Ibu'),
            ImportColumn::make('nama_wali')->label('Nama Wali'),
            ImportColumn::make('telepon_wali')->label('Telepon Wali'),
            
            ImportColumn::make('kelas_id')
                ->label('ID Kelas')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']), 
                
            ImportColumn::make('sekolah_asal')->label('Sekolah Asal'),
        ];
    }

    public function resolveRecord(): ?Siswa
    {
        $nisn = $this->data['nisn'] ?? null;

        if ($nisn) {
            if (in_array($nisn, self::$nisnTerbaca)) {
                throw new \Exception('Data ditolak: NISN ' . $nisn . ' terdeteksi ganda di dalam file ini.');
            }
            self::$nisnTerbaca[] = $nisn;
        }

        return Siswa::firstOrNew([
            'nisn' => $nisn,
        ]);
    }   

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Proses impor data siswa telah selesai dan ' . number_format($import->successful_rows) . ' baris berhasil diproses (Dibuat/Diperbarui).';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' Namun, ' . number_format($failedRowsCount) . ' baris ditolak (Karena tidak ada ID kelas atau data ganda).';
        }

        return $body;
    }
}