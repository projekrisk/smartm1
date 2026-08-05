<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $guarded = [];

    protected $casts = [
        'tugas_tambahan' => 'array',
        'tanggal_lahir' => 'date',
        'tmt_cpns_honorer' => 'date',
        'tmt_pns_pppk' => 'date',
        'tmt_golongan_terakhir' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($pegawai) {
            $peran = 'staf';
            if ($pegawai->jenis_ptk === 'Guru' || $pegawai->jenis_ptk === 'Kepala Sekolah') {
                $peran = 'guru';
            }

            $user = User::create([
                'name' => $pegawai->nama,
                'email' => $pegawai->email,
                'password' => Hash::make($pegawai->nik),
                'peran' => $peran,
            ]);
            $pegawai->updateQuietly(['user_id' => $user->id]);
        });

        static::updated(function ($pegawai) {
            if ($pegawai->user) {
                $pegawai->user->updateQuietly([
                    'name' => $pegawai->nama,
                    'email' => $pegawai->email,
                ]);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getMasaKerjaGolonganAttribute()
    {
        if (!$this->tmt_golongan_terakhir) return 0;
        return Carbon::parse($this->tmt_golongan_terakhir)->diffInYears(now());
    }

    public function getMasaKerjaKeseluruhanAttribute()
    {
        $tmt = $this->tmt_cpns_honorer ?? $this->tmt_pns_pppk;
        if (!$tmt) return 0;
        return Carbon::parse($tmt)->diffInYears(now());
    }

    public function getDaftarTugasTambahanAttribute()
    {
        $tugas = $this->tugas_tambahan ?? [];
        
        if ($this->user_id) {
            $daftarKelas = Kelas::where('wali_kelas_id', $this->user_id)->pluck('nama_kelas');
            foreach($daftarKelas as $kelas) {
                $tugas[] = 'Wali Kelas ' . $kelas;
            }
        }
        
        return $tugas;
    }

    public function berkas()
    {
        return $this->hasMany(BerkasPegawai::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($pegawai) {
            if ($pegawai->isDirty('foto')) {
                $fotoLama = $pegawai->getOriginal('foto');
                
                if ($fotoLama) {
                    Storage::disk('publik_upload')->delete($fotoLama);
                }
            }
        });

        static::deleting(function ($pegawai) {
            if ($pegawai->foto) {
                Storage::disk('publik_upload')->delete($pegawai->foto);
            }
        });
    }
}