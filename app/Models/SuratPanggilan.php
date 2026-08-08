<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPanggilan extends Model
{
    use HasFactory;

    protected $table = 'surat_panggilan';

    protected $fillable = [
        'nomor_surat',
        'siswa_id',
        'penandatangan_id', // 🌟 WAJIB DITAMBAHKAN DI SINI
        'tanggal_surat',
        'dibuat_oleh',
        'tanggal_panggilan',
        'waktu_panggilan',
        'tempat_pertemuan',
        'alasan_panggilan',
        'status',
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    // Relasi ke Penandatangan (Pegawai)
    public function penandatangan()
    {
        return $this->belongsTo(Pegawai::class, 'penandatangan_id');
    }
}