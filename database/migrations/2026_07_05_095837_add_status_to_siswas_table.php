<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('siswa', function (Blueprint $table) {
            if (!Schema::hasColumn('siswa', 'status_siswa')) {
                $table->string('status_siswa')->default('Aktif');
            }
            
            if (!Schema::hasColumn('siswa', 'tanggal_status')) {
                $table->date('tanggal_status')->nullable();
            }
            
            if (!Schema::hasColumn('siswa', 'keterangan_status')) {
                $table->text('keterangan_status')->nullable();
            }
        });
    }

    public function down(): void {
        Schema::table('siswa', function (Blueprint $table) {
            if (Schema::hasColumn('siswa', 'tanggal_status')) {
                $table->dropColumn('tanggal_status');
            }
            if (Schema::hasColumn('siswa', 'keterangan_status')) {
                $table->dropColumn('keterangan_status');
            }
        });
    }
};