<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $guarded = [];

    // Relasi: Kelas ini memiliki 1 Wali Kelas (dari tabel User)
    public function waliKelas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'wali_kelas_id');
    }

    public function tingkat()
    {
        return $this->belongsTo(Tingkat::class);
    }
}