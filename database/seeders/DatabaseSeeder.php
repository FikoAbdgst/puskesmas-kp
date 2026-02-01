<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin Puskesmas',
            'email' => 'admin123@gmail.com',
            'nik' => null,
            'role' => 'admin',
            'password' => Hash::make('admin123'), // Password: password
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'pasien@gmail.com',
            'nik' => '3201123456780001',
            'role' => 'pasien',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'piko',
            'email' => 'piko@gmail.com',
            'nik' => '3201123456780002',
            'role' => 'pasien',
            'password' => Hash::make('password'),
        ]);
    }
}
