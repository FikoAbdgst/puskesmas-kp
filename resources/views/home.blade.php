@extends('layouts.app')

@section('content')
    <style>
        /* --- 1. LIVE QUEUE STYLES (Tetap Menonjol) --- */
        .live-queue-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
            background: white;
            height: 100%;
            position: relative;
        }

        .live-queue-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .live-header {
            background: linear-gradient(135deg, var(--primary-green, #2e7d32) 0%, #1b5e20 100%);
            color: white;
            padding: 0.75rem;
            text-align: center;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .live-body {
            padding: 1.25rem;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .live-number {
            font-size: 3.5rem;
            font-weight: 800;
            color: #2e7d32;
            line-height: 1;
            margin: 0.5rem 0;
        }

        .live-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #888;
            font-weight: 500;
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background-color: #e53935;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
            animation: pulse-animation 1.5s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(229, 57, 53, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(229, 57, 53, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(229, 57, 53, 0);
            }
        }

        /* --- 2. ACTION AREA (DAFTAR BUTTON & MENU LAIN) --- */
        .action-section {
            margin-bottom: 2.5rem;
        }

        /* Tombol Daftar yang SANGAT MENONJOL */
        .daftar-banner {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            border-radius: 16px;
            padding: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid #a5d6a7;
            height: 100%;
            transition: transform 0.2s;
        }

        .daftar-banner:hover {
            transform: scale(1.01);
        }

        .daftar-content h3 {
            color: #1b5e20;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .daftar-content p {
            color: #388e3c;
            margin-bottom: 0;
            font-weight: 500;
        }

        .btn-daftar-cta {
            background: linear-gradient(135deg, #2e7d32 0%, #1b5e20 100%);
            color: white;
            padding: 0.85rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1.1rem;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(27, 94, 32, 0.3);
            transition: all 0.3s;
            white-space: nowrap;
        }

        .btn-daftar-cta:hover {
            background: linear-gradient(135deg, #388e3c 0%, #2e7d32 100%);
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(27, 94, 32, 0.4);
            color: white;
        }

        /* Kartu Menu Sekunder (Jadwal & Poli) - Tampilan Minimalis/Tidak Menonjol */
        .secondary-menu-card {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            padding: 1rem;
            text-align: center;
            color: #555;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            transition: all 0.2s;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        }

        .secondary-menu-card:hover {
            background: #fafafa;
            border-color: #ddd;
            color: #2e7d32;
            transform: translateY(-2px);
        }

        .secondary-icon {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            opacity: 0.8;
        }

        .secondary-title {
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* --- 3. RIWAYAT TABLE --- */
        .history-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.04);
        }

        .history-header {
            background: white;
            padding: 1.5rem;
            border-bottom: 1px solid #f0f0f0;
        }
    </style>

    <div class="container py-4">

        {{-- BAGIAN 1: LIVE MONITOR ANTREAN (Tetap di Atas) --}}
        <div class="row mb-4">
            <div class="col-12 mb-3 d-flex align-items-center">
                <h5 class="fw-bold text-dark m-0">
                    <span class="pulse-dot"></span>Live Antrian Saat Ini
                </h5>
            </div>

            @foreach ($live_antrian as $poli)
                <div class="col-md-3 col-6 mb-3">
                    <div class="live-queue-card">
                        <div class="live-header">{{ $poli->nama_poli }}</div>
                        <div class="live-body">
                            <span class="live-label">Dipanggil</span>
                            <span class="live-number" id="poli-{{ $poli->id }}">
                                {{ $poli->nomor_sekarang }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- BAGIAN 2: MENU UTAMA (DAFTAR) & SEKUNDER (JADWAL/POLI) --}}
        <div class="row action-section g-3">
            {{-- Tombol Daftar (Besar & Dominan) --}}
            <div class="col-lg-8">
                <div class="daftar-banner">
                    <div class="daftar-content">
                        <h3>Ingin Berobat?</h3>
                        <p>Ambil nomor antrian Anda sekarang secara online.</p>
                    </div>
                    <a href="{{ route('pendaftaran.create') }}" class="btn-daftar-cta">
                        + Daftar Sekarang
                    </a>
                </div>
            </div>

            {{-- Menu Sekunder --}}
            <div class="col-lg-2 col-6">
                {{-- Ubah link ini --}}
                <a href="{{ route('pasien.jadwal') }}" class="secondary-menu-card">
                    <span class="secondary-icon">📅</span>
                    <span class="secondary-title">Jadwal Dokter</span>
                </a>
            </div>
            <div class="col-lg-2 col-6">
                {{-- Ubah link ini --}}
                <a href="{{ route('pasien.poli') }}" class="secondary-menu-card">
                    <span class="secondary-icon">🏥</span>
                    <span class="secondary-title">Info Poli</span>
                </a>
            </div>
        </div>

        {{-- BAGIAN 3: RIWAYAT SAYA --}}
        <div class="row">
            <div class="col-12">
                <div class="card history-card">
                    <div class="history-header">
                        <h5 class="fw-bold m-0">📋 Riwayat Pendaftaran Saya</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-secondary">
                                    <tr>
                                        <th class="ps-4">Tanggal</th>
                                        <th>Poli Tujuan</th>
                                        <th>Keluhan</th>
                                        <th>No. Antrian</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($riwayat as $item)
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark">
                                                {{ \Carbon\Carbon::parse($item->tanggal_kunjungan)->format('d M Y') }}</td>
                                            <td><span
                                                    class="badge bg-light text-dark border">{{ $item->poli->nama_poli }}</span>
                                            </td>
                                            <td><small class="text-muted">{{ Str::limit($item->keluhan, 40) }}</small></td>
                                            <td>
                                                @if ($item->nomor_antrian)
                                                    <span
                                                        class="fs-5 fw-bold text-primary">{{ $item->nomor_antrian }}</span>
                                                @else
                                                    <span class="text-muted small">Belum ada</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->status == 'Menunggu Verifikasi')
                                                    <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                                @elseif($item->status == 'Terverifikasi')
                                                    <span class="badge bg-info text-dark">Menunggu Dipanggil</span>
                                                @elseif($item->status == 'Dipanggil')
                                                    <span class="badge bg-success animate__animated animate__flash">Sedang
                                                        Dipanggil</span>
                                                @elseif($item->status == 'Selesai')
                                                    <span class="badge bg-secondary">Selesai</span>
                                                @elseif($item->status == 'Ditolak')
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-5 text-muted">
                                                <div style="opacity: 0.5; font-size: 3rem; margin-bottom: 1rem;">📭</div>
                                                Belum ada riwayat pendaftaran. Silakan daftar baru.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        setInterval(function() {
            // location.reload();
        }, 10000);
    </script>
@endsection
