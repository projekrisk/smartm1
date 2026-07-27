<?php

namespace App\Filament\Exports;

use App\Models\JurnalGuru;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class JurnalGuruExporter extends Exporter
{
    protected static ?string $model = JurnalGuru::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('tanggal')->label('Tanggal'),
            ExportColumn::make('jam_mulai')->label('Jam Masuk'),
            ExportColumn::make('jam_selesai')->label('Jam Keluar'),
            ExportColumn::make('kelas.nama_kelas')->label('Kelas'),
            ExportColumn::make('mataPelajaran.nama_pelajaran')->label('Mata Pelajaran'),
            ExportColumn::make('materi_pembahasan')->label('Materi Pembahasan'),
            ExportColumn::make('catatan_kejadian')->label('Catatan Kejadian'),
            
            // FUNGSI AJAIB: Menghitung otomatis rekap absensi per sesi ke dalam kolom Excel
            ExportColumn::make('hadir')
                ->label('Jml Hadir')
                ->state(fn (JurnalGuru $record) => $record->kehadiranPelajaran->where('status', 'Hadir')->count()),
            ExportColumn::make('sakit')
                ->label('Jml Sakit')
                ->state(fn (JurnalGuru $record) => $record->kehadiranPelajaran->where('status', 'Sakit')->count()),
            ExportColumn::make('izin')
                ->label('Jml Izin')
                ->state(fn (JurnalGuru $record) => $record->kehadiranPelajaran->where('status', 'Izin')->count()),
            ExportColumn::make('alpa')
                ->label('Jml Alpa')
                ->state(fn (JurnalGuru $record) => $record->kehadiranPelajaran->where('status', 'Alpa')->count()),
            ExportColumn::make('terlambat')
                ->label('Jml Terlambat')
                ->state(fn (JurnalGuru $record) => $record->kehadiranPelajaran->where('status', 'Terlambat')->count()),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Backup Jurnal Mengajar Anda telah selesai. ' . number_format($export->successful_rows) . ' sesi berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' Namun, ' . number_format($failedRowsCount) . ' baris gagal diekspor.';
        }

        return $body;
    }
}