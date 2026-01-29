@extends('layouts.app')

@section('content')
    <div class="container">
        <h3 class="text-success mb-4 fw-bold">🏥 Informasi Poliklinik</h3>
        <div class="list-group">
            @foreach ($polis as $p)
                <div class="list-group-item list-group-item-action flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1 text-success fw-bold">{{ $p->nama_poli }}</h5>
                    </div>
                    <p class="mb-1">{{ $p->deskripsi }}</p>
                </div>
            @endforeach
        </div>
        <a href="{{ url('/') }}" class="btn btn-secondary mt-3">Kembali</a>
    </div>
@endsection
