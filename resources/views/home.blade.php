@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body text-center p-5">
                    <h2 class="fw-bold text-success">Selamat Datang, {{ Auth::user()->name }}</h2>
                    <p class="text-muted">Silakan pilih layanan kesehatan yang Anda butuhkan.</p>
                    <hr>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-success">
                                <div class="card-body">
                                    <h4>📝</h4>
                                    <h5>Daftar Berobat</h5>
                                    <p class="small text-muted">Ambil antrean poli secara online</p>
                                    <a href="{{ route('pendaftaran.create') }}" class="btn btn-hijau w-100">Daftar</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h4>📅</h4>
                                    <h5>Jadwal Dokter</h5>
                                    <p class="small text-muted">Cek jadwal praktek dokter</p>
                                    <a href="{{ route('pasien.jadwal') }}" class="btn btn-outline-success w-100">Lihat</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h4>ℹ️</h4>
                                    <h5>Info Poli</h5>
                                    <p class="small text-muted">Daftar poli tersedia</p>
                                    <a href="{{ route('pasien.poli') }}" class="btn btn-outline-success w-100">Cek</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-custom">Status Pendaftaran Terkini</div>
                <div class="card-body">
                    @php $riwayat = \App\Models\Pendaftaran::where('user_id', Auth::id())->latest()->take(3)->get(); @endphp
                    @if ($riwayat->isEmpty())
                        <p class="text-center text-muted my-3">Belum ada riwayat pendaftaran.</p>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Poli</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($riwayat as $r)
                                    <tr>
                                        <td>{{ $r->tanggal_kunjungan }}</td>
                                        <td>{{ $r->poli->nama_poli }}</td>
                                        <td>
                                            @if ($r->status == 'pending')
                                                <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                                            @elseif($r->status == 'verified')
                                                <span class="badge bg-primary">Menunggu Dipanggil</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
