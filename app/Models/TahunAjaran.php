<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';
    protected $guarded = [];

    // FUNGSI AJAIB: Mencegah ada 2 tahun ajaran yang aktif bersamaan
    protected static function booted()
    {
        static::saving(function ($model) {
            // Jika tahun ajaran ini sedang diset menjadi aktif (true)
            if ($model->is_active) {
                // Maka matikan (false) SEMUA data lainnya di tabel ini
                static::where('id', '!=', $model->id)->update(['is_active' => false]);
            }
        });
    }
}