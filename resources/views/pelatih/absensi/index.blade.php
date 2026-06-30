@extends('template.main')
@section('title', 'Absensi Murid')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-clipboard-check"></i> Absensi Murid</h2>
    <a href="{{ route('pelatih.absensi.create') }}" class="btn btn-gold">
        <i class="fa-solid fa-plus"></i> Input Absensi
    </a>
</div>

<form action="{{ route('pelatih.absensi') }}" method="GET" class="filter-bar">
    <div class="form-group">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
    </div>
    <div class="form-group">
        <label class="form-label">Kelas</label>
        <select name="jadwal_id" class="form-control form-select" style="min-width:180px;">
            <option value="">-- Semua Kelas --</option>
            @foreach($jadwalList as $j)
                <option value="{{ $j->id }}" {{ $jadwal_id==$j->id ? 'selected' : '' }}>{{ $j->nama_kelas }} ({{ $j->hari }}, {{ $j->jam }})</option>
            @endforeach
        </select>
    </div>
    <div class="form-group" style="align-self:flex-end;">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter</button>
        <a href="{{ route('pelatih.absensi') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

<div class="card">
    <div class="card-body" style="padding:0;">
        @if($absensi->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">
                <i class="fa-solid fa-clipboard-list" style="font-size:36px;margin-bottom:12px;display:block;"></i>
                Tidak ada data absensi.
            </div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr><th>No</th><th>Murid</th><th>Kelas</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @foreach($absensi as $i => $a)
                <tr>
                    <td>{{ $absensi->firstItem() + $i }}</td>
                    <td>{{ $a->user->nama ?? '-' }}</td>
                    <td>{{ $a->jadwal->nama_kelas ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</td>
                    <td>
                        @if($a->status === 'hadir')
                            <span class="badge badge-success">Hadir</span>
                        @else
                            <span class="badge badge-warning">Tidak Hadir</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:5px;">
                            <a href="{{ route('pelatih.absensi.edit', $a->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('pelatih.absensi.destroy', $a->id) }}"
                                  onsubmit="return confirm('Hapus data absensi ini?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">{{ $absensi->links('vendor.pagination.simple') }}</div>
        @endif
    </div>
</div>
@endsection
