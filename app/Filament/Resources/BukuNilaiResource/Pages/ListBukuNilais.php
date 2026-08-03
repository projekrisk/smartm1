<?php

namespace App\Filament\Resources\BukuNilaiResource\Pages;

use App\Filament\Resources\BukuNilaiResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListBukuNilais extends ListRecords
{
    protected static string $resource = BukuNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // FITUR BARU: Cetak Leger dipindah ke sini
            Actions\Action::make('cetak_rekap_kelas')
                ->label('Cetak Leger / Rekap Kelas')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('kelas_id')
                        ->label('Pilih Kelas Binaan / Ajar')
                        ->options(function () {
                            if (auth()->user()->peran === 'admin') {
                                return \App\Models\Kelas::pluck('nama_kelas', 'id')->toArray();
                            }
                            $kelasIds = \App\Models\JadwalPelajaran::where('guru_id', auth()->id())->pluck('kelas_id');
                            return \App\Models\Kelas::whereIn('id', $kelasIds)->pluck('nama_kelas', 'id')->toArray();
                        })
                        ->searchable()
                        ->required()
                ])
                ->action(function (array $data) {
                    return redirect()->route('export.leger.rapor', ['kelas_id' => $data['kelas_id']]);
                })
                ->modalHeading('Unduh Rekap Nilai Kelas')
                ->modalDescription('Fitur ini akan mengunduh seluruh data nilai siswa di kelas tersebut (dalam format Excel) untuk dianalisis oleh Wali Kelas atau Guru.')
                ->modalSubmitActionLabel('Unduh Excel'),

            // Tombol bawaan untuk membuat data baru
            Actions\CreateAction::make()->label('Buat Penilaian'),
        ];
    }
}