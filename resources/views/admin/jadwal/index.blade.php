@extends('template.main')
@section('title', 'Jadwal Latihan')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-calendar-days"></i> Jadwal Latihan</h2>
    @if(auth()->user()->isAdmin())
    <a href="{{ route('admin.jadwal.create') }}" class="btn btn-gold">
        <i class="fa-solid fa-plus"></i> Tambah Jadwal
    </a>
    @endif
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        @if($jadwal->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">
                <i class="fa-solid fa-calendar-xmark" style="font-size:36px;margin-bottom:12px;display:block;"></i>
                Belum ada jadwal latihan.
            </div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr><th>No</th><th>Nama Kelas</th><th style="text-align:center;">Kategori & Jenis</th><th>Pelatih</th><th>Murid</th><th>Hari</th><th>Jam</th>@if(auth()->user()->isAdmin())<th>Aksi</th>@endif</tr>
                </thead>
                <tbody>
                @foreach($jadwal as $i => $j)
                <tr>
                    <td>{{ $jadwal->firstItem() + $i }}</td>
                    <td><strong>{{ $j->nama_kelas }}</strong></td>
                    <td style="text-align:center; vertical-align:middle;">
                        <div style="display:flex; flex-direction:column; gap:5px; align-items:center; justify-content:center;">
                            @if($j->kategori_kelas)
                                <span class="badge" style="background:#f1f3f5;color:#495057;border:1px solid #dee2e6;padding:3px 8px;border-radius:4px;font-size:10px;">{{ ucfirst($j->kategori_kelas) }}</span>
                            @endif
                            @if($j->jenis_kelas)
                                <span style="font-size:12px;color:#212529;font-weight:700;">{{ strtoupper($j->jenis_kelas) }}</span>
                            @endif
                            @if(!$j->kategori_kelas && !$j->jenis_kelas)
                                <span style="color:#adb5bd;">-</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $j->pelatih->nama ?? '-' }}</td>
                    <td><span class="badge" style="background:#17a2b8;color:#fff;">{{ $j->jumlah_murid }} Orang</span></td>
                    <td>
                        <span class="badge badge-burgundy">{{ $j->hari }}</span>
                    </td>
                    <td><i class="fa-regular fa-clock" style="color:#800020;"></i> {{ $j->jam }} WIB</td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div style="display:flex;gap:5px;">
                            <a href="{{ route('admin.jadwal.edit', $j->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.jadwal.destroy', $j->id) }}"
                                  onsubmit="return confirm('Hapus jadwal ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">{{ $jadwal->links('vendor.pagination.simple') }}</div>
        @endif
    </div>
</div>
@endsection
