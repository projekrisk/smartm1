<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $guarded = [];

    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function tahunAjaran() { return $this->belongsTo(TahunAjaran::class); }
    
    public function bukuNilai() { return $this->hasMany(BukuNilai::class); }
}