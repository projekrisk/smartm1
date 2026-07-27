<?php
namespace App\Filament\Resources\PrestasiResource\Pages;

use App\Filament\Resources\PrestasiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Prestasi;

class ListPrestasis extends ListRecords
{
    protected static string $resource = PrestasiResource::class;

    // FUNGSI BARU: Tab Filter Siswa Aktif vs Alumni
    public function getTabs(): array
    {
        return [
            'Siswa Aktif' => Tab::make('Siswa Aktif')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('siswa', function ($q) {
                    $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
                }))
                ->badge(Prestasi::whereHas('siswa', function ($q) {
                    $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
                })->count()),
                
            'Alumni (Lulus)' => Tab::make('Alumni (Lulus)')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('siswa', function ($q) {
                    $q->where('status_siswa', 'Lulus');
                }))
                ->badge(Prestasi::whereHas('siswa', function ($q) {
                    $q->where('status_siswa', 'Lulus');
                })->count()),
                
            'Semua Data' => Tab::make('Semua Data'),
        ];
    }

    protected function getHeaderActions(): array 
    { 
        return [ 
            // FUNGSI BARU: Tombol Cetak Laporan
            Actions\Action::make('cetak_prestasi')
                ->label('Cetak Daftar Prestasi')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn (): string => url('/cetak/prestasi'))
                ->openUrlInNewTab(),
                
            Actions\CreateAction::make()->label('Input Prestasi Baru'), 
        ]; 
    }
}