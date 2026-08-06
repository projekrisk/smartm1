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
        Schema::create('surat_dispensasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_surat_id')->constrained('kategori_surat')->cascadeOnDelete();
            $table->string('nomor_urut'); // Nomor yang diinput TU
            $table->string('nomor_surat_lengkap'); // Hasil gabungan otomatis
            $table->string('nama_kegiatan');
            $table->string('penyelenggara');
            $table->string('tempat');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('tanggal_surat');
            $table->foreignId('penandatangan_id')->nullable()->constrained('pegawai')->nullOnDelete(); // Relasi ke Pegawai (Wakasek)
            $table->timestamps();
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
