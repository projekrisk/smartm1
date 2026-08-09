<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('catatan_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            
            $table->enum('jenis_catatan', ['Positif', 'Negatif', 'Biasa']);
            $table->string('judul_catatan');
            $table->text('isi_catatan');
            $table->date('tanggal');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_siswa');
    }
};