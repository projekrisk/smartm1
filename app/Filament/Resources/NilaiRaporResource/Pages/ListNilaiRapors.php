<?php

namespace App\Filament\Resources\NilaiRaporResource\Pages;

use App\Filament\Resources\NilaiRaporResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\NilaiRapor;

class ListNilaiRapors extends ListRecords
{
    protected static string $resource = NilaiRaporResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ==============================================================================
            // 1. TOMBOL EKSPOR DIRECT NILAI RAPOR (SUPER CEPAT & BEBAS TIMEOUT)
            // ==============================================================================
            Actions\Action::make('ekspor_cepat_rapor')
                ->label('Ekspor Data')
                ->color('warning')
                ->icon('heroicon-o-arrow-up-tray')
                ->visible(fn () => in_array(Auth::user()->peran ?? 'admin', ['admin', 'staf', 'guru']))
                ->action(function () {
                    // Mencegah Timeout saat mengunduh puluhan ribu data
                    set_time_limit(0);
                    
                    $headers = [
                        'NIS', 
                        'NISN', 
                        'Nama Siswa', 
                        'Kelas', 
                        'Mata Pelajaran', 
                        'Semester', 
                        'Nilai Pengetahuan', 
                        'Nilai Keterampilan'
                    ];
                    
                    $callback = function () use ($headers) {
                        $file = fopen('php://output', 'w');
                        fputcsv($file, $headers);

                        // PERBAIKAN: Mengubah 'kelas' menjadi 'siswa.kelas'
                        // Jika nanti error di 'mataPelajaran', ubah namanya sesuaikan dengan relasi di Model Anda (misal 'mapel')
                        NilaiRapor::with(['siswa.kelas', 'mataPelajaran'])
                            ->chunk(1000, function ($nilais) use ($file) {
                                foreach ($nilais as $nilai) {
                                    fputcsv($file, [
                                        isset($nilai->siswa->nis) ? "'" . $nilai->siswa->nis : '',
                                        isset($nilai->siswa->nisn) ? "'" . $nilai->siswa->nisn : '',
                                        
                                        $nilai->siswa->nama_lengkap ?? '',
                                        
                                        // PERBAIKAN: Mengambil nama kelas lewat data siswa
                                        $nilai->siswa->kelas->nama_kelas ?? '',
                                        
                                        $nilai->mataPelajaran->nama_matpel ?? ($nilai->mataPelajaran->nama ?? ''),
                                        
                                        $nilai->semester ?? '',
                                        $nilai->nilai_pengetahuan ?? ($nilai->nilai ?? ''),
                                        $nilai->nilai_keterampilan ?? '',
                                    ]);
                                }
                            });
                        
                        fclose($file);
                    };

                    $fileName = 'Ekspor_Nilai_Rapor_' . date('Y-m-d_H-i') . '.csv';

                    return response()->stream($callback, 200, [
                        'Content-Type' => 'text/csv',
                        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    ]);
                }),

            // ==============================================================================
            // 2. TOMBOL TAMBAH DATA (Bawaan Filament)
            // ==============================================================================
            Actions\CreateAction::make()
                ->label('Tambah Nilai Rapor'),
        ];
    }
}