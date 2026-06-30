@extends('template.main')
@section('title', 'Riwayat Absensi')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-clipboard-list"></i> Riwayat Absensi</h2>
    <div class="breadcrumb">
        <span>Pantau kehadiran Anda di setiap sesi latihan</span>
    </div>
</div>

<div class="stats-grid grid-2">
    <div class="stat-card">
        <div class="stat-icon success"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $hadir }}</div>
            <div class="stat-label">Total Hadir</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon danger"><i class="fa-solid fa-circle-xmark"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $TidakHadir }}</div>
            <div class="stat-label">Total Tidak Hadir</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3><i class="fa-solid fa-filter"></i> Filter Periode</h3>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('murid.absensi') }}" class="filter-bar filter-form">
            <div class="form-group" style="flex:1; min-width:150px;">
                <label class="form-label">Bulan</label>
                <select name="bulan" class="form-control">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->locale('id')->monthName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="flex:1; min-width:150px;">
                <label class="form-label">Tahun</label>
                <select name="tahun" class="form-control">
                    @foreach(range(now()->year - 2, now()->year) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="flex:1; min-width:150px;">
                <label class="form-label">Kelas</label>
                <select name="kelas" class="form-control">
                    <option value="">Semua Kelas</option>
                    @if(in_array('rampak', $jenisKelas))
                        <option value="rampak" {{ $kelas == 'rampak' ? 'selected' : '' }}>Rampak</option>
                    @endif
                    @if(in_array('reguler', $jenisKelas))
                        <option value="reguler" {{ $kelas == 'reguler' ? 'selected' : '' }}>Reguler</option>
                    @endif
                </select>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-bottom:0; align-self:flex-end;">
                <i class="fa-solid fa-magnifying-glass"></i> Filter
            </button>
            <a href="{{ route('murid.absensi') }}" class="btn btn-secondary" style="align-self:flex-end;">Reset</a>
        </form>
    </div>
</div>

<div class="card" style="margin-top:20px;">
    <div class="card-header">
        <h3><i class="fa-solid fa-list-check"></i> Detail Kehadiran</h3>
    </div>
    <div class="card-body" style="padding:0;">
        @if($absensi->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">
                <i class="fa-solid fa-calendar-xmark" style="font-size:36px;margin-bottom:12px;display:block;"></i>
                Belum ada data absensi untuk periode ini.
            </div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kelas / Jadwal</th>
                        <th>Hari</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($absensi as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($a->tanggal)->format('d M Y') }}</td>
                    <td><strong>{{ $a->jadwal->nama_kelas ?? '-' }}</strong></td>
                    <td>{{ $a->jadwal->hari ?? '-' }}</td>
                    <td>
                        @if($a->status === 'hadir')
                            <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Hadir</span>
                        @else
                            <span class="badge badge-warning"><i class="fa-solid fa-circle-info"></i> Tidak Hadir</span>
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
