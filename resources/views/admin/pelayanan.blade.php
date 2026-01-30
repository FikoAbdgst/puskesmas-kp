@extends('layouts.app')

@section('content')
    <style>
        .page-header {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-back {
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            background: #e9ecef;
            color: #212529;
            transform: translateX(-3px);
        }

        .service-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .service-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .service-body {
            padding: 1.5rem;
        }

        .btn-examine {
            background: linear-gradient(135deg, #1976d2 0%, #2196f3 100%);
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 6px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-examine:hover {
            background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(33, 150, 243, 0.3);
            color: white;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            color: white;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }
    </style>

    <div class="page-header">
        <a href="{{ route('admin.dashboard') }}" class="btn-back">
            <span>←</span>
        </a>
        <h4 class="mb-0" style="color: var(--primary-green); font-weight: 600;">Antrean Pemeriksaan Dokter</h4>
    </div>

    <div class="service-card">
        <div class="service-header">
            🩺 Daftar Pasien Siap Diperiksa
        </div>
        <div class="service-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pasien</th>
                            <th>Poli</th>
                            <th>Keluhan</th>
                            <th style="width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($antrean as $index => $a)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <strong style="color: var(--primary-green);">{{ $a->user->name }}</strong>
                                </td>
                                <td><span class="badge bg-success badge-poli">{{ $a->poli->nama_poli }}</span></td>
                                <td><small>{{ $a->keluhan }}</small></td>
                                <td>
                                    <button type="button" class="btn btn-examine btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#periksaModal{{ $a->id }}">
                                        🩺 Periksa
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal -->
                            <div class="modal fade" id="periksaModal{{ $a->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
                                        <div class="modal-header">
                                            <h5 class="modal-title">📋 Pemeriksaan Pasien: {{ $a->user->name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('admin.periksa', $a->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-body" style="padding: 2rem;">
                                                <div class="mb-4">
                                                    <label class="form-label fw-bold" style="color: var(--primary-green);">
                                                        Hasil Diagnosa / Catatan Medis
                                                    </label>
                                                    <textarea name="catatan_medis" class="form-control" rows="4" required style="border-radius: 8px;"
                                                        placeholder="Masukkan diagnosa dan catatan pemeriksaan..."></textarea>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold" style="color: var(--primary-green);">
                                                        Resep Obat
                                                    </label>
                                                    <textarea name="resep_obat" class="form-control" rows="3" style="border-radius: 8px;"
                                                        placeholder="Masukkan resep obat (opsional)..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer" style="background-color: #f8f9fa;">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                                    style="border-radius: 8px;">Batal</button>
                                                <button type="submit" class="btn-hijau">
                                                    <span>✓</span> Simpan & Selesai
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <div class="empty-state-icon">🩺</div>
                                    <div>Tidak ada pasien dalam antrean pemeriksaan</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
