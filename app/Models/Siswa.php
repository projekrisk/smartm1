<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Support\Facades\Storage;

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
        return $this->belongsTo(Kelas::class, 'kelas_id');
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

    public function suratPanggilan(): HasMany
    {
        return $this->hasMany(SuratPanggilan::class, 'siswa_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($siswa) {
            if ($siswa->isDirty('foto')) {
                $fotoLama = $siswa->getOriginal('foto');
                
                if ($fotoLama) {
                    Storage::disk('publik_upload')->delete($fotoLama);
                }
            }
        });

        static::deleting(function ($siswa) {
            if ($siswa->foto) {
                Storage::disk('publik_upload')->delete($siswa->foto);
            }
        });
    }

    public function suratDispensasi()
    {
        return $this->belongsToMany(SuratDispensasi::class, 'siswa_surat_dispensasi', 'siswa_id', 'surat_dispensasi_id');
    }
}