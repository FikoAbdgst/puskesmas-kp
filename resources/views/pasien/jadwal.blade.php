@extends('layouts.app')

@section('content')
    <style>
        .jadwal-wrapper {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        /* Page Header */
        .page-header {
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title-section h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #1b1b1b;
            margin-bottom: 0.5rem;
        }

        .page-title-section p {
            color: #666;
            margin: 0;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: white;
            color: #1b5e20;
            border: 2px solid #e8e8e8;
            border-radius: 12px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: #1b5e20;
            color: white;
            border-color: #1b5e20;
            transform: translateX(-4px);
        }

        /* Doctors Grid */
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
            gap: 2rem;
        }

        .doctor-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .doctor-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .doctor-card-header {
            padding: 2rem 2rem 1.5rem;
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
            color: white;
        }

        .poli-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .poli-name {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0;
        }

        .doctor-card-body {
            padding: 2rem;
        }

        .doctor-info {
            margin-bottom: 1.75rem;
        }

        .doctor-name {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.5rem;
            font-weight: 700;
            color: #1b1b1b;
            margin-bottom: 0.5rem;
        }

        .doctor-icon {
            font-size: 2rem;
        }

        .schedule-section {
            background: linear-gradient(to bottom, #fafafa 0%, #f5f5f5 100%);
            border-radius: 14px;
            padding: 1.5rem;
        }

        .schedule-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #666;
            margin-bottom: 0.75rem;
            letter-spacing: 0.5px;
        }

        .schedule-time {
            font-size: 1.15rem;
            font-weight: 600;
            color: #1b1b1b;
        }

        /* Empty State */
        .empty-container {
            background: white;
            border-radius: 20px;
            padding: 5rem 2rem;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        .empty-icon {
            font-size: 6rem;
            opacity: 0.2;
            margin-bottom: 1.5rem;
        }

        .empty-container h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1b1b1b;
            margin-bottom: 0.75rem;
        }

        .empty-container p {
            color: #666;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .page-title-section h1 {
                font-size: 1.5rem;
            }

            .doctors-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .doctor-card-body {
                padding: 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .jadwal-wrapper {
                padding: 0 0.5rem;
            }

            .doctor-card-header {
                padding: 1.5rem 1.5rem 1.25rem;
            }

            .poli-name {
                font-size: 1.2rem;
            }

            .doctor-name {
                font-size: 1.3rem;
            }
        }
    </style>

    <div class="jadwal-wrapper">
        <div class="page-header">
            <div class="page-title-section">
                <h1>Jadwal Praktik Dokter</h1>
                <p>Lihat jadwal praktik dokter yang tersedia</p>
            </div>
            <a href="{{ route('home') }}" class="btn-back">
                <span>←</span>
            </a>
        </div>

        @if ($dokters->count() > 0)
            <div class="doctors-grid">
                @foreach ($dokters as $d)
                    <div class="doctor-card">
                        <div class="doctor-card-header">
                            <div class="poli-label">Poliklinik</div>
                            <h3 class="poli-name">{{ $d->poli->nama_poli }}</h3>
                        </div>
                        <div class="doctor-card-body">
                            <div class="doctor-info">
                                <div class="doctor-name">
                                    <span class="doctor-icon">👨‍⚕️</span>
                                    <span>{{ $d->nama_dokter }}</span>
                                </div>
                            </div>
                            <div class="schedule-section">
                                <div class="schedule-label">
                                    <span>🕐</span>
                                    <span>Jadwal Praktik</span>
                                </div>
                                <div class="schedule-time">{{ $d->jadwal_praktek }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-container">
                <div class="empty-icon">📅</div>
                <h3>Belum Ada Jadwal Tersedia</h3>
                <p>Jadwal dokter akan ditampilkan di halaman ini</p>
            </div>
        @endif
    </div>
@endsection
