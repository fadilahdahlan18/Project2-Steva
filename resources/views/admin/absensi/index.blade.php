@extends('template.main')
@section('title', 'Absensi')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-clipboard-check"></i> Monitor Absensi</h2>
</div>

{{-- FILTER SECTION --}}
<form action="{{ route('admin.absensi') }}" method="GET" class="filter-bar">
    <div class="form-group">
        <label class="form-label">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $tanggal }}">
    </div>
    <div class="form-group">
        <label class="form-label">Kelas</label>
        <select name="jadwal_id" id="jadwal_id" class="form-control form-select" style="min-width:180px;">
            <option value="">-- Pilih Kelas --</option>
            @foreach($jadwalList as $j)
                <option value="{{ $j->id }}" {{ $jadwal_id==$j->id ? 'selected' : '' }}>
                    {{ preg_replace('/\s*\([A-Za-z]+\)$/', '', $j->nama_kelas) }} ({{ ucfirst($j->hari) }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group" style="align-self:flex-end;">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Tampilkan Murid</button>
        <a href="{{ route('admin.absensi') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

@if($jadwal_id && $tanggal)
<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
        <h3 style="display:flex; align-items:center; flex-wrap:wrap; gap:12px; margin:0;">
            <span><i class="fa-solid fa-users"></i> Daftar Murid</span>
            @if($selectedJadwal && $selectedJadwal->pelatih)
                <span class="badge badge-secondary" style="background:#f1f5f9; color:#475569; border: 1px solid #cbd5e1; text-transform:none; font-size:12px; font-weight:600; padding:4px 10px; display:inline-flex; align-items:center; gap:6px;">
                    <i class="fa-solid fa-chalkboard-user" style="color:var(--burgundy);"></i>
                    Pelatih Pengampu: <strong>{{ $selectedJadwal->pelatih->nama }}</strong>
                </span>
            @endif
        </h3>
        <span class="badge" style="background:#800020; color:#fff; font-size:14px; padding:6px 12px; border-radius:6px;">
            Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        @if($muridList->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">
                <i class="fa-solid fa-user-slash" style="font-size:36px;margin-bottom:12px;display:block;"></i>
                Tidak ada murid yang terdaftar di kelas ini.
            </div>
        @else
            {{-- DATA TAMPILAN (READ-ONLY) --}}
            <div class="table-responsive">
                <table class="steva-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">No</th>
                            <th>Nama Murid</th>
                            <th style="width:200px;">Status Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($muridList as $i => $murid)
                        @php
                            $currentStatus = $absensiMap[$murid->id] ?? 'belum';
                        @endphp
                        <tr>
                            <td>{{ $muridList->firstItem() + $i }}</td>
                            <td><strong>{{ $murid->nama }}</strong></td>
                            <td>
                                @if($currentStatus == 'hadir')
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Hadir</span>
                                @elseif($currentStatus == 'izin' || $currentStatus == 'tidak hadir' || $currentStatus == 'alpha')
                                    <span class="badge badge-warning" style="background:#ffc107; color:#856404;"><i class="fa-solid fa-circle-info"></i> Tidak Hadir</span>
                                @else
                                    <span class="badge badge-secondary" style="background:#e9ecef; color:#6c757d;"><i class="fa-solid fa-question-circle"></i> Belum Absen</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if ($muridList->hasPages())
                <div style="padding:16px 20px; display:flex; justify-content:center; border-top:1px solid var(--gray-200);">
                    {{ $muridList->links('vendor.pagination.simple') }}
                </div>
            @endif
        @endif
    </div>
</div>
@else
<div class="card" style="background:#f8f9fa; border:1px dashed #ced4da;">
    <div class="card-body" style="text-align:center; padding:40px; color:#6c757d;">
        <i class="fa-solid fa-arrow-pointer" style="font-size:36px; margin-bottom:12px; display:block;"></i>
        Silakan pilih Tanggal dan Kelas terlebih dahulu untuk memantau data absensi.
    </div>
</div>
@endif
@endsection