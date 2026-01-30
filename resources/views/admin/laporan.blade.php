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
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            color: var(--primary-green);
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-print {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, var(--primary-green-dark) 0%, var(--primary-green) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(27, 94, 32, 0.3);
            color: white;
        }

        .report-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .report-body {
            padding: 1.5rem;
        }

        .print-header {
            display: none;
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
            font-size: 0.9rem;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        @media print {
            body {
                background-color: white !important;
            }

            .no-print {
                display: none !important;
            }

            .print-header {
                display: block !important;
                text-align: center;
                margin-bottom: 2rem;
                padding-bottom: 1rem;
                border-bottom: 3px solid var(--primary-green);
            }

            .print-header h2 {
                color: var(--primary-green);
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .print-header p {
                margin: 0.25rem 0;
                color: #495057;
            }

            .print-header hr {
                border: 2px solid var(--primary-green);
                margin: 1rem 0;
            }

            .print-header h4 {
                color: #212529;
                font-weight: 600;
                margin-top: 1rem;
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
            }

            .report-card {
                box-shadow: none !important;
            }

            .table {
                font-size: 0.85rem;
            }

            .table thead th {
                background-color: #e8f5e9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>

    <div class="no-print">
        <div class="page-header">
            <h3 class="page-title">
                <span>📄</span>
                <span>Laporan Pelayanan Pasien</span>
            </h3>
            <button onclick="window.print()" class="btn-print">
                <span>🖨️</span>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <div class="print-header">
        <h2>PUSKESMAS SEHAT</h2>
        <p>Jl. Kesehatan No. 1, Indonesia</p>
        <p>Telp: (021) 1234567 | Email: info@puskesmassehat.id</p>
        <hr>
        <h4>LAPORAN KUNJUNGAN PASIEN</h4>
        <p style="font-size: 0.9rem; color: #6c757d;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <div class="report-card">
        <div class="report-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Nama Pasien</th>
                            <th>NIK</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Diagnosa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laporan as $index => $data)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal_kunjungan)->format('d/m/Y') }}</td>
                                <td><strong style="color: var(--primary-green);">{{ $data->user->name }}</strong></td>
                                <td>{{ $data->user->nik ?? '-' }}</td>
                                <td>{{ $data->poli->nama_poli }}</td>
                                <td>{{ $data->dokter->nama_dokter ?? '-' }}</td>
                                <td>{{ $data->catatan_medis }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <div class="empty-state-icon">📋</div>
                                    <div>Belum ada data pelayanan yang selesai</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="print-header" style="display: block; margin-top: 3rem; border-top: 2px solid #dee2e6; padding-top: 2rem;">
        <div style="display: flex; justify-content: space-between; max-width: 800px; margin: 0 auto;">
            <div style="text-align: center;">
                <p style="margin-bottom: 4rem;">Mengetahui,</p>
                <p style="font-weight: 600; border-top: 2px solid #212529; display: inline-block; padding-top: 0.5rem;">
                    Kepala Puskesmas</p>
            </div>
            <div style="text-align: center;">
                <p style="margin-bottom: 4rem;">Petugas Admin,</p>
                <p style="font-weight: 600; border-top: 2px solid #212529; display: inline-block; padding-top: 0.5rem;">
                    {{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>
@endsection
