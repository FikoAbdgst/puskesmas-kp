@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3">&larr; Kembali</a>

            <div class="card">
                <div class="card-header card-header-custom">Antrean Pemeriksaan Dokter</div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Pasien</th>
                                <th>Poli</th>
                                <th>Keluhan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($antrean as $a)
                                <tr>
                                    <td>{{ $a->user->name }}</td>
                                    <td>{{ $a->poli->nama_poli }}</td>
                                    <td>{{ $a->keluhan }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#periksaModal{{ $a->id }}">
                                            🩺 Periksa
                                        </button>

                                        <div class="modal fade" id="periksaModal{{ $a->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Periksa: {{ $a->user->name }}</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.periksa', $a->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label>Hasil Diagnosa / Catatan Medis</label>
                                                                <textarea name="catatan_medis" class="form-control" rows="4" required></textarea>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label>Resep Obat</label>
                                                                <textarea name="resep_obat" class="form-control" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="submit" class="btn btn-hijau">Simpan &
                                                                Selesai</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
