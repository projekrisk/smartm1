<?php

namespace App\Filament\Resources\BukuNilaiResource\Pages;

use App\Filament\Resources\BukuNilaiResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Schema;

class ListBukuNilais extends ListRecords
{
    protected static string $resource = BukuNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetak_rekap_kelas')
                ->label('Pantauan Wali Kelas')
                ->icon('heroicon-o-presentation-chart-line')
                ->color('success')
                ->form([
                    Forms\Components\Select::make('kelas_id')
                        ->label('Pilih Kelas Binaan Anda')
                        ->options(function () {
                            $query = \App\Models\Kelas::query();
                            
                            // Jika Guru, HANYA tampilkan kelas di mana dia adalah wali kelasnya.
                            // (Mengecek kolom guru_id atau wali_kelas_id di tabel kelas)
                            if (auth()->user()->peran === 'guru') {
                                if (Schema::hasColumn('kelas', 'wali_kelas_id')) {
                                    $query->where('wali_kelas_id', auth()->id());
                                } else {
                                    $query->where('guru_id', auth()->id());
                                }
                            }
                            
                            return $query->get()
                                ->sortBy('nama_kelas', SORT_NATURAL | SORT_FLAG_CASE)
                                ->pluck('nama_kelas', 'id')
                                ->toArray();
                        })
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Melempar ke rute laporan matriks wali kelas
                    return redirect()->to('/cetak-rekap-wali-kelas/' . $data['kelas_id']);
                })
                ->modalHeading('Cetak Pantauan Nilai Kelas')
                ->modalDescription('Laporan ini akan menarik SEMUA data nilai dari berbagai mata pelajaran sekaligus, khusus untuk kelas binaan Anda. Sangat berguna untuk mendeteksi siswa yang banyak bolos / kosong nilainya.')
                ->modalSubmitActionLabel('Buka Laporan'),

            Actions\CreateAction::make()->label('Buat Penilaian'),
        ];
    }
}