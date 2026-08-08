<?php

namespace App\Filament\Resources\PegawaiResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Pegawai;
use Illuminate\Support\HtmlString;

class PegawaiStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        // 1. Data Keseluruhan
        $total = Pegawai::count();
        
        // 2. Data Kepala Sekolah
        $kepsek = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->count();
        $kepsekL = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->where('jenis_kelamin', 'Laki-laki')->count();
        $kepsekP = Pegawai::where('jenis_ptk', 'Kepala Sekolah')->where('jenis_kelamin', 'Perempuan')->count();

        // 3. Data Guru
        $guru = Pegawai::where('jenis_ptk', 'Guru')->count();
        $guruL = Pegawai::where('jenis_ptk', 'Guru')->where('jenis_kelamin', 'Laki-laki')->count();
        $guruP = Pegawai::where('jenis_ptk', 'Guru')->where('jenis_kelamin', 'Perempuan')->count();

        // 4. Data Tenaga Kependidikan
        $tendik = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->count();
        $tendikL = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->where('jenis_kelamin', 'Laki-laki')->count();
        $tendikP = Pegawai::where('jenis_ptk', 'Tenaga Kependidikan')->where('jenis_kelamin', 'Perempuan')->count();

        // 5. Data Status Kepegawaian
        $pns = Pegawai::where('status_kepegawaian', 'PNS')->count();
        $pppk = Pegawai::where('status_kepegawaian', 'PPPK')->count();
        $honorer = Pegawai::where('status_kepegawaian', 'Honorer')->count();
        $gty = Pegawai::where('status_kepegawaian', 'GTY/PTY')->count();

        // Membuat format tabel mini menggunakan HTML & Tailwind CSS
        $tabelRincian = new HtmlString("
            <div class='mt-2 overflow-x-auto'>
                <table class='w-full text-[11px] text-gray-600 dark:text-gray-400'>
                    <thead>
                        <tr class='border-b border-gray-200 dark:border-gray-700'>
                            <th class='text-left font-bold pb-1'>Jenis PTK</th>
                            <th class='text-center font-bold pb-1'>L</th>
                            <th class='text-center font-bold pb-1'>P</th>
                            <th class='text-center font-bold pb-1 text-primary-600'>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class='border-b border-gray-200 dark:border-gray-700'>
                            <td class='py-1'>Kepala Sekolah</td>
                            <td class='text-center py-1'>{$kepsekL}</td>
                            <td class='text-center py-1'>{$kepsekP}</td>
                            <td class='text-center py-1 font-bold text-primary-600'>{$kepsek}</td>
                        </tr>
                        <tr class='border-b border-gray-200 dark:border-gray-700'>
                            <td class='py-1'>Guru</td>
                            <td class='text-center py-1'>{$guruL}</td>
                            <td class='text-center py-1'>{$guruP}</td>
                            <td class='text-center py-1 font-bold text-primary-600'>{$guru}</td>
                        </tr>
                        <tr>
                            <td class='py-1'>Tendik</td>
                            <td class='text-center py-1'>{$tendikL}</td>
                            <td class='text-center py-1'>{$tendikP}</td>
                            <td class='text-center py-1 font-bold text-primary-600'>{$tendik}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        ");

        return [
            // Widget 1: Total Pegawai + Tabel Rincian
            Stat::make('Total Seluruh Pegawai', $total . ' Orang')
                ->description($tabelRincian)
                ->icon('heroicon-o-users')
                ->color('primary'),
                
            // Widget 2: ASN
            Stat::make('Pegawai ASN', ($pns + $pppk) . ' Orang')
                ->description("PNS: $pns Orang | PPPK: $pppk Orang")
                ->icon('heroicon-o-check-badge')
                ->color('success'),
                
            // Widget 3: Non-ASN
            Stat::make('Pegawai Non-ASN', ($honorer + $gty) . ' Orang')
                ->description("Honorer: $honorer Orang | GTY/PTY: $gty Orang")
                ->icon('heroicon-o-briefcase')
                ->color('warning'),
        ];
    }
}