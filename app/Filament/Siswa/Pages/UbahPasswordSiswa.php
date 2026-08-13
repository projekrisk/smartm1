<?php

namespace App\Filament\Siswa\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;

class UbahPasswordSiswa extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $title = 'Keamanan Akun';
    protected static string $view = 'filament.siswa.pages.ubah-password-siswa';
    
    protected static ?string $slug = 'ubah-password';
    
    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('new_password')
                    ->label('Masukkan Password Baru')
                    ->password()
                    ->revealable()
                    ->required()
                    ->minLength(6)
                    ->same('new_password_confirmation')
                    ->helperText('Gunakan kombinasi yang mudah diingat, minimal 6 karakter.'),
                TextInput::make('new_password_confirmation')
                    ->label('Ulangi Password Baru')
                    ->password()
                    ->revealable()
                    ->required(),
            ])
            ->statePath('data');
    }

    public function simpan()
    {
        $data = $this->form->getState();
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $user->password = Hash::make($data['new_password']);
        $user->save();

        request()->session()->put([
            'password_hash_web' => $user->getAuthPassword(),
        ]);

        Auth::login($user);

        Notification::make()
            ->title('Password Berhasil Diubah!')
            ->body('Mulai sekarang gunakan password baru ini untuk login.')
            ->success()
            ->send();

        $this->redirect('/siswa');
    }

    public function lewati()
    {
        session()->put('skip_password_change', true);
        
        return redirect()->to('/siswa');
    }
}