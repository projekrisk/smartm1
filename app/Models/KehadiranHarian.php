<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KehadiranHarian extends Model
{
    protected $table = 'kehadiran_harian';
    protected $guarded = [];

    public function rekapKehadiran(): BelongsTo
    {
        return $this->belongsTo(RekapKehadiran::class);
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}