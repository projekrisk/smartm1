<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa_surat_dispensasi', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('surat_dispensasi_id')
                  ->constrained('surat_dispensasi')
                  ->cascadeOnDelete();
                  
            $table->foreignId('siswa_id')
                  ->constrained('siswa')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_surat_dispensasi');
    }
};