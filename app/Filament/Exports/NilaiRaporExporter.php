<?php

namespace App\Filament\Exports;

use App\Models\NilaiRapor;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class NilaiRaporExporter extends Exporter
{
    protected static ?string $model = NilaiRapor::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('tahunAjaran.nama_tahun')->label('Tahun Ajaran'),
            ExportColumn::make('tahunAjaran.semester')->label('Semester'),
            ExportColumn::make('siswa.nisn')->label('NISN'),
            ExportColumn::make('siswa.nama_lengkap')->label('Nama Siswa'),
            ExportColumn::make('siswa.kelas.nama_kelas')->label('Kelas Saat Ini'),
            ExportColumn::make('mataPelajaran.kode_pelajaran')->label('Kode Mapel'),
            ExportColumn::make('mataPelajaran.nama_pelajaran')->label('Mata Pelajaran'),
            ExportColumn::make('nilai_akhir')->label('Nilai Akhir'),
            ExportColumn::make('predikat')->label('Predikat'),
            ExportColumn::make('deskripsi')->label('Deskripsi / Catatan'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        return 'Ekspor data Nilai Rapor selesai. ' . number_format($export->successful_rows) . ' baris berhasil diekspor.';
    }
}