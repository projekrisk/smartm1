<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catatan_siswa', function (Blueprint $table) {
            // Tambahkan kolom penanda sudah dibaca (default: false / belum)
            if (!Schema::hasColumn('catatan_siswa', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('isi_catatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('catatan_siswa', function (Blueprint $table) {
            if (Schema::hasColumn('catatan_siswa', 'is_read')) {
                $table->dropColumn('is_read');
            }
        });
    }
};