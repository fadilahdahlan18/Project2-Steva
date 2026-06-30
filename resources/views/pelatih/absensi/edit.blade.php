@extends('template.main')
@section('title', 'Edit Absensi')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-clipboard-pen"></i> Edit Absensi</h2>
    <div class="breadcrumb">
        <a href="{{ route('pelatih.absensi') }}">Absensi</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i> Edit
    </div>
</div>
<div class="card" style="max-width:480px;">
    <div class="card-header"><h3><i class="fa-solid fa-clipboard-pen"></i> Edit Status Absensi</h3></div>
    <div class="card-body">
        <div style="background:#f9e8ec;padding:12px;border-radius:8px;margin-bottom:16px;font-size:13.5px;">
            <strong>{{ $absensi->user->nama ?? '-' }}</strong> —
            {{ $absensi->jadwal->nama_kelas ?? '-' }} —
            {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}
        </div>
        <form method="POST" action="{{ route('pelatih.absensi.update', $absensi->id) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Status Kehadiran</label>
                <div style="display:flex;gap:12px;margin-top:4px;">
                    @foreach(['hadir' => ['color' => 'success', 'label' => 'Hadir'], 'alpha' => ['color' => 'warning', 'label' => 'Tidak Hadir']] as $st => $data)
                    <label style="display:flex;align-items:center;gap:6px;cursor:pointer;font-size:13px;">
                        <input type="radio" name="status" value="{{ $st }}" {{ $absensi->status==$st ? 'checked' : '' }}>
                        <span class="badge badge-{{ $data['color'] }}">{{ $data['label'] }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:12px;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Perbarui</button>
                <a href="{{ route('pelatih.absensi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
