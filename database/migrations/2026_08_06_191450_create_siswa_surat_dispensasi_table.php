<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Bersihkan sisa tabel jika menyangkut
        Schema::dropIfExists('siswa_surat_dispensasi');

        // 2. Buat tabel murni (tanpa ikatan paksa MySQL)
        Schema::create('siswa_surat_dispensasi', function (Blueprint $table) {
            $table->id();
            
            // Kolom relasi biasa
            $table->unsignedBigInteger('surat_dispensasi_id')->index();
            $table->unsignedBigInteger('siswa_id')->index();
            
            // Kita sengaja TIDAK menggunakan $table->foreign() di sini
            // Agar MySQL di hosting tidak memprotes/membentrokkan tipe data & nama tabel.
            // Relasi tetap akan berjalan lancar lewat Model Laravel.

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_surat_dispensasi');
    }
};