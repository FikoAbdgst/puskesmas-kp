@extends('layouts.app')

@section('content')
    <style>
        .poli-wrapper {
            max-width: 1200px;
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

        /* Poli Grid */
        .poli-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(480px, 1fr));
            gap: 2rem;
        }

        .poli-card {
            background: white;
            border-radius: 20px;
            padding: 2.25rem 2.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .poli-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
        }

        .poli-header {
            display: flex;
            align-items: flex-start;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
        }

        .poli-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #1b5e20 0%, #2e7d32 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(27, 94, 32, 0.25);
        }

        .poli-title-section h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1b1b1b;
            margin-bottom: 0.25rem;
        }

        .poli-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: #1b5e20;
            letter-spacing: 0.5px;
        }

        .poli-description {
            color: #555;
            line-height: 1.7;
            font-size: 0.975rem;
        }

        .poli-description.empty {
            color: #aaa;
            font-style: italic;
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

            .poli-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .poli-card {
                padding: 1.75rem 2rem;
            }

            .poli-title-section h3 {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .poli-wrapper {
                padding: 0 0.5rem;
            }

            .poli-card {
                padding: 1.5rem;
            }

            .poli-header {
                gap: 1rem;
            }

            .poli-icon {
                width: 52px;
                height: 52px;
                font-size: 1.5rem;
            }

            .poli-title-section h3 {
                font-size: 1.2rem;
            }
        }
    </style>

    <div class="poli-wrapper">
        <div class="page-header">
            <div class="page-title-section">
                <h1>Informasi Poliklinik</h1>
                <p>Layanan poliklinik yang tersedia untuk Anda</p>
            </div>
            <a href="{{ route('home') }}" class="btn-back">
                <span>←</span>
            </a>
        </div>

        @if ($polis->count() > 0)
            <div class="poli-grid">
                @foreach ($polis as $p)
                    <div class="poli-card">
                        <div class="poli-header">
                            <div class="poli-icon">🏥</div>
                            <div class="poli-title-section">
                                <div class="poli-label">Poliklinik</div>
                                <h3>{{ $p->nama_poli }}</h3>
                            </div>
                        </div>
                        @if ($p->deskripsi)
                            <p class="poli-description">{{ $p->deskripsi }}</p>
                        @else
                            <p class="poli-description empty">Informasi lengkap akan segera tersedia</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-container">
                <div class="empty-icon">🏥</div>
                <h3>Belum Ada Poliklinik</h3>
                <p>Informasi poliklinik akan ditampilkan di halaman ini</p>
            </div>
        @endif
    </div>
@endsection
