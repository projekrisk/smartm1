<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE prestasi MODIFY COLUMN diajukan_oleh VARCHAR(255) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE prestasi MODIFY COLUMN diajukan_oleh ENUM('Siswa', 'Admin/Staf') NULL");
    }
};