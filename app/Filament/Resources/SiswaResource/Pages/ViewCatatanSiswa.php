<?php

namespace App\Filament\Resources\CatatanSiswaResource\Pages;

use App\Filament\Resources\CatatanSiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;

class ViewCatatanSiswa extends ViewRecord
{
    protected static string $resource = CatatanSiswaResource::class;

    // ===========================================================================
    // FUNGSI 1: OTOMATIS TANDAI "SUDAH DIBACA" SAAT HALAMAN INI DIBUKA WALI KELAS
    // ===========================================================================
    public function mount(int | string $record): void
    {
        parent::mount($record);

        $catatan = $this->record;
        $user = Auth::user();

        // Jika yang membuka adalah Wali Kelas dari siswa tersebut, dan statusnya belum dibaca
        if ($user->peran === 'guru' && !$catatan->is_read) {
            $isWaliKelas = Kelas::where('wali_kelas_id', $user->id)
                ->where('id', $catatan->siswa->kelas_id)
                ->exists();

            if ($isWaliKelas) {
                // Tandai sudah dibaca di database agar notifikasi (badge) angka merahnya berkurang/hilang
                $catatan->update(['is_read' => true]);
            }
        }
    }

    // ===========================================================================
    // FUNGSI 2: TOMBOL-TOMBOL AKSI DI POJOK KANAN ATAS (KEMBALI, TINDAK LANJUT, EDIT)
    // ===========================================================================
    protected function getHeaderActions(): array
    {
        return [
            // TOMBOL KEMBALI
            Actions\Action::make('kembali')
                ->label('Kembali ke Daftar')
                ->color('gray')
                ->url($this->getResource()::getUrl('index')),

            // TOMBOL TINDAK LANJUT (HANYA MUNCUL DI VIEW JIKA BELUM DITINDAKLANJUTI & DIA WALI KELAS/ADMIN)
            Actions\Action::make('tindak_lanjut')
                ->label('Tindak Lanjuti Kasus')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn ($record) => 
                    $record->status_tindak_lanjut === 'Belum' && 
                    (Auth::user()->peran === 'admin' || $record->siswa->kelas->wali_kelas_id === Auth::id())
                )
                ->form([
                    Forms\Components\Textarea::make('tindak_lanjut')
                        ->label('Hasil / Riwayat Tindak Lanjut')
                        ->placeholder('Contoh: Telah dipanggil dan diberikan nasihat. Siswa berjanji tidak mengulangi...')
                        ->required()
                        ->rows(4),
                ])
                ->action(function ($record, array $data) {
                    $record->update([
                        'status_tindak_lanjut' => 'Sudah',
                        'tindak_lanjut' => $data['tindak_lanjut'],
                        'tanggal_tindak_lanjut' => now(),
                        'ditindaklanjuti_oleh' => Auth::id(),
                    ]);
                    
                    \Filament\Notifications\Notification::make()
                        ->title('Berhasil Ditindaklanjuti')
                        ->success()
                        ->send();
                }),

            Actions\EditAction::make()
                ->hidden(fn ($record) => Auth::user()->peran === 'guru' && $record->guru_id !== Auth::id()),
        ];
    }
}