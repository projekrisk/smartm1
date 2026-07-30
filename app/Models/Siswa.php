<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Filament\Models\Contracts\HasAvatar;

class Siswa extends Model implements HasAvatar
{
    protected $table = 'siswa';
    protected $guarded = [];

    protected static $tahunAktifCache = null;

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->foto ? url('/uploads/' . $this->foto) : null;
    }
    
    protected static function booted()
    {
        static::created(function ($siswa) {
            $user = User::create([
                'name' => $siswa->nama_lengkap,
                'email' => $siswa->nisn ? $siswa->nisn . '@siswa.com' : $siswa->nis . '@siswa.com',
                'password' => Hash::make($siswa->nis),
                'peran' => 'siswa',
            ]);
            
            $siswa->updateQuietly(['user_id' => $user->id]);

            if ($siswa->kelas_id) {
                if (self::$tahunAktifCache === null) {
                    self::$tahunAktifCache = TahunAjaran::where('is_active', true)->first();
                }
                
                if (self::$tahunAktifCache) {
                    RiwayatKelasSiswa::create([
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $siswa->kelas_id,
                        'tahun_ajaran_id' => self::$tahunAktifCache->id,
                        'status_riwayat' => $siswa->jalur_masuk ?? 'Siswa Baru',
                    ]);
                }
            }
        });

        static::updated(function ($siswa) {
            if ($siswa->isDirty('kelas_id') && $siswa->kelas_id !== null) {
                
                if (self::$tahunAktifCache === null) {
                    self::$tahunAktifCache = TahunAjaran::where('is_active', true)->first();
                }

                if (self::$tahunAktifCache) {
                    $cekRiwayat = RiwayatKelasSiswa::where('siswa_id', $siswa->id)
                        ->where('tahun_ajaran_id', self::$tahunAktifCache->id)
                        ->where('kelas_id', $siswa->kelas_id)
                        ->exists();

                    if (!$cekRiwayat) {
                        RiwayatKelasSiswa::create([
                            'siswa_id' => $siswa->id,
                            'kelas_id' => $siswa->kelas_id,
                            'tahun_ajaran_id' => self::$tahunAktifCache->id,
                            'status_riwayat' => 'Ditempatkan di Kelas Baru',
                        ]);
                    }
                }
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function riwayatKelas(): HasMany
    {
        return $this->hasMany(RiwayatKelasSiswa::class);
    }

    public function catatan(): HasMany
    {
        return $this->hasMany(CatatanSiswa::class);
    }

    public function kehadiranHarian(): HasMany
    {
        return $this->hasMany(KehadiranHarian::class);
    }
}