@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-4">Meja Pelayanan Dokter</h4>

        <div class="row">
            @foreach ($polis as $poli)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white d-flex justify-content-between">
                            <strong>{{ $poli->nama_poli }}</strong>
                            <span class="badge bg-white text-success">Live</span>
                        </div>
                        <div class="card-body">
                            @php
                                $current = $sedang_diperiksa->where('poli_id', $poli->id)->first();
                                $waiting = $antrean_menunggu->where('poli_id', $poli->id);
                            @endphp

                            <div class="text-center p-3 border rounded bg-light mb-3">
                                <small class="text-muted d-block">Sedang Diperiksa:</small>
                                @if ($current)
                                    <h5 class="mt-2 text-primary">{{ $current->user->name }}</h5>
                                    <span class="badge bg-danger mb-2">{{ $current->nomor_antrian }}</span>
                                    <p class="small text-muted mb-3">Keluhan: {{ $current->keluhan }}</p>
                                    <button class="btn btn-primary btn-sm w-100" data-bs-toggle="modal"
                                        data-bs-target="#modal-{{ $current->id }}">
                                        Input Diagnosa
                                    </button>
                                @else
                                    <em class="text-muted">Kosong / Menunggu Antrean</em>
                                @endif
                            </div>

                            <label class="small fw-bold text-muted mb-2">Daftar Tunggu Poli:</label>
                            <ul class="list-group list-group-flush">
                                @forelse($waiting as $w)
                                    <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                        <span class="small">{{ $w->nomor_antrian }} - {{ $w->user->name }}</span>
                                        <span class="badge bg-secondary rounded-pill">Menunggu</span>
                                    </li>
                                @empty
                                    <li class="list-group-item small text-muted text-center px-0 py-2">Tidak ada antrean
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                @if ($current)
                    <div class="modal fade" id="modal-{{ $current->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.periksa', $current->id) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5>Pemeriksaan: {{ $current->user->name }}</h5>
                                    </div>
                                    <div class="modal-body">
                                        <textarea name="catatan_medis" class="form-control" rows="4" placeholder="Diagnosa & Resep..." required></textarea>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Selesai & Panggil Berikutnya</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
