<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data user yang sudah ada sebelumnya
        DB::table('users')->delete();

        // Reset auto increment
        DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');

        DB::table('users')->insert([
            [
                'username' => 'admin',
                'password' => Hash::make('password123'), // Password baru
                'role' => 'admin',
                'nama' => 'Administrator',
                'bidang' => 'Umum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'user',
                'password' => Hash::make('user12345'), // Password baru
                'role' => 'user', 
                'nama' => 'Regular User',
                'bidang' => 'Pertanian',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}