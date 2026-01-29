@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <h3 class="text-success fw-bold">📄 Laporan Pelayanan Pasien</h3>
            <button onclick="window.print()" class="btn btn-hijau">🖨️ Cetak Laporan</button>
        </div>

        <div class="d-none print-only text-center mb-4">
            <h2>PUSKESMAS SEHAT</h2>
            <p>Jl. Kesehatan No. 1, Indonesia | Telp: 021-1234567</p>
            <hr style="border: 2px solid black;">
            <h4>LAPORAN KUNJUNGAN PASIEN</h4>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Tgl Kunjungan</th>
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
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $data->tanggal_kunjungan }}</td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ $data->user->nik ?? '-' }}</td>
                                <td>{{ $data->poli->nama_poli }}</td>
                                <td>{{ $data->dokter->nama_dokter ?? '-' }}</td>
                                <td>{{ $data->catatan_medis }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Belum ada data pelayanan selesai.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {

            /* Sembunyikan elemen navigasi, tombol, dan sidebar saat nge-print */
            .no-print,
            nav,
            header,
            footer,
            .sidebar,
            .btn {
                display: none !important;
            }

            /* Tampilkan Kop Surat */
            .print-only {
                display: block !important;
            }

            /* Atur lebar tabel agar full kertas */
            .container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            body {
                background-color: white !important;
            }
        }
    </style>
@endsection
