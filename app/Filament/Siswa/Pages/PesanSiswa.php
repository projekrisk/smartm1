<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\PesanBantuan;
use App\Models\PesanBantuanDetail;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;

class PesanSiswa extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Pusat Bantuan';
    protected static string $view = 'filament.siswa.pages.pesan-siswa';
    protected static ?string $slug = 'pesan';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];
    
    public ?int $activeSesiId = null;
    public bool $isCreatingNew = false;

    public function getLayout(): string { return 'filament-panels::components.layout.simple'; }
    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function bukaSesi($id): void
    {
        $this->activeSesiId = $id;
        $this->isCreatingNew = false;
        $this->form->fill();
        
        $sesi = PesanBantuan::find($id);
        if ($sesi && !$sesi->is_read_siswa) {
            $sesi->update(['is_read_siswa' => true]);
        }
    }

    public function buatPesanBaru(): void
    {
        $this->activeSesiId = null;
        $this->isCreatingNew = true;
        $this->form->fill();
    }

    public function kembaliKeList(): void
    {
        $this->activeSesiId = null;
        $this->isCreatingNew = false;
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Textarea::make('pesan')
                ->label('Ketik Pesan / Keluhan Anda')
                ->placeholder('Ketik pesan di sini...')
                ->required()
                ->rows(3),
        ])->statePath('data');
    }

    public function kirimPesan(): void
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        $data = $this->form->getState();

        if ($siswa) {
            $tiket = null;
            
            if ($this->activeSesiId) {
                $tiket = PesanBantuan::find($this->activeSesiId);
            }

            if (!$tiket) {
                $tiket = PesanBantuan::create([
                    'siswa_id' => $siswa->id,
                    'status' => 'Open',
                    'is_read_admin' => false,
                    'is_read_siswa' => true,
                ]);
                $this->activeSesiId = $tiket->id;
                $this->isCreatingNew = false;
            } else {
                if ($tiket->status !== 'Selesai') {
                    $tiket->update([
                        'is_read_admin' => false,
                        'status' => 'Open',
                        'updated_at' => now(),
                    ]);
                }
            }

            if ($tiket->status !== 'Selesai') {
                PesanBantuanDetail::create([
                    'pesan_bantuan_id' => $tiket->id,
                    'pengirim' => 'Siswa',
                    'pesan' => $data['pesan'],
                ]);
            }

            $this->form->fill(); 
        }
    }

    protected function getViewData(): array
    {
        $siswa = Siswa::where('user_id', Auth::id())->first();
        
        $daftar_sesi = collect();
        $pesan_list = collect();
        $sesiAktif = null;

        if ($siswa) {
            $daftar_sesi = PesanBantuan::with(['details' => function($q) {
                $q->latest()->limit(1);
            }])
            ->where('siswa_id', $siswa->id)
            ->orderBy('updated_at', 'desc')
            ->get();

            if ($this->activeSesiId) {
                $sesiAktif = PesanBantuan::find($this->activeSesiId);
                if ($sesiAktif) {
                    $pesan_list = $sesiAktif->details()->with('user')->orderBy('created_at', 'asc')->get();
                }
            }
        }

        return [
            'daftar_sesi' => $daftar_sesi,
            'sesiAktif' => $sesiAktif,
            'pesan_list' => $pesan_list,
        ];
    }
}