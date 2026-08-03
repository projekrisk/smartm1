<?php

namespace App\Filament\Resources\JadwalPelajaranResource\Pages;

use App\Filament\Resources\JadwalPelajaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\Select;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Auth;

class ListJadwalPelajarans extends ListRecords
{
    protected static string $resource = JadwalPelajaranResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [];

        $actions[] = Actions\Action::make('cetak_jadwal')
            ->label('Cetak Jadwal')
            ->icon('heroicon-o-printer')
            ->color('success')
            ->form(function () {
                $user = Auth::user();
                if ($user->peran === 'guru') {
                    return [];
                }

                return [
                    Select::make('jenis_cetak')
                        ->label('Cetak Berdasarkan')
                        ->options([
                            'guru' => 'Jadwal Mengajar Per Guru',
                            'kelas' => 'Jadwal Pelajaran Per Kelas',
                            'semua' => 'Cetak Seluruh Jadwal (Global)',
                        ])
                        ->default('guru')
                        ->required()
                        ->live(),

                    Select::make('guru_id')
                        ->label('Pilih Guru')
                        ->options(User::where('peran', 'guru')->pluck('name', 'id'))
                        ->required(fn (\Filament\Forms\Get $get) => $get('jenis_cetak') === 'guru')
                        ->visible(fn (\Filament\Forms\Get $get) => $get('jenis_cetak') === 'guru')
                        ->searchable(),

                    Select::make('kelas_id')
                        ->label('Pilih Kelas')
                        ->options(Kelas::pluck('nama_kelas', 'id'))
                        ->required(fn (\Filament\Forms\Get $get) => $get('jenis_cetak') === 'kelas')
                        ->visible(fn (\Filament\Forms\Get $get) => $get('jenis_cetak') === 'kelas')
                        ->searchable(),
                ];
            })
            ->action(function (array $data, \Livewire\Component $livewire) {
                $user = Auth::user();
                
                if ($user->peran === 'guru') {
                    $url = url('/cetak/jadwal-pelajaran?jenis=guru&id=' . $user->id);
                } else {
                    $jenis = $data['jenis_cetak'];
                    $id = $jenis === 'guru' ? $data['guru_id'] : ($jenis === 'kelas' ? $data['kelas_id'] : 'all');
                    $url = url('/cetak/jadwal-pelajaran?jenis=' . $jenis . '&id=' . $id);
                }

                $livewire->js("window.open('{$url}', '_blank');");
            });

        if (in_array(Auth::user()->peran, ['admin', 'staf'])) {
            $actions[] = Actions\CreateAction::make()->label('Tambah Jadwal');
        }

        return $actions;
    }
}