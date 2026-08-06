<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JenisSurat extends Model
{
    protected $table = 'jenis_surat';
    protected $guarded = [];

    public function kategoriSurat()
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_surat_id');
    }
}