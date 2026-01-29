@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="list-group shadow-sm">
                <div class="list-group-item bg-light fw-bold text-muted">MENU UTAMA</div>

                <a href="{{ route('admin.dashboard') }}"
                    class="list-group-item list-group-item-action {{ request()->routeIs('admin.dashboard') ? 'active bg-success border-success' : '' }}">
                    ✅ Verifikasi Pendaftaran
                </a>

                <a href="{{ route('admin.pelayanan') }}"
                    class="list-group-item list-group-item-action {{ request()->routeIs('admin.pelayanan') ? 'active bg-success border-success' : '' }}">
                    🩺 Pelayanan Dokter
                </a>

                <div class="list-group-item bg-light fw-bold text-muted mt-2">DATA MASTER</div>

                <a href="{{ route('poli.index') }}"
                    class="list-group-item list-group-item-action {{ request()->routeIs('poli.index') ? 'active bg-success border-success' : '' }}">
                    🏥 Data Poli
                </a>

                <a href="{{ route('dokter.index') }}"
                    class="list-group-item list-group-item-action {{ request()->routeIs('dokter.index') ? 'active bg-success border-success' : '' }}">
                    👨‍⚕️ Data Dokter
                </a>

                <div class="list-group-item bg-light fw-bold text-muted mt-2">LAPORAN</div>

                <a href="{{ route('admin.laporan') }}"
                    class="list-group-item list-group-item-action {{ request()->routeIs('admin.laporan') ? 'active bg-success border-success' : '' }}">
                    📄 Laporan Pelayanan
                </a>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-warning text-dark p-3">
                        <h3>{{ $stats['pending'] }}</h3>
                        <small>Menunggu Verifikasi</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-primary text-white p-3">
                        <h3>{{ $stats['verified'] }}</h3>
                        <small>Siap Diperiksa</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white p-3">
                        <h3>{{ $stats['done'] }}</h3>
                        <small>Selesai</small>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white fw-bold">Daftar Pasien Masuk (Perlu Verifikasi)</div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama / NIK</th>
                                <th>Poli</th>
                                <th>Keluhan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pending as $key => $p)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <strong>{{ $p->user->name }}</strong><br>
                                        <small class="text-muted">{{ $p->user->nik }}</small>
                                    </td>
                                    <td>{{ $p->poli->nama_poli }}</td>
                                    <td>{{ $p->keluhan }}</td>
                                    <td>{{ $p->tanggal_kunjungan }}</td>
                                    <td>
                                        <form action="{{ route('admin.verifikasi', $p->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-hijau">✅ Validasi</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">Tidak ada pendaftaran baru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
