<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable;

    // INI ADALAH KUNCI MASALAHNYA! PASTIKAN 'username' ADA DI SINI
    protected $fillable = [
        'name',
        'username', // <--- BARIS INI WAJIB ADA!
        'email',
        'password',
        'peran',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($panel->getId() === 'admin') {
            return in_array($this->peran, ['admin', 'staf', 'guru']);
        }
        
        if ($panel->getId() === 'siswa') {
            return $this->peran === 'siswa';
        }

        return false;
    }

    public function pegawai(): HasOne
    {
        return $this->hasOne(Pegawai::class, 'user_id');
    }

    public function getFilamentAvatarUrl(): ?string
    {
        $pegawai = $this->pegawai;
        
        if ($pegawai && $pegawai->foto) {
            return url('/uploads/' . $pegawai->foto);
        }
        
        return null; 
    }
}