<?php

namespace App\Filament\Exports;

use App\Models\Siswa;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class SiswaExporter extends Exporter
{
    protected static ?string $model = Siswa::class;

    public static function getColumns(): array
    {
        // Mendefinisikan semua data yang akan dimasukkan ke dalam file Excel
        return [
            ExportColumn::make('nis')->label('NIS'),
            ExportColumn::make('nisn')->label('NISN'),
            ExportColumn::make('nama_lengkap')->label('Nama Lengkap'),
            ExportColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
            ExportColumn::make('agama')->label('Agama'),
            ExportColumn::make('tempat_lahir')->label('Tempat Lahir'),
            ExportColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
            ExportColumn::make('nik')->label('NIK'),
            ExportColumn::make('no_kk')->label('No KK'),
            ExportColumn::make('telepon')->label('Telepon'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('alamat')->label('Alamat'),
            ExportColumn::make('rt')->label('RT'),
            ExportColumn::make('rw')->label('RW'),
            ExportColumn::make('kelurahan')->label('Kelurahan'),
            ExportColumn::make('kecamatan')->label('Kecamatan'),
            ExportColumn::make('kabupaten')->label('Kabupaten'),
            ExportColumn::make('lintang')->label('Lintang'),
            ExportColumn::make('bujur')->label('Bujur'),
            ExportColumn::make('nama_ayah')->label('Nama Ayah'),
            ExportColumn::make('telepon_ayah')->label('Telepon Ayah'),
            ExportColumn::make('nama_ibu')->label('Nama Ibu'),
            ExportColumn::make('telepon_ibu')->label('Telepon Ibu'),
            ExportColumn::make('nama_wali')->label('Nama Wali'),
            ExportColumn::make('telepon_wali')->label('Telepon Wali'),
            ExportColumn::make('kelas.nama_kelas')->label('Kelas'),
            ExportColumn::make('sekolah_asal')->label('Sekolah Asal'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data siswa telah selesai dan ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' Namun, ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}