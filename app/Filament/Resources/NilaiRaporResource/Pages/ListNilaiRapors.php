<?php

namespace App\Filament\Resources\NilaiRaporResource\Pages;

use App\Filament\Resources\NilaiRaporResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use App\Models\Kelas;

class ListNilaiRapors extends ListRecords
{
    protected static string $resource = NilaiRaporResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download_leger')
                ->label('Unduh Leger')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->form([
                    Select::make('kelas_id')
                        ->label('Pilih Kelas untuk Leger')
                        ->options(function () {
                            $kelas = Kelas::pluck('nama_kelas', 'id')->toArray();
                            return ['all' => 'Semua Kelas (Seluruh Sekolah)'] + $kelas;
                        })
                        ->default('all')
                        ->required()
                        ->searchable()
                ])
                ->action(function (array $data, \Livewire\Component $livewire) {
                    $url = route('export.leger.rapor', ['kelas_id' => $data['kelas_id']]);
                    $livewire->js("window.open('{$url}', '_self');"); 
                }),

            Actions\CreateAction::make()->label('Input Nilai Rapor'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'Siswa Aktif' => Tab::make('Siswa Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('siswa', function ($q) {
                    $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
                }))
                ->badgeColor('success'),
                
            'Alumni' => Tab::make('Alumni')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('siswa', function ($q) {
                    $q->where('status_siswa', 'Lulus');
                }))
                ->badgeColor('info'),
                
            'Semua Data' => Tab::make('Semua Data'),
        ];
    }
}