<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('surat_dispensasi')) {
            Schema::table('surat_dispensasi', function (Blueprint $table) {
                try {
                    $table->dropForeign(['kategori_surat_id']);
                } catch (\Exception $e) {}

                try {
                    if (Schema::hasColumn('surat_dispensasi', 'kategori_surat_id')) {
                        $table->dropColumn('kategori_surat_id');
                    }
                } catch (\Exception $e) {}
            });
        }

        if (Schema::hasTable('surat_dispensasis')) {
            Schema::table('surat_dispensasis', function (Blueprint $table) {
                try {
                    $table->dropForeign(['kategori_surat_id']);
                } catch (\Exception $e) {}

                try {
                    if (Schema::hasColumn('surat_dispensasis', 'kategori_surat_id')) {
                        $table->dropColumn('kategori_surat_id');
                    }
                } catch (\Exception $e) {}
            });
        }
    }

    public function down(): void
    {
    }
};