@extends('template.main')
@section('title', 'Kelola Pembayaran')
@section('content')
<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <h2><i class="fa-solid fa-money-bill-wave"></i> Data Pembayaran Pertemuan</h2>
    <div style="display:flex; gap:10px;">
        <button type="button" class="btn btn-secondary" onclick="document.getElementById('infoRekening').style.display = document.getElementById('infoRekening').style.display === 'none' ? 'block' : 'none'">
            <i class="fa-solid fa-info-circle"></i> Info Rekening
        </button>
        <a href="{{ route('admin.pembayaran.create') }}" class="btn btn-gold">
            <i class="fa-solid fa-plus"></i> Tambah Pembayaran
        </a>
    </div>
</div>

<!-- Info Rekening -->
<div id="infoRekening" class="card" style="display:none; margin-bottom:20px; border:1.5px solid #c9a84c;">
    <div class="card-body" style="background:#fffdf0; display:flex; flex-wrap:wrap; gap:20px; align-items:center;">
        <div style="flex:1; display:grid;grid-template-columns:1fr 1fr;gap:16px;font-size:14px; min-width:280px;">
            <div>
                <div style="color:#6c757d;font-size:12px;margin-bottom:4px;">Bank / Atas Nama</div>
                <div style="font-weight:700;">{{ $rekening->nama_bank ?? 'BCA' }} / {{ $rekening->nama_pemilik ?? 'Eva Tania' }}</div>
            </div>
            <div>
                <div style="color:#6c757d;font-size:12px;margin-bottom:4px;">No. Rekening</div>
                <div style="font-weight:700;font-family:monospace;font-size:16px;">{{ $rekening->nomor_rekening ?? '0551637106' }}</div>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:16px;">
            <div>
                <a href="{{ route('admin.rekening.edit') }}" class="btn btn-sm btn-gold">
                    <i class="fa-solid fa-pen-to-square"></i> Ubah Rekening
                </a>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('admin.pembayaran') }}" method="GET" class="filter-form">
    <div class="form-group">
        <label class="form-label">Kelas</label>
        <select name="jadwal_id" class="form-control form-select" style="min-width:200px;">
            <option value="">-- Pilih Kelas --</option>
            @foreach($jadwalList as $j)
                @php
                    $optionValue = $j->kategori_kelas . '|' . $j->jenis_kelas . '|' . $j->hari;
                @endphp
                <option value="{{ $optionValue }}" {{ $jadwal_id === $optionValue ? 'selected' : '' }}>
                    {{ $j->nama_kelas }} ({{ ucfirst($j->hari) }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Tanggal Pertemuan</label>
        <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" style="min-width:160px;">
    </div>
    <div class="form-group" style="align-self:flex-end;">
        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Tampilkan Murid</button>
        <a href="{{ route('admin.pembayaran') }}" class="btn btn-secondary">Reset</a>
    </div>
</form>

@if(request('status') === 'pending' && (!$jadwal_id || !$tanggal))
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3><i class="fa-solid fa-clock"></i> Daftar Pembayaran Menunggu Persetujuan</h3>
    </div>
    <div class="card-body" style="padding:0;">
        @if($pendingPayments->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">
                <i class="fa-solid fa-circle-check" style="font-size:36px;margin-bottom:12px;display:block;color:#28a745;"></i>
                Tidak ada pembayaran yang menunggu persetujuan.
            </div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Murid</th>
                        <th>Kelas</th>
                        <th>Tanggal Pertemuan</th>
                        <th>Jumlah Bayar</th>
                        <th>Metode</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($pendingPayments as $i => $p)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $p->user->nama ?? '-' }}</strong></td>
                        <td>{{ $p->jadwal->nama_kelas ?? 'Umum' }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                        <td>Rp {{ number_format($p->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($p->metode === 'qr')
                                <span class="badge badge-info"><i class="fa-solid fa-qrcode"></i> QRIS</span>
                            @elseif($p->metode === 'cash')
                                <span class="badge badge-secondary"><i class="fa-solid fa-money-bill"></i> Tunai</span>
                            @else
                                <span class="badge badge-primary" style="background:var(--burgundy);color:white;"><i class="fa-solid fa-building-columns"></i> Transfer</span>
                            @endif
                            @if($p->bukti_transfer)
                                <a href="{{ asset('storage/'.$p->bukti_transfer) }}" target="_blank" title="Lihat Bukti" style="margin-left:5px; color:#6c757d;">
                                    <i class="fa-solid fa-image"></i>
                                </a>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;gap:5px;flex-wrap:wrap;">
                                <a href="{{ route('admin.pembayaran.edit', $p->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fa-solid fa-pen"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.pembayaran.validasi', $p->id) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="disetujui">
                                    <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.pembayaran.validasi', $p->id) }}" style="display:inline;">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="ditolak">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Tolak" onclick="return confirm('Tolak pembayaran ini?')">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.pembayaran.destroy', $p->id) }}" style="display:inline;" onsubmit="return confirm('Hapus data pembayaran ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@elseif($jadwal_id && $tanggal)
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h3><i class="fa-solid fa-users"></i> Status Pembayaran Kelas</h3>
        <span class="badge" style="background:#800020; color:#fff; font-size:14px; padding:6px 12px; border-radius:6px;">
            Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}
        </span>
    </div>
    <div class="card-body" style="padding:0;">
        @if($muridList->isEmpty())
            <div style="padding:40px;text-align:center;color:#adb5bd;">
                <i class="fa-solid fa-user-slash" style="font-size:36px;margin-bottom:12px;display:block;"></i>
                Tidak ada murid yang terdaftar di kelas ini.
            </div>
        @else
        <div class="table-responsive">
            <table class="steva-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Murid</th>
                        <th>Jumlah Bayar</th>
                        <th>Metode</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($muridList as $i => $murid)
                    @php
                        $pembayaran = $pembayaranMap[$murid->id] ?? null;
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td><strong>{{ $murid->nama }}</strong></td>
                        
                        @if($pembayaran)
                            <td>Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</td>
                            <td>
                                @if($pembayaran->metode === 'qr')
                                    <span class="badge badge-info"><i class="fa-solid fa-qrcode"></i> QRIS</span>
                                @elseif($pembayaran->metode === 'cash')
                                    <span class="badge badge-secondary"><i class="fa-solid fa-money-bill"></i> Tunai</span>
                                @else
                                    <span class="badge badge-primary" style="background:var(--burgundy);color:white;"><i class="fa-solid fa-building-columns"></i> Transfer</span>
                                @endif
                                @if($pembayaran->bukti_transfer)
                                    <a href="{{ asset('storage/'.$pembayaran->bukti_transfer) }}" target="_blank" title="Lihat Bukti" style="margin-left:5px; color:#6c757d;">
                                        <i class="fa-solid fa-image"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if($pembayaran->status === 'disetujui')
                                    <span class="badge badge-success" style="background:#28a745;color:white;padding:4px 8px;border-radius:4px;font-size:12px;">Lunas</span>
                                @elseif($pembayaran->status === 'ditolak')
                                    <span class="badge badge-danger" style="background:#dc3545;color:white;padding:4px 8px;border-radius:4px;font-size:12px;">Belum Lunas (Ditolak)</span>
                                @else
                                    <span class="badge badge-warning" style="background:#ffc107;color:black;padding:4px 8px;border-radius:4px;font-size:12px;">Pending (Menunggu)</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex;gap:5px;flex-wrap:wrap;">
                                    <a href="{{ route('admin.pembayaran.edit', $pembayaran->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    @if($pembayaran->status === 'pending')
                                    <form method="POST" action="{{ route('admin.pembayaran.validasi', $pembayaran->id) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="disetujui">
                                        <button type="submit" class="btn btn-sm btn-success" title="Setujui">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.pembayaran.validasi', $pembayaran->id) }}" style="display:inline;">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" value="ditolak">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Tolak" onclick="return confirm('Tolak pembayaran ini?')">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form method="POST" action="{{ route('admin.pembayaran.destroy', $pembayaran->id) }}" style="display:inline;" onsubmit="return confirm('Hapus data pembayaran ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        @else
                            <td style="color:#adb5bd;">-</td>
                            <td style="color:#adb5bd;">-</td>
                            <td>
                                <span class="badge badge-danger" style="background:#dc3545;color:white;padding:4px 8px;border-radius:4px;font-size:12px;">Belum Lunas</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.pembayaran.create', ['user_id' => $murid->id, 'jadwal_id' => $jadwal_id, 'tanggal' => $tanggal]) }}" class="btn btn-sm btn-gold">
                                    <i class="fa-solid fa-plus"></i> Input Bayar
                                </a>
                            </td>
                        @endif
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@else
<div class="card" style="background:#f8f9fa; border:1px dashed #ced4da;">
    <div class="card-body" style="text-align:center; padding:40px; color:#6c757d;">
        <i class="fa-solid fa-arrow-pointer" style="font-size:36px; margin-bottom:12px; display:block;"></i>
        Silakan pilih <strong>Kelas</strong> dan <strong>Tanggal Pertemuan</strong> terlebih dahulu untuk melihat status pembayaran murid.
    </div>
</div>
@endif
@endsection