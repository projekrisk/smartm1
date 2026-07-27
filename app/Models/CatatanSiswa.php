<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CatatanSiswa extends Model
{
    protected $table = 'catatan_siswa';
    protected $guarded = [];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // TAMBAHAN: Relasi untuk mengetahui siapa yang menindaklanjuti
    public function penindaklanjut(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditindaklanjuti_oleh');
    }

    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}