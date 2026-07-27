<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesan_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            
            $table->string('judul')->default('Perbaikan Data Profil');
            $table->text('pesan');
            $table->text('balasan')->nullable();
            
            $table->enum('status', ['Menunggu', 'Dibalas', 'Selesai'])->default('Menunggu');
            $table->boolean('is_read_admin')->default(false);
            $table->boolean('is_read_siswa')->default(true);
            
            $table->foreignId('dibalas_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_bantuan');
    }
};