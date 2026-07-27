<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';
    
    // Mengizinkan semua kolom diisi
    protected $guarded = [];
}