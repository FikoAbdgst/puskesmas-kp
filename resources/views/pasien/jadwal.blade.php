@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-success mb-4 fw-bold">📅 Jadwal Praktek Dokter</h3>
        <div class="row">
            @foreach ($dokters as $d)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-header card-header-custom text-white">
                            {{ $d->poli->nama_poli }}
                        </div>
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{{ $d->nama_dokter }}</h5>
                            <p class="card-text text-muted">🕒 {{ $d->jadwal_praktek }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <a href="{{ url('/') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
@endsection
