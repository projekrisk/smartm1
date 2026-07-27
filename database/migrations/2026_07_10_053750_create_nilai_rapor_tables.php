<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom kode_pelajaran ke tabel mata_pelajaran (Untuk kunci baca Excel)
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_pelajaran', 'kode_pelajaran')) {
                $table->string('kode_pelajaran')->nullable()->unique()->after('id');
            }
        });

        // 2. Buat tabel khusus Nilai Rapor Akhir Semester
        Schema::create('nilai_rapor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            
            $table->integer('nilai_akhir'); // Angka 0 - 100
            $table->string('predikat')->nullable(); // A, B, C, D
            $table->text('deskripsi')->nullable(); // Catatan capaian kompetensi
            
            $table->timestamps();

            // Mencegah 1 siswa punya 2 nilai untuk mapel yang sama di semester yang sama
            $table->unique(['siswa_id', 'mata_pelajaran_id', 'tahun_ajaran_id'], 'nilai_rapor_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_rapor');
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            if (Schema::hasColumn('mata_pelajaran', 'kode_pelajaran')) {
                $table->dropColumn('kode_pelajaran');
            }
        });
    }
};