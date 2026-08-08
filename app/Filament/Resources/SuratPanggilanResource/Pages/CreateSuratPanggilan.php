<?php

namespace App\Filament\Resources\SuratPanggilanResource\Pages;

use App\Filament\Resources\SuratPanggilanResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\User;

class CreateSuratPanggilan extends CreateRecord
{
    protected static string $resource = SuratPanggilanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // 🌟 PERBAIKAN NOTIFIKASI KE USER WALI KELAS
    protected function afterCreate(): void
    {
        $surat = $this->record;
        $siswa = $surat->siswa;
        
        if ($siswa && $siswa->kelas) {
            // Karena sistem Anda menyimpan ID User sebagai Wali Kelas
            $waliKelasId = $siswa->kelas->wali_kelas_id; 
            
            if ($waliKelasId) {
                // Langsung temukan User-nya
                $userWaliKelas = User::find($waliKelasId);
                
                if ($userWaliKelas) {
                    Notification::make()
                        ->title('Surat Panggilan Baru')
                        ->body("Staf Kesiswaan telah membuat Surat Panggilan untuk siswa kelas Anda: {$siswa->nama_lengkap}.")
                        ->success()
                        ->sendToDatabase($userWaliKelas);
                }
            }
        }
    }
}