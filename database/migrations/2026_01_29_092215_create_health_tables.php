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
            $table->string('nama_poli');
            $table->integer('kuota');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel Dokter
        Schema::create('dokters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poli_id')->constrained('polis');
            $table->string('nama_dokter');
            $table->string('jadwal_praktek');
            $table->timestamps();
        });

        Schema::create('pendaftarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('poli_id')->constrained('polis');
            $table->foreignId('dokter_id')->nullable()->constrained('dokters');
            $table->date('tanggal_kunjungan');
            $table->string('nomor_antrian')->nullable();
            $table->text('keluhan');
            $table->string('status')->default('Menunggu');
            $table->text('catatan_medis')->nullable();
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
