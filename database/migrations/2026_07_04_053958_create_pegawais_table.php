<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('nik')->unique();
            
            $table->string('nip')->nullable();
            $table->string('nuptk')->nullable();
            $table->string('pangkat_golongan')->nullable();
            $table->string('jabatan')->nullable();
            $table->enum('status_kepegawaian', ['Guru', 'Staf', 'Keamanan', 'Lainnya']);
            $table->string('tugas_utama');
            $table->json('tugas_tambahan')->nullable();
            
            $table->date('tmt_cpns_honorer')->nullable();
            $table->date('tmt_pns_pppk')->nullable();
            $table->date('tmt_golongan_terakhir')->nullable();
            
            $table->string('pendidikan_ijazah')->nullable();
            $table->string('pendidikan_jurusan')->nullable();
            $table->string('pendidikan_tahun')->nullable();
            
            $table->string('email')->unique();
            $table->string('telepon')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};