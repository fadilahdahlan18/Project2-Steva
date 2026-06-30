@extends('template.main')
@section('title', 'Monitoring Laporan')
@section('styles')
<style>
    @media print {
        /* Hide everything not related to the report */
        .sidebar, .topbar, .filter-bar, .page-header, .btn, footer, .breadcrumb {
            display: none !important;
        }
        .main-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .content-area {
            padding: 0 !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        body {
            background: white;
        }
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        /* Ensure tables look good printed */
        .steva-table th {
            background-color: #eee !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact;
        }
    }

    .print-header {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h2><i class="fa-solid fa-chart-line"></i> Monitoring Laporan</h2>
        @if($selectedJadwal)
            <span class="badge badge-info" style="font-size:13px; padding:6px 12px; margin-top:6px; display:inline-block;">
                <i class="fa-solid fa-chalkboard"></i>
                Kelas: {{ $selectedJadwal->nama_kelas ?? 'Kelas ' . ucfirst($selectedJadwal->kategori_kelas) . ' ' . ucfirst($selectedJadwal->jenis_kelas) }}
            </span>
        @else
            <span class="badge badge-secondary" style="font-size:13px; padding:6px 12px; margin-top:6px; display:inline-block;">
                <i class="fa-solid fa-layer-group"></i> Semua Kelas
            </span>
        @endif
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
        <a href="{{ route('admin.laporan', $jadwalId ? ['jadwal_id' => $jadwalId] : []) }}" class="btn btn-secondary">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button type="button" class="btn btn-gold" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Print Laporan
        </button>
    </div>
</div>

<div class="print-header">
    <h2>Laporan Monitoring STEVA</h2>
    @if($selectedJadwal)
        <p>Kelas: {{ $selectedJadwal->nama_kelas ?? 'Kelas ' . ucfirst($selectedJadwal->kategori_kelas) . ' ' . ucfirst($selectedJadwal->jenis_kelas) }}</p>
    @else
        <p>Kelas: Semua Kelas</p>
    @endif
    <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('admin.laporan.monitoring') }}" class="filter-bar card" style="margin-bottom:25px; padding: 15px; display: flex; flex-direction: row; gap: 15px; align-items: flex-end; background: var(--burgundy-pale); border: 1.5px solid rgba(128,0,32,0.12);">
    <div class="form-group" style="margin-bottom: 0;">
        <label class="form-label">Tanggal Mulai</label>
        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}" required>
    </div>

    <div class="form-group" style="margin-bottom: 0;">
        <label class="form-label">Tanggal Selesai</label>
        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
    </div>

    <div class="form-group" style="margin-bottom: 0; flex-grow: 1; max-width: 250px;">
        <label class="form-label">Kelas</label>
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
            <i class="fa-solid fa-filter"></i> Tampilkan
        </button>
    </div>
</form>

{{-- HIGHLIGHTS --}}
<div class="stats-grid" style="margin-bottom: 25px; grid-template-columns: 1fr 1fr;">
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fa-solid fa-money-bill-trend-up"></i></div>
        <div class="stat-info">
            <div class="stat-num">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            <div class="stat-label">Total Pendapatan STEVA (Periode Ini)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon info"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $totalMurid }}</div>
            <div class="stat-label">Total Murid Aktif {{ $selectedJadwal ? 'Kelas Ini' : 'Keseluruhan' }}</div>
        </div>
    </div>
</div>

