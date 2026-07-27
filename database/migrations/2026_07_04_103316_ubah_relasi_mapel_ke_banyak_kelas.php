<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Hapus ikatan 1 kelas di tabel mata_pelajaran
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });

        // 2. Buat tabel jembatan agar 1 mapel bisa menampung banyak kelas
        Schema::create('kelas_mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_mata_pelajaran');
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
        });
    }
};