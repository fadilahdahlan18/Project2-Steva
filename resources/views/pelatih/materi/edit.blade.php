@extends('template.main')
@section('title', 'Edit Materi')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-file-pen"></i> Edit Materi</h2>
    <div class="breadcrumb">
        <a href="{{ route('pelatih.materi') }}">Materi</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i> Edit
    </div>
</div>
<div class="card" style="max-width:600px;">
    <div class="card-header"><h3><i class="fa-solid fa-file-pen"></i> Edit — {{ $materi->judul }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('pelatih.materi.update', $materi->id) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Judul Materi *</label>
                <input type="text" name="judul" class="form-control"
                    value="{{ old('judul', $materi->judul) }}">
            </div>
            <div class="form-group">
                <label class="form-label">File PDF</label>
                @if($materi->file_pdf)
                    <div style="margin-bottom:8px;padding:10px;background:#f9e8ec;border-radius:6px;display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-file-pdf" style="color:#800020;font-size:18px;"></i>
                        <span style="font-size:13px;">File PDF ada. Upload baru untuk mengganti.</span>
                        <a href="{{ asset('storage/'.$materi->file_pdf) }}" target="_blank" class="btn btn-sm btn-secondary" style="margin-left:auto;">
                            <i class="fa-solid fa-eye"></i> Lihat
                        </a>
                    </div>
                @endif
                <input type="file" name="file_pdf" class="form-control" accept=".pdf">
                <small style="color:#6c757d;font-size:11px;">Maks. 10MB. Kosongkan jika tidak ingin mengganti.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Upload Video Latihan (MP4/MOV)</label>
                @if($materi->file_video)
                    <div style="margin-bottom:8px;padding:10px;background:#f9e8ec;border-radius:6px;display:flex;align-items:center;gap:8px;">
                        <i class="fa-solid fa-file-video" style="color:#800020;font-size:18px;"></i>
                        <span style="font-size:13px;">File video ada. Upload baru untuk mengganti.</span>
                        <a href="{{ asset('storage/'.$materi->file_video) }}" target="_blank" class="btn btn-sm btn-secondary" style="margin-left:auto;">
                            <i class="fa-solid fa-eye"></i> Lihat
                        </a>
                    </div>
                @endif
                <input type="file" name="file_video" class="form-control" accept="video/*">
                <small style="color:#6c757d;font-size:11px;">Maks. 50MB. Kosongkan jika tidak ingin mengganti.</small>
            </div>
            <div class="form-group">
                <label class="form-label">Link Video Koreografi</label>
                <div style="position:relative;">
                    <i class="fa-brands fa-youtube" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#dc3545;font-size:16px;"></i>
                    <input type="url" name="link_video" class="form-control"
                        value="{{ old('link_video', $materi->link_video) }}"
                        placeholder="https://youtube.com/watch?v=..."
                        style="padding-left:38px;">
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px;">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Perbarui</button>
                <a href="{{ route('pelatih.materi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
