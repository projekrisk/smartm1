<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        // Berbicara langsung dengan MySQL untuk memaksa kolom menjadi teks bebas (VARCHAR)
        DB::statement("ALTER TABLE siswa MODIFY status_siswa VARCHAR(255) DEFAULT 'Aktif'");
    }

    public function down(): void {
        // Tidak perlu aksi mundur
    }
};