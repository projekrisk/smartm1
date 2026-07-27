<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('kelas_mata_pelajaran');
    }

    public function down(): void
    {
        // Tidak perlu fungsi mundur
    }
};