@extends('layouts.app')

@section('content')
    <style>
        .admin-sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .sidebar-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            color: white;
            padding: 1.25rem;
            font-weight: 600;
            text-align: center;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
        }

        .sidebar-section-title {
            background-color: #f8f9fa;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid #e9ecef;
        }

        .sidebar-link {
            padding: 1rem 1.25rem;
            color: #495057;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
            border-left: 3px solid transparent;
            font-weight: 500;
        }

        .sidebar-link:hover {
            background-color: #f8f9fa;
            color: var(--primary-green);
            border-left-color: var(--accent-green);
        }

        .sidebar-link.active {
            background-color: #e8f5e9;
            color: var(--primary-green);
            border-left-color: var(--primary-green);
            font-weight: 600;
        }

        .sidebar-link .icon {
            font-size: 1.25rem;
            width: 24px;
            text-align: center;
        }

        .stat-card {
            border-radius: 12px;
            padding: 1.75rem;
            color: white;
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .stat-card h3 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card small {
            font-size: 0.95rem;
            opacity: 0.95;
            font-weight: 500;
        }

        .stat-card.warning {
            background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%);
        }

        .stat-card.primary {
            background: linear-gradient(135deg, #1976d2 0%, #2196f3 100%);
        }

        .stat-card.success {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
        }

        .data-table-wrapper {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .data-table-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .table-container {
            padding: 1.5rem;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge-poli {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .btn-validasi {
            background: linear-gradient(135deg, var(--accent-green) 0%, #66bb6a 100%);
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-validasi:hover {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--accent-green) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(76, 175, 80, 0.3);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6c757d;
        }

        .empty-state-icon {
            font-size: 4rem;
            opacity: 0.3;
            margin-bottom: 1rem;
        }
    </style>

    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="admin-sidebar">
                <div class="sidebar-header">
                    PANEL ADMINISTRASI
                </div>

                <div class="sidebar-section-title">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <span class="icon">✅</span>
                    <span>Verifikasi Pendaftaran</span>
                </a>
                <a href="{{ route('admin.pelayanan') }}"
                    class="sidebar-link {{ request()->routeIs('admin.pelayanan') ? 'active' : '' }}">
                    <span class="icon">🩺</span>
                    <span>Pelayanan Dokter</span>
                </a>

                <div class="sidebar-section-title">Data Master</div>
                <a href="{{ route('poli.index') }}"
                    class="sidebar-link {{ request()->routeIs('poli.index') ? 'active' : '' }}">
                    <span class="icon">🏥</span>
                    <span>Data Poli</span>
                </a>
                <a href="{{ route('dokter.index') }}"
                    class="sidebar-link {{ request()->routeIs('dokter.index') ? 'active' : '' }}">
                    <span class="icon">👨‍⚕️</span>
                    <span>Data Dokter</span>
                </a>

                <div class="sidebar-section-title">Laporan</div>
                <a href="{{ route('admin.laporan') }}"
                    class="sidebar-link {{ request()->routeIs('admin.laporan') ? 'active' : '' }}">
                    <span class="icon">📄</span>
                    <span>Laporan Pelayanan</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <!-- Statistics Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card warning">
                        <h3 id="stat-pending">{{ $stats['pending'] }}</h3>
                        <small>Menunggu Verifikasi</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card primary">
                        <h3 id="stat-verified">{{ $stats['verified'] }}</h3>
                        <small>Siap Diperiksa</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card success">
                        <h3 id="stat-done">{{ $stats['done'] }}</h3>
                        <small>Selesai Dilayani</small>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="data-table-wrapper">
                <div class="data-table-header">
                    📋 Daftar Pendaftaran Pasien (Perlu Verifikasi)
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Poli</th>
                                    <th>Keluhan</th>
                                    <th>Tanggal</th>
                                    <th style="width: 150px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="table-pending-admin">
                                @include('admin.partials._pending_table')
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let lastDataCount = {{ $pending->count() }};

        function checkNewRegistration() {
            $.ajax({
                url: "{{ route('admin.pending.json') }}",
                type: "GET",
                success: function(response) {
                    $('#stat-pending').text(response.stats.pending);
                    $('#stat-verified').text(response.stats.verified);
                    $('#stat-done').text(response.stats.done);

                    if (response.data.length > lastDataCount) {
                        console.log("Ada pendaftaran baru!");
                    }

                    lastDataCount = response.data.length;

                    let rows = '';
                    if (response.data.length > 0) {
                        response.data.forEach(function(p) {
                            rows += `
                            <tr>
                                <td>
                                    <div><strong style="color: #1b5e20;">${p.user.name}</strong></div>
                                    <small class="text-muted">NIK: ${p.user.nik || '-'}</small>
                                </td>
                                <td><span class="badge bg-success badge-poli">${p.poli.nama_poli}</span></td>
                                <td><small>${p.keluhan}</small></td>
                                <td><small>${p.tanggal_kunjungan}</small></td>
                                <td>
                                    <form action="/admin/verifikasi/${p.id}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-validasi btn-sm">✅ Validasi</button>
                                    </form>
                                </td>
                            </tr>`;
                        });
                    } else {
                        rows = `
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <div class="empty-state-icon">📋</div>
                                    <div>Tidak ada pendaftaran yang perlu diverifikasi</div>
                                </td>
                            </tr>`;
                    }

                    $('#table-pending-admin').html(rows);
                }
            });
        }

        setInterval(checkNewRegistration, 3000);
    </script>
@endsection
