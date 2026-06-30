@extends('template.main')
@section('title', 'Manajemen Pengguna')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-users"></i> Data {{ ucfirst($role) }}</h2>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.users', ['role'=>'murid']) }}"
           class="btn {{ $role=='murid' ? 'btn-primary' : 'btn-secondary' }}">
            <i class="fa-solid fa-user-graduate"></i> Murid
        </a>
        <a href="{{ route('admin.users', ['role'=>'pelatih']) }}"
           class="btn {{ $role=='pelatih' ? 'btn-primary' : 'btn-secondary' }}">
            <i class="fa-solid fa-chalkboard-user"></i> Pelatih
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn btn-gold">
            <i class="fa-solid fa-plus"></i> Tambah
        </a>
    </div>
</div>

<!-- Search -->
<form action="{{ route('admin.users') }}" method="GET" class="filter-form">
    <input type="hidden" name="role" value="{{ $role }}">
    
    <select name="kategori_kelas" class="form-control" style="max-width:180px;">
        <option value="">Semua Kategori</option>
        <option value="pemula" {{ $kategori == 'pemula' ? 'selected' : '' }}>Pemula</option>
        <option value="madya" {{ $kategori == 'madya' ? 'selected' : '' }}>Madya</option>
        <option value="ahli" {{ $kategori == 'ahli' ? 'selected' : '' }}>Ahli</option>
    </select>

    <select name="jenis_kelas" class="form-control" style="max-width:180px;">
        <option value="">Semua Jenis Kelas</option>
        <option value="rampak" {{ $jenis == 'rampak' ? 'selected' : '' }}>Rampak</option>
        <option value="reguler" {{ $jenis == 'reguler' ? 'selected' : '' }}>Reguler</option>
    </select>

    <select name="status" class="form-control" style="max-width:180px;">
        <option value="">Semua Status</option>
        <option value="aktif" {{ ($status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="tidak aktif" {{ ($status ?? '') == 'tidak aktif' ? 'selected' : '' }}>Tidak Aktif</option>
        <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="ditolak" {{ ($status ?? '') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
    </select>

    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i></button>
    @if($kategori || $jenis || ($status ?? ''))<a href="{{ route('admin.users',['role'=>$role]) }}" class="btn btn-secondary">Reset</a>@endif
</form>

<div class="card">
    <div class="card-body" style="padding:0;">
        @if($users->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">
                <i class="fa-solid fa-users-slash" style="font-size:36px;margin-bottom:12px;display:block;"></i>
                Belum ada data {{ $role }}.
            </div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Jenis Kelas</th>
                        <th>No. HP</th>
                        <th>Peran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($users as $i => $u)
                <tr>
                    <td>{{ $users->firstItem() + $i }}</td>
                    <td>
                        @if($u->foto)
                            <img src="{{ $u->foto_url }}" alt="foto"
                                 style="width:38px;height:38px;border-radius:50%;object-fit:cover;border:2px solid #800020;">
                        @else
                            <div style="width:38px;height:38px;border-radius:50%;background:#800020;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:14px;">
                                {{ strtoupper(substr($u->nama,0,1)) }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $u->nama }}</strong>
                        <div style="font-size:11px;color:#6c757d;margin-top:2px;">
                            <i class="fa-solid fa-user-tag" style="font-size:10px;"></i> {{ $u->username }}
                        </div>
                    </td>
                    <td>
                        <span class="badge" style="background:#f1f3f5;color:#495057;border:1px solid #dee2e6;padding:4px 8px;border-radius:4px;font-size:12px;">{{ ucfirst($u->kategori_kelas ?? '-') }}</span>
                    </td>
                    <td>
                        @if($u->jenis_kelas)
                            @foreach(explode(',', $u->jenis_kelas) as $jk)
                                <span style="font-size:12px;color:#495057;display:block;">{{ strtoupper(trim($jk)) }}</span>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $u->no_hp ?? '-' }}</td>
                    <td><span class="badge" style="background:#800020;color:#fff;padding:4px 8px;border-radius:4px;font-size:12px;">{{ ucfirst($u->role) }}</span></td>
                    <td>
                        @if($u->keaktifan_status == 'aktif')
                            <span class="badge" style="background:#28a745;color:#fff;padding:4px 8px;border-radius:4px;font-size:12px;">Aktif</span>
                        @elseif($u->keaktifan_status == 'tidak aktif')
                            <span class="badge" style="background:#dc3545;color:#fff;padding:4px 8px;border-radius:4px;font-size:12px;">Tidak Aktif</span>
                        @else
                            <span class="badge" style="background:#6c757d;color:#fff;padding:4px 8px;border-radius:4px;font-size:12px;">{{ ucfirst($u->keaktifan_status) }}</span>
                        @endif
</td>
                    <td>
                        <div style="display:flex;gap:5px;">
                            @if($u->status == 'pending' || $u->status == 'tidak aktif')
                                <form method="POST" action="{{ route('admin.users.approve', $u->id) }}" onsubmit="return confirm('Aktifkan kembali pengguna ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Aktifkan">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-warning">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.users.destroy', $u->id) }}"
                                  onsubmit="return confirm('Hapus pengguna ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">
            {{ $users->appends(['role'=>$role,'kategori_kelas'=>$kategori,'jenis_kelas'=>$jenis,'status'=>$status])->links('vendor.pagination.simple') }}
        </div>
        @endif
    </div>
</div>
@endsection
