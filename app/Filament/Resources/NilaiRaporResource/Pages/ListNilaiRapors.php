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

                        // Memuat relasi tambahan: riwayatKelas dan tahunAjaran
                        NilaiRapor::with(['siswa.riwayatKelas.kelas', 'siswa.riwayatKelas.tahunAjaran', 'mataPelajaran', 'tahunAjaran'])
                            ->chunk(1000, function ($nilais) use ($file) {
                                foreach ($nilais as $nilai) {
                                    
                                    // Mencari riwayat kelas siswa secara spesifik pada tahun ajaran rapor ini dibuat
                                    $riwayatSpesifik = $nilai->siswa->riwayatKelas
                                        ->where('tahun_ajaran_id', $nilai->tahun_ajaran_id)
                                        ->first();
                                    
                                    $namaKelas = $riwayatSpesifik ? $riwayatSpesifik->kelas->nama_kelas : 'Belum/Tidak Ada Riwayat';
                                    
                                    fputcsv($file, [
                                        isset($nilai->siswa->nis) ? "'" . $nilai->siswa->nis : '',
                                        $nilai->siswa->nama_lengkap ?? '',
                                        $namaKelas, // Menggunakan riwayat kelas per tahun ajaran
                                        $nilai->tahunAjaran->nama_tahun ?? '',
                                        $nilai->mataPelajaran->nama_matpel ?? '',
                                        // ... dan kolom kompleks lainnya ...
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