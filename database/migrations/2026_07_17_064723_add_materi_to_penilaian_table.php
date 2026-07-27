<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('penilaian', function (Blueprint $table) {
            if (!Schema::hasColumn('penilaian', 'materi')) {
                // Menambahkan kolom materi setelah jenis_nilai
                $table->string('materi')->nullable()->after('jenis_nilai');
            }
        });
    }

    public function down(): void {
        Schema::table('penilaian', function (Blueprint $table) {
            if (Schema::hasColumn('penilaian', 'materi')) {
                $table->dropColumn('materi');
            }
        });
    }
};