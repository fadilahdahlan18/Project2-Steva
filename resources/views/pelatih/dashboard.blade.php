@extends('template.main')
@section('title', 'Dashboard Pelatih')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-gauge-high"></i> Selamat Datang di Dashboard Pelatih!!!</h2>
    <div class="breadcrumb"><span>Selamat datang, {{ auth()->user()->nama }}</span></div>
</div>

<div class="stats-grid grid-3">
    <div class="stat-card">
        <div class="stat-icon burgundy"><i class="fa-solid fa-book-open"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $totalMateri }}</div>
            <div class="stat-label">Materi Diunggah</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fa-solid fa-calendar-days"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $totalJadwal }}</div>
            <div class="stat-label">Total Jadwal</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="fa-solid fa-user-graduate"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $totalMurid }}</div>
            <div class="stat-label">Total Murid</div>
        </div>
    </div>
</div>

<div class="grid-2col">
    <!-- Monitoring Jadwal -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-calendar-check"></i> Monitoring Jadwal</h3>
            <a href="{{ route('pelatih.jadwal') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="card-body" style="padding:0;">
            @if($jadwal->isEmpty())
                <div style="padding:24px;text-align:center;color:#adb5bd;font-size:13px;">Belum ada jadwal yang dikelola.</div>
            @else
            @foreach($jadwal->take(5) as $j)
            <div style="padding:15px 20px;border-bottom:1px solid #f1f3f5;display:flex;align-items:center;justify-content:space-between;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;background:var(--burgundy-pale);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-music" style="color:var(--burgundy);"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:14px;color:var(--gray-800);">{{ $j->nama_kelas }}</div>
                        <div style="font-size:12px;color:var(--gray-500);margin-top:2px;">
                            <i class="fa-solid fa-calendar-day" style="font-size:10px;"></i> {{ $j->hari }}
                        </div>
                    </div>
                </div>
                <div style="text-align:right;">
                    <div style="font-weight:600;font-size:13px;color:var(--burgundy);">{{ $j->jam }}</div>
                    <div style="font-size:10px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.5px;">WIB</div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>

    <!-- Recent Materi -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-book-open"></i> Materi Terbaru</h3>
            <a href="{{ route('pelatih.materi.create') }}" class="btn btn-sm btn-gold">
                <i class="fa-solid fa-plus"></i> Upload
            </a>
        </div>
        <div class="card-body" style="padding:0;">
            @if($recentMateri->isEmpty())
                <div style="padding:24px;text-align:center;color:#adb5bd;font-size:13px;">Belum ada materi.</div>
            @else
            @foreach($recentMateri as $m)
            <div style="padding:12px 16px;border-bottom:1px solid #f1f3f5;display:flex;align-items:center;gap:12px;">
                <div style="width:44px;height:44px;background:#f9e8ec;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="fa-solid fa-file-pdf" style="color:#800020;"></i>
                </div>
                <div style="min-width:0;flex:1;">
                    <div style="font-weight:600;font-size:13.5px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $m->judul }}</div>
                    <div style="font-size:11px;color:#6c757d;">{{ $m->created_at->diffForHumans() }}</div>
                </div>
                <a href="{{ route('pelatih.materi.edit', $m->id) }}" class="btn btn-sm btn-secondary">
                    <i class="fa-solid fa-pen"></i>
                </a>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<div style="margin-top:20px;">
    <div class="card">
        <div class="card-header"><h3><i class="fa-solid fa-bolt"></i> Aksi Cepat</h3></div>
        <div class="card-body">
            <div style="display:flex; flex-direction:column; gap:20px;">
                <!-- Absensi Actions -->
                <div>
                    <div style="font-size:11px; color:var(--gray-500); text-transform:uppercase; letter-spacing:1px; margin-bottom:10px;">Manajemen Absensi</div>
                    <div style="display:flex; flex-wrap:wrap; gap:10px;">
                        <a href="{{ route('pelatih.absensi.create') }}" class="btn btn-primary">
                            <i class="fa-solid fa-clipboard-list"></i> Input Absensi Baru
                        </a>
                        <a href="{{ route('pelatih.absensi') }}" class="btn btn-secondary">
                            <i class="fa-solid fa-history"></i> Riwayat Absensi
                        </a>
                    </div>
                </div>

                <!-- Quick Materi Upload -->
                <div style="padding-top:20px; border-top:1px solid var(--gray-100);">
                    <div style="font-size:11px; color:var(--gray-500); text-transform:uppercase; letter-spacing:1px; margin-bottom:15px;">Upload Materi Latihan</div>
                    <form action="{{ route('pelatih.materi.create') }}" method="GET">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:600;">Judul Materi *</label>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                <input type="text" name="judul" class="form-control" 
                                    placeholder="Contoh: Teknik Dasar Tari Kipas" 
                                    style="flex:1; min-width:250px;" required>
                                <button type="submit" class="btn btn-gold">
                                    <i class="fa-solid fa-cloud-upload-alt"></i> Lanjut Upload
                                </button>
                            </div>
                            <p style="font-size:11px; color:var(--gray-400); margin-top:8px;">
                                <i class="fa-solid fa-keyboard"></i> Ketik judul materi di atas, lalu klik tombol untuk mengunggah file.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
