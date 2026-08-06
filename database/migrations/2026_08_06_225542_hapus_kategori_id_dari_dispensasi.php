<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            // Matikan pengecekan relasi sementara agar tidak diblokir MySQL
            Schema::disableForeignKeyConstraints();
            
            // 1. Coba hapus ikatan Foreign Key (jika ada)
            try {
                $table->dropForeign(['kategori_surat_id']);
            } catch (\Exception $e) {
                // Abaikan jika ternyata tidak ada foreign key
            }

            // 2. Hapus kolom kategori_surat_id
            if (Schema::hasColumn('surat_dispensasi', 'kategori_surat_id')) {
                $table->dropColumn('kategori_surat_id');
            }

            // Nyalakan kembali pengecekan relasi
            Schema::enableForeignKeyConstraints();
        });
    }

    public function down()
    {
        // Dikosongkan saja
    }
};