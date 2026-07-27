<?php

namespace App\Filament\Resources\SiswaResource\Pages;

use App\Filament\Resources\SiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class ListSiswas extends ListRecords
{
    protected static string $resource = SiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        // JIKA GURU / WALI KELAS, JANGAN TAMPILKAN TAB FILTER SAMA SEKALI
        if (Auth::user()->peran === 'guru') {
            return [];
        }

        // TAMPILAN TAB UNTUK ADMIN DAN STAF
        return [
            'Siswa Aktif' => Tab::make('Siswa Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->where(function ($q) {
                    $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
                }))
                ->badge(Siswa::where(function ($q) {
                    $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
                })->count())
                ->badgeColor('success'),
                
            'Alumni (Lulus)' => Tab::make('Alumni (Lulus)')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status_siswa', 'Lulus'))
                ->badge(Siswa::where('status_siswa', 'Lulus')->count())
                ->badgeColor('info'),
                
            'Keluar / Mutasi' => Tab::make('Keluar / Mutasi')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status_siswa', ['Mutasi Keluar', 'Dikeluarkan', 'Wafat']))
                ->badge(Siswa::whereIn('status_siswa', ['Mutasi Keluar', 'Dikeluarkan', 'Wafat'])->count())
                ->badgeColor('danger'),
                
            'Semua Data' => Tab::make('Semua Data'),
        ];
    }
}