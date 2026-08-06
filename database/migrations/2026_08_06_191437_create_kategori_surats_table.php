<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kategori_surat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori'); // Cth: Surat Kesiswaan
            $table->string('kode_prefix');   // Cth: 400.03.08
            $table->string('kode_suffix')->default('SMA.01-MLP'); // Cth: SMA.01-MLP
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_surats');
    }
};
