<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BerkasPegawai extends Model
{
    use HasFactory;
    
    protected $table = 'berkas_pegawai';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($berkas) {
            if ($berkas->isDirty('file_path')) {
                $berkasLama = $berkas->getOriginal('file_path');
                if ($berkasLama) {
                    Storage::disk('publik_upload')->delete($berkasLama);
                }
            }
        });

        static::deleting(function ($berkas) {
            if ($berkas->file_path) { // 
                Storage::disk('publik_upload')->delete($berkas->file_path);
            }
        });
    }

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class);
    }
}