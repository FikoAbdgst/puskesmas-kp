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

        .poli-description {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.5;
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
        <h4 class="mb-0" style="color: var(--primary-green); font-weight: 600;">Manajemen Data Poli</h4>
    </div>

    <div class="management-card">
        <div class="management-header">
            <h5 class="management-title">🏥 Daftar Poli Klinik</h5>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addPoliModal">
                <span style="font-size: 1.2rem;">+</span>
                <span>Tambah Poli</span>
            </button>
        </div>
        <div class="management-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">No</th>
                            <th>Nama Poli</th>
                            <th>Deskripsi</th>
                            <th style="width: 200px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($polis as $index => $poli)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <strong style="color: var(--primary-green);">{{ $poli->nama_poli }}</strong>
                                </td>
                                <td>
                                    <span class="poli-description">{{ $poli->deskripsi ?: '-' }}</span>
                                </td>
                                <td class="text-center">
                                    <button class="action-btn btn-edit" data-bs-toggle="modal"
                                        data-bs-target="#editPoliModal{{ $poli->id }}">
                                        Edit
                                    </button>
                                    <form action="{{ route('poli.destroy', $poli->id) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus poli {{ $poli->nama_poli }}?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="action-btn btn-delete">Hapus</button>
                                    </form>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editPoliModal{{ $poli->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">✏️ Edit Data Poli</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('poli.update', $poli->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Poli</label>
                                                    <input type="text" name="nama_poli" class="form-control"
                                                        value="{{ $poli->nama_poli }}" required
                                                        placeholder="Contoh: Poli Umum">
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Deskripsi</label>
                                                    <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan layanan poli ini...">{{ $poli->deskripsi }}</textarea>
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
                                <td colspan="4" class="empty-state">
                                    <div class="empty-state-icon">🏥</div>
                                    <div>Belum ada data poli. Silakan tambahkan data poli baru.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addPoliModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">➕ Tambah Poli Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('poli.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Poli</label>
                            <input type="text" name="nama_poli" class="form-control" required
                                placeholder="Contoh: Poli Umum">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan layanan poli ini..."></textarea>
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
