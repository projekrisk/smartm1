<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\RekapKehadiran;
use App\Models\KehadiranHarian;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class AbsensiSekretaris extends Page
{
    protected static ?string $title = 'Absensi Kelas';
    protected static string $view = 'filament.siswa.pages.absensi-sekretaris';
    
    protected static ?string $slug = 'absensi';
    
    protected static bool $shouldRegisterNavigation = false;

    public array $absensi = [];
    public bool $isLocked = false;
    public string $namaKelas = '';
    public string $tanggalIndo = '';

    public function getLayout(): string
    {
        return 'filament-panels::components.layout.simple';
    }

    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    public function mount(): void
    {
        $user = Auth::user();
        if (!$user || $user->peran !== 'siswa') abort(403);

        $sekretaris = Siswa::with('kelas')->where('user_id', $user->id)->first();
        if (!$sekretaris || !$sekretaris->is_sekretaris) abort(403, 'Akses Ditolak: Anda bukan sekretaris kelas.');

        $this->namaKelas = $sekretaris->kelas->nama_kelas ?? '-';
        $this->tanggalIndo = now()->isoFormat('dddd, D MMMM Y');
        
        $kelasId = $sekretaris->kelas_id;
        $today = today();
        
        $rekap = RekapKehadiran::where('kelas_id', $kelasId)->whereDate('tanggal', $today)->first();
        if ($rekap && $rekap->is_valid) $this->isLocked = true;

        $siswas = Siswa::where('kelas_id', $kelasId)
            ->where(function ($q) {
                $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
            })
            ->orderBy('nama_lengkap')
            ->get();

        foreach ($siswas as $siswa) {
            $status = 'Hadir'; 
            $keterangan = '';
            $isDispensasi = false;

            $adaDispensasi = $siswa->suratDispensasi()
                ->where('tanggal_mulai', '<=', $today->format('Y-m-d'))
                ->where('tanggal_selesai', '>=', $today->format('Y-m-d'))
                ->first();

            if ($adaDispensasi) {
                $status = 'Dispensasi';
                $keterangan = "Nomor Surat: " . $adaDispensasi->nomor_surat_lengkap; 
                $isDispensasi = true;
            }

            if ($rekap) {
                $hadir = KehadiranHarian::where('rekap_kehadiran_id', $rekap->id)->where('siswa_id', $siswa->id)->first();
                if ($hadir) {
                    if (!$isDispensasi) {
                        $status = $hadir->status;
                        $keterangan = $hadir->keterangan ?? '';
                    }
                }
            }

            $this->absensi[] = [
                'siswa_id' => $siswa->id,
                'nama' => $siswa->nama_lengkap,
                'nisn' => $siswa->nisn,
                'nis' => $siswa->nis,
                'foto' => $siswa->foto,
                'status' => $status,
                'keterangan' => $keterangan,
                'is_dispensasi' => $isDispensasi,
            ];
        }
    }

    public function simpan(): void
    {
        if ($this->isLocked) return;

        $user = Auth::user();
        $sekretaris = Siswa::where('user_id', $user->id)->first();
        $kelasId = $sekretaris->kelas_id;
        $today = today();
        $ta = TahunAjaran::where('is_active', true)->first();

        $rekap = RekapKehadiran::firstOrCreate(
            ['kelas_id' => $kelasId, 'tanggal' => $today],
            ['tahun_ajaran_id' => $ta ? $ta->id : null, 'is_valid' => false]
        );

        foreach ($this->absensi as $item) {
            
            $statusFinal = ($item['is_dispensasi'] ?? false) ? 'Dispensasi' : $item['status'];

            KehadiranHarian::updateOrCreate(
                ['rekap_kehadiran_id' => $rekap->id, 'siswa_id' => $item['siswa_id']],
                ['status' => $statusFinal, 'keterangan' => $item['keterangan']]
            );
        }

        Notification::make()
            ->title('Absensi Tersimpan!')
            ->body('Data absensi kelas hari ini berhasil diserahkan ke sistem TU.')
            ->success()
            ->send();
            
        $this->redirect('/siswa');
    }
}