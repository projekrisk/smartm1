<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Pengaturan;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Carbon\Carbon;
use Livewire\WithPagination;

class KeadaanSiswa extends Page implements HasForms
{
    use InteractsWithForms;
    use WithPagination;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';
    protected static ?string $navigationGroup = 'Kesiswaan';
    protected static ?string $navigationLabel = 'Keadaan Siswa';
    protected static ?string $title = 'Laporan Keadaan Siswa';
    protected static ?int $navigationSort = 16;

    protected static string $view = 'filament.pages.keadaan-siswa';

    public ?string $bulan = null;
    public ?string $tahun = null;
    public ?string $searchLulusan = null;

    public function mount(): void
    {
        $this->bulan = now()->format('m');
        $this->tahun = now()->format('Y');
        $this->form->fill([
            'bulan' => $this->bulan,
            'tahun' => $this->tahun,
        ]);
    }

    public static function canAccess(): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf']);
    }

    public function updatedSearchLulusan()
    {
        $this->resetPage('lulusPage');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)->schema([
                    Select::make('bulan')
                        ->label('Pilih Bulan Laporan')
                        ->options([
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ])
                        ->live()
                        ->afterStateUpdated(function ($state, $livewire) {
                            $livewire->bulan = $state;
                            $livewire->resetPage('masukPage');
                            $livewire->resetPage('keluarPage');
                        }),

                    TextInput::make('tahun')
                        ->label('Tahun')
                        ->numeric()
                        ->maxLength(4)
                        ->live(debounce: 500)
                        ->afterStateUpdated(function ($state, $livewire) {
                            $livewire->tahun = $state;
                            $livewire->resetPage('masukPage');
                            $livewire->resetPage('keluarPage');
                        }),
                ]),
            ]);
    }

    protected function getViewData(): array
    {
        $b = $this->bulan ?: now()->format('m');
        $t = $this->tahun ?: now()->format('Y');

        $startOfMonth = Carbon::createFromDate((int)$t, (int)$b, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $semuaSiswa = Siswa::with('kelas')->get();
        $semuaKelas = Kelas::all()->sortBy('nama_kelas', SORT_NATURAL)->values();

        $rekapKelasScreen = [];
        $totalAll = 0; $totalL = 0; $totalP = 0;

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
                $tingkatTotals[$tingkat] = [
                    'lalu_L'=>0, 'lalu_P'=>0, 'masuk_L'=>0, 'masuk_P'=>0,
                    'keluar_L'=>0, 'keluar_P'=>0, 'sekarang_L'=>0, 'sekarang_P'=>0,
                ];
            }

            $kelasMatrix = [
                'lalu_L'=>0, 'lalu_P'=>0, 'masuk_L'=>0, 'masuk_P'=>0,
                'keluar_L'=>0, 'keluar_P'=>0, 'sekarang_L'=>0, 'sekarang_P'=>0,
            ];

            $muridDiKelas = $semuaSiswa->where('kelas_id', $k->id);

            foreach($muridDiKelas as $siswa) {
                $jk = $siswa->jenis_kelamin == 'Laki-laki' ? 'L' : 'P';
                $tglMasuk = $siswa->tanggal_masuk ? Carbon::parse($siswa->tanggal_masuk) : Carbon::parse($siswa->created_at);
                $tglStatus = $siswa->tanggal_status ? Carbon::parse($siswa->tanggal_status) : null;

                $isAktif = in_array($siswa->status_siswa, ['Aktif', null, 'Mutasi Masuk']);
                $isMutasiMasuk = (str_contains($siswa->jalur_masuk ?? '', 'Mutasi') || str_contains($siswa->status_siswa ?? '', 'Mutasi Masuk'));

                if ($isMutasiMasuk && $tglMasuk->between($startOfMonth, $endOfMonth)) {
                    $kelasMatrix['masuk_'.$jk]++;
                }

                if (!$isAktif && $tglStatus && $tglStatus->between($startOfMonth, $endOfMonth)) {
                    $kelasMatrix['keluar_'.$jk]++;
                }

                $isSekarang = false;
                $validMasuk = $tglMasuk->lte($endOfMonth) || !$isMutasiMasuk; 
                if ($validMasuk) {
                    if ($isAktif) {
                        $isSekarang = true; 
                    } else if ($tglStatus && $tglStatus->gt($endOfMonth)) {
                        $isSekarang = true; 
                    }
                }

                if ($isSekarang) {
                    $kelasMatrix['sekarang_'.$jk]++;
                }
            }

            $kelasMatrix['lalu_L'] = max(0, $kelasMatrix['sekarang_L'] - $kelasMatrix['masuk_L'] + $kelasMatrix['keluar_L']);
            $kelasMatrix['lalu_P'] = max(0, $kelasMatrix['sekarang_P'] - $kelasMatrix['masuk_P'] + $kelasMatrix['keluar_P']);

            if (array_sum($kelasMatrix) > 0) {
                $reportData[$tingkat][$namaKelas] = $kelasMatrix;

                foreach (['lalu_L','lalu_P','masuk_L','masuk_P','keluar_L','keluar_P','sekarang_L','sekarang_P'] as $key) {
                    $tingkatTotals[$tingkat][$key] += $kelasMatrix[$key];
                    $grandTotal[$key] += $kelasMatrix[$key];
                }

                $rekapKelasScreen[$namaKelas] = [
                    'L' => $kelasMatrix['sekarang_L'],
                    'P' => $kelasMatrix['sekarang_P'],
                    'Total' => $kelasMatrix['sekarang_L'] + $kelasMatrix['sekarang_P']
                ];
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

        $totalL = $grandTotal['sekarang_L'];
        $totalP = $grandTotal['sekarang_P'];
        $totalAll = $totalL + $totalP;

        $rekapTingkatScreen = [];
        foreach ($tingkatTotals as $t => $data) {
            $rekapTingkatScreen[$t] = [
                'sekarang_L' => $data['sekarang_L'],
                'sekarang_P' => $data['sekarang_P'],
            ];
        }

        $mutasiMasukQuery = Siswa::with('kelas')
            ->where(function($q) use ($startOfMonth, $endOfMonth) {
                $q->whereBetween('tanggal_masuk', [$startOfMonth, $endOfMonth])
                  ->orWhereBetween('created_at', [$startOfMonth, $endOfMonth]);
            })
            ->where('jalur_masuk', 'like', '%Mutasi%');

        $mutasiKeluarQuery = Siswa::with('kelas')
            ->whereIn('status_siswa', ['Mutasi Keluar', 'Dikeluarkan', 'Wafat', 'Pindah']) 
            ->whereBetween('tanggal_status', [$startOfMonth, $endOfMonth]);

        $mutasiMasukScreen = (clone $mutasiMasukQuery)->orderBy('id', 'desc')->paginate(5, ['*'], 'masukPage');
        $mutasiKeluarScreen = (clone $mutasiKeluarQuery)->orderBy('tanggal_status', 'desc')->paginate(5, ['*'], 'keluarPage');

        $mutasiMasukPrint = (clone $mutasiMasukQuery)->orderBy('id', 'desc')->get();
        $mutasiKeluarPrint = (clone $mutasiKeluarQuery)->orderBy('tanggal_status', 'desc')->get();

        $mutasiMasukStats = [
            'L' => $mutasiMasukPrint->where('jenis_kelamin', 'Laki-laki')->count(),
            'P' => $mutasiMasukPrint->where('jenis_kelamin', 'Perempuan')->count(),
            'Total' => $mutasiMasukPrint->count(),
        ];

        $mutasiKeluarStats = [
            'L' => $mutasiKeluarPrint->where('jenis_kelamin', 'Laki-laki')->count(),
            'P' => $mutasiKeluarPrint->where('jenis_kelamin', 'Perempuan')->count(),
            'Total' => $mutasiKeluarPrint->count(),
        ];

        $lulusQuery = Siswa::with('kelas')->where('status_siswa', 'Lulus');

        if (!empty($this->searchLulusan)) {
            $lulusQuery->where(function($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->searchLulusan . '%')
                  ->orWhere('nisn', 'like', '%' . $this->searchLulusan . '%')
                  ->orWhere('nis', 'like', '%' . $this->searchLulusan . '%');
            });
        }
        $lulusanScreen = $lulusQuery->orderBy('tanggal_status', 'desc')->paginate(10, ['*'], 'lulusPage');

        $totalAlumniL = Siswa::where('status_siswa', 'Lulus')->where('jenis_kelamin', 'Laki-laki')->count();
        $totalAlumniP = Siswa::where('status_siswa', 'Lulus')->where('jenis_kelamin', 'Perempuan')->count();
        $totalAlumniAll = $totalAlumniL + $totalAlumniP;

        $alumniPerTahun = Siswa::where('status_siswa', 'Lulus')
            ->whereNotNull('tanggal_status')
            ->selectRaw('YEAR(tanggal_status) as tahun, 
                         SUM(CASE WHEN jenis_kelamin = "Laki-laki" THEN 1 ELSE 0 END) as jml_L,
                         SUM(CASE WHEN jenis_kelamin = "Perempuan" THEN 1 ELSE 0 END) as jml_P,
                         COUNT(*) as total')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get();

        $pengaturan = null;
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('pengaturan')) {
                $pengaturan = Pengaturan::first();
            }
        } catch (\Exception $e) {}

        return [
            'totalL' => $totalL,
            'totalP' => $totalP,
            'totalAll' => $totalAll,
            'rekapKelasScreen' => $rekapKelasScreen,
            'rekapTingkatScreen' => $rekapTingkatScreen,

            'mutasiMasukScreen' => $mutasiMasukScreen,
            'mutasiKeluarScreen' => $mutasiKeluarScreen,
            'mutasiMasukStats' => $mutasiMasukStats,
            'mutasiKeluarStats' => $mutasiKeluarStats,

            'lulusanScreen' => $lulusanScreen,
            'alumniPerTahun' => $alumniPerTahun,
            'totalAlumniL' => $totalAlumniL,
            'totalAlumniP' => $totalAlumniP,
            'totalAlumniAll' => $totalAlumniAll,

            'mutasiMasukPrint' => $mutasiMasukPrint,
            'mutasiKeluarPrint' => $mutasiKeluarPrint,

            'reportData' => $reportData,
            'tingkatTotals' => $tingkatTotals,
            'grandTotal' => $grandTotal,

            'pengaturan' => $pengaturan,
            'bulanNama' => $startOfMonth->isoFormat('MMMM YYYY'),
        ];
    }
}