<?php

namespace App\Filament\Resources\BukuNilaiResource\Pages;

use App\Filament\Resources\BukuNilaiResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListBukuNilais extends ListRecords
{
    protected static string $resource = BukuNilaiResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [];

        // Akses kini dibuka untuk Admin, Staf, dan GURU
        if (in_array(Auth::user()->peran, ['admin', 'staf', 'guru'])) {
            $actions[] = Actions\Action::make('pantau_ujian')
                ->label('Pantau Pengumpulan (UTS/UAS)')
                ->icon('heroicon-o-eye')
                ->color('warning')
                ->url(fn (): string => BukuNilaiResource::getUrl('pantau'));
        }

        $actions[] = Actions\CreateAction::make()->label('Input Nilai Kelas');

        return $actions;
    }

    public function getTabs(): array
    {
        // Hanya Admin/Staf yang bisa melihat tab filter Tahun Ajaran (Biarkan seperti ini)
        if (!in_array(Auth::user()->peran, ['admin', 'staf'])) {
            return [];
        }

        $tabs = ['Semua Data' => Tab::make('Semua Data')];

        // Ambil semua tahun ajaran yang ada untuk dijadikan Tab
        $tahunAjarans = \App\Models\TahunAjaran::orderBy('id', 'desc')->get();

        foreach ($tahunAjarans as $ta) {
            $tabs[$ta->nama_tahun . ' ' . $ta->semester] = Tab::make($ta->nama_tahun . ' ' . $ta->semester)
                ->modifyQueryUsing(fn (Builder $query) => $query->where('tahun_ajaran_id', $ta->id))
                ->badgeColor($ta->is_active ? 'success' : 'gray')
                ->badge($ta->is_active ? 'Aktif' : null);
        }

        return $tabs;
    }
}