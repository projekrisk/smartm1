<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE kehadiran_harian MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alpa', 'Dispensasi') NOT NULL DEFAULT 'Hadir'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE kehadiran_harian MODIFY COLUMN status ENUM('Hadir', 'Sakit', 'Izin', 'Alpa') NOT NULL DEFAULT 'Hadir'");
    }
};