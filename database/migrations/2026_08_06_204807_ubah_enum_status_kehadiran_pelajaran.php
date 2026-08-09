<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE kehadiran_pelajaran MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alpa', 'Terlambat', 'Dispensasi') NOT NULL DEFAULT 'Hadir'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE kehadiran_pelajaran MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alpa', 'Terlambat') NOT NULL DEFAULT 'Hadir'");
    }
};