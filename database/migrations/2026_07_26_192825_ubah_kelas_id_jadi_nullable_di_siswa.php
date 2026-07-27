<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            // Mengubah agar kolom kelas_id boleh kosong (null) saat kenaikan kelas
            $table->unsignedBigInteger('kelas_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        // ...
    }
};