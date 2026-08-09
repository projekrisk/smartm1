<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prestasi', function (Blueprint $table) {
            if (!Schema::hasColumn('prestasi', 'juara')) {
                $table->string('juara')->after('nama_prestasi')->default('Juara 1');
            }
            if (!Schema::hasColumn('prestasi', 'jenis')) {
                $table->string('jenis')->after('juara')->default('Individu');
            }
        });

        DB::statement("ALTER TABLE prestasi MODIFY tingkat VARCHAR(255)");
    }

    public function down(): void
    {
        Schema::table('prestasi', function (Blueprint $table) {
            if (Schema::hasColumn('prestasi', 'juara')) {
                $table->dropColumn('juara');
            }
            if (Schema::hasColumn('prestasi', 'jenis')) {
                $table->dropColumn('jenis');
            }
        });
    }
};