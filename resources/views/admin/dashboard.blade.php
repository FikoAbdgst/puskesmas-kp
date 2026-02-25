@extends('layouts.app')

@section('content')
    <style>
        /* (CSS LAMA ANDA) */
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
        }

        .sidebar-section-title {
            background-color: #f8f9fa;
            padding: 0.75rem 1.25rem;
            font-weight: 600;
            font-size: 0.85rem;
            color: #6c757d;
            text-transform: uppercase;
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
            margin-bottom: 2rem;
        }

        .data-table-header {
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
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
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .badge-poli {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .status-banner {
            background: #fff;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 5px solid var(--primary-green);
        }

        .status-label {
            font-weight: 600;
            color: #495057;
        }

        /* WARNA HEADER TABEL CUSTOM */
        .header-urgent {
            background: linear-gradient(135deg, #d32f2f 0%, #c62828 100%);
        }

        /* Merah */
        .header-future {
            background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
        }

        /* Biru */
        .header-verified {
            background: linear-gradient(135deg, #455a64 0%, #37474f 100%);
        }

        /* Abu-abu */
    </style>



    <div class="row g-4">
        {{-- SIDEBAR AREA --}}
        <div class="col-lg-3">
            <div class="admin-sidebar">
                <div class="sidebar-header">PANEL ADMINISTRASI</div>
                <div class="sidebar-section-title">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link active">
                    <span class="icon">✅</span><span>Verifikasi Pendaftaran</span>
                </a>
                <a href="{{ route('admin.pelayanan') }}" class="sidebar-link">
                    <span class="icon">🩺</span><span>Pelayanan Dokter</span>
                </a>
                <div class="sidebar-section-title">Data Master</div>
                <a href="{{ route('poli.index') }}" class="sidebar-link">
                    <span class="icon">🏥</span><span>Data Poli</span>
                </a>
                <a href="{{ route('dokter.index') }}" class="sidebar-link">
                    <span class="icon">👨‍⚕️</span><span>Data Dokter</span>
                </a>
                <div class="sidebar-section-title">Laporan</div>
                <a href="{{ route('admin.laporan') }}" class="sidebar-link">
                    <span class="icon">📄</span><span>Laporan Pelayanan</span>
                </a>
            </div>
        </div>

        {{-- CONTENT AREA --}}
        <div class="col-lg-9">

            {{-- Status Banner --}}
            <div class="status-banner">
                <div>
                    <span class="status-label">Status Puskesmas: </span>
                    @if ($isOpen == '1')
                        <span class="badge bg-success">BUKA</span>
                    @else
                        <span class="badge bg-danger">TUTUP</span>
                    @endif
                </div>
                <form action="{{ route('admin.toggle-open') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="btn {{ $isOpen == '1' ? 'btn-outline-danger' : 'btn-success' }} btn-sm fw-bold">
                        {{ $isOpen == '1' ? '🔴 Tutup Puskesmas' : '🟢 Buka Puskesmas' }}
                    </button>
                </form>
            </div>

            {{-- Statistik --}}
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card warning">
                        <h3 id="stat-pending">{{ $stats['pending'] }}</h3><small>Total Menunggu</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card primary">
                        <h3 id="stat-verified">{{ $stats['verified'] }}</h3><small>Pasien Hari Ini (OK)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card success">
                        <h3 id="stat-done">{{ $stats['done'] }}</h3><small>Selesai Dilayani</small>
                    </div>
                </div>
            </div>

            {{-- TABLE 1: HARI INI (URGENT) --}}
            <div class="data-table-wrapper">
                <div class="data-table-header header-urgent">
                    <span>🔥 PERLU VERIFIKASI (HARI INI: {{ date('d M Y') }})</span>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Pasien</th>
                                    <th>Poli</th>
                                    <th>Keluhan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pending_today as $p)
                                    <tr style="background-color: #fff8f8;">
                                        <td><strong>{{ $p->user->name }}</strong><br><small class="text-muted">NIK:
                                                {{ $p->user->nik }}</small></td>
                                        <td><span class="badge bg-danger">{{ $p->poli->nama_poli }}</span></td>
                                        <td><small>{{ Str::limit($p->keluhan, 40) }}</small></td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <form action="{{ route('admin.verifikasi', $p->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-primary btn-sm">✅
                                                        Verifikasi</button>
                                                </form>
                                                <form action="{{ route('admin.tolak', $p->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Tolak?')">❌</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Tidak ada pendaftaran baru
                                            untuk hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TABLE 2: HARI LAIN (FUTURE) --}}
            <div class="data-table-wrapper">
                <div class="data-table-header header-future">
                    <span>📅 BOOKING HARI MENDATANG (Menunggu Verifikasi)</span>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tgl Kunjungan</th>
                                    <th>Pasien</th>
                                    <th>Poli</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pending_future as $p)
                                    <tr>
                                        <td><span
                                                class="badge bg-info text-dark">{{ date('d/m/Y', strtotime($p->tanggal_kunjungan)) }}</span>
                                        </td>
                                        <td>{{ $p->user->name }}</td>
                                        <td>{{ $p->poli->nama_poli }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <form action="{{ route('admin.verifikasi', $p->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">✅
                                                        Verifikasi</button>
                                                </form>
                                                <form action="{{ route('admin.tolak', $p->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Tolak?')">❌</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Tidak ada booking untuk hari
                                            mendatang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TABLE 3: MONITORING (TERVERIFIKASI) --}}
            <div class="data-table-wrapper">
                <div class="data-table-header header-verified">
                    <span>📋 DATA TERVERIFIKASI (Siap Dilayani)</span>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Tgl</th>
                                    <th>Pasien</th>
                                    <th>Poli</th>
                                    <th>No. Antrian</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($verified_all as $v)
                                    <tr class="{{ $v->tanggal_kunjungan == date('Y-m-d') ? 'table-success' : '' }}">
                                        <td>{{ date('d/m', strtotime($v->tanggal_kunjungan)) }}</td>
                                        <td>{{ $v->user->name }}</td>
                                        <td>{{ $v->poli->nama_poli }}</td>
                                        <td><strong class="text-primary">{{ $v->nomor_antrian }}</strong></td>
                                        <td>
                                            @if ($v->tanggal_kunjungan == date('Y-m-d'))
                                                <span class="badge bg-success">Hari Ini</span>
                                            @else
                                                <span class="badge bg-secondary">Jadwal Nanti</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-3">Belum ada data terverifikasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
