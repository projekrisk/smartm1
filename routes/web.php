<?php

use Illuminate\Support\Facades\Route;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use App\Models\NilaiRapor;
use App\Models\Kelas;
use App\Models\TahunAjaran;

Route::get('/', function () {
    if (auth()->check()) {
        $peran = auth()->user()->peran ?? '';
        
        if ($peran === 'siswa') {
            return redirect('/siswa');
        } elseif (in_array($peran, ['admin', 'guru', 'staf'])) {
            return redirect('/admin');
        }
    }

    return view('welcome'); 
});

Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::get('/cetak/biodata/{id}', function ($id) {
    $tahunAjaranAktif = \App\Models\TahunAjaran::where('is_active', true)->first();
    
    $siswa = \App\Models\Siswa::with([
        'kelas', 
        'riwayatKelas.tahunAjaran', 
        'riwayatKelas.kelas', 
        'catatan.pencatat',
        'kehadiranHarian' => function ($q) use ($tahunAjaranAktif) {
            $q->where('status', '!=', 'Hadir');
            if ($tahunAjaranAktif) {
                $q->whereHas('rekapKehadiran', function ($q2) use ($tahunAjaranAktif) {
                    $q2->where('tahun_ajaran_id', $tahunAjaranAktif->id);
                });
            }
        },
        'kehadiranHarian.rekapKehadiran'
    ])->findOrFail($id);
    
    return view('cetak.biodata-siswa', compact('siswa', 'tahunAjaranAktif'));
})->name('cetak.biodata');

Route::get('/cetak/biodata-pegawai/{id}', function ($id) {
    $pegawai = \App\Models\Pegawai::findOrFail($id);
    return view('cetak.biodata-pegawai', compact('pegawai'));
})->name('cetak.biodata-pegawai');

Route::get('/cetak/riwayat-catatan/{id}', function ($id) {
    $siswa = \App\Models\Siswa::with([
        'kelas', 
        'riwayatKelas.tahunAjaran', 
        'riwayatKelas.kelas', 
        'catatan.pencatat'
    ])->findOrFail($id);
    
    return view('cetak.riwayat-catatan', compact('siswa'));
})->name('cetak.riwayat-catatan');

Route::get('/cetak/penilaian/{id}', function ($id) {
    $penilaian = \App\Models\Penilaian::with(['mataPelajaran', 'kelas', 'tahunAjaran', 'bukuNilai.siswa'])
        ->findOrFail($id);
    return view('cetak.rekap-penilaian', compact('penilaian'));
})->name('cetak.penilaian');

Route::get('/cetak/biodata-siswa/{id}', function ($id) {
    $siswa = \App\Models\Siswa::with(['kelas', 'riwayatKelas.tahunAjaran', 'riwayatKelas.kelas', 'catatan.pencatat', 'kehadiranHarian.rekapKehadiran'])->findOrFail($id);
    $tahunAjaranAktif = \App\Models\TahunAjaran::where('is_active', true)->first();
    return view('cetak.biodata-siswa', compact('siswa', 'tahunAjaranAktif'));
})->name('cetak.biodata');

Route::get('/cetak/buku-rapor/{id}', function ($id) {
    $siswa = \App\Models\Siswa::with('kelas')->findOrFail($id);
    $nilaisGrouped = \App\Models\NilaiRapor::with(['mataPelajaran', 'tahunAjaran'])
        ->where('siswa_id', $id)
        ->get()
        ->groupBy(function ($item) {
            return $item->tahunAjaran->nama_tahun . ' (' . $item->tahunAjaran->semester . ')';
        });
    return view('cetak.buku-rapor', compact('siswa', 'nilaisGrouped'));
})->name('cetak.buku-rapor');

