<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BukuNilai extends Model
{
    protected $table = 'buku_nilai';
    protected $guarded = [];

    public function penilaian() { return $this->belongsTo(Penilaian::class); }
    public function siswa() { return $this->belongsTo(Siswa::class); }
}