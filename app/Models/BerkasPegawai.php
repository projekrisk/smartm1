<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerkasPegawai extends Model
{
    protected $table = 'berkas_pegawai';
    protected $guarded = [];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}