Route::get('/cetak/jadwal-pelajaran', function (\Illuminate\Http\Request $request) {
    $jenis = $request->query('jenis', 'guru');
    $id = $request->query('id', 'all');

    $dataCetak = []; 
    $tahunAktif = \App\Models\TahunAjaran::where('is_active', true)->first();
    $tahunId = $tahunAktif ? $tahunAktif->id : null;

    if ($jenis === 'guru') {
        $guru = \App\Models\User::where('peran', 'guru')->findOrFail($id);
        $jadwals = \App\Models\JadwalPelajaran::with(['mataPelajaran', 'kelas', 'guru'])
            ->where('guru_id', $id)
            ->where('tahun_ajaran_id', $tahunId)
            ->orderBy('jam_mulai')
            ->get();
            
        $dataCetak['Guru: ' . $guru->name] = $jadwals->groupBy('hari');

    } elseif ($jenis === 'kelas') {
        $kelas = \App\Models\Kelas::findOrFail($id);
        $jadwals = \App\Models\JadwalPelajaran::with(['mataPelajaran', 'kelas', 'guru'])
            ->where('kelas_id', $id)
            ->where('tahun_ajaran_id', $tahunId)
            ->orderBy('jam_mulai')
            ->get();
            
        $dataCetak['Kelas: ' . $kelas->nama_kelas] = $jadwals->groupBy('hari');

    } elseif ($jenis === 'semua') {
        $semuaKelas = \App\Models\Kelas::all();
        foreach ($semuaKelas as $kls) {
            $jadwals = \App\Models\JadwalPelajaran::with(['mataPelajaran', 'kelas', 'guru'])
                ->where('kelas_id', $kls->id)
                ->where('tahun_ajaran_id', $tahunId)
                ->orderBy('jam_mulai')
                ->get();
            
            if ($jadwals->isNotEmpty()) {
                $dataCetak['Jadwal Kelas: ' . $kls->nama_kelas] = $jadwals->groupBy('hari');
            }
        }
    }

    return view('cetak.jadwal-pelajaran', compact('dataCetak', 'jenis'));
})->name('cetak.jadwal');

Route::get('/cetak/laporan-absensi', function (\Illuminate\Http\Request $request) {
    $start = $request->start;
    $end = $request->end;
    $kelasId = $request->kelas;

    $startDate = \Carbon\Carbon::parse($start);
    $endDate = \Carbon\Carbon::parse($end);

    $months = [];
    $current = $startDate->copy()->startOfMonth();
    while ($current->lte($endDate->endOfMonth())) {
        $months[] = [
            'label' => $current->isoFormat('MMMM YYYY'),
            'key' => $current->format('Y-m') 
        ];
        $current->addMonth();
    }

    $siswasGrouped = \App\Models\Siswa::with('kelas')
        ->when($kelasId !== 'all', fn($q) => $q->where('kelas_id', $kelasId))
        ->where(function ($q) {
            $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
        })
        ->orderBy('kelas_id')
        ->orderBy('nama_lengkap')
        ->get()
        ->groupBy('kelas.nama_kelas');

    $semuaSiswaIds = $siswasGrouped->flatten()->pluck('id');

    $absensiData = \App\Models\KehadiranHarian::with('rekapKehadiran')
        ->whereIn('siswa_id', $semuaSiswaIds)
        ->whereHas('rekapKehadiran', function ($q) use ($start, $end) {
            $q->whereBetween('tanggal', [$start, $end]);
        })
        ->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
        ->get();

    $dataRekap = [];
    foreach ($semuaSiswaIds as $sId) {
        foreach ($months as $m) {
            $dataRekap[$sId][$m['key']] = ['Sakit' => 0, 'Izin' => 0, 'Alpa' => 0];
        }
        $dataRekap[$sId]['total'] = ['Sakit' => 0, 'Izin' => 0, 'Alpa' => 0];
    }

    foreach ($absensiData as $absen) {
        $bln = \Carbon\Carbon::parse($absen->rekapKehadiran->tanggal)->format('Y-m');
        $sId = $absen->siswa_id;
        $status = $absen->status;
        
        if (isset($dataRekap[$sId][$bln])) {
            $dataRekap[$sId][$bln][$status]++;
            $dataRekap[$sId]['total'][$status]++;
        }
    }

    $nama_kelas = 'Umum (Semua Kelas)';
    if ($kelasId !== 'all' && !empty($kelasId)) {
        $kelasInfo = \App\Models\Kelas::find($kelasId);
        if ($kelasInfo) {
            $nama_kelas = $kelasInfo->nama_kelas;
        }
    }

    return view('cetak.laporan-absensi', compact('siswasGrouped', 'months', 'dataRekap', 'startDate', 'endDate', 'nama_kelas'));
})->name('cetak.laporan-absensi');

