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
        $this->siswa = Siswa::with('kelas')->where('user_id', Auth::id())->first();
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
                    // Mengaktifkan fitur crop bawaan Filament
                    ->imageEditor()
                    ->imageEditorAspectRatios(['1:1'])
                    ->imageCropAspectRatio('1:1')
                    // Fitur Kompresi Otomatis (Resizing agar file ringan)
                    ->imageResizeMode('cover')
                    ->imageResizeTargetWidth('512')
                    ->imageResizeTargetHeight('512')
                    ->disk('publik_upload')
                    ->directory('foto-siswa')
                    ->maxSize(2048) // Maksimal unggah 2MB
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