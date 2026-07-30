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
            Actions\Action::make('ekspor_cepat_rapor')
                ->label('Ekspor')
                ->color('warning')
                ->icon('heroicon-o-arrow-up-tray')
                ->visible(fn () => in_array(Auth::user()->peran ?? 'admin', ['admin', 'staf', 'guru']))
                ->action(function () {
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

                        NilaiRapor::with(['siswa', 'kelas', 'mataPelajaran'])
                            ->chunk(1000, function ($nilais) use ($file) {
                                foreach ($nilais as $nilai) {
                                    fputcsv($file, [
                                        isset($nilai->siswa->nis) ? "'" . $nilai->siswa->nis : '',
                                        isset($nilai->siswa->nisn) ? "'" . $nilai->siswa->nisn : '',
                                        
                                        $nilai->siswa->nama_lengkap ?? '',
                                        $nilai->kelas->nama_kelas ?? '',
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

            Actions\CreateAction::make()
                ->label('Tambah Nilai Rapor'),
        ];
    }
}