Route::get('/cetak/laporan-absensi-pelajaran', function (\Illuminate\Http\Request $request) {
    $start = $request->start_date;
    $end = $request->end_date;
    $mapelId = $request->mata_pelajaran_id;
    $kelasId = $request->kelas_id;

    $startDate = \Carbon\Carbon::parse($start);
    $endDate = \Carbon\Carbon::parse($end);

    $mataPelajaran = \App\Models\MataPelajaran::with('guru')->find($mapelId);

    $jurnalQuery = \App\Models\JurnalGuru::whereBetween('tanggal', [$start, $end])
        ->where('mata_pelajaran_id', $mapelId);
    
    if ($kelasId) {
        $jurnalQuery->where('kelas_id', $kelasId);
    }
    if (auth()->user()->peran === 'guru') {
        $jurnalQuery->where('guru_id', auth()->id());
    }

    $jurnalIds = $jurnalQuery->pluck('id');
    $kelasTerlibatIds = $jurnalQuery->pluck('kelas_id')->unique();

    $months = [];
    $current = $startDate->copy()->startOfMonth();
    while ($current->lte($endDate->endOfMonth())) {
        $months[] = [
            'label' => $current->isoFormat('MMMM YYYY'),
            'key' => $current->format('Y-m')
        ];
        $current->addMonth();
    }

    $siswasGrouped = \App\Models\Siswa::with('kelas')
        ->whereIn('kelas_id', $kelasTerlibatIds)
        ->where(function ($q) {
            $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
        })
        ->orderBy('kelas_id')
        ->orderBy('nama_lengkap')
        ->get()
        ->groupBy('kelas.nama_kelas');

    $semuaSiswaIds = $siswasGrouped->flatten()->pluck('id');

    $absensiData = \App\Models\KehadiranPelajaran::with('jurnalGuru')
        ->whereIn('siswa_id', $semuaSiswaIds)
        ->whereIn('jurnal_guru_id', $jurnalIds)
        ->whereIn('status', ['Sakit', 'Izin', 'Alpa', 'Terlambat'])
        ->get();

    $dataRekap = [];
    foreach ($semuaSiswaIds as $sId) {
        foreach ($months as $m) {
            $dataRekap[$sId][$m['key']] = ['Sakit' => 0, 'Izin' => 0, 'Alpa' => 0, 'Terlambat' => 0];
        }
        $dataRekap[$sId]['total'] = ['Sakit' => 0, 'Izin' => 0, 'Alpa' => 0, 'Terlambat' => 0];
    }

    foreach ($absensiData as $absen) {
        $bln = \Carbon\Carbon::parse($absen->jurnalGuru->tanggal)->format('Y-m');
        $sId = $absen->siswa_id;
        $status = $absen->status;
        
        if (isset($dataRekap[$sId][$bln][$status])) {
            $dataRekap[$sId][$bln][$status]++;
            $dataRekap[$sId]['total'][$status]++;
        }
    }

    return view('cetak.laporan-absensi-pelajaran', compact('siswasGrouped', 'months', 'dataRekap', 'startDate', 'endDate', 'mataPelajaran'));
})->name('cetak.laporan-absensi-pelajaran');

