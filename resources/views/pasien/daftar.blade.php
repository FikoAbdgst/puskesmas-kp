@extends('layouts.app')

@section('content')
    <style>
        .registration-wrapper {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Header */
        .form-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .form-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1b1b1b;
            margin-bottom: 0.75rem;
        }

        /* Form Container */
        .form-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .form-inner {
            padding: 3rem;
        }

        /* Alert & Info Box */
        .info-alert {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 2.5rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .queue-info-box {
            background: #fff3e0;
            border: 1px solid #ffe0b2;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .info-alert-text {
            color: #0d47a1;
            line-height: 1.6;
            margin: 0;
        }

        /* Form Section */
        .form-section {
            margin-bottom: 2.5rem;
            padding-bottom: 2.5rem;
            border-bottom: 2px solid #f5f5f5;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1b1b1b;
            margin-bottom: 1.75rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .section-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 0.875rem 1.125rem;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #1b5e20;
            box-shadow: 0 0 0 4px rgba(27, 94, 32, 0.1);
        }

        /* Form Actions */
        .form-actions {
            margin-top: 2.5rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
            color: white;
            border: none;
        }

        .btn-secondary {
            background: white;
            color: #666;
            border: 2px solid #e8e8e8;
        }

        @media (max-width: 768px) {
            .form-actions {
                grid-template-columns: 1fr;
            }

            .btn-secondary {
                order: 2;
            }
        }
    </style>

    <div class="registration-wrapper">
        <div class="form-header">
            [cite_start]<h1>Booking Antrian Online</h1>
            <p>Dapatkan nomor antrian tanpa harus mengantri secara fisik [cite: 18]</p>
        </div>

        <div class="form-container">
            <div class="form-inner">
                {{-- Pesan Error/Sukses --}}
                @if (session('error'))
                    <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                @endif

                <div class="info-alert">
                    <div class="info-alert-icon">ℹ️</div>
                    <p class="info-alert-text">
                        <strong>Ketentuan Booking</strong>
                        [cite_start]Pendaftaran diproses berdasarkan kuota harian tiap poliklinik[cite: 9].
                        [cite_start]Nomor antrian otomatis dihasilkan setelah Anda mengirim form ini[cite: 11].
                    </p>
                </div>

                <form action="{{ route('pendaftaran.store') }}" method="POST">
                    @csrf

                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">👤</span>
                            <span>Data Identitas (NIK)</span>
                        </h3>

                        <div class="form-group mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="form-group">
                            <label class="form-label">NIK (Nomor Induk Kependudukan) <span
                                    class="text-danger">*</span></label>
                            <input type="number" name="nik" class="form-control" value="{{ Auth::user()->nik }}"
                                placeholder="Masukkan 16 digit NIK Anda" required maxlength="16">
                            <small class="text-muted">NIK digunakan sebagai basis autentikasi dan rekam medis[cite: 7,
                                9].</small>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">🏥</span>
                            <span>Pilih Layanan & Jadwal</span>
                        </h3>

                        <div class="form-group mb-4">
                            <label class="form-label">Poliklinik Tujuan <span class="text-danger">*</span></label>
                            <select name="poli_id" class="form-select" required>
                                <option value="">-- Pilih Poliklinik --</option>
                                @foreach ($polis as $poli)
                                    <option value="{{ $poli->id }}">
                                        {{ $poli->nama_poli }} (Sisa Kuota: {{ $poli->kuota }}) </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-4">
                            <label class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_kunjungan" class="form-control" min="{{ date('Y-m-d') }}"
                                required>
                            <small class="text-muted">Nomor antrian akan di-reset setiap hari[cite: 11].</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Keluhan Utama <span class="text-danger">*</span></label>
                            <textarea name="keluhan" class="form-control" rows="3"
                                placeholder="Jelaskan secara singkat alasan kunjungan Anda..." required></textarea>
                        </div>
                    </div>

                    {{-- Info Mekanisme Antrian --}}
                    <div class="queue-info-box">
                        <div style="font-size: 1.5rem;">🎫</div>
                        <div style="font-size: 0.9rem; color: #e65100;">
                            <strong>Mekanisme Otomatis:</strong> Sistem akan memberikan format nomor seperti
                            <strong>U-01</strong> (Umum) atau <strong>G-02</strong> (Gigi) setelah Anda menekan tombol
                            kirim[cite: 11].
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Konfirmasi Booking</button> <a
                            href="{{ route('home') }}" class="btn btn-secondary">Kembali ke Beranda</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
