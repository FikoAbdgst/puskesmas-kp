<?php

namespace Database\Seeders;

use App\Models\Dokter;
use App\Models\Poli;
use App\Models\User;
use App\Models\Pendaftaran; // Tambahkan ini
use Carbon\Carbon;          // Tambahkan ini
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
        // 1. Data User / Admin / Pasien
        User::create([
            'name' => 'Admin Puskesmas',
            'email' => 'admin123@gmail.com',
            'nik' => null,
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);

        $pasien1 = User::create([
            'name' => 'Budi Santoso',
            'email' => 'pasien@gmail.com',
            'nik' => '3201123456780001',
            'role' => 'pasien',
            'password' => Hash::make('password'),
        ]);

        $pasien2 = User::create([
            'name' => 'ardan',
            'email' => 'ardan@gmail.com',
            'nik' => '3201123456780002',
            'role' => 'pasien',
            'password' => Hash::make('password'),
        ]);

        // 2. Data Poli
        $poliUmum = Poli::create([
            'nama_poli' => 'Umum',
            'deskripsi' => 'Pelayanan kesehatan umum untuk semua keluhan.',
        ]);

        $poliGigi = Poli::create([
            'nama_poli' => 'Gigi',
            'deskripsi' => 'Pelayanan kesehatan gigi dan mulut.',
        ]);

        // 3. Data Dokter
        $dokterUmum = Dokter::create([
            'poli_id' => $poliUmum->id,
            'nama_dokter' => 'Dr. John Doe',
            'jadwal_praktek' => 'Senin - Jumat 10:00 - 16:00',
        ]);

        $dokterGigi = Dokter::create([
            'poli_id' => $poliGigi->id,
            'nama_dokter' => 'Dr. Jane Smith',
            'jadwal_praktek' => 'Senin - Jumat 10:00 - 16:00',
        ]);

        // 4. DATA DUMMY PENDAFTARAN (UNTUK TES LAPORAN)
        // ==============================================

        // Data 1: Bulan lalu (Misal sekarang Feb, ini jadi Januari)
        Pendaftaran::create([
            'user_id' => $pasien1->id,
            'poli_id' => $poliUmum->id,
            'dokter_id' => $dokterUmum->id,
            'tanggal_kunjungan' => Carbon::now()->subMonth()->format('Y-m-15'), // Tanggal 15 bulan lalu
            'nomor_antrian' => 'U-01',
            'keluhan' => 'Demam tinggi dan pusing sejak 3 hari yang lalu',
            'status' => 'Selesai',
            'catatan_medis' => 'Gejala tipes. Disarankan istirahat total dan diberikan resep Paracetamol & Antibiotik.',
        ]);

        // Data 2: Bulan lalu (Misal sekarang Feb, ini jadi Januari)
        Pendaftaran::create([
            'user_id' => $pasien2->id,
            'poli_id' => $poliGigi->id,
            'dokter_id' => $dokterGigi->id,
            'tanggal_kunjungan' => Carbon::now()->subMonth()->format('Y-m-18'), // Tanggal 18 bulan lalu
            'nomor_antrian' => 'G-01',
            'keluhan' => 'Gigi geraham belakang berlubang dan ngilu saat makan',
            'status' => 'Selesai',
            'catatan_medis' => 'Karies gigi geraham. Telah dilakukan penambalan sementara.',
        ]);

        // Data 3: Dua Bulan lalu (Misal sekarang Feb, ini jadi Desember tahun lalu)
        Pendaftaran::create([
            'user_id' => $pasien1->id,
            'poli_id' => $poliGigi->id,
            'dokter_id' => $dokterGigi->id,
            'tanggal_kunjungan' => Carbon::now()->subMonths(2)->format('Y-m-10'), // Tanggal 10 dua bulan lalu
            'nomor_antrian' => 'G-02',
            'keluhan' => 'Pembersihan karang gigi rutin',
            'status' => 'Selesai',
            'catatan_medis' => 'Telah dilakukan scaling. Gusi sedikit meradang tapi normal.',
        ]);

        // Data 4: Bulan ini (Hari ini, untuk membuktikan filter bulan ini tidak bisa di-print)
        Pendaftaran::create([
            'user_id' => $pasien2->id,
            'poli_id' => $poliUmum->id,
            'dokter_id' => $dokterUmum->id,
            'tanggal_kunjungan' => Carbon::now()->format('Y-m-d'), // Hari ini
            'nomor_antrian' => 'U-02',
            'keluhan' => 'Batuk berdahak dan pilek',
            'status' => 'Selesai',
            'catatan_medis' => 'ISPA ringan. Diberikan obat batuk sirup dan vitamin C.',
        ]);
    }
}