Route::get('/export/leger-rapor', function (\Illuminate\Http\Request $request) {
    $kelasId = $request->kelas_id;
    if (!$kelasId) abort(404);

    $isAllClass = ($kelasId === 'all');
    $filename = $isAllClass ? "Leger_Rapor_Semua_Kelas.xls" : "Leger_Rapor_" . str_replace(' ', '_', \App\Models\Kelas::findOrFail($kelasId)->nama_kelas) . ".xls";

    $siswaQuery = \App\Models\Siswa::with('kelas')
        ->where(function ($q) {
            $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
        })
        ->orderBy('kelas_id')
        ->orderBy('nama_lengkap');

    if (!$isAllClass) {
        $siswaQuery->where('kelas_id', $kelasId);
    }
    
    $siswas = $siswaQuery->get();
    $siswaIds = $siswas->pluck('id');
    
    $mapelIds = \App\Models\NilaiRapor::whereIn('siswa_id', $siswaIds)->distinct()->pluck('mata_pelajaran_id');
    $mapels = \App\Models\MataPelajaran::whereIn('id', $mapelIds)->orderBy('nama_pelajaran')->get();

    $riwayatKelasRaw = \App\Models\RiwayatKelasSiswa::with(['kelas', 'tahunAjaran'])->whereIn('siswa_id', $siswaIds)->get();
    $nilaiRaporRaw = \App\Models\NilaiRapor::with('tahunAjaran')->whereIn('siswa_id', $siswaIds)->get();
    
    $semuaTaRaw = \App\Models\TahunAjaran::all();
    $semuaTa = $semuaTaRaw->sortBy(function($ta) {
        $smtOrder = strtolower($ta->semester) == 'ganjil' ? 1 : 2;
        return $ta->nama_tahun . $smtOrder;
    })->values();

    $uniqueTahunNames = $semuaTa->pluck('nama_tahun')->unique()->values();

    $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
    $html .= '<head><meta charset="utf-8">';
    $html .= '<style>';
    $html .= 'table { border-collapse: collapse; font-family: Arial, sans-serif; font-size: 11px; } ';
    $html .= 'th, td { border: .5pt solid windowtext; padding: 4px; vertical-align: middle; } ';
    $html .= 'th { background-color: #f3f4f6; font-weight: bold; text-align: center; } ';
    $html .= '.str { mso-number-format:"\@"; } ';
    $html .= '</style></head>';
    $html .= '<body><table>';
    
    $html .= '<thead><tr>';
    $html .= '<th rowspan="2" style="width: 30px;">No</th>';
    $html .= '<th rowspan="2" style="width: 80px;">NIS</th>';
    $html .= '<th rowspan="2" style="width: 100px;">NISN</th>';
    $html .= '<th rowspan="2" style="width: 250px;">Nama Lengkap</th>';
    $html .= '<th rowspan="2" style="background-color: #dcfce7; width: 100px;">Kelas Saat Ini</th>';
    
    if ($uniqueTahunNames->count() > 0) {
        $html .= '<th colspan="'.$uniqueTahunNames->count().'" style="background-color: #dbeafe;">Riwayat Kelas (Sesuai Tahun Ajaran)</th>';
    }
    
    foreach ($mapels as $m) {
        $html .= '<th colspan="'.$semuaTa->count().'" style="background-color: #e0e7ff;">' . htmlspecialchars(strtoupper($m->nama_pelajaran)) . '</th>';
    }
    $html .= '</tr>';

    $html .= '<tr>';
    foreach ($uniqueTahunNames as $tahunName) {
        $html .= '<th style="background-color: #dbeafe;">TA. ' . htmlspecialchars($tahunName) . '</th>';
    }
    
    foreach ($mapels as $m) {
        foreach ($semuaTa as $ta) {
            $html .= '<th style="background-color: #fef9c3; width: 65px;">' . htmlspecialchars($ta->nama_tahun) . '<br>Smt ' . htmlspecialchars($ta->semester) . '</th>';
        }
    }
    $html .= '</tr></thead>';
    
    $html .= '<tbody>';
    $no = 1;
    $currentKelasGroupId = null;

    foreach ($siswas as $siswa) {
        
        if ($isAllClass && $currentKelasGroupId !== $siswa->kelas_id) {
            $currentKelasGroupId = $siswa->kelas_id;
            $namaKelasGrup = $siswa->kelas->nama_kelas ?? 'Tanpa Kelas';
            $totalKolom = 5 + $uniqueTahunNames->count() + ($mapels->count() * $semuaTa->count());
            $html .= '<tr><td colspan="'.$totalKolom.'" style="background-color: #cbd5e1; font-weight: bold; text-align: left; padding-left: 10px;">KELOMPOK KELAS: ' . htmlspecialchars($namaKelasGrup) . '</td></tr>';
            $no = 1; 
        }

        $html .= '<tr>';
        $html .= '<td style="text-align: center;">' . $no++ . '</td>';
        $html .= '<td class="str" style="text-align: center;">' . $siswa->nis . '</td>';
        $html .= '<td class="str" style="text-align: center;">' . ($siswa->nisn ?? '-') . '</td>';
        $html .= '<td>' . htmlspecialchars($siswa->nama_lengkap) . '</td>';
        $html .= '<td style="background-color: #dcfce7; text-align: center; font-weight: bold;">' . htmlspecialchars($siswa->kelas->nama_kelas ?? '-') . '</td>';

        $riwayatByTahun = [];
        $riwayatSiswa = $riwayatKelasRaw->where('siswa_id', $siswa->id);
        foreach ($riwayatSiswa as $rw) {
            if ($rw->tahunAjaran && $rw->kelas) {
                $riwayatByTahun[$rw->tahunAjaran->nama_tahun] = $rw->kelas->nama_kelas;
            }
        }

        foreach ($uniqueTahunNames as $tahunName) {
            $kls = $riwayatByTahun[$tahunName] ?? '-';
            $html .= '<td style="text-align: center;">' . htmlspecialchars($kls) . '</td>';
        }

        $nilaiByMapelTa = [];
        $nilaiSiswa = $nilaiRaporRaw->where('siswa_id', $siswa->id);
        foreach ($nilaiSiswa as $n) {
            $nilaiByMapelTa[$n->mata_pelajaran_id][$n->tahun_ajaran_id] = $n->nilai_akhir;
        }

        foreach ($mapels as $m) {
            foreach ($semuaTa as $ta) {
                $score = $nilaiByMapelTa[$m->id][$ta->id] ?? '';
                $html .= '<td style="text-align: center;">' . $score . '</td>';
            }
        }

        $html .= '</tr>';
    }

    $html .= '</tbody></table></body></html>';

    return response($html)
        ->header('Content-Type', 'application/vnd.ms-excel')
        ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

})->name('export.leger.rapor');

