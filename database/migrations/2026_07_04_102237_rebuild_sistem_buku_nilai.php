<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hapus tabel lama yang strukturnya kurang tepat
        Schema::dropIfExists('buku_nilai');

        // 1. Buat Tabel Master (Kegiatan Penilaian / Header)
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->enum('jenis_nilai', ['Tugas', 'Ulangan Harian', 'UTS', 'UAS', 'Sikap']);
            $table->date('tanggal_penilaian');
            $table->timestamps();
        });

        // 2. Buat Tabel Detail (Nilai Siswa per Penilaian / Line)
        Schema::create('buku_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained('penilaian')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2)->nullable(); // Bisa kosong jika belum dinilai
            $table->text('catatan_guru')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_nilai');
        Schema::dropIfExists('penilaian');
    }
};