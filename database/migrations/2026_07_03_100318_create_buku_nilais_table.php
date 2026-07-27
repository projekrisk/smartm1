<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_nilai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete(); // Nilai dikunci per semester
            
            $table->enum('jenis_nilai', ['Tugas', 'Ulangan Harian', 'UTS', 'UAS', 'Sikap']);
            $table->decimal('nilai', 5, 2); // Mendukung nilai desimal seperti 85.50
            $table->text('catatan_guru')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_nilai');
    }
};