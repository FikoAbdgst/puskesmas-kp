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

        .form-header p {
            color: #666;
            font-size: 1.05rem;
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

        /* Alert */
        .info-alert {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 2.5rem;
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }

        .info-alert-icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .info-alert-text {
            color: #0d47a1;
            line-height: 1.6;
            margin: 0;
        }

        .info-alert-text strong {
            display: block;
            margin-bottom: 0.25rem;
        }

        /* Form Section */
        .form-section {
            margin-bottom: 2.5rem;
            padding-bottom: 2.5rem;
            border-bottom: 2px solid #f5f5f5;
        }

        .form-section:last-of-type {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
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
            font-size: 1.25rem;
        }

        /* Form Group */
        .form-group {
            margin-bottom: 1.75rem;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.625rem;
            font-size: 0.95rem;
        }

        .form-label .required {
            color: #dc3545;
            margin-left: 0.25rem;
        }

        .form-control,
        .form-select {
            width: 100%;
            padding: 0.875rem 1.125rem;
            font-size: 1rem;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            background: white;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            border-color: #1b5e20;
            box-shadow: 0 0 0 4px rgba(27, 94, 32, 0.1);
        }

        .form-control[readonly] {
            background: #f8f9fa;
            color: #666;
            cursor: not-allowed;
        }

        .form-text {
            display: block;
            margin-top: 0.5rem;
            color: #666;
            font-size: 0.875rem;
            line-height: 1.5;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
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
            font-size: 1rem;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(27, 94, 32, 0.3);
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #144a19 0%, #1b5e20 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(27, 94, 32, 0.4);
            color: white;
        }

        .btn-secondary {
            background: white;
            color: #666;
            border: 2px solid #e8e8e8;
        }

        .btn-secondary:hover {
            background: #f8f9fa;
            border-color: #ddd;
            color: #333;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-inner {
                padding: 2rem 1.5rem;
            }

            .form-header h1 {
                font-size: 1.5rem;
            }

            .form-actions {
                grid-template-columns: 1fr;
            }

            .btn-secondary {
                order: 2;
            }
        }

        @media (max-width: 576px) {
            .registration-wrapper {
                padding: 0 0.5rem;
            }

            .form-inner {
                padding: 1.5rem 1rem;
            }

            .section-title {
                font-size: 1.1rem;
            }

            .info-alert {
                padding: 1rem;
                flex-direction: column;
            }
        }
    </style>

    <div class="registration-wrapper">
        <div class="form-header">
            <h1>Form Pendaftaran Pasien</h1>
            <p>Lengkapi formulir berikut untuk mendaftar layanan kesehatan</p>
        </div>

        <div class="form-container">
            <div class="form-inner">
                <div class="info-alert">
                    <div class="info-alert-icon">ℹ️</div>
                    <p class="info-alert-text">
                        <strong>Perhatian</strong>
                        Pastikan semua data yang Anda masukkan sudah benar dan sesuai. Pendaftaran akan diproses oleh
                        petugas administrasi kami.
                    </p>
                </div>

                <form action="{{ route('pendaftaran.store') }}" method="POST">
                    @csrf

                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">👤</span>
                            <span>Data Pasien</span>
                        </h3>

                        <div class="form-group">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            <small class="form-text">Nama tidak dapat diubah. Hubungi administrator jika ada
                                kesalahan.</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                NIK / Nomor Rekam Medis<span class="required">*</span>
                            </label>
                            <input type="number" name="nik" class="form-control" value="{{ Auth::user()->nik }}"
                                placeholder="Masukkan 16 digit NIK Anda" required maxlength="16">
                            <small class="form-text">NIK digunakan untuk validasi data dan keperluan administrasi.</small>
                        </div>
                    </div>

                    <div class="form-section">
                        <h3 class="section-title">
                            <span class="section-icon">🏥</span>
                            <span>Informasi Kunjungan</span>
                        </h3>

                        <div class="form-group">
                            <label class="form-label">
                                Poliklinik Tujuan<span class="required">*</span>
                            </label>
                            <select name="poli_id" class="form-select" required>
                                <option value="">-- Pilih Poliklinik --</option>
                                @foreach ($polis as $poli)
                                    <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                @endforeach
                            </select>
                            <small class="form-text">Pilih poliklinik sesuai dengan keluhan kesehatan Anda.</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Tanggal Kunjungan<span class="required">*</span>
                            </label>
                            <input type="date" name="tanggal_kunjungan" class="form-control" min="{{ date('Y-m-d') }}"
                                required>
                            <small class="form-text">Pilih tanggal kunjungan yang Anda inginkan.</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">
                                Keluhan<span class="required">*</span>
                            </label>
                            <textarea name="keluhan" class="form-control" placeholder="Jelaskan keluhan kesehatan yang Anda alami secara detail..."
                                required></textarea>
                            <small class="form-text">Jelaskan keluhan Anda dengan jelas agar dokter dapat memberikan
                                penanganan yang tepat.</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Kirim Pendaftaran</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
