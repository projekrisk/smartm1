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
            Actions\Action::make('cetak_rekap_kelas')
                ->label('Cetak Rekap Buku Nilai')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('kelas_id')
                        ->label('Pilih Kelas Binaan / Ajar')
                        ->options(function () {
                            if (auth()->user()->peran === 'admin') {
                                return \App\Models\Kelas::all()->sortBy('nama_kelas', SORT_NATURAL | SORT_FLAG_CASE)->pluck('nama_kelas', 'id')->toArray();
                            }
                            $kelasIds = \App\Models\JadwalPelajaran::where('guru_id', auth()->id())->pluck('kelas_id');
                            return \App\Models\Kelas::whereIn('id', $kelasIds)->get()->sortBy('nama_kelas', SORT_NATURAL | SORT_FLAG_CASE)->pluck('nama_kelas', 'id')->toArray();
                        })
                        ->searchable()
                        ->required()
                        ->live(), // Live agar Mapel menyesuaikan
                        
                    Forms\Components\Select::make('mata_pelajaran_id')
                        ->label('Pilih Mata Pelajaran')
                        ->options(function (Forms\Get $get) {
                            if (!$get('kelas_id')) return [];
                            $mapelIds = \App\Models\JadwalPelajaran::where('kelas_id', $get('kelas_id'))->pluck('mata_pelajaran_id');
                            return \App\Models\MataPelajaran::whereIn('id', $mapelIds)->pluck('nama_pelajaran', 'id')->toArray();
                        })
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Melempar ke rute laporan rekap buku nilai spesifik
                    return redirect()->to('/cetak-rekap-buku-nilai/' . $data['kelas_id'] . '/' . $data['mata_pelajaran_id']);
                })
                ->modalHeading('Cetak Rekap Progres Nilai')
                ->modalDescription('Cetak laporan ini untuk melihat matriks seluruh nilai (Sumatif, Sikap, dll) siswa di kelas tersebut. Kotak kosong menandakan siswa belum memiliki nilai.')
                ->modalSubmitActionLabel('Buka Laporan'),

            Actions\CreateAction::make()->label('Buat Penilaian'),
        ];
    }
}