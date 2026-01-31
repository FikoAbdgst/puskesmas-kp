@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white font-weight-bold">Konfirmasi Pendaftaran Baru</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Pasien</th>
                                    <th>Poli</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pending as $p)
                                    <tr>
                                        <td>{{ $p->user->name }} <br><small class="text-muted">No:
                                                {{ $p->nomor_antrian }}</small></td>
                                        <td><span class="badge badge-info">{{ $p->poli->nama_poli }}</span></td>
                                        <td>
                                            <form action="{{ route('admin.verifikasi', $p->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-success btn-sm">Verifikasi</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">Tidak ada pendaftaran baru
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white font-weight-bold">Meja Pemeriksaan (Antrean Live)</div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Poli</th>
                                    <th>Pasien Sedang Diperiksa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($periksa as $pk)
                                    <tr>
                                        <td class="font-weight-bold text-primary">{{ $pk->poli->nama_poli }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-3 text-center bg-danger text-white rounded px-2">
                                                    <small>Antrean</small><br><strong>{{ $pk->nomor_antrian }}</strong>
                                                </div>
                                                <div>
                                                    <strong>{{ $pk->user->name }}</strong><br>
                                                    <small class="text-muted">Keluhan: {{ $pk->keluhan }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" data-toggle="modal"
                                                data-target="#modalPeriksa-{{ $pk->id }}">Input Diagnosa</button>

                                            <div class="modal fade" id="modalPeriksa-{{ $pk->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <form action="{{ route('admin.periksa', $pk->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5>Hasil Pemeriksaan</h5>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Diagnosa & Resep Obat</label>
                                                                    <textarea name="catatan_medis" class="form-control" rows="5" placeholder="Tulis diagnosa dan resep di sini..."
                                                                        required></textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-success">Selesai &
                                                                    Panggil Berikutnya</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">Belum ada pasien di meja
                                            pemeriksaan. <br>Silahkan verifikasi pendaftaran di samping.</td>
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
