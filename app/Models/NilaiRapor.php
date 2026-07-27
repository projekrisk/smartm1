<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NilaiRapor extends Model
{
    protected $table = 'nilai_rapor';
    protected $guarded = [];

    // Fungsi otomatis untuk menentukan predikat berdasarkan nilai
    protected static function booted()
    {
        static::saving(function ($model) {
            if ($model->nilai_akhir >= 90) {
                $model->predikat = 'A';
            } elseif ($model->nilai_akhir >= 80) {
                $model->predikat = 'B';
            } elseif ($model->nilai_akhir >= 70) {
                $model->predikat = 'C';
            } else {
                $model->predikat = 'D';
            }
        });
    }

    public function siswa(): BelongsTo { return $this->belongsTo(Siswa::class); }
    public function mataPelajaran(): BelongsTo { return $this->belongsTo(MataPelajaran::class); }
    public function tahunAjaran(): BelongsTo { return $this->belongsTo(TahunAjaran::class); }
}