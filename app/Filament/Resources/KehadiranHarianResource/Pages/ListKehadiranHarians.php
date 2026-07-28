<?php

namespace App\Filament\Resources\KehadiranHarianResource\Pages;

use App\Filament\Resources\KehadiranHarianResource;
use App\Filament\Resources\KehadiranHarianResource\Widgets\KehadiranHarianStats;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions;
use App\Models\Kelas;

class ListKehadiranHarians extends ListRecords
{
    protected static string $resource = KehadiranHarianResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            KehadiranHarianStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'Hari Ini' => Tab::make('Hari Ini')
                ->modifyQueryUsing(fn (Builder $query) =>$query->whereDate('tanggal', today()))
                ->badge(\App\Models\RekapKehadiran::whereDate('tanggal', today())->count())
                ->badgeColor('success'),
                
            'Data Bulanan' => Tab::make('Data Bulanan')
                ->badge(\App\Models\RekapKehadiran::whereMonth('tanggal', today()->month)->whereYear('tanggal', today()->year)->count())
                ->badgeColor('info'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('rekap_absen_harian')
                ->label('Tidak Hadir')
                ->icon('heroicon-o-document-magnifying-glass')
                ->color('warning')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('tanggal')
                        ->label('Pilih Tanggal Rekap')
                        ->default(now())
                        ->required(),
                ])
                ->action(function (array $data, \Filament\Resources\Pages\ListRecords$livewire) {
                    $url = route('cetak.rekap-harian', ['tanggal' =>$data['tanggal']]);
                    $livewire->js("window.open('{$url}', '_blank');");
                }),

            Actions\Action::make('cetak_laporan')
                ->label('Laporan')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('start_date')
                        ->label('Dari Tanggal')
                        ->default(now()->startOfMonth())
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('end_date')
                        ->label('Sampai Tanggal')
                        ->default(now()->endOfMonth())
                        ->required(),
                    \Filament\Forms\Components\Select::make('kelas_id')
                        ->label('Pilih Kelas')
                        ->options(Kelas::pluck('nama_kelas', 'id'))
                        ->placeholder('Semua Kelas')
                        ->helperText('Kosongkan jika ingin merekap semua kelas di sekolah.'),
                ])
                ->action(function (array $data, \Filament\Resources\Pages\ListRecords $livewire) {$start = $data['start_date'];$end = $data['end_date'];$kelas = $data['kelas_id'] ?? 'all';$url = route('cetak.laporan-absensi', [
                        'start' => $start, 
                        'end' => $end, 
                        'kelas' => $kelas
                    ]);
                    
                    $livewire->js("window.open('{$url}', '_blank');");
                }),

            Actions\CreateAction::make()->label('Buat Rekap Baru'),
        ];
    }
}