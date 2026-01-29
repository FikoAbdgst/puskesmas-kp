@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3">&larr; Kembali ke Dashboard</a>

            <div class="card">
                <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
                    <span>Manajemen Data Poli</span>
                    <button class="btn btn-light btn-sm text-success fw-bold" data-bs-toggle="modal"
                        data-bs-target="#addPoliModal">
                        + Tambah Poli
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Poli</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($polis as $index => $poli)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $poli->nama_poli }}</td>
                                    <td>{{ $poli->deskripsi }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning text-white" data-bs-toggle="modal"
                                            data-bs-target="#editPoliModal{{ $poli->id }}">Edit</button>

                                        <form action="{{ route('poli.destroy', $poli->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Yakin hapus poli ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                        </form>
                                    </td>
                                </tr>

                                <div class="modal fade" id="editPoliModal{{ $poli->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Poli</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('poli.update', $poli->id) }}" method="POST">
                                                @csrf @method('PUT')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Nama Poli</label>
                                                        <input type="text" name="nama_poli" class="form-control"
                                                            value="{{ $poli->nama_poli }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Deskripsi</label>
                                                        <textarea name="deskripsi" class="form-control" rows="3">{{ $poli->deskripsi }}</textarea>
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

    <div class="modal fade" id="addPoliModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Poli Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('poli.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Poli</label>
                            <input type="text" name="nama_poli" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
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
