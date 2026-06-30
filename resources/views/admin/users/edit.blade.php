@extends('template.main')
@section('title', 'Edit Pengguna')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-user-pen"></i> Edit Pengguna</h2>
    <div class="breadcrumb">
        <a href="{{ route('admin.users') }}">Pengguna</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i> Edit
    </div>
</div>

<div class="card" style="max-width:640px;">
    <div class="card-header"><h3><i class="fa-solid fa-user-pen"></i> Edit Data — {{ $user->nama }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Peran *</label>
                <select name="role" id="roleSelect" class="form-control form-select">
                    <option value="murid"   {{ $user->role=='murid'   ? 'selected' : '' }}>Murid</option>
                    <option value="pelatih" {{ $user->role=='pelatih' ? 'selected' : '' }}>Pelatih</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Status Akun *</label>
                <select name="status" class="form-control form-select">
                    <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="tidak aktif" {{ old('status', $user->status) == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <!-- DATA TAMBAHAN (Kategori & Jenis Kelas) -->
            <div id="form-murid" class="form-group" style="{{ $user->role == 'murid' || $user->role == 'pelatih' ? '' : 'display:none;' }}">
                <div style="background: #f9e8ec; padding: 20px; border-radius: 12px; border: 1px solid #800020;">
                    <div class="form-group">
                        <label class="form-label" style="color:#800020;">Kategori Kelas *</label>
                        <select name="kategori_kelas" class="form-control form-select">
                            <option value="pemula" {{ old('kategori_kelas', $user->kategori_kelas)=='pemula' ? 'selected' : '' }}>Pemula</option>
                            <option value="madya" {{ old('kategori_kelas', $user->kategori_kelas)=='madya' ? 'selected' : '' }}>Madya</option>
                            <option value="ahli" {{ old('kategori_kelas', $user->kategori_kelas)=='ahli' ? 'selected' : '' }}>Ahli</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" style="color:#800020;">Jenis Kelas *</label>
                        @php $jenis = explode(',', $user->jenis_kelas); @endphp
                        <div style="display:flex; gap:20px; font-size:14px; margin-top:5px; color:#800020; font-weight:600;">
                            <label style="cursor:pointer;"><input type="checkbox" name="jenis_kelas[]" value="rampak" {{ in_array('rampak', $jenis) ? 'checked' : '' }} style="accent-color:#800020;"> Rampak</label>
                            <label style="cursor:pointer;"><input type="checkbox" name="jenis_kelas[]" value="reguler" {{ in_array('reguler', $jenis) ? 'checked' : '' }} style="accent-color:#800020;"> Reguler</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DATA TAMBAHAN KHUSUS PELATIH -->
            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}"
                    value="{{ old('nama', $user->nama) }}">
                @error('nama')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Username *</label>
                <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                    value="{{ old('username', $user->username) }}" placeholder="Username (tanpa spasi)">
                @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                    value="{{ old('email', $user->email) }}">
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">No. HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $user->no_hp) }}">
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru <span style="color:#adb5bd;font-size:12px;">(Kosongkan jika tidak diubah)</span></label>
                <input type="password" name="password" class="form-control" placeholder="Isi untuk mengubah password">
            </div>
            <div class="form-group">
                <label class="form-label">Foto Profil</label>
                @if($user->foto)
                    <div style="margin-bottom:8px;">
                        <img src="{{ $user->foto_url }}" alt="Foto"
                             style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:2px solid #800020;">
                        <small style="color:#6c757d;margin-left:8px;">Foto saat ini</small>
                    </div>
                @endif
                <input type="file" name="foto" class="form-control" accept="image/*">
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Perbarui</button>
                <a href="{{ route('admin.users', ['role'=>$user->role]) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<script>
    const roleSelect = document.getElementById('roleSelect');
    const formMurid = document.getElementById('form-murid');

    roleSelect.addEventListener('change', function() {
        if (this.value === 'murid' || this.value === 'pelatih') {
            formMurid.style.display = 'block';
        } else {
            formMurid.style.display = 'none';
        }
    });
</script>
@endsection
