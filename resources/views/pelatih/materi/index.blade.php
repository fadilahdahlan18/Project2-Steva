@extends('template.main')
@section('title', 'Materi Latihan')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-book-open"></i> Materi Latihan</h2>
    <a href="{{ route('pelatih.materi.create') }}" class="btn btn-gold">
        <i class="fa-solid fa-upload"></i> Upload Materi
    </a>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        @if($materi->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">
                <i class="fa-solid fa-book-open" style="font-size:36px;margin-bottom:12px;display:block;"></i>
                Belum ada materi yang diunggah.
            </div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr><th>No</th><th>Judul Materi</th><th>PDF</th><th>Video</th><th>Link Video</th><th>Tanggal Upload</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                @foreach($materi as $i => $m)
                <tr>
                    <td>{{ $materi->firstItem() + $i }}</td>
                    <td><strong>{{ $m->judul }}</strong></td>
                    <td>
                        @if($m->file_pdf)
                            <a href="{{ asset('storage/'.$m->file_pdf) }}" target="_blank" class="btn btn-sm btn-danger">
                                <i class="fa-solid fa-file-pdf"></i> PDF
                            </a>
                        @else <span style="color:#adb5bd;">—</span> @endif
                    </td>
                    <td>
                        @if($m->file_video)
                            <a href="{{ asset('storage/'.$m->file_video) }}" target="_blank" class="btn btn-sm btn-primary">
                                <i class="fa-solid fa-video"></i> Video
                            </a>
                        @else <span style="color:#adb5bd;">—</span> @endif
                    </td>
                    <td>
                        @if($m->link_video)
                            <a href="{{ $m->link_video }}" target="_blank" class="btn btn-sm btn-danger">
                                <i class="fa-brands fa-youtube"></i> Link
                            </a>
                        @else <span style="color:#adb5bd;">—</span> @endif
                    </td>
                    <td>{{ $m->created_at->format('d M Y') }}</td>
                    <td>
                        <div style="display:flex;gap:5px;">
                            <a href="{{ route('pelatih.materi.edit', $m->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('pelatih.materi.destroy', $m->id) }}"
                                  onsubmit="return confirm('Hapus materi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">{{ $materi->links('vendor.pagination.simple') }}</div>
        @endif
    </div>
</div>
@endsection
