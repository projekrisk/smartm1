<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';
    protected $guarded = [];

    // Relasi untuk menarik nama Tahun Ajaran
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Relasi untuk menarik nama Guru
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    // Relasi untuk menarik nama Mata Pelajaran
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    // Relasi untuk menarik nama Kelas
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}