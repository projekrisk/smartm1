<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'jalur_masuk')) {
                $table->enum('jalur_masuk', ['Siswa Baru', 'Mutasi Masuk'])
                      ->default('Siswa Baru')
                      ->after('status_siswa');
            }
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (Schema::hasColumn('siswa', 'jalur_masuk')) {
                $table->dropColumn('jalur_masuk');
            }
        });
    }
};