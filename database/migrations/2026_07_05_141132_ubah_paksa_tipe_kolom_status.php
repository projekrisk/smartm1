<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        DB::statement("ALTER TABLE siswa MODIFY status_siswa VARCHAR(255) DEFAULT 'Aktif'");
    }

    public function down(): void {
        //
    }
};