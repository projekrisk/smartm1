<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kita ubah nama tabelnya menjadi 'tahun_ajaran' tanpa 's'
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama_tahun'); // Contoh: "2026/2027"
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('is_active')->default(false); // Untuk menandai tahun ajaran aktif
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};