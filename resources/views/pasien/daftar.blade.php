@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-custom">Form Pendaftaran Online</div>
                <div class="card-body">
                    <form action="{{ route('pendaftaran.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label>Nama Pasien</label>
                            <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label>NIK / No. RM</label>
                            <input type="number" name="nik" class="form-control" value="{{ Auth::user()->nik }}"
                                placeholder="Masukkan NIK" required>
                        </div>

                        <div class="mb-3">
                            <label>Poli Tujuan</label>
                            <select name="poli_id" class="form-select" required>
                                <option value="">-- Pilih Poli --</option>
                                @foreach ($polis as $poli)
                                    <option value="{{ $poli->id }}">{{ $poli->nama_poli }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Rencana Tanggal Kunjungan</label>
                            <input type="date" name="tanggal_kunjungan" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Keluhan</label>
                            <textarea name="keluhan" class="form-control" rows="3" placeholder="Jelaskan keluhan singkat..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-hijau w-100">Kirim Pendaftaran</button>
                        <a href="{{ route('home') }}" class="btn btn-link text-muted w-100 mt-2">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
