<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_dispensasi', function (Blueprint $table) {
            $table->id();
            
            $table->string('nomor_urut')->nullable();
            $table->string('nomor_surat_lengkap')->nullable();
            $table->string('nama_kegiatan')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->string('tempat')->nullable();
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->dateTime('tanggal_surat')->nullable();
            $table->unsignedBigInteger('penandatangan_id')->nullable();
            
            $table->string('token')->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_dispensasi');
    }
};