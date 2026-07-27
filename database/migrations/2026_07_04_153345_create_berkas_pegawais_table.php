<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan baris ini untuk menghapus paksa tabel yang "nyangkut"
        Schema::dropIfExists('berkas_pegawai');

        Schema::create('berkas_pegawai', function (Blueprint $table) {
            $table->id();
            // Sesuaikan dengan nama tabel pegawai Anda, misalnya 'pegawai' atau 'pegawais'
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->string('nama_berkas');
            $table->string('jenis_berkas');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_pegawais');
    }
};