Route::get('/cetak/rekap-harian', function (\Illuminate\Http\Request $request) {
    $tanggal = $request->query('tanggal', now()->format('Y-m-d'));
    
    $absenHarian = \App\Models\KehadiranHarian::with(['siswa.kelas'])
        ->whereHas('rekapKehadiran', function ($q) use ($tanggal) {
            $q->whereDate('tanggal', $tanggal);
        })
        ->whereIn('status', ['Sakit', 'Izin', 'Alpa'])
        ->get();

    $groupedByKelas = $absenHarian->groupBy(function($item) {
        return $item->siswa->kelas->nama_kelas ?? 'Tanpa Kelas';
    });

    $groupedAndSorted = $groupedByKelas->sortKeysUsing('strnatcasecmp');

    foreach ($groupedAndSorted as $kelas => $siswas) {
        $groupedAndSorted[$kelas] = $siswas->sortBy('siswa.nama_lengkap')->values();
    }

    $pengaturan = null;
    try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}

    return view('cetak.rekap-harian', compact('groupedAndSorted', 'tanggal', 'pengaturan'));
})->name('cetak.rekap-harian');

Route::get('/cetak/jurnal/{id}', function ($id) {
    $jurnal = \App\Models\JurnalGuru::with([
        'kelas', 
        'mataPelajaran', 
        'guru', 
        'tahunAjaran', 
        'kehadiranPelajaran.siswa' => function($query) {
            $query->orderBy('nama_lengkap', 'asc');
        }
    ])->findOrFail($id);
    
    return view('cetak.jurnal-absensi', compact('jurnal'));
})->name('cetak.jurnal');

Route::get('/cetak/rekap-jurnal', function (\Illuminate\Http\Request $request) {
    $guruId = $request->query('guru_id');
    $guru = \App\Models\User::findOrFail($guruId);
    $tahunAktif = \App\Models\TahunAjaran::where('is_active', true)->first();

    $jurnals = collect();
    if ($tahunAktif) {
        $jurnals = \App\Models\JurnalGuru::with(['kelas', 'mataPelajaran', 'kehadiranPelajaran'])
            ->where('guru_id', $guruId)
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->orderBy('tanggal', 'asc')
            ->orderBy('jam_mulai', 'asc')
            ->get();
    }

    return view('cetak.rekap-jurnal', compact('guru', 'tahunAktif', 'jurnals'));
})->name('cetak.rekap-jurnal');

