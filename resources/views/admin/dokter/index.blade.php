@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3">&larr; Kembali ke Dashboard</a>

            <div class="card">
                <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                    <span>Manajemen Data Dokter</span>
                    <button class="btn btn-light btn-sm text-success fw-bold" data-bs-toggle="modal"
                        data-bs-target="#addDokterModal">
                        + Tambah Dokter
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Dokter</th>
                                <th>Spesialis (Poli)</th>
                                <th>Jadwal Praktek</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dokters as $index => $d)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $d->nama_dokter }}</td>
                                    <td><span class="badge bg-success">{{ $d->poli->nama_poli }}</span></td>
                                    <td>{{ $d->jadwal_praktek }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                            data-bs-target="#editDokterModal{{ $d->id }}">Edit</button>
                                        <form action="{{ route('dokter.destroy', $d->id) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus dokter ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editDokterModal{{ $d->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Dokter</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('dokter.update', $d->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Nama Dokter</label>
                                                        <input type="text" name="nama_dokter" class="form-control"
                                                            value="{{ $d->nama_dokter }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Poli</label>
                                                        <select name="poli_id" class="form-select" required>
                                                            @foreach ($polis as $p)
                                                                <option value="{{ $p->id }}"
                                                                    {{ $d->poli_id == $p->id ? 'selected' : '' }}>
                                                                    {{ $p->nama_poli }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Jadwal Praktek</label>
                                                        <input type="text" name="jadwal_praktek" class="form-control"
                                                            value="{{ $d->jadwal_praktek }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-hijau">Simpan Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addDokterModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dokter Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dokter.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Dokter</label>
                            <input type="text" name="nama_dokter" class="form-control" placeholder="Contoh: dr. Budi"
                                required>
                        </div>
                        <div class="mb-3">
                            <label>Poli</label>
                            <select name="poli_id" class="form-select" required>
                                <option value="">-- Pilih Poli --</option>
                                @foreach ($polis as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jadwal Praktek</label>
                            <input type="text" name="jadwal_praktek" class="form-control"
                                placeholder="Contoh: Senin - Kamis (08:00 - 12:00)" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-hijau">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
