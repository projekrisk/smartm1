<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('siswa_surat_dispensasi', function (Blueprint $table) {
            $table->id();
            
            // 1. Buat kolom untuk relasi surat_dispensasi (Pasti BigInteger karena tabel baru)
            $table->unsignedBigInteger('surat_dispensasi_id');
            
            // 2. Buat kolom untuk relasi siswa
            // Coba gunakan unsignedBigInteger terlebih dahulu
            $table->unsignedBigInteger('siswa_id'); 
            
            // CATATAN PENTING: 
            // Jika setelah di-migrate MASIH error 150, berarti tabel siswa Anda menggunakan Integer biasa.
            // Hapus/Komentari baris di atas, dan gunakan baris di bawah ini:
            // $table->unsignedInteger('siswa_id');

            // 3. Definisikan kunci tamu (Foreign Key) secara manual
            $table->foreign('surat_dispensasi_id')
                ->references('id')
                ->on('surat_dispensasi')
                ->onDelete('cascade');
                
            $table->foreign('siswa_id')
                ->references('id')
                ->on('siswa') // Pastikan nama tabel siswa di sini benar
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_dispensasis');
    }
};
