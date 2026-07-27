<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengampuPelajaran extends Model
{
    // Menggunakan tabel pivot lama yang sudah dimodifikasi
    protected $table = 'kelas_mata_pelajaran';
    
    public $timestamps = false;
    protected $guarded = [];

    public function mataPelajaran() { return $this->belongsTo(MataPelajaran::class); }
    public function kelas() { return $this->belongsTo(Kelas::class); }
    public function guru() { return $this->belongsTo(User::class, 'guru_id'); }
}