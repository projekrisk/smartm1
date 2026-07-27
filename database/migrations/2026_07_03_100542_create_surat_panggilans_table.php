<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_panggilan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat')->unique();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('dibuat_oleh')->constrained('users')->cascadeOnDelete(); // Staf TU
            
            $table->date('tanggal_surat');
            $table->date('tanggal_panggilan');
            $table->time('waktu_panggilan');
            $table->string('tempat_pertemuan');
            $table->text('alasan_panggilan');
            $table->enum('status', ['Dibuat', 'Selesai', 'Dibatalkan'])->default('Dibuat');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_panggilan');
    }
};