<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\Poli;
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
            'password' => Hash::make('admin123'),
        ]);

        User::create([
            'name' => 'Budi Santoso',
            'email' => 'pasien@gmail.com',
            'nik' => '3201123456780001',
            'role' => 'pasien',
            'password' => Hash::make('password'),
        ]);
        User::create([
            'name' => 'ardan',
            'email' => 'ardan@gmail.com',
            'nik' => '3201123456780002',
            'role' => 'pasien',
            'password' => Hash::make('password'),
        ]);
        Poli::create([
            'nama_poli' => 'Umum',
            'deskripsi' => 'Pelayanan kesehatan umum untuk semua keluhan.',
        ]);
        Poli::create([
            'nama_poli' => 'Gigi',
            'deskripsi' => 'Pelayanan kesehatan gigi dan mulut.',
        ]);
        Dokter::create([
            'poli_id' => 1,
            'nama_dokter' => 'Dr. John Doe',
            'jadwal_praktek' => 'Senin - Jumat 10:00 - 16:00',
        ]);
        Dokter::create([
            'poli_id' => 2,
            'nama_dokter' => 'Dr. Jane Smith',
            'jadwal_praktek' => 'Senin - Jumat 10:00 - 16:00',
        ]);
    }
}
