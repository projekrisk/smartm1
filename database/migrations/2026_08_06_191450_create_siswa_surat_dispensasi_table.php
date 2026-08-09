<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('siswa_surat_dispensasi');

        Schema::create('siswa_surat_dispensasi', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('surat_dispensasi_id')->index();
            $table->unsignedBigInteger('siswa_id')->index();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_surat_dispensasi');
    }
};