<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            // Mengubah tipe kolom menjadi string/varchar dengan panjang 50 karakter
            $table->string('jenis_nilai', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            // Opsional: kembalikan ke ENUM jika di-rollback (sesuaikan dengan ENUM lama Anda)
            $table->enum('jenis_nilai', ['Tugas', 'Ulangan Harian', 'UTS', 'UAS', 'Sikap'])->change();
        });
    }
};