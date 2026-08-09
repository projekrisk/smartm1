<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pesan_bantuan');

        Schema::create('pesan_bantuan', function (Blueprint $table) {$table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();$table->string('judul')->default('Layanan Perbaikan Data');
            $table->enum('status', ['Open', 'Diproses', 'Selesai'])->default('Open');$table->boolean('is_read_admin')->default(false);
            $table->boolean('is_read_siswa')->default(true);$table->timestamps();
        });

        Schema::create('pesan_bantuan_detail', function (Blueprint $table) {
            $table->id();$table->foreignId('pesan_bantuan_id')->constrained('pesan_bantuan')->cascadeOnDelete();
            $table->enum('pengirim', ['Siswa', 'Admin']);$table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('pesan');$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesan_bantuan_detail');
        Schema::dropIfExists('pesan_bantuan');
    }
};