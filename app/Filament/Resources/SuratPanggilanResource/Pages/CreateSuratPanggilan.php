<?php

namespace App\Filament\Resources\SuratPanggilanResource\Pages;

use App\Filament\Resources\SuratPanggilanResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use App\Models\Pegawai;
use App\Models\User;

class CreateSuratPanggilan extends CreateRecord
{
    protected static string $resource = SuratPanggilanResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function afterCreate(): void
    {
        $surat = $this->record;
        $siswa = $surat->siswa;
        
        if ($siswa && $siswa->kelas) {
            $waliKelasId = $siswa->kelas->wali_kelas_id;
            
            if ($waliKelasId) {
                $pegawai = Pegawai::find($waliKelasId);
                
                if ($pegawai && $pegawai->user_id) {
                    $userWaliKelas = User::find($pegawai->user_id);
                    
                    if ($userWaliKelas) {
                        Notification::make()
                            ->title('Surat Panggilan Baru')
                            ->body("Staf Kesiswaan telah membuat Surat Panggilan untuk siswa Anda: {$siswa->nama_lengkap}.")
                            ->success()
                            ->sendToDatabase($userWaliKelas);
                    }
                }
            }
        }
    }
}