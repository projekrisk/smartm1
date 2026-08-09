<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mata_pelajaran', function (Blueprint $table) {
            if (!Schema::hasColumn('mata_pelajaran', 'kode_pelajaran')) {
                $table->string('kode_pelajaran')->nullable()->unique()->after('id');
            }
        });

        Schema::create('nilai_rapor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            
            $table->integer('nilai_akhir');
            $table->string('predikat')->nullable();
            $table->text('deskripsi')->nullable();
            
            $table->timestamps();

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