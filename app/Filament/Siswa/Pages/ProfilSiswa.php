<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;

class ProfilSiswa extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Profil Siswa';
    protected static string $view = 'filament.siswa.pages.profil-siswa';
    protected static ?string $slug = 'profil';
    protected static bool $shouldRegisterNavigation = false;

    public ?array $fotoData = [];
    public ?Siswa $siswa = null;

    public function getLayout(): string { return 'filament-panels::components.layout.simple'; }
    public function getHeading(): string { return ''; }
    public function hasLogo(): bool { return false; }

    public function mount(): void
    {
        $user = Auth::user();

        // 1. Coba cari siswa yang user_id nya sudah terhubung
        $this->siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();

        // 2. CEK CERDAS: Jika tidak ketemu, atau ketemu TAPI NIS-nya kosong (berarti data ganda/belum lengkap)
        // Maka kita cari data aslinya menggunakan kecocokan Username login dengan NIS atau NISN
        if (!$this->siswa || empty($this->siswa->nis)) {
            $siswaAsli = Siswa::with('kelas')
                ->where('nis', $user->username)
                ->orWhere('nisn', $user->username)
                ->orWhere('email', $user->email)
                ->first();
                
            if ($siswaAsli) {
                // Hapus data yang kosong/ganda tadi (jika ada)
                if ($this->siswa && $this->siswa->id !== $siswaAsli->id) {
                    $this->siswa->delete(); 
                }
                
                // Tautkan user_id ke data asli yang lengkap
                $siswaAsli->updateQuietly(['user_id' => $user->id]);
                
                // Jadikan data asli ini sebagai data yang ditampilkan
                $this->siswa = $siswaAsli;
            }
        }

        // Isi form foto jika data siswa valid
        if ($this->siswa) {
            $this->fotoForm->fill([
                'foto' => $this->siswa->foto,
            ]);
        }
    }

    protected function getForms(): array
    {
        return ['fotoForm'];
    }

    public function fotoForm(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('foto')
                    ->hiddenLabel()
                    ->image()
                    ->avatar()
                    ->imageEditor()
                    ->imageEditorAspectRatios(['1:1'])
                    ->imageCropAspectRatio('1:1')
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('512')
                    ->imageResizeTargetHeight('512')
                    ->disk('publik_upload')
                    ->directory('foto-siswa')
                    ->maxSize(2048)
                    ->helperText('Pilih gambar dari Kamera/Galeri.')
                    ->extraAttributes([
                        'class' => 'mx-auto flex justify-center text-center',
                    ]),
            ])
            ->statePath('fotoData');
    }

    public function simpanFoto(): void
    {
        if ($this->siswa) {
            $data = $this->fotoForm->getState();
            $this->siswa->updateQuietly([
                'foto' => $data['foto'],
            ]);
            Notification::make()
                ->title('Foto Profil Diperbarui!')
                ->success()
                ->send();
        }
    }

    protected function getViewData(): array
    {
        return [
            'siswa' => $this->siswa,
        ];
    }
}