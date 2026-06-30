@extends('template.main')
@section('title', 'Edit Jadwal')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-calendar-pen"></i> Edit Jadwal</h2>
    <div class="breadcrumb">
        <a href="{{ route('admin.jadwal') }}">Jadwal</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i> Edit
    </div>
</div>
<div class="card" style="max-width:520px;">
    <div class="card-header"><h3><i class="fa-solid fa-calendar-pen"></i> Edit — {{ $jadwal->nama_kelas }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.jadwal.update', $jadwal->id) }}">
            @csrf @method('PUT')

            <div class="form-group">
                <label class="form-label">Pelatih Pengajar *</label>
                <select name="pelatih_id" class="form-control form-select" required>
                    <option value="">-- Pilih Pelatih --</option>
                    @foreach($pelatihList as $pelatih)
                        <option value="{{ $pelatih->id }}" {{ old('pelatih_id', $jadwal->pelatih_id)==$pelatih->id ? 'selected' : '' }}>{{ $pelatih->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Kategori Kelas *</label>
                <select name="kategori_kelas" class="form-control form-select" required>
                    <option value="pemula" {{ old('kategori_kelas', $jadwal->kategori_kelas)=='pemula' ? 'selected' : '' }}>Pemula</option>
                    <option value="madya" {{ old('kategori_kelas', $jadwal->kategori_kelas)=='madya' ? 'selected' : '' }}>Madya</option>
                    <option value="ahli" {{ old('kategori_kelas', $jadwal->kategori_kelas)=='ahli' ? 'selected' : '' }}>Ahli</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Jenis Kelas *</label>
                <select name="jenis_kelas" class="form-control form-select" required>
                    <option value="rampak" {{ old('jenis_kelas', $jadwal->jenis_kelas)=='rampak' ? 'selected' : '' }}>Rampak</option>
                    <option value="reguler" {{ old('jenis_kelas', $jadwal->jenis_kelas)=='reguler' ? 'selected' : '' }}>Reguler</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Hari *</label>
                <select name="hari" class="form-control form-select">
                    @foreach(['Jumat','Sabtu','Minggu'] as $hari)
                        <option value="{{ $hari }}" {{ $jadwal->hari==$hari ? 'selected' : '' }}>{{ $hari }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Jam Latihan *</label>
                <input type="text" name="jam" class="form-control {{ $errors->has('jam') ? 'is-invalid' : '' }}" 
                    value="{{ old('jam', $jadwal->jam) }}" placeholder="14.00-16.00" required pattern="^([01]\d|2[0-3])\.([0-5]\d)-([01]\d|2[0-3])\.([0-5]\d)$" title="Format harus 00.00-00.00 (contoh: 14.00-16.00)">
                <small style="color: #6c757d; font-size: 11px; margin-top: 5px; display: block;">
                    * Format: 00.00-00.00 (Misal: 14.00-16.00)
                </small>
                @error('jam')<span class="invalid-feedback" style="color: #dc3545; font-size: 12px;">{{ $message }}</span>@enderror
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Perbarui</button>
                <a href="{{ route('admin.jadwal') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
