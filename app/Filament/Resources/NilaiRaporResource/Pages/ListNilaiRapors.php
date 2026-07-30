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