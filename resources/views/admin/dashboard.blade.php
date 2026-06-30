@extends('template.main')
@section('title', 'Dashboard Admin')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-gauge-high"></i> Selamat Datang di Dashboard Admin!!!</h2>
    <div class="breadcrumb"><span>Beranda</span></div>
</div>

@if($pendingPelatih->isNotEmpty())
<div class="alert alert-warning" style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; background: #fff8e1; border: 1.5px solid #ffe082; border-left: 5px solid #ffc107; color: #856404; padding: 16px 20px; border-radius: 8px;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 26px; color: #ffc107;"></i>
        <div>
            <h4 style="margin: 0 0 4px; font-size: 15px; font-weight: 700; color: #856404;">Persetujuan Registrasi Pelatih</h4>
            <p style="margin: 0; font-size: 13px; color: #664d03;">Terdapat <strong>{{ $pendingPelatih->count() }}</strong> pendaftaran pelatih baru yang membutuhkan persetujuan Anda agar dapat aktif.</p>
        </div>
    </div>
    <a href="{{ route('admin.users', ['role' => 'pelatih', 'status' => 'pending']) }}" class="btn btn-sm" style="background: #c9a84c; border-color: #c9a84c; color: #1a1a2e; font-weight: bold; text-decoration: none; display: flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 4px;">
        <i class="fa-solid fa-arrow-right"></i> Tinjau Sekarang
    </a>
</div>
@endif

@if($pendingMurid->isNotEmpty())
<div class="alert alert-warning" style="margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; background: #fff8e1; border: 1.5px solid #ffe082; border-left: 5px solid #ffc107; color: #856404; padding: 16px 20px; border-radius: 8px;">
    <div style="display: flex; align-items: center; gap: 14px;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 26px; color: #ffc107;"></i>
        <div>
            <h4 style="margin: 0 0 4px; font-size: 15px; font-weight: 700; color: #856404;">Persetujuan Registrasi Murid</h4>
            <p style="margin: 0; font-size: 13px; color: #664d03;">Terdapat <strong>{{ $pendingMurid->count() }}</strong> pendaftaran murid baru yang membutuhkan persetujuan Anda agar dapat aktif.</p>
        </div>
    </div>
    <a href="{{ route('admin.users', ['role' => 'murid', 'status' => 'pending']) }}" class="btn btn-sm" style="background: #c9a84c; border-color: #c9a84c; color: #1a1a2e; font-weight: bold; text-decoration: none; display: flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 4px;">
        <i class="fa-solid fa-arrow-right"></i> Tinjau Sekarang
    </a>
</div>
@endif

<div class="stats-grid">
    <div class="stat-card" style="cursor: pointer;" onclick="window.location='{{ route('admin.users', ['role' => 'murid']) }}'">
        <div class="stat-icon burgundy"><i class="fa-solid fa-user-graduate"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $totalMurid }}</div>
            <div class="stat-label">Total Murid</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="fa-solid fa-sack-dollar"></i></div>
        <div class="stat-info">
            <div class="stat-num">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan</div>
        </div>
    </div>
    <div class="stat-card" style="cursor: pointer;" onclick="window.location='{{ route('admin.jadwal') }}'">
        <div class="stat-icon success"><i class="fa-solid fa-calendar-days"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $totalJadwal }}</div>
            <div class="stat-label">Jadwal Aktif</div>
        </div>
    </div>
    <div class="stat-card" style="cursor: pointer;" onclick="window.location='{{ route('admin.pembayaran', ['status' => 'pending']) }}'">
        <div class="stat-icon warning"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $pendingBayar }}</div>
            <div class="stat-label">Pembayaran Menunggu</div>
        </div>
    </div>
</div>

