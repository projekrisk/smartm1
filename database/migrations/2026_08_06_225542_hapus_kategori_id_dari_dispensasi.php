<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('surat_dispensasi', function (Blueprint $table) {
            Schema::disableForeignKeyConstraints();
            
            try {
                $table->dropForeign(['kategori_surat_id']);
            } catch (\Exception $e) {
                // 
            }

            if (Schema::hasColumn('surat_dispensasi', 'kategori_surat_id')) {
                $table->dropColumn('kategori_surat_id');
            }

            Schema::enableForeignKeyConstraints();
        });
    }

    public function down()
    {
        // Dikosongkan
    }
};