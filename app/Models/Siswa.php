<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Filament\Models\Contracts\HasAvatar;
// use Filament\Models\Contracts\HasGlobalSearchTitle; <-- HAPUS INI

class Siswa extends Model implements HasAvatar // <-- HAPUS HasGlobalSearchTitle
{
    protected $table = 'siswa';
    protected $guarded = [];

    protected static $tahunAktifCache = null;

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->foto ? url('/uploads/' . $this->foto) : null;
    }
    
    // =========================================================================
    // FITUR GLOBAL SEARCH: Menentukan judul utama yang muncul saat dicari
    // =========================================================================
    // HAPUS FUNGSI getGlobalSearchResultTitle() DARI SINI !!!

    protected static function booted()
    {
        // Berjalan setelah data siswa BERHASIL DISIMPAN pertama kali
        static::created(function ($siswa) {
            // 1. Buat akun User otomatis
            $user = User::create([
                'name' => $siswa->nama_lengkap,
                // Pastikan menggunakan domain dummy atau email asli jika ada
                'email' => $siswa->nisn ? $siswa->nisn . '@siswa.com' : $siswa->nis . '@siswa.com',
                'password' => Hash::make($siswa->nis),
                'peran' => 'siswa',
            ]);
            
            // Hubungkan ID user yang baru dibuat ke tabel siswa tanpa memicu event update lagi
            $siswa->updateQuietly(['user_id' => $user->id]);

            // 2. Buat Riwayat Kelas Pertama (Buku Induk)
            if ($siswa->kelas_id) {
                // Cek cache, jika kosong barulah query ke database (ini menghemat 999 query saat impor masal)
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

        // =========================================================================
        // SENSOR AJAIB: DETEKSI PERUBAHAN KELAS VIA IMPORT EXCEL
        // =========================================================================
        static::updated(function ($siswa) {
            // Jika kelas_id berubah (misal dari kosong menjadi ada isinya karena diimpor Excel)
            if ($siswa->isDirty('kelas_id') && $siswa->kelas_id !== null) {
                
                if (self::$tahunAktifCache === null) {
                    self::$tahunAktifCache = TahunAjaran::where('is_active', true)->first();
                }

                if (self::$tahunAktifCache) {
                    // Cek agar tidak terjadi pencatatan riwayat ganda di tahun yang sama
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