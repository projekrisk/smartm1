<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            if (!Schema::hasColumn('pegawai', 'no_kk')) {
                $table->string('no_kk')->nullable()->after('nik');
            }
            if (!Schema::hasColumn('pegawai', 'npwp')) {
                $table->string('npwp')->nullable()->after('no_kk');
            }
            if (!Schema::hasColumn('pegawai', 'jenis_ptk')) {
                $table->string('jenis_ptk')->default('Guru')->after('email');
            }
            
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE pegawai MODIFY status_kepegawaian VARCHAR(50) DEFAULT 'PNS'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            //
        });
    }
};
