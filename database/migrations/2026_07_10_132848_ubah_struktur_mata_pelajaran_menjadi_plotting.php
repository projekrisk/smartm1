<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Melepaskan ikatan guru tunggal dari Master Mata Pelajaran
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            if (Schema::hasColumn('mata_pelajaran', 'guru_id')) {
                $table->dropForeign(['guru_id']);
                $table->dropColumn('guru_id');
            }
        });

        // 2. Menambahkan kolom guru_id ke tabel pivot agar menjadi tabel Pembagian Tugas
        Schema::table('kelas_mata_pelajaran', function (Blueprint $table) {
            if (!Schema::hasColumn('kelas_mata_pelajaran', 'guru_id')) {
                $table->foreignId('guru_id')->nullable()->constrained('users')->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        // ... (Fungsi mundur jika diperlukan)
    }
};