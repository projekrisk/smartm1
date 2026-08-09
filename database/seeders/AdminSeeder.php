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
        User::updateOrCreate(
            ['email' => 'admin@smart-m1.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password123'),
                'peran' => 'admin',
            ]
        );
    }
}