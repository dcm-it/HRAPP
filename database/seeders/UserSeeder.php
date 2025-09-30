<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat user default
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@thecustome.com',
            'password' => Hash::make('password'), // ganti dengan password aman
            'role' => 'admin',
        ]);

        // HRD
        User::create([
            'name' => 'HRD',
            'email' => 'hrd@thecustome.com',
            'password' => Hash::make('password'),
            'role' => 'hrd',
        ]);

        // Karyawan
        User::create([
            'name' => 'Karyawan',
            'email' => 'karyawan@thecustome.com',
            'password' => Hash::make('password'),
            'role' => 'karyawan',
        ]);
    }
}
