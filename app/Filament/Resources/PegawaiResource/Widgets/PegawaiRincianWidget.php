<?php

namespace App\Filament\Resources\PegawaiResource\Widgets;

use Filament\Widgets\Widget;
use App\Models\Pegawai;

class PegawaiRincianWidget extends Widget
{
    protected static string $view = 'filament.resources.pegawai-resource.widgets.pegawai-rincian-widget';
    protected int | string | array $columnSpan = 'full';

    protected function getViewData(): array
    {
        $kepsekL = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->where('jenis_kelamin', 'Laki-laki')->count();
        $kepsekP = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->where('jenis_kelamin', 'Perempuan')->count();
        
        $guruL = Pegawai::where('jenis_ptk', 'Guru')->where('jenis_kelamin', 'Laki-laki')->count();
        $guruP = Pegawai::where('jenis_ptk', 'Guru')->where('jenis_kelamin', 'Perempuan')->count();
        
        $tendikL = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->where('jenis_kelamin', 'Laki-laki')->count();
        $tendikP = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->where('jenis_kelamin', 'Perempuan')->count();

        $pnsL = Pegawai::where('status_kepegawaian', 'PNS')->where('jenis_kelamin', 'Laki-laki')->count();
        $pnsP = Pegawai::where('status_kepegawaian', 'PNS')->where('jenis_kelamin', 'Perempuan')->count();

        $pppkL = Pegawai::where('status_kepegawaian', 'PPPK')->where('jenis_kelamin', 'Laki-laki')->count();
        $pppkP = Pegawai::where('status_kepegawaian', 'PPPK')->where('jenis_kelamin', 'Perempuan')->count();

        $honorerL = Pegawai::where('status_kepegawaian', 'Honorer')->where('jenis_kelamin', 'Laki-laki')->count();
        $honorerP = Pegawai::where('status_kepegawaian', 'Honorer')->where('jenis_kelamin', 'Perempuan')->count();
        
        $gtyL = Pegawai::where('status_kepegawaian', 'GTY/PTY')->where('jenis_kelamin', 'Laki-laki')->count();
        $gtyP = Pegawai::where('status_kepegawaian', 'GTY/PTY')->where('jenis_kelamin', 'Perempuan')->count();

        $totalL = Pegawai::where('jenis_kelamin', 'Laki-laki')->count();
        $totalP = Pegawai::where('jenis_kelamin', 'Perempuan')->count();

        return [
            'kepsekL' => $kepsekL, 'kepsekP' => $kepsekP, 'kepsek' => $kepsekL + $kepsekP,
            'guruL' => $guruL, 'guruP' => $guruP, 'guru' => $guruL + $guruP,
            'tendikL' => $tendikL, 'tendikP' => $tendikP, 'tendik' => $tendikL + $tendikP,
            
            'pnsL' => $pnsL, 'pnsP' => $pnsP, 'pns' => $pnsL + $pnsP,
            'pppkL' => $pppkL, 'pppkP' => $pppkP, 'pppk' => $pppkL + $pppkP,
            'honorerL' => $honorerL, 'honorerP' => $honorerP, 'honorer' => $honorerL + $honorerP,
            'gtyL' => $gtyL, 'gtyP' => $gtyP, 'gty' => $gtyL + $gtyP,
            
            'totalL' => $totalL, 'totalP' => $totalP, 'grandTotal' => $totalL + $totalP,
        ];
    }
}