<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat 1 akun Admin Utama
        User::updateOrCreate(
            ['email' => 'admin@smart-m1.com'], // Mencegah duplikasi jika dijalankan ulang
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'), // Passwordnya: password123
                'peran' => 'admin',
            ]
        );
    }
}