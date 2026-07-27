<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KehadiranPelajaran extends Model
{
    protected $table = 'kehadiran_pelajaran';
    protected $guarded = [];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jurnalGuru(): BelongsTo
    {
        return $this->belongsTo(JurnalGuru::class);
    }
}