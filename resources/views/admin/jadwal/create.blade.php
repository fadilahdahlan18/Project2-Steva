@extends('template.main')
@section('title', 'Tambah Jadwal')
@section('content')
<style>
    /* Styling Header Kartu Burgundy */
    .card-header-steva {
        background-color: #800020;
        color: white;
        padding: 15px 20px;
        border-radius: 12px 12px 0 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card-header-steva h3 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    /* Button Simpan warna Burgundy */
    .btn-burgundy {
        background-color: #800020;
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
    }
    .btn-burgundy:hover {
        background-color: #5c0016;
        color: white;
        box-shadow: 0 4px 12px rgba(128, 0, 32, 0.3);
    }
    /* Input Fokus & Styling */
    .form-control {
        padding: 12px;
        border-radius: 8px;
        border: 1.5px solid #dee2e6;
    }
    .form-control:focus {
        border-color: #800020;
        box-shadow: 0 0 0 0.2rem rgba(128, 0, 32, 0.1);
        outline: none;
    }
    .form-label {
        font-weight: 600;
        color: #444;
        margin-bottom: 8px;
        display: block;
    }
</style>

<div class="page-header" style="margin-bottom: 25px;">
    <h2><i class="fa-solid fa-calendar-plus"></i> Tambah Jadwal Latihan</h2>
    <div class="breadcrumb" style="font-size: 13px;">
        <a href="{{ route('admin.jadwal') }}" style="color: #800020; text-decoration: none; font-weight: 600;">Jadwal</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px; margin: 0 8px; color: #ccc;"></i>
        <span style="color: #666;">Tambah Baru</span>
    </div>
</div>

<div class="card" style="max-width:550px; border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
    <div class="card-header-steva">
        <i class="fa-solid fa-pen-to-square"></i>
        <h3>Form Jadwal Baru</h3>
    </div>

    <div class="card-body" style="padding: 30px;">
        <form method="POST" action="{{ route('admin.jadwal.store') }}">
            @csrf



            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Pelatih Pengajar *</label>
                <select name="pelatih_id" class="form-control form-select {{ $errors->has('pelatih_id') ? 'is-invalid' : '' }}" required>
                    <option value="">-- Pilih Pelatih --</option>
                    @foreach($pelatihList as $pelatih)
                        <option value="{{ $pelatih->id }}" {{ old('pelatih_id')==$pelatih->id ? 'selected' : '' }}>{{ $pelatih->nama }}</option>
                    @endforeach
                </select>
                @error('pelatih_id')<span class="invalid-feedback" style="color: #dc3545; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Kategori Kelas *</label>
                <select name="kategori_kelas" class="form-control form-select {{ $errors->has('kategori_kelas') ? 'is-invalid' : '' }}" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="pemula" {{ old('kategori_kelas')=='pemula' ? 'selected' : '' }}>Pemula</option>
                    <option value="madya" {{ old('kategori_kelas')=='madya' ? 'selected' : '' }}>Madya</option>
                    <option value="ahli" {{ old('kategori_kelas')=='ahli' ? 'selected' : '' }}>Ahli</option>
                </select>
                @error('kategori_kelas')<span class="invalid-feedback" style="color: #dc3545; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Jenis Kelas *</label>
                <select name="jenis_kelas" class="form-control form-select {{ $errors->has('jenis_kelas') ? 'is-invalid' : '' }}" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="rampak" {{ old('jenis_kelas')=='rampak' ? 'selected' : '' }}>Rampak</option>
                    <option value="reguler" {{ old('jenis_kelas')=='reguler' ? 'selected' : '' }}>Reguler</option>
                </select>
                @error('jenis_kelas')<span class="invalid-feedback" style="color: #dc3545; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label">Hari Latihan *</label>
                <select name="hari" class="form-control form-select {{ $errors->has('hari') ? 'is-invalid' : '' }}" required>
                    <option value="">-- Pilih Hari --</option>
                    @foreach(['Jumat','Sabtu','Minggu'] as $hari)
                        <option value="{{ $hari }}" {{ old('hari')==$hari ? 'selected' : '' }}>{{ $hari }}</option>
                    @endforeach
                </select>
                @error('hari')<span class="invalid-feedback" style="color: #dc3545; font-size: 12px;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label class="form-label">Jam Latihan *</label>
                <div style="position: relative;">
                    <span style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #800020;">
                        <i class="fa-regular fa-clock"></i>
                    </span>
                    <input type="text" name="jam"
                        class="form-control {{ $errors->has('jam') ? 'is-invalid' : '' }}"
                        value="{{ old('jam') }}"
                        placeholder="00.00-00.00"
                        pattern="^([01]\d|2[0-3])\.([0-5]\d)-([01]\d|2[0-3])\.([0-5]\d)$"
                        title="Format harus 00.00-00.00 (contoh: 14.00-16.00)"
                        style="padding-left: 35px;"
                        required>
                </div>

                <small style="color: #6c757d; font-size: 11px; margin-top: 5px; display: block;">
                    * Format: 00.00-00.00 (contoh: 14.00-16.00)
                </small>

                @error('jam')
                    <span class="invalid-feedback" style="color: #dc3545; font-size: 12px;">
                        {{ $message }}
                    </span>
                @enderror
            </div>

            <div style="display:flex; gap:12px; margin-top:15px; border-top: 1px solid #eee; padding-top: 20px;">
                <button type="submit" class="btn-burgundy">
                    <i class="fa-solid fa-save"></i> Simpan Jadwal
                </button>
                <a href="{{ route('admin.jadwal') }}" class="btn" style="padding: 10px 20px; border-radius: 8px; font-weight: 600; color: #666; border: 1px solid #ddd; text-decoration: none; background: #f8f9fa;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
