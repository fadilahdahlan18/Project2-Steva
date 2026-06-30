@extends('template.main')
@section('title', 'Edit Informasi Rekening')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-credit-card"></i> Edit Informasi Rekening</h2>
    <div class="breadcrumb">
        <a href="{{ route('admin.pembayaran') }}">Pembayaran</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i> Edit Rekening
    </div>
</div>

<div class="card" style="max-width:540px;">
    <div class="card-header"><h3><i class="fa-solid fa-pen-to-square"></i> Form Informasi Rekening</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.rekening.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Nama Bank *</label>
                <input type="text" name="nama_bank" class="form-control {{ $errors->has('nama_bank') ? 'is-invalid' : '' }}" value="{{ old('nama_bank', $rekening->nama_bank) }}" placeholder="Contoh: BCA, Mandiri, BRI" required>
                @error('nama_bank')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Rekening *</label>
                <input type="text" name="nomor_rekening" class="form-control {{ $errors->has('nomor_rekening') ? 'is-invalid' : '' }}" value="{{ old('nomor_rekening', $rekening->nomor_rekening) }}" placeholder="Masukkan nomor rekening" required>
                @error('nomor_rekening')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nama Pemilik Rekening *</label>
                <input type="text" name="nama_pemilik" class="form-control {{ $errors->has('nama_pemilik') ? 'is-invalid' : '' }}" value="{{ old('nama_pemilik', $rekening->nama_pemilik) }}" placeholder="Masukkan nama pemilik rekening" required>
                @error('nama_pemilik')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div style="display:flex;gap:10px;margin-top:24px;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
                <a href="{{ route('admin.pembayaran') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
