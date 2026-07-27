<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus tabel lama
        Schema::dropIfExists('kehadiran_harian');

        // 2. Tabel Master (Data per Kelas per Tanggal)
        Schema::create('rekap_kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->date('tanggal');
            
            // Validasi oleh Staf TU
            $table->boolean('is_valid')->default(false);
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });

        // 3. Tabel Detail (Daftar Siswa & Statusnya)
        Schema::create('kehadiran_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rekap_kehadiran_id')->constrained('rekap_kehadiran')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpa'])->default('Hadir');
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kehadiran_harian');
        Schema::dropIfExists('rekap_kehadiran');
    }
};