<!-- Laporan Perbandingan Kelas & Summary -->
<div class="row" style="margin-bottom: 20px;">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-chart-pie"></i> Perbandingan Antar Kelas & Ringkasan Laporan</h3>
            </div>
            <div class="card-body">
                <div class="grid-2col">
                    <div>
                        <h4 style="font-size:14px; color:#800020; margin-bottom:12px;">Data Perbandingan Kelas</h4>
                        <table class="steva-table">
                            <thead>
                                <tr><th>Kategori Kelas</th><th>Jumlah Murid</th></tr>
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
                    <div style="display:flex; flex-direction:column; gap:15px; justify-content:center;">
                        <div style="background:#f9e8ec; padding:15px; border-radius:10px; border-left:4px solid #800020;">
                            <div style="font-size:12px; color:#6c757d;">Rekap Kehadiran Terkumpul</div>
                            <div style="font-size:18px; font-weight:700; color:#800020;">{{ $totalAbsensi }} Sesi Absensi</div>
                        </div>
                        <div style="background:#fff8e8; padding:15px; border-radius:10px; border-left:4px solid #c9a84c;">
                            <div style="font-size:12px; color:#6c757d;">Total Materi Tersedia</div>
                            <div style="font-size:18px; font-weight:700; color:#c9a84c;">{{ $totalMateri }} Judul Materi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="grid-2col" style="margin-top:20px;">
    <!-- Recent Pembayaran -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-money-bill-wave"></i> Pembayaran Terbaru</h3>
            <a href="{{ route('admin.pembayaran') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding:0;">
            @if($recentPembayaran->isEmpty())
                <div style="padding:24px;text-align:center;color:#adb5bd;font-size:13px;">
                    <i class="fa-solid fa-inbox" style="font-size:28px;margin-bottom:8px;display:block;"></i>
                    Belum ada data pembayaran
                </div>
            @else
            <div class="table-responsive">
                <table class="steva-table">
                    <thead><tr><th>Murid</th><th>Jumlah</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($recentPembayaran as $p)
                    <tr>
                        <td>{{ $p->user->nama ?? '-' }}</td>
                        <td>Rp{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($p->status === 'disetujui')
                                <span class="badge badge-success">Disetujui</span>
                            @elseif($p->status === 'ditolak')
                                <span class="badge badge-danger">Ditolak</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    <!-- Recent Absensi -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-clipboard-check"></i> Absensi Terbaru</h3>
            <a href="{{ route('admin.absensi') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding:0;">
            @if($recentAbsensi->isEmpty())
                <div style="padding:24px;text-align:center;color:#adb5bd;font-size:13px;">
                    <i class="fa-solid fa-inbox" style="font-size:28px;margin-bottom:8px;display:block;"></i>
                    Belum ada data absensi
                </div>
            @else
            <div class="table-responsive">
                <table class="steva-table">
                    <thead><tr><th>Murid</th><th>Kelas</th><th>Tanggal</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($recentAbsensi as $a)
                    <tr>
                        <td>{{ $a->user->nama ?? '-' }}</td>
                        <td>{{ $a->jadwal->nama_kelas ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d/m/Y') }}</td>
                        <td>
                            @if($a->status === 'hadir')
                                <span class="badge badge-success">Hadir</span>
                            @else
                                <span class="badge badge-warning">Tidak Hadir</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="card" style="margin-top:20px;">
    <div class="card-header"><h3><i class="fa-solid fa-bolt"></i> Aksi Cepat</h3></div>
    <div class="card-body" style="display:flex;flex-wrap:wrap;gap:12px;">
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="fa-solid fa-user-plus"></i> Tambah Pengguna</a>
        <a href="{{ route('admin.jadwal.create') }}" class="btn btn-gold"><i class="fa-solid fa-calendar-plus"></i> Tambah Jadwal</a>
        <a href="{{ route('admin.laporan.monitoring') }}" class="btn btn-secondary"><i class="fa-solid fa-chart-line"></i> Monitoring Laporan (Absensi & Pembayaran)</a>
    </div>
</div>
@endsection
