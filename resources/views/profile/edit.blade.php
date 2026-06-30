@extends('template.main')
@section('title', 'Update Profil')
@section('content')
<div class="row">
    <div class="col-12" style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">
                <h3><i class="fa-solid fa-user-pen"></i> Update Profil</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div style="text-align: center; margin-bottom: 20px;">
                        @if(auth()->user()->foto)
                            <img id="img-preview" src="{{ auth()->user()->foto_url }}" alt="Foto" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; box-shadow: var(--shadow-sm);">
                        @else
                            <div id="text-preview" style="width: 100px; height: 100px; border-radius: 50%; background: var(--gold); display: inline-flex; align-items: center; justify-content: center; font-size: 36px; font-weight: bold; color: var(--burgundy-dark);">
                                {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                            </div>
                            <img id="img-preview" src="" style="display:none; width: 100px; height: 100px; object-fit: cover; border-radius: 50%; box-shadow: var(--shadow-sm);">
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', auth()->user()->nama) }}" required>
                        @error('nama') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', auth()->user()->username) }}" required placeholder="Username (tanpa spasi)">
                        @error('username') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" value="{{ old('no_hp', auth()->user()->no_hp) }}">
                        @error('no_hp') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password Baru (Opsional)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                        @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Upload Foto Profil Baru (Opsional)</label>
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                        <small style="color: var(--gray-500); font-size: 11px;">Format: JPG, JPEG, PNG. Maksimal 10MB (akan dikompresi otomatis).</small>
                        @error('foto') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-top: 24px; text-align: right;">
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.querySelector('input[name="foto"]');

    if (fotoInput) {
        fotoInput.addEventListener('change', function(e) {
            if(e.target.files && e.target.files[0]) {
                const file = e.target.files[0];

                // Hanya kompres jika file adalah gambar
                if (!file.type.startsWith('image/')) return;

                // Tampilkan loading/saran visual jika perlu, di sini kita langsung proses
                const reader = new FileReader();
                reader.onload = function(evt) {
                    const img = new Image();
                    img.onload = function() {
                        // Batas resolusi maksimum
                        const MAX_WIDTH = 500;
                        const MAX_HEIGHT = 500;
                        let width = img.width;
                        let height = img.height;

                        if (width > height) {
                            if (width > MAX_WIDTH) {
                                height *= MAX_WIDTH / width;
                                width = MAX_WIDTH;
                            }
                        } else {
                            if (height > MAX_HEIGHT) {
                                width *= MAX_HEIGHT / height;
                                height = MAX_HEIGHT;
                            }
                        }

                        const canvas = document.createElement('canvas');
                        canvas.width = width;
                        canvas.height = height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob(function(blob) {
                            // Buat file baru dari blob terkompresi
                            const resizedFile = new File([blob], 'profile_resized.jpg', {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });

                            // Masukkan file baru ke dalam file input input.files
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(resizedFile);
                            fotoInput.files = dataTransfer.files;

                            // Perbarui gambar pratinjau (preview)
                            const imgPreview = document.getElementById('img-preview');
                            const txtPreview = document.getElementById('text-preview');
                            if (txtPreview) txtPreview.style.display = 'none';
                            if (imgPreview) {
                                imgPreview.src = URL.createObjectURL(resizedFile);
                                imgPreview.style.display = 'inline-block';
                            }
                        }, 'image/jpeg', 0.85); // Kualitas 85% untuk efisiensi ukuran
                    };
                    img.src = evt.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection
