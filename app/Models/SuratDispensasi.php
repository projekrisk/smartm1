<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SuratDispensasi extends Model
{
    protected $table = 'surat_dispensasi';
    protected $guarded = [];
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_surat' => 'date',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriSurat::class, 'kategori_surat_id');
    }

    public function penandatangan()
    {
        return $this->belongsTo(Pegawai::class, 'penandatangan_id');
    }

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'siswa_surat_dispensasi', 'surat_dispensasi_id', 'siswa_id');
    }
}