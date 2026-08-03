<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesanBantuan extends Model
{
    protected $table = 'pesan_bantuan';
    protected $guarded = [];

    public function siswa(): BelongsTo { return $this->belongsTo(Siswa::class); }
    
    public function details(): HasMany { return $this->hasMany(PesanBantuanDetail::class); }
}