Route::get('/cetak/keadaan-siswa', function (\Illuminate\Http\Request $request) {
    $b = $request->query('bulan', now()->format('m'));
    $t = $request->query('tahun', now()->format('Y'));

    $startOfMonth = \Carbon\Carbon::createFromDate((int)$t, (int)$b, 1)->startOfMonth();
    $endOfMonth = $startOfMonth->copy()->endOfMonth();

    $semuaSiswa = \App\Models\Siswa::with('kelas')->get();
    $semuaKelas = \App\Models\Kelas::all()->sortBy('nama_kelas', SORT_NATURAL)->values();

    $reportData = [];
    $tingkatTotals = [];
    $grandTotal = [
        'lalu_L'=>0, 'lalu_P'=>0, 'masuk_L'=>0, 'masuk_P'=>0,
        'keluar_L'=>0, 'keluar_P'=>0, 'sekarang_L'=>0, 'sekarang_P'=>0,
    ];

    foreach($semuaKelas as $k) {
        $namaKelas = $k->nama_kelas;
        $prefix = explode('-', str_replace(' ', '-', trim($namaKelas)))[0];
        $tingkat = $prefix;
        if (strtoupper($prefix) === 'X' || $prefix === '10') $tingkat = '10';
        elseif (strtoupper($prefix) === 'XI' || $prefix === '11') $tingkat = '11';
        elseif (strtoupper($prefix) === 'XII' || $prefix === '12') $tingkat = '12';

        if (!isset($reportData[$tingkat])) {
            $reportData[$tingkat] = [];
            $tingkatTotals[$tingkat] = ['lalu_L'=>0, 'lalu_P'=>0, 'masuk_L'=>0, 'masuk_P'=>0, 'keluar_L'=>0, 'keluar_P'=>0, 'sekarang_L'=>0, 'sekarang_P'=>0];
        }

        $kelasMatrix = ['lalu_L'=>0, 'lalu_P'=>0, 'masuk_L'=>0, 'masuk_P'=>0, 'keluar_L'=>0, 'keluar_P'=>0, 'sekarang_L'=>0, 'sekarang_P'=>0];
        $muridDiKelas = $semuaSiswa->where('kelas_id', $k->id);

        foreach($muridDiKelas as $siswa) {
            $jk = $siswa->jenis_kelamin == 'Laki-laki' ? 'L' : 'P';
            $tglMasuk = $siswa->tanggal_masuk ? \Carbon\Carbon::parse($siswa->tanggal_masuk) : \Carbon\Carbon::parse($siswa->created_at);
            $tglStatus = $siswa->tanggal_status ? \Carbon\Carbon::parse($siswa->tanggal_status) : null;
            $isAktif = in_array($siswa->status_siswa, ['Aktif', null, 'Mutasi Masuk']);
            $isMutasiMasuk = (str_contains($siswa->jalur_masuk ?? '', 'Mutasi') || str_contains($siswa->status_siswa ?? '', 'Mutasi Masuk'));

            if ($isMutasiMasuk && $tglMasuk->between($startOfMonth, $endOfMonth)) $kelasMatrix['masuk_'.$jk]++;
            if (!$isAktif && $tglStatus && $tglStatus->between($startOfMonth, $endOfMonth)) $kelasMatrix['keluar_'.$jk]++;

            $isSekarang = false;
            $validMasuk = $tglMasuk->lte($endOfMonth) || !$isMutasiMasuk;
            if ($validMasuk) {
                if ($isAktif) $isSekarang = true;
                else if ($tglStatus && $tglStatus->gt($endOfMonth)) $isSekarang = true;
            }

            if ($isSekarang) $kelasMatrix['sekarang_'.$jk]++;
        }

        $kelasMatrix['lalu_L'] = max(0, $kelasMatrix['sekarang_L'] - $kelasMatrix['masuk_L'] + $kelasMatrix['keluar_L']);
        $kelasMatrix['lalu_P'] = max(0, $kelasMatrix['sekarang_P'] - $kelasMatrix['masuk_P'] + $kelasMatrix['keluar_P']);

        if (array_sum($kelasMatrix) > 0) {
            $reportData[$tingkat][$namaKelas] = $kelasMatrix;

            foreach (['lalu_L','lalu_P','masuk_L','masuk_P','keluar_L','keluar_P','sekarang_L','sekarang_P'] as $key) {
                $tingkatTotals[$tingkat][$key] += $kelasMatrix[$key];
                $grandTotal[$key] += $kelasMatrix[$key];
            }
        }
    }

    foreach ($reportData as $tingkat => $kelasData) {
        if (empty($kelasData)) {
            unset($reportData[$tingkat]);
            unset($tingkatTotals[$tingkat]);
        }
    }

    ksort($reportData);
    ksort($tingkatTotals);

    $mutasiMasukPrint = \App\Models\Siswa::with('kelas')->where(function($q) use ($startOfMonth, $endOfMonth) {
        $q->whereBetween('tanggal_masuk', [$startOfMonth, $endOfMonth])->orWhereBetween('created_at', [$startOfMonth, $endOfMonth]);
    })->where('jalur_masuk', 'like', '%Mutasi%')->orderBy('id', 'desc')->get();

    $mutasiKeluarPrint = \App\Models\Siswa::with('kelas')->whereIn('status_siswa', ['Mutasi Keluar', 'Dikeluarkan', 'Wafat'])
        ->whereBetween('tanggal_status', [$startOfMonth, $endOfMonth])->orderBy('tanggal_status', 'desc')->get();

    $pengaturan = null;
    try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}

    $bulanNama = $startOfMonth->isoFormat('MMMM YYYY');

    return view('cetak.keadaan-siswa', compact('reportData', 'tingkatTotals', 'grandTotal', 'mutasiMasukPrint', 'mutasiKeluarPrint', 'pengaturan', 'bulanNama'));
})->name('cetak.keadaan-siswa');

