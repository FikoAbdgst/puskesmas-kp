@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="font-weight-bold mb-0">Riwayat Kunjungan</h4>
            <a href="{{ route('pendaftaran.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">Daftar Baru</a>
        </div>

        <div id="riwayat-list" class="row">
            <div class="col-12 text-center py-5" id="loader">
                <div class="spinner-border text-success" role="status"></div>
                <p class="mt-2 text-muted">Sinkronisasi data...</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetchRiwayat();
            setInterval(fetchRiwayat, 20000); // Refresh otomatis
        });

        function fetchRiwayat() {
            fetch("{{ route('pendaftaran.json') }}")
                .then(res => res.json())
                .then(data => {
                    const container = document.getElementById('riwayat-list');
                    if (data.length === 0) {
                        container.innerHTML =
                        '<div class="col-12 text-center py-5 text-muted">Tidak ada riwayat.</div>';
                        return;
                    }

                    let html = '';
                    data.forEach(item => {
                        html += `
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card border-0 shadow-sm" style="border-radius: 15px;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="badge badge-pill ${getBadgeColor(item.status)}">${item.status}</span>
                                        <small class="text-muted">${item.tanggal_formatted}</small>
                                    </div>
                                    <h5 class="card-title font-weight-bold mb-1">${item.nama_poli}</h5>
                                    <p class="small text-truncate text-secondary mb-3">${item.keluhan}</p>

                                    <div class="bg-light p-2 rounded d-flex justify-content-between align-items-center">
                                        <div class="text-center flex-fill border-right">
                                            <small class="d-block text-muted">Nomor Anda</small>
                                            <span class="h5 font-weight-bold mb-0">${item.nomor_antrian}</span>
                                        </div>
                                        <div class="text-center flex-fill">
                                            <small class="d-block text-muted text-danger">Sedang Dilayani</small>
                                            <span class="h5 font-weight-bold mb-0 text-danger" data-id="${item.poli_id}" name="live-val">--</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    });
                    container.innerHTML = html;
                    syncLiveStatus();
                });
        }

        function syncLiveStatus() {
            document.getElementsByName('live-val').forEach(el => {
                fetch("{{ url('/api/live-antrian') }}/" + el.dataset.id)
                    .then(r => r.json())
                    .then(d => el.innerText = d.nomor_sekarang);
            });
        }

        function getBadgeColor(s) {
            if (s === 'Menunggu') return 'badge-warning';
            if (s === 'Dipanggil') return 'badge-primary';
            if (s === 'Selesai') return 'badge-success';
            return 'badge-danger';
        }
    </script>
@endsection
