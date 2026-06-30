@extends('template.main')
@section('title', 'Materi Latihan')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-book-open"></i> Materi Latihan</h2>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        @if($materi->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">
                <i class="fa-solid fa-book" style="font-size:36px;margin-bottom:12px;display:block;"></i>
                Belum ada materi latihan tersedia.
            </div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Materi</th>
                        <th>Pelatih</th>
                        <th>File PDF</th>
                        <th>Video</th>
                        <th>Link Video</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($materi as $i => $m)
                <tr>
                    <td>{{ $materi->firstItem() + $i }}</td>
                    <td><strong>{{ $m->judul }}</strong></td>
                    <td>{{ $m->pelatih->nama ?? '-' }}</td>
                    <td>
                        @if($m->file_pdf)
                            <a href="{{ asset('storage/' . $m->file_pdf) }}" target="_blank" class="badge badge-burgundy" style="text-decoration:none;">
                                <i class="fa-solid fa-file-pdf"></i> Lihat PDF
                            </a>
                        @else
                            <span style="color:#adb5bd;">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        @if($m->file_video)
                            <span class="badge badge-info"><i class="fa-solid fa-video"></i> File Video</span>
                        @else
                            <span style="color:#adb5bd;">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        @if($m->link_video)
                            <a href="{{ $m->link_video }}" target="_blank" class="badge badge-danger" style="text-decoration:none;">
                                <i class="fa-brands fa-youtube"></i> Link Video
                            </a>
                        @else
                            <span style="color:#adb5bd;">Tidak ada</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            @if($m->file_pdf)
                                <a href="{{ asset('storage/' . $m->file_pdf) }}" download class="btn btn-sm btn-secondary" title="Download PDF">
                                    <i class="fa-solid fa-download"></i> PDF
                                </a>
                            @endif
                            
                            @if($m->file_video)
                                <a href="{{ asset('storage/' . $m->file_video) }}" download class="btn btn-sm btn-secondary" title="Download Video">
                                    <i class="fa-solid fa-download"></i> Video
                                </a>
                                <button type="button" class="btn btn-sm btn-primary" onclick="playVideo('{{ asset('storage/' . $m->file_video) }}', '{{ $m->judul }}')" title="Putar Video">
                                    <i class="fa-solid fa-play"></i> Putar
                                </button>
                            @endif
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

<!-- Modal Video (Simple Overlay) -->
<div id="videoModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center; padding:20px;">
    <div style="background:#fff; border-radius:12px; width:100%; max-width:800px; position:relative; overflow:hidden;">
        <div style="padding:15px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; align-items:center;">
            <h3 id="videoTitle" style="font-size:16px; margin:0;">Putar Video</h3>
            <button onclick="closeVideo()" style="background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>
        <div style="padding:0; background:#000; aspect-ratio:16/9;">
            <video id="videoPlayer" controls style="width:100%; height:100%;">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</div>

<script>
function playVideo(url, title) {
    const modal = document.getElementById('videoModal');
    const player = document.getElementById('videoPlayer');
    const titleEl = document.getElementById('videoTitle');
    
    titleEl.textContent = title;
    player.src = url;
    modal.style.display = 'flex';
    player.play();
}

function closeVideo() {
    const modal = document.getElementById('videoModal');
    const player = document.getElementById('videoPlayer');
    
    player.pause();
    player.src = "";
    modal.style.display = 'none';
}

// Close on background click
document.getElementById('videoModal').addEventListener('click', function(e) {
    if (e.target === this) closeVideo();
});
</script>
@endsection
