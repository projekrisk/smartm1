<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prestasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            
            $table->string('nama_prestasi'); // Cth: Juara 1 Lomba Pidato
            $table->enum('kategori', ['Akademik', 'Non-Akademik']);
            $table->enum('tingkat', ['Sekolah', 'Kabupaten/Kota', 'Provinsi', 'Nasional', 'Internasional']);
            $table->date('tanggal_perolehan');
            $table->string('penyelenggara')->nullable();
            
            // Bukti foto/pdf (Wajib untuk pengajuan siswa)
            $table->string('bukti_file')->nullable();
            
            // Sistem Approval
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');
            $table->text('catatan_admin')->nullable(); // Alasan jika ditolak
            
            // Tracking
            $table->enum('diajukan_oleh', ['Siswa', 'Admin/Staf'])->default('Siswa');
            $table->foreignId('validator_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prestasi');
    }
};