<?php

namespace App\Filament\Resources\BukuNilaiResource\Pages;

use App\Filament\Resources\BukuNilaiResource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class InputNilaiMassal extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = BukuNilaiResource::class;
    protected static string $view = 'filament.resources.buku-nilai-resource.pages.input-nilai-massal';
    protected static ?string $title = 'Input Nilai Massal';
    public ?array $data = [];

    public function mount(): void { $this->form->fill(); }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('tahun_ajaran_id')->default(fn () => \App\Models\TahunAjaran::where('is_active', true)->first()?->id),

                Forms\Components\Section::make('1. Pengaturan Dasar')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\Select::make('mata_pelajaran_id')
                                ->label('Mata Pelajaran')
                                ->options(function () {
                                    if (auth()->user()->peran === 'admin') return \App\Models\MataPelajaran::pluck('nama_pelajaran', 'id');
                                    $mapelIds = \App\Models\JadwalPelajaran::where('guru_id', auth()->id())->pluck('mata_pelajaran_id');
                                    return \App\Models\MataPelajaran::whereIn('id', $mapelIds)->pluck('nama_pelajaran', 'id');
                                })
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (callable $set) => $set('kelas_id', null)),

                            Forms\Components\Select::make('jenis_nilai')
                                ->label('Jenis Nilai')
                                ->options([ 'Tugas' => 'Tugas', 'Ulangan Harian' => 'Ulangan Harian', 'UTS' => 'UTS', 'UAS' => 'UAS', 'Sikap' => 'Sikap' ])
                                ->required(),

                            Forms\Components\Select::make('kelas_id')
                                ->label('Pilih Kelas')
                                ->options(function (callable $get) {
                                    $mapelId = $get('mata_pelajaran_id');
                                    if (!$mapelId) return [];
                                    $query = \App\Models\JadwalPelajaran::with('kelas')->where('mata_pelajaran_id', $mapelId);
                                    if (auth()->user()->peran === 'guru') $query->where('guru_id', auth()->id());
                                    return $query->get()->pluck('kelas.nama_kelas', 'kelas_id');
                                })
                                ->live() 
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if (!$state) { $set('daftar_siswa', []); return; }
                                    $siswas = \App\Models\Siswa::where('kelas_id', $state)->orderBy('nama_lengkap')->get();
                                    $daftar = [];
                                    foreach ($siswas as $siswa) {
                                        $daftar[(string)$siswa->id] = [ 'siswa_id' => $siswa->id, 'nama_lengkap' => $siswa->nama_lengkap, 'nilai' => null, 'catatan_guru' => null ];
                                    }
                                    $set('daftar_siswa', $daftar);
                                })
                                ->required(),
                        ]),
                        
                        Forms\Components\TextInput::make('materi')
                            ->label('Materi / Pembahasan (Topik)')
                            ->placeholder('Contoh: Persamaan Kuadrat')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('2. Daftar Siswa')
                    ->description('Kosongkan baris jika siswa tidak memiliki nilai.')
                    ->schema([
                        Forms\Components\Placeholder::make('header_tabel')
                            ->hiddenLabel()
                            ->content(new HtmlString('
                                <style>
                                    /* Trik CSS Super Padat: Merampingkan baris repeater persis seperti baris tabel */
                                    .tabel-repeater .fi-rep-item { box-shadow: none !important; border-radius: 0 !important; border: none !important; border-bottom: 1px solid #e5e7eb !important; margin: 0 !important; }
                                    .tabel-repeater .fi-rep-item > div { padding: 0.25rem 0.5rem !important; background-color: transparent !important; }
                                </style>
                            ')),

                        Forms\Components\Repeater::make('daftar_siswa')
                            ->hiddenLabel()
                            ->extraAttributes(['class' => 'tabel-repeater']) // Dipasangkan dengan CSS di atas
                            ->schema([
                                Forms\Components\Hidden::make('siswa_id'),
                                Forms\Components\TextInput::make('nama_lengkap')
                                    ->hiddenLabel()
                                    ->disabled()
                                    ->dehydrated(false)
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('nilai')
                                    ->hiddenLabel()
                                    ->placeholder('0 - 100')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->columnSpan(1),
                                Forms\Components\TextInput::make('catatan_guru')
                                    ->hiddenLabel()
                                    ->placeholder('Ketik catatan...')
                                    ->columnSpan(2),
                            ])
                            ->columns(5)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                    ])->hidden(fn (\Filament\Forms\Get $get) => !$get('kelas_id')),
            ])->statePath('data');
    }

    public function simpan(): void
    {
        $data = $this->form->getState();
        $berhasil = 0;
        
        $penilaian = \App\Models\Penilaian::updateOrCreate(
            [ 
                'mata_pelajaran_id' => $data['mata_pelajaran_id'], 
                'kelas_id' => $data['kelas_id'],
                'tahun_ajaran_id' => $data['tahun_ajaran_id'], 
                'jenis_nilai' => $data['jenis_nilai'],
                'materi' => $data['materi']
            ],
            [ 'tanggal_penilaian' => now() ]
        );

        foreach ($data['daftar_siswa'] as $siswaData) {
            if ($siswaData['nilai'] !== null && $siswaData['nilai'] !== '') {
                \App\Models\BukuNilai::updateOrCreate(
                    [ 'siswa_id' => $siswaData['siswa_id'], 'penilaian_id' => $penilaian->id ],
                    [ 'nilai' => $siswaData['nilai'], 'catatan_guru' => $siswaData['catatan_guru'] ]
                );
                $berhasil++;
            }
        }
        Notification::make()->title('Berhasil')->body("Sebanyak $berhasil data nilai tersimpan.")->success()->send();
        $this->redirect(BukuNilaiResource::getUrl('index'));
    }
}