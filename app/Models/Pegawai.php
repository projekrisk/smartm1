<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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

    // LOGIKA OTOMATIS SAAT DISIMPAN
    protected static function booted()
    {
        static::created(function ($pegawai) {
            // Tentukan peran (role) Filament berdasarkan Status Kepegawaian
            $peran = 'staf';
            if ($pegawai->status_kepegawaian === 'Guru') {
                $peran = 'guru';
            }

            // Buat Akun User Otomatis (Password default: NIK)
            $user = User::create([
                'name' => $pegawai->nama,
                'email' => $pegawai->email,
                'password' => Hash::make($pegawai->nik),
                'peran' => $peran,
            ]);
            $pegawai->updateQuietly(['user_id' => $user->id]);
        });

        // Sinkronisasi pembaruan nama/email ke tabel Users jika diedit
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

    // MENGHITUNG MASA KERJA GOLONGAN (Tahun)
    public function getMasaKerjaGolonganAttribute()
    {
        if (!$this->tmt_golongan_terakhir) return 0;
        return Carbon::parse($this->tmt_golongan_terakhir)->diffInYears(now());
    }

    // MENGHITUNG MASA KERJA KESELURUHAN (Tahun)
    public function getMasaKerjaKeseluruhanAttribute()
    {
        $tmt = $this->tmt_cpns_honorer ?? $this->tmt_pns_pppk;
        if (!$tmt) return 0;
        return Carbon::parse($tmt)->diffInYears(now());
    }

    // MENGGABUNGKAN TUGAS TAMBAHAN + WALI KELAS OTOMATIS
    public function getDaftarTugasTambahanAttribute()
    {
        $tugas = $this->tugas_tambahan ?? []; // Ambil dari JSON
        
        // Cek apakah dia wali kelas di pengaturan Kelas
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
}