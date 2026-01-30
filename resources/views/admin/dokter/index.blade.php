@extends('layouts.app')

@section('content')
    <style>
        .page-header {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn-back {
            background: #f8f9fa;
            color: #495057;
            border: 1px solid #dee2e6;
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back:hover {
            background: #e9ecef;
            color: #212529;
            transform: translateX(-3px);
        }

        .management-card {
            background: white;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
            overflow: hidden;
        }

        .management-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .management-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin: 0;
        }

        .btn-add {
            background: white;
            color: var(--primary-green);
            border: none;
            padding: 0.5rem 1.25rem;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-add:hover {
            background: #f1f8f4;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .management-body {
            padding: 1.5rem;
        }

        .action-btn {
            padding: 0.4rem 1rem;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.875rem;
            transition: var(--transition);
            border: none;
        }

        .btn-edit {
            background: #ff9800;
            color: white;
        }

        .btn-edit:hover {
            background: #f57c00;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(255, 152, 0, 0.3);
            color: white;
        }

        .btn-delete {
            background: #f44336;
            color: white;
        }

        .btn-delete:hover {
            background: #d32f2f;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(244, 67, 54, 0.3);
            color: white;
        }

        .badge-specialist {
            background: linear-gradient(135deg, var(--accent-green) 0%, #66bb6a 100%);
            color: white;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .schedule-info {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            overflow: hidden;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, #2e7d32 100%);
            color: white;
            border: none;
            padding: 1.25rem 1.5rem;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            background-color: #f8f9fa;
            border: none;
            padding: 1rem 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #dee2e6;
            padding: 0.625rem 1rem;
            transition: var(--transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-green);
            box-shadow: 0 0 0 0.2rem rgba(76, 175, 80, 0.1);
        }

        .table thead th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #dee2e6;
            padding: 1rem;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f0f0f0;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>

    <div class="page-header">
        <a href="{{ route('admin.dashboard') }}" class="btn-back">
            <span>←</span>
        </a>
        <h4 class="mb-0" style="color: var(--primary-green); font-weight: 600;">Manajemen Data Dokter</h4>
    </div>

    <div class="management-card">
        <div class="management-header">
            <h5 class="management-title">👨‍⚕️ Daftar Dokter</h5>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addDokterModal">
                <span style="font-size: 1.2rem;">+</span>
                <span>Tambah Dokter</span>
            </button>
        </div>
        <div class="management-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nama Dokter</th>
                            <th>Spesialis (Poli)</th>
                            <th>Jadwal Praktek</th>
                            <th style="width: 200px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($dokters as $index => $d)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong style="color: var(--primary-green);">{{ $d->nama_dokter }}</strong>
                                </td>
                                <td>
                                    <span class="badge-specialist">{{ $d->poli->nama_poli }}</span>
                                </td>
                                <td>
                                    <span class="schedule-info">📅 {{ $d->jadwal_praktek }}</span>
                                </td>
                                <td class="text-center">
                                    <button class="action-btn btn-edit" data-bs-toggle="modal"
                                        data-bs-target="#editDokterModal{{ $d->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('dokter.destroy', $d->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus dokter {{ $d->nama_dokter }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editDokterModal{{ $d->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">✏️ Edit Data Dokter</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('dokter.update', $d->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Dokter</label>
                                                    <input type="text" name="nama_dokter" class="form-control"
                                                        value="{{ $d->nama_dokter }}" required
                                                        placeholder="Contoh: dr. Ahmad Santoso">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Poli</label>
                                                    <select name="poli_id" class="form-select" required>
                                                        <option value="">-- Pilih Poli --</option>
                                                        @foreach ($polis as $p)
                                                            <option value="{{ $p->id }}"
                                                                {{ $d->poli_id == $p->id ? 'selected' : '' }}>
                                                                {{ $p->nama_poli }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Jadwal Praktek</label>
                                                    <input type="text" name="jadwal_praktek" class="form-control"
                                                        value="{{ $d->jadwal_praktek }}" required
                                                        placeholder="Contoh: Senin - Kamis (08:00 - 12:00)">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn-hijau">
                                                    <span>✓</span> Simpan Perubahan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <div class="empty-state-icon">👨‍⚕️</div>
                                    <div>Belum ada data dokter. Silakan tambahkan data dokter baru.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addDokterModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">➕ Tambah Dokter Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('dokter.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Dokter</label>
                            <input type="text" name="nama_dokter" class="form-control"
                                placeholder="Contoh: dr. Ahmad Santoso" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Poli</label>
                            <select name="poli_id" class="form-select" required>
                                <option value="">-- Pilih Poli --</option>
                                @foreach ($polis as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_poli }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jadwal Praktek</label>
                            <input type="text" name="jadwal_praktek" class="form-control"
                                placeholder="Contoh: Senin - Kamis (08:00 - 12:00)" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn-hijau">
                            <span>✓</span> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
