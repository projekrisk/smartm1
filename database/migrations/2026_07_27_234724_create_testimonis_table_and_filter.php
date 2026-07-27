<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan kolom filter kata kasar di pengaturan
        Schema::table('pengaturan', function (Blueprint $table) {
            if (!Schema::hasColumn('pengaturan', 'filter_kata_kasar')) {
                $table->text('filter_kata_kasar')->nullable();
            }
        });

        // 2. Buat tabel testimoni/rating
        Schema::create('testimoni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->integer('rating'); // Bintang 1 - 5
            $table->text('pesan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimoni');
        Schema::table('pengaturan', function (Blueprint $table) {
            if (Schema::hasColumn('pengaturan', 'filter_kata_kasar')) {
                $table->dropColumn('filter_kata_kasar');
            }
        });
    }
};