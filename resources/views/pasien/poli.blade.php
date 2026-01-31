@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @foreach ($polis as $poli)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">
                            <h5 class="font-weight-bold">{{ $poli->nama_poli }}</h5>
                            <hr>
                            <p class="text-muted mb-1">Antrian Sedang Dilayani:</p>
                            <h2 class="display-4 text-primary" id="antrian-{{ $poli->id }}">--</h2>
                            <p class="small text-secondary">{{ $poli->deskripsi }}</p>
                            <a href="{{ route('pendaftaran.create') }}" class="btn btn-success btn-sm mt-2">Daftar
                                Sekarang</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        // Fungsi untuk memperbarui antrian setiap 10 detik secara live
        function updateLiveAntrian() {
            @foreach ($polis as $poli)
                fetch("{{ url('/api/live-antrian') }}/{{ $poli->id }}")
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('antrian-{{ $poli->id }}').innerText = data.nomor_sekarang;
                    });
            @endforeach
        }

        setInterval(updateLiveAntrian, 10000); // Update tiap 10 detik
        updateLiveAntrian(); // Jalankan saat pertama kali buka
    </script>
@endsection
