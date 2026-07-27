<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Siswa;
use App\Models\Pegawai;
use App\Models\Kelas;
use App\Models\JurnalGuru;
use Illuminate\Support\Facades\Auth;

class DashboardStats extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $peran = Auth::user()->peran;
        $stats = [];

        if ($peran === 'admin') {
            $stats = [
                Stat::make('Total Pegawai', Pegawai::count())
                    ->description('Seluruh tenaga pendidik & kependidikan')
                    ->descriptionIcon('heroicon-m-briefcase')
                    ->icon('heroicon-o-users')->color('primary'),
                Stat::make('Total Siswa', Siswa::count())
                    ->description('Siswa yang terdaftar di sekolah')
                    ->descriptionIcon('heroicon-m-academic-cap')
                    ->icon('heroicon-o-academic-cap')->color('success'),
                Stat::make('Kelas Aktif', Siswa::distinct('kelas_id')->count('kelas_id'))
                    ->description('Kelas yang memiliki siswa')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->icon('heroicon-o-building-library')
                    ->color('info'),
            ];
        } 
        elseif ($peran === 'staf') {
            $stats = [
                Stat::make('Total Siswa', Siswa::count())
                    ->description('Siswa yang terdaftar di sekolah')
                    ->descriptionIcon('heroicon-m-academic-cap')
                    ->icon('heroicon-o-academic-cap')->color('success'),
                Stat::make('Surat Panggilan Dibuat', \App\Models\SuratPanggilan::where('status', 'Dibuat')->count())
                    ->description('Surat baru belum diselesaikan')
                    ->descriptionIcon('heroicon-m-envelope')
                    ->icon('heroicon-o-envelope-open')->color('danger'),
                Stat::make('Kelas Aktif', Siswa::distinct('kelas_id')->count('kelas_id'))
                    ->description('Kelas yang memiliki siswa')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->icon('heroicon-o-building-library')
                    ->color('info'),
            ];
        } 
        elseif ($peran === 'guru') {
            $userId = Auth::id();
            
            // FUNGSI BARU: Menghitung total mapel unik dari Jadwal Pelajaran (Single Source of Truth)
            $totalMapel = \App\Models\JadwalPelajaran::where('guru_id', $userId)->distinct('mata_pelajaran_id')->count('mata_pelajaran_id');
            $jurnalHariIni = JurnalGuru::where('guru_id', $userId)->whereDate('tanggal', today())->count();

            $stats = [
                Stat::make('Mata Pelajaran', $totalMapel)->description('Mapel yang diampu')->icon('heroicon-o-book-open')->color('success'),
                Stat::make('Sesi Mengajar Hari Ini', $jurnalHariIni)->description(today()->isoFormat('dddd, D MMMM Y'))->icon('heroicon-o-clipboard-document-list')->color('warning'),
            ];

            $kelasBinaan = Kelas::where('wali_kelas_id', $userId)->first();
            if ($kelasBinaan) {
                $rekapHariIni = \App\Models\RekapKehadiran::where('kelas_id', $kelasBinaan->id)->whereDate('tanggal', today())->first();
                $jumlahAbsen = $rekapHariIni ? \App\Models\KehadiranHarian::where('rekap_kehadiran_id', $rekapHariIni->id)->whereIn('status', ['Sakit', 'Izin', 'Alpa'])->count() : 0;

                $stats[] = Stat::make('Absensi Binaan (' . $kelasBinaan->nama_kelas . ')', $jumlahAbsen . ' Siswa')
                    ->description($jumlahAbsen > 0 ? 'Tidak hadir hari ini' : 'Semua siswa hadir')
                    ->icon('heroicon-o-users')->color($jumlahAbsen > 0 ? 'danger' : 'success');
            } else {
                $jumlahAbsenSesi = \App\Models\KehadiranPelajaran::whereHas('jurnalGuru', function($q) use ($userId) {
                    $q->where('guru_id', $userId)->whereDate('tanggal', today());
                })->whereIn('status', ['Sakit', 'Izin', 'Alpa', 'Terlambat'])->count();

                $stats[] = Stat::make('Siswa Absen di Sesi Anda', $jumlahAbsenSesi . ' Kejadian')
                    ->description('Dari semua kelas yang Anda ajar hari ini')
                    ->icon('heroicon-o-user-minus')->color($jumlahAbsenSesi > 0 ? 'danger' : 'success');
            }
        }
        return $stats;
    }
}