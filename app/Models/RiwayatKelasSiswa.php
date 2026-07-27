<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKelasSiswa extends Model
{
    protected $table = 'riwayat_kelas_siswa';
    protected $guarded = [];

    // Tambahkan 3 relasi ini agar tabel bisa membaca nama kelas & tahun
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}