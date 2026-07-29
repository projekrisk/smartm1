<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('keterangan')->nullable();
            
            // Pilihan Sumber: File Upload langsung ATAU sekadar URL/Link eksternal
            $table->enum('jenis_sumber', ['File', 'Link'])->default('File');
            $table->string('file_path')->nullable();
            $table->string('url_link')->nullable();
            
            // Target Audiens Dokumen
            $table->enum('target_audience', ['Semua', 'Guru & Staf', 'Siswa'])->default('Semua');
            
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};