Route::get('/cetak/prestasi', function () {
    $prestasis = \App\Models\Prestasi::with('siswa.kelas')
        ->where('status', 'Disetujui')
        ->orderBy('tanggal_perolehan', 'desc')
        ->get();
    
    $pengaturan = null;
    try { if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) $pengaturan = \App\Models\Pengaturan::first(); } catch (\Exception $e) {}

    return view('cetak.prestasi-siswa', compact('prestasis', 'pengaturan'));
})->name('cetak.prestasi');

Route::get('/cetak-absensi/{id}', function ($id) {
    $rekap = \App\Models\RekapKehadiran::with([
        'kelas', 
        'validator',
        'kehadiranHarian.siswa' => function($query) {
            $query->orderBy('nama_lengkap', 'asc');
        }
    ])->findOrFail($id);
    
    $nama_kelas = $rekap->kelas->nama_kelas ?? 'Tanpa Kelas';

    return view('cetak.absensi-harian', compact('rekap', 'nama_kelas'));
    
})->name('cetak.absensi-harian');

Route::get('/rekap-buku-nilai/{kelas_id}', function ($kelas_id) {
    $kelas = \App\Models\Kelas::findOrFail($kelas_id);
    
    $tahunAktif = \App\Models\TahunAjaran::where('is_active', true)->first();
    $tahunAktifId = $tahunAktif ? $tahunAktif->id : null;
    $tahunAjaranNama = $tahunAktif ? $tahunAktif->nama_tahun . ' (' . $tahunAktif->semester . ')' : '-';

    $namaSekolah = 'SMAN 1 MALINGPING';
    try {
        if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
            $pengaturan = \App\Models\Pengaturan::first();
            if ($pengaturan && $pengaturan->nama_sekolah) {
                $namaSekolah = $pengaturan->nama_sekolah;
            }
        }
    } catch (\Exception $e) {}

    $penilaians = \App\Models\Penilaian::with('mataPelajaran')
        ->where('kelas_id', $kelas_id)
        ->where('tahun_ajaran_id', $tahunAktifId)
        ->get()
        ->sortBy('jenis_nilai', SORT_NATURAL | SORT_FLAG_CASE)
        ->sortBy(fn($p) => $p->mataPelajaran->nama_pelajaran);

    $grupMapel = [];
    $penilaianIds = [];
    foreach ($penilaians as $p) {
        $namaMapel = $p->mataPelajaran->nama_pelajaran;
        if (!isset($grupMapel[$namaMapel])) {
            $grupMapel[$namaMapel] = [];
        }
        $grupMapel[$namaMapel][] = $p;
        $penilaianIds[] = $p->id;
    }

    $siswas = \App\Models\Siswa::where('kelas_id', $kelas_id)
        ->where(function ($q) {
            $q->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])->orWhereNull('status_siswa');
        })
        ->orderBy('nama_lengkap', 'asc')
        ->get();

    $bukuNilais = \App\Models\BukuNilai::whereIn('penilaian_id', $penilaianIds)
        ->get()
        ->groupBy('siswa_id');

    $rekap = [];
    foreach ($siswas as $siswa) {
        $nilai_siswa = [];
        $dataNilaiSiswaIni = $bukuNilais->get($siswa->id) ? $bukuNilais->get($siswa->id)->keyBy('penilaian_id') : collect();
        
        foreach ($penilaianIds as $pid) {
            $bn = $dataNilaiSiswaIni->get($pid);
            $nilai_siswa[$pid] = $bn ? $bn->nilai : null;
        }

        $rekap[] = [
            'siswa' => $siswa,
            'nilai' => $nilai_siswa
        ];
    }

    $namaFile = 'Rekap Nilai Kelas ' . $kelas->nama_kelas . '.xls';

    return response(view('exports.pantauan-wali-kelas', compact('kelas', 'grupMapel', 'rekap', 'namaSekolah', 'tahunAjaranNama')))
        ->header('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
        ->header('Content-Disposition', 'attachment; filename="' . $namaFile . '"');
});


