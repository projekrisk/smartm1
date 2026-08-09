<?php

namespace App\Filament\Resources\BukuNilaiResource\Pages;

use App\Filament\Resources\BukuNilaiResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Actions\Action;
use App\Models\TahunAjaran;
use App\Models\JadwalPelajaran;
use App\Models\Penilaian;
use App\Models\Siswa;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Auth;

class PantauPengumpulan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BukuNilaiResource::class;
    protected static string $view = 'filament.resources.buku-nilai-resource.pages.pantau-pengumpulan';
    protected static ?string $title = 'Pantau Progres Pengumpulan Nilai';

    public ?int $tahun_ajaran_id = null;
    public ?string $jenis_nilai = 'UTS';

    public static function canAccess(array $parameters = []): bool
    {
        return in_array(Auth::user()->peran, ['admin', 'staf', 'guru']);
    }

    public function mount(): void
    {
        $ta = TahunAjaran::where('is_active', true)->first();
        $this->tahun_ajaran_id = $ta ? $ta->id : null;
        $this->form->fill([
            'tahun_ajaran_id' => $this->tahun_ajaran_id,
            'jenis_nilai' => $this->jenis_nilai,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                Select::make('tahun_ajaran_id')
                    ->label('Pilih Tahun Ajaran')
                    ->options(TahunAjaran::pluck('nama_tahun', 'id'))
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->tahun_ajaran_id = $state),
                Select::make('jenis_nilai')
                    ->label('Pilih Jenis Pengumpulan')
                    ->options([
                        'UTS' => 'Ujian Tengah Semester (UTS)',
                        'UAS' => 'Ujian Akhir Semester (UAS)',
                    ])
                    ->live()
                    ->afterStateUpdated(fn($state) => $this->jenis_nilai = $state),
            ]),
        ]);
    }

    protected function getHeaderActions(): array
    {
        $actions = [];

        if (in_array(Auth::user()->peran, ['admin', 'staf'])) {
            $actions[] = Action::make('download_rekap')
                ->label('Download')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(function () {
                    $ta = TahunAjaran::find($this->tahun_ajaran_id);
                    if (!$ta) return;
                    
                    $filename = "Rekap_{$this->jenis_nilai}_{$ta->nama_tahun}.csv";
                    $filename = str_replace('/', '-', $filename); 

                    $headers = [
                        "Content-type"        => "text/csv",
                        "Content-Disposition" => "attachment; filename={$filename}",
                        "Pragma"              => "no-cache",
                        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                        "Expires"             => "0"
                    ];

                    $mapels = MataPelajaran::orderBy('nama_pelajaran')->get();
                    $columns = ['No', 'NISN', 'Nama Lengkap', 'Kelas'];
                    foreach($mapels as $m) $columns[] = $m->nama_pelajaran;
                    $columns[] = 'Rata-rata';

                    $callback = function() use ($columns, $mapels, $ta) {
                        $file = fopen('php://output', 'w');
                        fputcsv($file, $columns);

                        $siswas = Siswa::with('kelas')->orderBy('kelas_id')->orderBy('nama_lengkap')->get();
                        $grades = \App\Models\BukuNilai::with('penilaian')->whereHas('penilaian', function($q) use ($ta) {
                            $q->where('tahun_ajaran_id', $ta->id)->where('jenis_nilai', $this->jenis_nilai);
                        })->get()->groupBy('siswa_id');

                        $no = 1;
                        foreach ($siswas as $siswa) {
                            $row = [$no++, $siswa->nisn ?? '-', $siswa->nama_lengkap, $siswa->kelas->nama_kelas ?? '-'];
                            $siswaGrades = $grades->get($siswa->id) ?? collect();
                            $sum = 0; $count = 0;

                            foreach($mapels as $m) {
                                $grade = $siswaGrades->firstWhere('penilaian.mata_pelajaran_id', $m->id);
                                $val = $grade ? $grade->nilai : '';
                                $row[] = $val;
                                if (is_numeric($val)) { $sum += $val; $count++; }
                            }
                            $row[] = $count > 0 ? round($sum / $count, 2) : '';
                            fputcsv($file, $row);
                        }
                        fclose($file);
                    };

                    return response()->stream($callback, 200, $headers);
                });
        }

        $actions[] = Action::make('kembali')
            ->label('Kembali')
            ->color('gray')
            ->url(BukuNilaiResource::getUrl('index'));

        return $actions;
    }

    protected function getViewData(): array
    {
        if (!$this->tahun_ajaran_id) {
            return ['groupedData' => [], 'stats' => ['total' => 0, 'sudah' => 0, 'belum' => 0]];
        }

        $kelasStats = Siswa::where(function ($query) {
                $query->whereIn('status_siswa', ['Aktif', 'Mutasi Masuk'])
                      ->orWhereNull('status_siswa');
            })
            ->selectRaw('kelas_id, count(*) as siswa_count')
            ->groupBy('kelas_id')
            ->pluck('siswa_count', 'kelas_id');

        $jadwals = JadwalPelajaran::with(['kelas', 'mataPelajaran', 'guru'])
            ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
            ->get()
            ->unique(fn ($item) => $item->kelas_id . '-' . $item->mata_pelajaran_id)
            ->values();

        $penilaians = Penilaian::withCount(['bukuNilai' => function ($q) {
            $q->whereNotNull('nilai');
        }])
        ->where('tahun_ajaran_id', $this->tahun_ajaran_id)
        ->where('jenis_nilai', $this->jenis_nilai)
        ->get();

        $stats = ['total' => count($jadwals), 'sudah' => 0, 'belum' => 0];
        $groupedData = [];

        foreach ($jadwals as $jadwal) {
            $totalSiswaDiKelas = $kelasStats[$jadwal->kelas_id] ?? 0;
            
            $penilaianTerkait = $penilaians->where('kelas_id', $jadwal->kelas_id)
                                           ->where('mata_pelajaran_id', $jadwal->mata_pelajaran_id)
                                           ->first();

            $jadwal->total_siswa = $totalSiswaDiKelas;
            $jadwal->siswa_dinilai = 0;
            $jadwal->status_pengumpulan = 'Belum';

            if ($penilaianTerkait) {
                $jadwal->siswa_dinilai = $penilaianTerkait->buku_nilai_count;
                
                if ($jadwal->siswa_dinilai >= $totalSiswaDiKelas && $totalSiswaDiKelas > 0) {
                    $jadwal->status_pengumpulan = 'Selesai';
                    $stats['sudah']++;
                } else {
                    $jadwal->status_pengumpulan = 'Proses';
                    $stats['belum']++; 
                }
            } else {
                $stats['belum']++;
            }

            $namaKelas = $jadwal->kelas->nama_kelas ?? 'Tanpa Kelas';
            $prefix = explode('-', str_replace(' ', '-', trim($namaKelas)))[0];
            $tingkat = match(strtoupper($prefix)) {
                'X', '10' => '10',
                'XI', '11' => '11',
                'XII', '12' => '12',
                default => $prefix
            };
            $tingkatLabel = 'Tingkat ' . $tingkat;

            if (!isset($groupedData[$tingkatLabel])) {
                $groupedData[$tingkatLabel] = [];
            }
            if (!isset($groupedData[$tingkatLabel][$namaKelas])) {
                $groupedData[$tingkatLabel][$namaKelas] = [];
            }

            $groupedData[$tingkatLabel][$namaKelas][] = $jadwal;
        }

        ksort($groupedData);
        foreach ($groupedData as &$kelasGroup) {
            ksort($kelasGroup);
        }

        return ['groupedData' => $groupedData, 'stats' => $stats];
    }
}