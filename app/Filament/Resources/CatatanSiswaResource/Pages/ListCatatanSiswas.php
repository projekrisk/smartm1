<?php

namespace App\Filament\Resources\CatatanSiswaResource\Pages;

use App\Filament\Resources\CatatanSiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;
use App\Models\CatatanSiswa;

class ListCatatanSiswas extends ListRecords
{
    protected static string $resource = CatatanSiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ==========================================
            // FITUR BARU: TOMBOL CETAK CATATAN
            // ==========================================
            Actions\Action::make('cetak_catatan')
                ->label('Cetak Catatan')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn () => url('/cetak-catatan-siswa'))
                ->openUrlInNewTab(),

            // ==========================================
            // FITUR LAMA ANDA: TANDAI DIBACA (DIPERTAHANKAN)
            // ==========================================
            Actions\Action::make('tandai_dibaca')
                ->label('Tandai Dibaca')
                ->icon('heroicon-o-check-circle')
                ->color('gray')
                ->visible(fn () => Auth::user()->peran === 'guru' && Kelas::where('wali_kelas_id', Auth::id())->exists())
                ->action(function () {
                    $kelasBinaanId = Kelas::where('wali_kelas_id', Auth::id())->value('id');
                    
                    if ($kelasBinaanId) {
                        CatatanSiswa::whereHas('siswa', function ($q) use ($kelasBinaanId) {
                            $q->where('kelas_id', $kelasBinaanId);
                        })->update(['is_read' => true]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('Selesai')
                            ->body('Semua laporan catatan kelas Anda telah ditandai sudah dibaca.')
                            ->success()
                            ->send();
                    }
                }),

            Actions\CreateAction::make()->label('Buat Catatan'),
        ];
    }
}