<?php

namespace App\Filament\Exports;

use App\Models\Pegawai;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PegawaiExporter extends Exporter
{
    protected static ?string $model = Pegawai::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('nama')->label('Nama Lengkap'),
            ExportColumn::make('nik')->label('NIK'),
            ExportColumn::make('jenis_kelamin')->label('Jenis Kelamin'),
            ExportColumn::make('tempat_lahir')->label('Tempat Lahir'),
            ExportColumn::make('tanggal_lahir')->label('Tanggal Lahir'),
            ExportColumn::make('telepon')->label('Telepon'),
            ExportColumn::make('email')->label('Email'),
            ExportColumn::make('status_kepegawaian')->label('Status Kepegawaian'),
            ExportColumn::make('tugas_utama')->label('Tugas Utama'),
            ExportColumn::make('nip')->label('NIP'),
            ExportColumn::make('nuptk')->label('NUPTK'),
            ExportColumn::make('pangkat_golongan')->label('Pangkat/Gol. Ruang'),
            ExportColumn::make('jabatan')->label('Jabatan'),
            ExportColumn::make('tmt_cpns_honorer')->label('TMT CPNS/Honorer'),
            ExportColumn::make('tmt_pns_pppk')->label('TMT PNS/PPPK'),
            ExportColumn::make('tmt_golongan_terakhir')->label('TMT Golongan Terakhir'),
            ExportColumn::make('pendidikan_ijazah')->label('Pendidikan Terakhir'),
            ExportColumn::make('pendidikan_tahun')->label('Tahun Lulus'),
            ExportColumn::make('pendidikan_jurusan')->label('Jurusan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Ekspor data pegawai telah selesai dan ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' Namun, ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}