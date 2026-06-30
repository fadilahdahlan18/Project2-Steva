@extends('template.main')
@section('title', 'Tambah Pengguna')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-user-plus"></i> Tambah Pengguna</h2>
    <div class="breadcrumb">
        <a href="{{ route('admin.users') }}">Pengguna</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i> Tambah
    </div>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-header"><h3><i class="fa-solid fa-user-plus"></i> Form Data Pengguna</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- Pilihan Peran --}}
            <div class="form-group">
                <label class="form-label">Peran *</label>
                <select name="role" id="roleSelect" class="form-control form-select {{ $errors->has('role') ? 'is-invalid' : '' }}">
                    <option value="murid" {{ old('role','murid')=='murid' ? 'selected' : '' }}>Murid</option>
                    <option value="pelatih" {{ old('role')=='pelatih' ? 'selected' : '' }}>Pelatih</option>
                </select>
                @error('role')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <!-- DATA TAMBAHAN (Kategori & Jenis Kelas) -->
            <div id="form-murid" class="form-group">
                <div style="background: #f9e8ec; padding: 20px; border-radius: 12px; border: 1px solid #800020;">
                    <div class="form-group">
                        <label class="form-label" style="color:#800020;">Kategori Kelas *</label>
                        <select name="kategori_kelas" class="form-control form-select">
                            <option value="pemula" {{ old('kategori_kelas')=='pemula' ? 'selected' : '' }}>Pemula</option>
                            <option value="madya" {{ old('kategori_kelas')=='madya' ? 'selected' : '' }}>Madya</option>
                            <option value="ahli" {{ old('kategori_kelas')=='ahli' ? 'selected' : '' }}>Ahli</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:#800020;">Jenis Kelas *</label>
                        <div style="display:flex; gap:20px; font-size:14px; margin-top:5px; color:#800020; font-weight:600;">
                            <label style="cursor:pointer;"><input type="checkbox" name="jenis_kelas[]" value="rampak" style="accent-color:#800020;"> Rampak</label>
                            <label style="cursor:pointer;"><input type="checkbox" name="jenis_kelas[]" value="reguler" style="accent-color:#800020;"> Reguler</label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Umum --}}
            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" value="{{ old('nama') }}" placeholder="Nama lengkap">
                @error('nama')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Username *</label>
                <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" value="{{ old('username') }}" placeholder="Username (tanpa spasi)">
                @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email * </label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="email@contoh.com">
                <small class="text-muted" style="font-size: 11px;">Kosongkan jika tidak ada email.</small>
            </div>

            <div class="form-group">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx">
                @error('no_hp')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password *</label>
                <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" placeholder="Minimal 6 karakter">
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Foto Profil</label>
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>

            <div style="display:flex;gap:10px;margin-top:15px;">
                <button type="submit" class="btn btn-primary" style="background-color: #800020; border: none;"><i class="fa-solid fa-save"></i> Simpan</button>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    const roleSelect = document.getElementById('roleSelect');
    const formMurid = document.getElementById('form-murid');

    function toggleRoleFields() {
        if (roleSelect.value === 'murid' || roleSelect.value === 'pelatih') {
            formMurid.style.display = 'block';
        } else {
            formMurid.style.display = 'none';
        }
    }

    roleSelect.addEventListener('change', toggleRoleFields);
    window.onload = toggleRoleFields;
</script>
@endsection
