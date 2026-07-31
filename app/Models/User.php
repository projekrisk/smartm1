<?php

namespace App\Models;

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

    protected $fillable = [
        'name',
        'username',
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
        
        if ($pegawai && !empty($pegawai->foto)) {
            if (\Illuminate\Support\Facades\Storage::disk('publik_upload')->exists($pegawai->foto)) {
                return \Illuminate\Support\Facades\Storage::disk('publik_upload')->url($pegawai->foto);
            }
        }
        
        return asset('images/default-avatar.png?v=2'); 
    }
}