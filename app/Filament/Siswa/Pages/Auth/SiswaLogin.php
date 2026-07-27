<?php

namespace App\Filament\Siswa\Pages\Auth;

use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Component;
use Illuminate\Support\Facades\Hash;
use Filament\Models\Contracts\FilamentUser;

// WAJIB DITAMBAHKAN AGAR PHP MENGENALI MODEL DATABASE:
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class SiswaLogin extends BaseLogin
{
    protected static string $view = 'filament.siswa.pages.auth.siswa-login';

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('username')
                ->label('NISN (Nomor Induk Siswa Nasional)')
                ->required()
                ->autocomplete('username')
                ->autofocus()
                ->extraInputAttributes(['tabindex' => 1]),
            
            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required()
                ->extraInputAttributes(['tabindex' => 2]),
                
            $this->getRememberFormComponent(),
        ]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    public function authenticate(): ?\Filament\Http\Responses\Auth\Contracts\LoginResponse
    {
        $data = $this->form->getState();
        $nisn = $data['username'];
        $password = $data['password'];

        // Cek apakah NISN tersebut terdaftar di tabel Siswa
        $siswa = Siswa::where('nisn', $nisn)->first();

        if ($siswa) {
            // Cek apakah akun loginnya sudah dibuat
            $user = User::where('username', $nisn)->first();
            
            if (!$user) {
                // Jika belum ada, dan password yang diketik sama dengan NISN, buat akunnya!
                if ($password === $nisn) {
                    $user = User::create([
                        'name' => $siswa->nama_lengkap,
                        'username' => $nisn,
                        // Buat email palsu sementara agar tidak error validasi Laravel
                        'email' => $nisn . '@siswa.smartm1.com', 
                        'password' => Hash::make($nisn),
                        'peran' => 'siswa',
                    ]);
                    
                    // Hubungkan ID user ke tabel siswa
                    $siswa->updateQuietly(['user_id' => $user->id]);
                } else {
                    throw ValidationException::withMessages([
                        'data.username' => __('Kata sandi default salah. Gunakan NISN sebagai password awal.'),
                    ]);
                }
            }
        } else {
            throw ValidationException::withMessages([
                'data.username' => __('NISN tidak ditemukan di database sekolah.'),
            ]);
        }

        return parent::authenticate();
    }

    // --- TAMBAHAN BARU: MEMBUNUH TAMPILAN BAWAAN FILAMENT ---

    // Membunuh judul "Masuk ke akun Anda" bawaan Filament
    public function getHeading(): string | \Illuminate\Contracts\Support\Htmlable
    {
        return '';
    }

    // Membunuh Logo Banten raksasa bawaan Filament
    public function hasLogo(): bool
    {
        return false;
    }
}