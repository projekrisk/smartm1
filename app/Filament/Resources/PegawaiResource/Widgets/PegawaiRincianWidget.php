<?php

namespace App\Filament\Resources\PegawaiResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\Pegawai;

class PegawaiRincianWidget extends Widget
{
    // Mengarahkan ke file tampilan tabel
    protected static string $view = 'filament.resources.pegawai-resource.widgets.pegawai-rincian-widget';
    
    // Memaksa widget tabel ini selebar layar (full width)
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $kepsekL = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->where('jenis_kelamin', 'Laki-laki')->count();
        $kepsekP = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->where('jenis_kelamin', 'Perempuan')->count();
        $kepsek = $kepsekL + $kepsekP;

        $guruL = Pegawai::where('jenis_ptk', 'Guru')->where('jenis_kelamin', 'Laki-laki')->count();
        $guruP = Pegawai::where('jenis_ptk', 'Guru')->where('jenis_kelamin', 'Perempuan')->count();
        $guru = $guruL + $guruP;

        $tendikL = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->where('jenis_kelamin', 'Laki-laki')->count();
        $tendikP = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->where('jenis_kelamin', 'Perempuan')->count();
        $tendik = $tendikL + $tendikP;

        return [
            'kepsekL' => $kepsekL, 'kepsekP' => $kepsekP, 'kepsek' => $kepsek,
            'guruL' => $guruL, 'guruP' => $guruP, 'guru' => $guru,
            'tendikL' => $tendikL, 'tendikP' => $tendikP, 'tendik' => $tendik,
        ];
    }
}