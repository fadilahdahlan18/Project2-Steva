@extends('template.main')
@section('title', 'Laporan Ringkasan')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-chart-line"></i> Ringkasan Laporan STEVA</h2>
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('admin.laporan') }}" class="filter-bar card" style="margin-bottom:25px; padding: 15px; display: flex; flex-direction: row; gap: 15px; align-items: flex-end; background: var(--burgundy-pale); border: 1.5px solid rgba(128,0,32,0.12);">
    <div class="form-group" style="margin-bottom: 0; flex-grow: 1; max-width: 300px;">
        <label class="form-label">Pilih Kelas</label>
        <select name="jadwal_id" class="form-control">
            <option value="">-- Semua Kelas --</option>
            @foreach($jadwalList as $jadwal)
                <option value="{{ $jadwal->id }}" {{ $jadwalId == $jadwal->id ? 'selected' : '' }}>
                    {{ $jadwal->nama_kelas ?? 'Kelas ' . ucfirst($jadwal->kategori_kelas) . ' ' . ucfirst($jadwal->jenis_kelas) }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="form-group" style="margin-bottom: 0;">
        <button type="submit" class="btn btn-primary">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
    </div>
</form>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon burgundy"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $totalMurid }}</div>
            <div class="stat-label">Total Murid</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fa-solid fa-chalkboard-user"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $totalPelatih }}</div>
            <div class="stat-label">Total Pelatih</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="fa-solid fa-money-bill-trend-up"></i></div>
        <div class="stat-info">
            <div class="stat-num">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="fa-solid fa-calendar-check"></i></div>
        <div class="stat-info">
            <div class="stat-num">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</div>
            <div class="stat-label">Pendapatan Bulan Ini</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-chart-pie"></i> Perbandingan Antar Kelas</h3>
            </div>
            <div class="card-body">
                <table class="steva-table">
                    <thead>
                        <tr>
                            <th>Kategori Kelas</th>
                            <th>Jumlah Murid</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perbandinganKelas as $kelas)
                        <tr>
                            <td>{{ ucfirst($kelas->kategori_kelas) }}</td>
                            <td>{{ $kelas->total }} Murid</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-list-check"></i> Menu Laporan Detail</h3>
            </div>
            <div class="card-body">
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <a href="{{ route('admin.laporan.monitoring', $jadwalId ? ['jadwal_id' => $jadwalId] : []) }}" class="btn btn-burgundy" style="justify-content:space-between;">
                        <span><i class="fa-solid fa-chart-line"></i> Monitoring Absensi & Pembayaran</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
