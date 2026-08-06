<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 🌟 TAMBAHKAN 2 BARIS INI: Hapus tabel jika sebelumnya sudah nyangkut
        Schema::dropIfExists('jenis_surat');
        Schema::dropIfExists('kategori_surat');

        // Baru buat ulang tabelnya
        Schema::create('kategori_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori');
            $table->timestamps();
        });

        Schema::create('jenis_surat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_surat_id')->constrained('kategori_surat')->cascadeOnDelete();
            $table->string('nama_surat');
            $table->string('deskripsi')->nullable();
            $table->string('url_create'); 
            $table->string('icon')->default('heroicon-o-document-text');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jenis_surat');
        Schema::dropIfExists('kategori_surat');
    }
};