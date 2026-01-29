<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tabel Poli
        Schema::create('polis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_poli'); // Contoh: Poli Gigi, Poli Umum
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel Dokter
        Schema::create('dokters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poli_id')->constrained('polis');
            $table->string('nama_dokter');
            $table->string('jadwal_praktek'); // Contoh: Senin - Jumat (08:00 - 12:00)
            $table->timestamps();
        });

        // Tabel Pendaftaran (Inti Alur)
        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users'); // Relasi ke Pasien
            $table->foreignId('poli_id')->constrained('polis'); // Poli Tujuan
            $table->foreignId('dokter_id')->nullable()->constrained('dokters'); // Dokter (Bisa dipilih nanti/otomatis)
            $table->date('tanggal_kunjungan');
            $table->text('keluhan');
            $table->string('status')->default('pending'); // pending, verified, examined, done
            $table->text('catatan_medis')->nullable(); // Diisi dokter/admin setelah pemeriksaan
            $table->text('resep_obat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_tables');
    }
};
