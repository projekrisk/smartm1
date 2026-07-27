<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('catatan_siswa', function (Blueprint $table) {
            $table->enum('status_tindak_lanjut', ['Belum', 'Sudah'])->default('Belum');
            $table->text('tindak_lanjut')->nullable();
            $table->dateTime('tanggal_tindak_lanjut')->nullable();
            $table->foreignId('ditindaklanjuti_oleh')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('catatan_siswa', function (Blueprint $table) {
            $table->dropForeign(['ditindaklanjuti_oleh']);
            $table->dropColumn(['status_tindak_lanjut', 'tindak_lanjut', 'tanggal_tindak_lanjut', 'ditindaklanjuti_oleh']);
        });
    }
};