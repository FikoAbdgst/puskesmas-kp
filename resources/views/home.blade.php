@extends('layouts.app')

@section('content')
    <style>
        .patient-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
            border-radius: 20px;
            padding: 3rem 2.5rem;
            margin-bottom: 3rem;
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(27, 94, 32, 0.25);
        }

        .welcome-banner::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }

        .welcome-banner::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
        }

        .welcome-content h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
        }

        .welcome-content p {
            font-size: 1.1rem;
            opacity: 0.95;
            margin: 0;
        }

        /* Quick Actions Grid */
        .quick-actions {
            margin-bottom: 3rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
        }

        .action-card {
            background: white;
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .action-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .action-card-top {
            padding: 2.5rem 2rem 2rem;
            flex-grow: 1;
            background: linear-gradient(to bottom, #ffffff 0%, #fafafa 100%);
        }

        .action-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.15);
        }

        .action-card.featured .action-icon {
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
            box-shadow: 0 4px 12px rgba(27, 94, 32, 0.25);
        }

        .action-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #1b1b1b;
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .action-description {
            color: #666;
            line-height: 1.7;
            margin: 0;
            font-size: 0.95rem;
        }

        .action-card-bottom {
            padding: 1.75rem 2rem;
            background: white;
        }

        .action-btn {
            display: block;
            width: 100%;
            padding: 1rem 1.5rem;
            background: white;
            color: #1b5e20;
            border: 2px solid #1b5e20;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .action-btn:hover {
            background: #1b5e20;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(27, 94, 32, 0.25);
        }

        .action-card.featured .action-btn {
            background: #1b5e20;
            color: white;
            border-color: #1b5e20;
        }

        .action-card.featured .action-btn:hover {
            background: #144a19;
            border-color: #144a19;
            box-shadow: 0 6px 16px rgba(20, 74, 25, 0.35);
        }

        /* Registration Status Section */
        .status-section {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .status-header {
            padding: 2rem 2.5rem;
            background: linear-gradient(to right, #fafafa 0%, #f5f5f5 100%);
        }

        .status-header h4 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 700;
            color: #1b1b1b;
        }

        .status-body {
            padding: 0;
        }

        .status-table {
            width: 100%;
            margin: 0;
        }

        .status-table thead th {
            background: #fafafa;
            padding: 1.5rem 2.5rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #666;
            border-bottom: 2px solid #f0f0f0;
            letter-spacing: 0.5px;
        }

        .status-table tbody td {
            padding: 1.75rem 2.5rem;
            border-bottom: 1px solid #f8f8f8;
            color: #333;
            vertical-align: middle;
        }

        .status-table tbody tr:last-child td {
            border-bottom: none;
        }

        .status-table tbody tr:hover {
            background: #fafafa;
        }

        .status-date {
            font-weight: 600;
            color: #1b1b1b;
        }

        .status-poli {
            color: #1b5e20;
            font-weight: 600;
        }

        .status-keluhan {
            color: #666;
            font-size: 0.9rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            white-space: nowrap;
        }

        /* Update Warna Status Sesuai Dokumen Desain */
        .status-pending {
            background: #fff3e0;
            color: #e65100;
        }

        /* Menunggu */
        .status-verified {
            background: #e8f5e9;
            color: #2e7d32;
        }

        /* Diterima */
        .status-cancelled {
            background: #ffebee;
            color: #c62828;
        }

        /* Dibatalkan */
        .status-done {
            background: #f5f5f5;
            color: #616161;
        }

        /* Selesai */

        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-icon {
            font-size: 4rem;
            opacity: 0.2;
            margin-bottom: 1rem;
        }

        .empty-text {
            color: #999;
            font-size: 1rem;
        }

        /* Loading State */
        .loading-state {
            padding: 3rem 2rem;
            text-align: center;
        }

        .loading-spinner {
            display: inline-block;
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #1b5e20;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            margin-top: 1rem;
            color: #666;
        }

        /* Responsive Design (Keep Original) */
        @media (max-width: 768px) {
            .welcome-banner {
                padding: 2rem 1.5rem;
            }

            .welcome-content h1 {
                font-size: 1.5rem;
            }

            .actions-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .status-table thead {
                display: none;
            }

            .status-table tbody tr {
                display: block;
                margin-bottom: 1rem;
                border: 1px solid #f0f0f0;
                border-radius: 16px;
                overflow: hidden;
                background: white;
            }

            .status-table tbody td {
                display: block;
                padding: 1rem 1.5rem;
                text-align: left;
                border: none;
            }

            .status-table tbody td:before {
                content: attr(data-label);
                font-weight: 700;
                font-size: 0.75rem;
                text-transform: uppercase;
                color: #666;
                display: block;
                margin-bottom: 0.5rem;
            }

            .status-table tbody td:first-child {
                background: #fafafa;
                font-weight: 600;
            }
        }
    </style>

    <div class="patient-container">
        <div class="welcome-banner">
            <div class="welcome-content">
                <h1>Selamat Datang, {{ Auth::user()->name }}</h1>
                <p>Akses layanan kesehatan dengan mudah dan cepat</p>
            </div>
        </div>

        <div class="quick-actions">
            <div class="actions-grid">
                <div class="action-card featured">
                    <div class="action-card-top">
                        <div class="action-icon">📋</div>
                        <h3 class="action-title">Pendaftaran Pasien</h3>
                        <p class="action-description">Daftarkan diri Anda untuk mendapatkan layanan kesehatan di poliklinik
                            yang tersedia</p>
                    </div>
                    <div class="action-card-bottom">
                        <a href="{{ route('pendaftaran.create') }}" class="action-btn">Daftar Sekarang</a>
                    </div>
                </div>

                <div class="action-card">
                    <div class="action-card-top">
                        <div class="action-icon">👨‍⚕️</div>
                        <h3 class="action-title">Jadwal Dokter</h3>
                        <p class="action-description">Lihat jadwal praktik dokter untuk merencanakan kunjungan Anda dengan
                            lebih baik</p>
                    </div>
                    <div class="action-card-bottom">
                        <a href="{{ route('pasien.jadwal') }}" class="action-btn">Lihat Jadwal</a>
                    </div>
                </div>

                <div class="action-card">
                    <div class="action-card-top">
                        <div class="action-icon">🏥</div>
                        <h3 class="action-title">Informasi Poliklinik</h3>
                        <p class="action-description">Temukan informasi lengkap tentang layanan poliklinik yang tersedia</p>
                    </div>
                    <div class="action-card-bottom">
                        <a href="{{ route('pasien.poli') }}" class="action-btn">Lihat Informasi</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="status-section">
            <div class="status-header">
                <h4>Riwayat Pendaftaran</h4>
            </div>
            <div class="status-body">
                <div class="table-responsive">
                    <table class="status-table">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Poliklinik</th>
                                <th>Antrian</th>
                                <th>Keluhan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="table-status-pasien">
                            <tr>
                                <td colspan="5">
                                    <div class="loading-state">
                                        <div class="loading-spinner"></div>
                                        <div class="loading-text">Memuat data pendaftaran...</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function refreshPasienStatus() {
            $.ajax({
                url: "{{ route('pendaftaran.json') }}",
                type: "GET",
                success: function(data) {
                    let rows = '';
                    if (data.length > 0) {
                        data.forEach(function(r) {
                            let badge = '';
                            let statusText = r
                                .status; // Sesuai database (Menunggu, Diterima, Dibatalkan, Selesai)

                            // Logika Mapping Status Sesuai Desain Word [cite: 1, 16]
                            if (r.status === 'Menunggu') {
                                badge = 'status-pending';
                            } else if (r.status === 'Diterima') {
                                badge = 'status-verified';
                            } else if (r.status === 'Dibatalkan') {
                                badge = 'status-cancelled';
                            } else if (r.status === 'Selesai') {
                                badge = 'status-done';
                            } else {
                                // Fallback jika status masih menggunakan format lama (pending/verified)
                                badge = r.status === 'pending' ? 'status-pending' : 'status-done';
                                statusText = r.status === 'pending' ? 'Menunggu' : 'Selesai';
                            }

                            rows += `
                                <tr>
                                    <td data-label="Tanggal" class="status-date">${r.tanggal_kunjungan}</td>
                                    <td data-label="Poliklinik" class="status-poli">${r.poli.nama_poli}</td>
                                    <td data-label="Antrian" class="fw-bold text-dark">${r.nomor_antrian || '-'}</td>
                                    <td data-label="Keluhan" class="status-keluhan">${r.keluhan}</td>
                                    <td data-label="Status">
                                        <span class="status-badge ${badge}">
                                            <span>${statusText}</span>
                                        </span>
                                    </td>
                                </tr>`;
                        });
                    } else {
                        rows =
                            `<tr><td colspan="5"><div class="empty-state"><div class="empty-icon">📋</div><div class="empty-text">Belum ada riwayat pendaftaran</div></div></td></tr>`;
                    }
                    $('#table-status-pasien').html(rows);
                }
            });
        }

        refreshPasienStatus();
        setInterval(refreshPasienStatus, 5000);
    </script>
@endsection
