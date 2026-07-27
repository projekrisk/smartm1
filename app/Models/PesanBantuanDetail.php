<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanBantuanDetail extends Model
{
    protected $table = 'pesan_bantuan_detail';
    protected $guarded = [];

    public function pesanBantuan(): BelongsTo { return $this->belongsTo(PesanBantuan::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}