<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $guarded = [];

    // FUNGSI BARU: Relasi sekarang langsung mengarah ke Jadwal Pelajaran (Bukan lagi ke tabel pengampu/pivot)
    public function jadwalPelajaran()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function nilaiRapor()
    {
        return $this->hasMany(NilaiRapor::class);
    }
}