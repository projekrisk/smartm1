<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_panggilan', function (Blueprint $table) {
            $table->unsignedBigInteger('penandatangan_id')->nullable()->after('siswa_id');
        });
    }

    public function down(): void
    {
        Schema::table('surat_panggilan', function (Blueprint $table) {
            $table->dropColumn('penandatangan_id');
        });
    }
};