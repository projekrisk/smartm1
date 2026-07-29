<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawai', 'no_rekening')) {
                $table->string('no_rekening')->nullable()->after('email');
            }
            if (!Schema::hasColumn('pegawai', 'nama_bank')) {
                $table->string('nama_bank')->nullable()->after('no_rekening');
            }
            if (!Schema::hasColumn('pegawai', 'alamat')) {
                $table->text('alamat')->nullable()->after('nama_bank');
            }
            if (!Schema::hasColumn('pegawai', 'rt')) {
                $table->string('rt')->nullable()->after('alamat');
            }
            if (!Schema::hasColumn('pegawai', 'rw')) {
                $table->string('rw')->nullable()->after('rt');
            }
            if (!Schema::hasColumn('pegawai', 'kelurahan')) {
                $table->string('kelurahan')->nullable()->after('rw');
            }
            if (!Schema::hasColumn('pegawai', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('kelurahan');
            }
            if (!Schema::hasColumn('pegawai', 'kabupaten')) {
                $table->string('kabupaten')->nullable()->after('kecamatan');
            }
        });
    }

    public function down(): void
    {
        // 
    }
};