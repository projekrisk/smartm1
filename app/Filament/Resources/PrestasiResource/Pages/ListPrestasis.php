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

    protected function getHeaderActions(): array 
    { 
        return [ 
            Actions\Action::make('cetak_prestasi')
                ->label('Rekap Prestasi')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn (): string => url('/cetak/prestasi'))
                ->openUrlInNewTab(),
                
            Actions\CreateAction::make()->label('Input Prestasi Baru'), 
        ]; 
    }
}