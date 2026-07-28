<?php

namespace App\Filament\Resources\JurnalGuruResource\Pages;

use App\Filament\Resources\JurnalGuruResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJurnalGurus extends ListRecords
{
    protected static string $resource = JurnalGuruResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('cetak_rekap')
                ->label('Rekap Jurnal')
                ->icon('heroicon-o-printer')
                ->color('warning')
                ->form(function () {
                    if (\Illuminate\Support\Facades\Auth::user()->peran === 'guru') return [];
                    
                    return [
                        \Filament\Forms\Components\Select::make('guru_id')
                            ->label('Pilih Guru')
                            ->options(\App\Models\User::where('peran', 'guru')->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                    ];
                })
                ->action(function (array $data, \Livewire\Component $livewire) {
                    $guruId = \Illuminate\Support\Facades\Auth::user()->peran === 'guru' 
                                ? \Illuminate\Support\Facades\Auth::id() 
                                : $data['guru_id'];
                    
                    $url = url('/cetak/rekap-jurnal?guru_id=' . $guruId);
                    $livewire->js("window.open('{$url}', '_blank');");
                }),
                
            Actions\CreateAction::make()->label('Tambah Jurnal'),
        ];
    }
}