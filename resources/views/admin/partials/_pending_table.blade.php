@forelse($pending as $p)
    <tr>
        <td>
            <div><strong style="color: #1b5e20;">{{ $p->user->name }}</strong></div>
            <small class="text-muted">NIK: {{ $p->user->nik ?? '-' }}</small>
        </td>
        <td><span class="badge bg-success badge-poli">{{ $p->poli->nama_poli }}</span></td>
        <td><small>{{ $p->keluhan }}</small></td>
        <td><small>{{ $p->tanggal_kunjungan }}</small></td>
        <td>
            <form action="{{ route('admin.verifikasi', $p->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-validasi btn-sm">✅ Terima Booking</button>
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="empty-state">
            <div class="empty-state-icon">📋</div>
            <div>Tidak ada pendaftaran yang perlu diverifikasi</div>
        </td>
    </tr>
@endforelse