Route::get('/cetak-catatan-siswa', function () {
    $user = auth()->user();
    
    $query = \App\Models\CatatanSiswa::with(['siswa.kelas', 'pencatat']);

    if ($user->peran === 'guru') {
        $guruId = $user->id;
        
        $kelasBinaanIds = [];
        if (\Illuminate\Support\Facades\Schema::hasColumn('kelas', 'wali_kelas_id')) {
            $kelasBinaanIds = \App\Models\Kelas::where('wali_kelas_id', $guruId)->pluck('id')->toArray();
        } else {
            $kelasBinaanIds = \App\Models\Kelas::where('guru_id', $guruId)->pluck('id')->toArray();
        }

        $query->where(function($q) use ($guruId, $kelasBinaanIds) {
            $q->where('guru_id', $guruId) 
              ->orWhereHas('siswa', function($q2) use ($kelasBinaanIds) {
                  $q2->whereIn('kelas_id', $kelasBinaanIds);
              });
        });
    }

    $catatans = $query->get();

    $catatans = $catatans->sort(function ($a, $b) {
        $kelasA = $a->siswa->kelas->nama_kelas ?? 'ZZZ';
        $kelasB = $b->siswa->kelas->nama_kelas ?? 'ZZZ';
        $cmpKelas = strnatcasecmp($kelasA, $kelasB);
        if ($cmpKelas !== 0) return $cmpKelas;
        
        $namaA = $a->siswa->nama_lengkap ?? 'ZZZ';
        $namaB = $b->siswa->nama_lengkap ?? 'ZZZ';
        $cmpNama = strcmp($namaA, $namaB);
        if ($cmpNama !== 0) return $cmpNama;
        
        return strtotime($b->tanggal) - strtotime($a->tanggal);
    });

    $groupedData = $catatans->groupBy(function($item) {
        return $item->siswa->kelas->nama_kelas ?? 'Tanpa Kelas';
    })->map(function($classGroup) {
        return $classGroup->groupBy(function($item) {
            return $item->siswa->nama_lengkap ?? 'Siswa Tidak Diketahui';
        });
    });

    return view('cetak.catatan-siswa', compact('groupedData', 'user'));
});