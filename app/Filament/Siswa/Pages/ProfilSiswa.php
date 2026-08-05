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
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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

        $this->siswa = Siswa::with('kelas')->where('user_id', $user->id)->first();

        if (!$this->siswa || empty($this->siswa->nis)) {
            $siswaAsli = Siswa::with('kelas')
                ->where('nis', $user->username)
                ->orWhere('nisn', $user->username)
                ->orWhere('email', $user->email)
                ->first();
                
            if ($siswaAsli) {
                if ($this->siswa && $this->siswa->id !== $siswaAsli->id) {
                    $this->siswa->delete(); 
                }
                
                $siswaAsli->updateQuietly(['user_id' => $user->id]);
                
                $this->siswa = $siswaAsli;
            }
        }

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
        $nisn = $this->siswa->nisn ?? 'Siswa';

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
                    ])
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) use ($nisn) {
                        $ekstensi = $file->getClientOriginalExtension();
                        return 'Foto_' . $nisn . '_' . time() . '.' . $ekstensi;
                    }),
            ])
            ->statePath('fotoData');
    }

    public function simpanFoto(): void
    {
        if ($this->siswa) {
            $data = $this->fotoForm->getState();
            
            $this->siswa->update([
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