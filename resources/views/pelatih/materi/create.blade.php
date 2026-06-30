@extends('template.main')
@section('title', 'Upload Materi')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-upload"></i> Upload Materi Baru</h2>
    <div class="breadcrumb">
        <a href="{{ route('pelatih.materi') }}">Materi</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i> Upload
    </div>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-header"><h3><i class="fa-solid fa-upload"></i> Form Upload Materi</h3></div>
    <div class="card-body">
        {{-- Menambahkan ID pada form untuk script loading --}}
        <form id="uploadMateriForm" method="POST" action="{{ route('pelatih.materi.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group" style="position: relative; z-index: 10;">
                <label class="form-label">Judul Materi *</label>
                <input type="text" name="judul"
                    class="form-control {{ $errors->has('judul') ? 'is-invalid' : '' }}"
                    value="{{ old('judul', $judul ?? '') }}"
                    placeholder="Contoh: Teknik Dasar Tari Kipas"
                    style="position: relative; z-index: 11;">
                @error('judul')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">File PDF</label>
                <div style="border:2px dashed #dee2e6;border-radius:8px;padding:20px;text-align:center;cursor:pointer;transition:border-color .2s;position:relative;"
                     onmouseover="this.style.borderColor='#800020'" onmouseout="this.style.borderColor='#dee2e6'">
                    <i class="fa-solid fa-file-pdf" style="font-size:32px;color:#800020;margin-bottom:8px;display:block;"></i>
                    <div style="font-size:13px;color:#6c757d;">Klik atau seret file PDF ke sini</div>
                    <div style="font-size:11px;color:#adb5bd;margin-top:4px;">Format: PDF, Maks. 10MB</div>
                    <input type="file" name="file_pdf" accept=".pdf" style="opacity:0;position:absolute;width:100%;height:100%;top:0;left:0;cursor:pointer;">
                </div>
                <div id="pdfName" style="font-size:12px;color:#800020;margin-top:6px;display:none;"></div>
                @error('file_pdf')<span class="invalid-feedback" style="display:block;">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Upload Video Latihan (MP4/MOV)</label>
                <div style="border:2px dashed #dee2e6;border-radius:8px;padding:20px;text-align:center;cursor:pointer;position:relative;transition:border-color .2s;"
                     onmouseover="this.style.borderColor='#800020'" onmouseout="this.style.borderColor='#dee2e6'">
                    <i class="fa-solid fa-file-video" style="font-size:32px;color:#800020;margin-bottom:8px;display:block;"></i>
                    <div style="font-size:13px;color:#6c757d;">Klik atau seret file Video ke sini</div>
                    <div style="font-size:11px;color:#adb5bd;margin-top:4px;">Format: MP4, MOV, Maks. 50MB</div>
                    <input type="file" name="file_video" accept="video/*" style="opacity:0;position:absolute;width:100%;height:100%;top:0;left:0;cursor:pointer;">
                </div>
                <div id="videoName" style="font-size:12px;color:#800020;margin-top:6px;display:none;"></div>
                @error('file_video')<span class="invalid-feedback" style="display:block;">{{ $message }}</span>@enderror
            </div>

            <div class="divider" style="text-align:center; margin:15px 0; font-size:12px; color:#adb5bd;">ATAU</div>

            <div class="form-group">
                <label class="form-label">Link Video Koreografi (YouTube/Drive)</label>
                <div style="position:relative;">
                    <i class="fa-brands fa-youtube" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#dc3545;font-size:16px;"></i>
                    <input type="url" name="link_video" class="form-control {{ $errors->has('link_video') ? 'is-invalid' : '' }}"
                        value="{{ old('link_video') }}" placeholder="https://youtube.com/watch?v=..."
                        style="padding-left:38px;">
                </div>
                @error('link_video')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div style="display:flex;gap:10px;margin-top:8px;">
                {{-- Menambahkan ID pada tombol upload --}}
                <button type="submit" id="btnSubmit" class="btn btn-primary"><i class="fa-solid fa-cloud-upload-alt"></i> Upload</button>
                <a href="{{ route('pelatih.materi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
<script>
document.querySelector('input[name="file_pdf"]').addEventListener('change', function() {
    const nameEl = document.getElementById('pdfName');
    if (this.files.length > 0) {
        nameEl.textContent = '✓ PDF: ' + this.files[0].name;
        nameEl.style.display = 'block';
    }
});
document.querySelector('input[name="file_video"]').addEventListener('change', function() {
    const nameEl = document.getElementById('videoName');
    if (this.files.length > 0) {
        nameEl.textContent = '✓ Video: ' + this.files[0].name;
        nameEl.style.display = 'block';
    }
});

// Script Tambahan untuk Loading
document.getElementById('uploadMateriForm').addEventListener('submit', function() {
    const btn = document.getElementById('btnSubmit');
    btn.disabled = true; // Biar ga klik dua kali
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menghubungkan...';
});
</script>
@endsection