{{-- ================= PENDAPATAN & MURID PER KELAS ================= --}}
<div class="card" style="margin-bottom:25px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3><i class="fa-solid fa-layer-group"></i> Pendapatan & Jumlah Murid Antar Kelas</h3>
        <button type="button" class="btn btn-sm btn-secondary" onclick="printSection('kelas-section', 'Laporan Pendapatan & Jumlah Murid Antar Kelas')">
            <i class="fa-solid fa-print"></i> Print
        </button>
    </div>
    <div class="card-body" style="padding:0;" id="kelas-section">
        @if(empty($laporanKelas))
            <div style="padding:40px;text-align:center;color:#adb5bd;">Tidak ada data kelas.</div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kelas</th>
                        <th style="text-align:center;">Jumlah Murid Aktif</th>
                        <th style="text-align:right;">Pendapatan (Periode Ini)</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($laporanKelas as $i => $lk)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><strong>{{ $lk['nama_kelas'] }}</strong></td>
                    <td align="center"><span class="badge badge-info">{{ $lk['jumlah_murid'] }} Murid</span></td>
                    <td align="right" style="color:#28a745; font-weight:bold;">Rp {{ number_format($lk['pendapatan'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

{{-- ================= DETAIL MURID ================= --}}
<div class="card" style="margin-bottom:25px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3><i class="fa-solid fa-users"></i> Laporan Murid</h3>
        <button type="button" class="btn btn-sm btn-secondary" onclick="printSection('murid-section', 'Laporan Murid - ' + '{{ $selectedJadwal ? ($selectedJadwal->nama_kelas ?? 'Kelas ' . ucfirst($selectedJadwal->kategori_kelas) . ' ' . ucfirst($selectedJadwal->jenis_kelas)) : 'Semua Kelas' }}')">
            <i class="fa-solid fa-print"></i> Print
        </button>
    </div>
    <div class="card-body" style="padding:0;" id="murid-section">
        @if($detailMurid->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">Tidak ada data murid.</div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Murid</th>
                        <th>No. Telepon</th>
                        <th>Kategori Kelas</th>
                        <th>Jenis Kelas</th>
                        <th style="text-align:center;">Status Keaktifan</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($detailMurid as $i => $murid)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><strong>{{ $murid->nama }}</strong></td>
                    <td>{{ $murid->no_hp ?? '-' }}</td>
                    <td>{{ ucfirst($murid->kategori_kelas) }}</td>
                    <td>{{ ucfirst(str_replace(',', ', ', $murid->jenis_kelas)) }}</td>
                    <td align="center">
                        @if($murid->keaktifan_status === 'aktif')
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Tidak Aktif</span>
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

{{-- ================= DETAIL PEMBAYARAN ================= --}}
<div class="card" style="margin-bottom:25px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3><i class="fa-solid fa-money-bill-wave"></i> Laporan Pembayaran</h3>
        <button type="button" class="btn btn-sm btn-secondary" onclick="printSection('pembayaran-section', 'Laporan Pembayaran - ' + '{{ $selectedJadwal ? ($selectedJadwal->nama_kelas ?? 'Kelas ' . ucfirst($selectedJadwal->kategori_kelas) . ' ' . ucfirst($selectedJadwal->jenis_kelas)) : 'Semua Kelas' }}')">
            <i class="fa-solid fa-print"></i> Print
        </button>
    </div>
    <div class="card-body" style="padding:0;" id="pembayaran-section">
        @if($detailPembayaran->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">Tidak ada data pembayaran.</div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Murid</th>
                        <th>Kelas</th>
                        <th>Metode</th>
                        <th style="text-align:right;">Jumlah</th>
                        <th style="text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($detailPembayaran as $i => $pembayaran)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d/m/Y') }}</td>
                    <td><strong>{{ $pembayaran->user->nama ?? '-' }}</strong></td>
                    <td>
                        @if($pembayaran->jadwal)
                            {{ $pembayaran->jadwal->nama_kelas ?? 'Kelas ' . ucfirst($pembayaran->jadwal->kategori_kelas) . ' ' . ucfirst($pembayaran->jadwal->jenis_kelas) }}
                        @else
                            Umum
                        @endif
                    </td>
                    <td>{{ ucfirst($pembayaran->metode) }}</td>
                    <td align="right">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                    <td align="center">
                        @if($pembayaran->status === 'disetujui')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($pembayaran->status === 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @else
                            <span class="badge badge-danger">Ditolak</span>
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

{{-- ================= DETAIL ABSENSI ================= --}}
<div class="card" style="margin-bottom:25px;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3><i class="fa-solid fa-calendar-check"></i> Laporan Absensi</h3>
        <button type="button" class="btn btn-sm btn-secondary" onclick="printSection('absensi-section', 'Laporan Absensi - ' + '{{ $selectedJadwal ? ($selectedJadwal->nama_kelas ?? 'Kelas ' . ucfirst($selectedJadwal->kategori_kelas) . ' ' . ucfirst($selectedJadwal->jenis_kelas)) : 'Semua Kelas' }}')">
            <i class="fa-solid fa-print"></i> Print
        </button>
    </div>
    <div class="card-body" style="padding:0;" id="absensi-section">
        @if($detailAbsensi->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">Tidak ada data absensi.</div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Nama Murid</th>
                        <th>Kelas</th>
                        <th style="text-align:center;">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($detailAbsensi as $i => $absensi)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d/m/Y') }}</td>
                    <td><strong>{{ $absensi->user->nama ?? '-' }}</strong></td>
                    <td>
                        @if($absensi->jadwal)
                            {{ $absensi->jadwal->nama_kelas ?? 'Kelas ' . ucfirst($absensi->jadwal->kategori_kelas) . ' ' . ucfirst($absensi->jadwal->jenis_kelas) }}
                        @else
                            -
                        @endif
                    </td>
                    <td align="center">
                        @if(strtolower($absensi->status) === 'hadir')
                            <span class="badge badge-success">Hadir</span>
                        @elseif(strtolower($absensi->status) === 'sakit')
                            <span class="badge badge-info">Sakit</span>
                        @elseif(strtolower($absensi->status) === 'izin')
                            <span class="badge badge-warning">Izin</span>
                        @else
                            <span class="badge badge-danger">Alfa</span>
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

@endsection

@section('scripts')
<script>
function printSection(sectionId, title) {
    let content = document.getElementById(sectionId).innerHTML;
    let printWindow = window.open('', '', 'height=800,width=1000');

    printWindow.document.write('<html><head><title>Print ' + title + '</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: "Inter", sans-serif; padding: 20px; color: #333; }');
    printWindow.document.write('h2 { text-align: center; border-bottom: 2px solid #000; padding-bottom: 10px; margin-bottom: 20px; font-size: 18px; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 13.5px; }');
    printWindow.document.write('table, th, td { border: 1px solid #ddd; }');
    printWindow.document.write('th, td { padding: 12px; text-align: left; }');
    printWindow.document.write('th { background-color: #f8f9fa; color: #000; font-weight: 600; text-transform: uppercase; font-size: 12px; -webkit-print-color-adjust: exact; }');
    printWindow.document.write('.badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; display: inline-block; }');
    printWindow.document.write('.badge-info { background: #17a2b8; color: white; }');
    printWindow.document.write('.badge-success { background: #28a745; color: white; }');
    printWindow.document.write('.badge-warning { background: #ffc107; color: #212529; }');
    printWindow.document.write('.badge-danger { background: #dc3545; color: white; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');

    printWindow.document.write('<h2>' + title + '</h2>');
    printWindow.document.write('<div>' + content + '</div>');

    printWindow.document.write('</body></html>');
    printWindow.document.close();

    setTimeout(function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }, 250);
}
</script>
@endsection
