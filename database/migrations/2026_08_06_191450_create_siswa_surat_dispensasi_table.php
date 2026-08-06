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
            $table->foreignId('surat_dispensasi_id')->constrained('surat_dispensasi')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswas')->cascadeOnDelete(); // Sesuaikan nama tabel siswa Anda jika beda
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_surat_dispensasi');
    }
};
