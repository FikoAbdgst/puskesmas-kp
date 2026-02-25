@extends('layouts.app')

@section('content')
    <style>
        .page-header {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
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

        .btn-disabled {
            background: #e0e0e0;
            color: #9e9e9e;
            cursor: not-allowed;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .report-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
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

        .print-header {
            display: none;
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
            }

            .container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
            }

            .report-card {
                box-shadow: none !important;
            }

            .table thead th {
                background-color: #e8f5e9 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>

    @php
        $daftarBulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',
        ];
        $bulanFormat = str_pad($bulan, 2, '0', STR_PAD_LEFT);
    @endphp

    <div class="no-print">

        <div class="card mb-3 border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body bg-light rounded" style="border-left: 5px solid var(--primary-green);">
                <form action="{{ route('admin.laporan') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small">Pilih Bulan</label>
                        <select name="bulan" class="form-select">
                            @foreach ($daftarBulan as $num => $name)
                                <option value="{{ $num }}" {{ $bulanFormat == $num ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small">Pilih Tahun</label>
                        <select name="tahun" class="form-select">
                            @for ($i = date('Y'); $i >= date('Y') - 3; $i--)
                                <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>{{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100" style="padding: 0.6rem;">
                            <span>🔍</span> Filter Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="page-header">
            <h3 class="page-title">
                <span>📄</span>
                <span>Laporan Pelayanan ({{ $daftarBulan[$bulanFormat] }} {{ $tahun }})</span>
            </h3>

            <div class="text-end">
                @if ($bisaDicetak)
                    <button onclick="window.print()" class="btn-print">
                        <span>🖨️</span>
                        <span>Cetak Laporan</span>
                    </button>
                @else
                    <button class="btn-disabled" title="Cetak laporan hanya tersedia untuk bulan yang sudah lewat.">
                        <span>🚫</span>
                        <span>Belum Bisa Dicetak</span>
                    </button>
                    <small class="text-danger d-block mt-1 fw-bold" style="font-size: 0.75rem;">
                        *Tersedia setelah bulan {{ $daftarBulan[$bulanFormat] }} berakhir.
                    </small>
                @endif
            </div>
        </div>
    </div>

    <div class="print-header">
        <h2>PUSKESMAS SEHAT</h2>
        <p>Jl. Kesehatan No. 1, Indonesia</p>
        <p>Telp: (021) 1234567 | Email: info@puskesmassehat.id</p>
        <hr>
        <h4>LAPORAN KUNJUNGAN PASIEN</h4>
        <p style="font-weight: 600; font-size: 1.1rem;">Periode: {{ $daftarBulan[$bulanFormat] }} {{ $tahun }}</p>
        <p style="font-size: 0.9rem; color: #6c757d;">Dicetak pada: {{ date('d F Y, H:i') }} WIB</p>
    </div>

    <div class="report-card">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Tgl Kunjungan</th>
                            <th>Nama Pasien</th>
                            <th>NIK</th>
                            <th>Poli</th>
                            <th>Dokter</th>
                            <th>Keluhan</th>
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
                                <td>{{ $data->keluhan }}</td>
                                <td>{{ $data->catatan_medis }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <div style="font-size: 2rem; margin-bottom: 10px;">📋</div>
                                    Tidak ada data pelayanan yang selesai pada bulan {{ $daftarBulan[$bulanFormat] }}
                                    {{ $tahun }}.
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
