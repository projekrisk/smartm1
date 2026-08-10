<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keterangan_aktif', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_surat');
            $table->string('nomor_urut')->nullable();
            $table->string('nomor_surat')->unique();
            $table->unsignedBigInteger('siswa_id')->index();
            $table->unsignedBigInteger('penandatangan_id')->index();
            $table->string('tahun_ajaran')->nullable();
            $table->string('token')->nullable();
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keterangan_aktif');
    }
};