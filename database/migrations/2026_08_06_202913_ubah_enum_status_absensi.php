<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Mengubah kolom ENUM dengan menambahkan 'Dispensasi' menggunakan perintah SQL mentah (Raw SQL)
        // Cara ini paling ampuh karena fitur ubah ENUM bawaan Laravel kadang bermasalah
        DB::statement("ALTER TABLE kehadiran_harian MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alpa', 'Dispensasi') NOT NULL DEFAULT 'Hadir'");
    }

    public function down()
    {
        // Kembalikan ke pengaturan awal jika di-rollback
        DB::statement("ALTER TABLE kehadiran_harian MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alpa') NOT NULL DEFAULT 'Hadir'");
    }
};