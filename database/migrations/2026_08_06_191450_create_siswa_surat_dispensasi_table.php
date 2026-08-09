<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 🌟 KUNCI PENYELESAIAN OTOMATIS: 
        // Hapus sisa tabel yang "nyangkut" dari error sebelumnya sebelum membuat yang baru
        Schema::dropIfExists('siswa_surat_dispensasi');

        Schema::create('siswa_surat_dispensasi', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('surat_dispensasi_id');
            $table->unsignedBigInteger('siswa_id');

            $table->foreign('surat_dispensasi_id')
                  ->references('id')
                  ->on('surat_dispensasi')
                  ->onDelete('cascade');
            
            $table->foreign('siswa_id')
                  ->references('id')
                  ->on('siswa') 
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_surat_dispensasi');
    }
};