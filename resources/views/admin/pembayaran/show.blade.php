@extends('template.main')
@section('title', 'Detail Pembayaran')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-receipt"></i> Detail Pembayaran</h2>
    <a href="{{ route('admin.pembayaran') }}" class="btn btn-secondary">
        <i class="fa-solid fa-arrow-left"></i> Kembali
    </a>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:900px;">
    <div class="card">
        <div class="card-header"><h3><i class="fa-solid fa-info-circle"></i> Informasi Pembayaran</h3></div>
        <div class="card-body">
            <table style="width:100%;font-size:13.5px;border-collapse:collapse;">
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 0;color:#6c757d;width:45%;">Murid</td>
                    <td style="padding:10px 0;font-weight:600;">{{ $pembayaran->user->nama ?? '-' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 0;color:#6c757d;">Email</td>
                    <td style="padding:10px 0;">{{ $pembayaran->user->email ?? '-' }}</td>
                </tr>
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 0;color:#6c757d;">Tanggal</td>
                    <td style="padding:10px 0;">{{ \Carbon\Carbon::parse($pembayaran->tanggal)->format('d MMMM Y') }}</td>
                </tr>
                <tr style="border-bottom:1px solid #f1f3f5;">
                    <td style="padding:10px 0;color:#6c757d;">Jumlah</td>
                    <td style="padding:10px 0;font-weight:700;color:#800020;font-size:16px;">
                        Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#6c757d;">Status</td>
                    <td style="padding:10px 0;">
                        @if($pembayaran->status === 'disetujui')
                            <span class="badge badge-success">Disetujui</span>
                        @elseif($pembayaran->status === 'ditolak')
                            <span class="badge badge-danger">Ditolak</span>
                        @else
                            <span class="badge badge-warning">Menunggu Verifikasi</span>
                        @endif
                    </td>
                </tr>
            </table>

            @if($pembayaran->status === 'pending')
            <div style="display:flex;gap:10px;margin-top:20px;">
                <form method="POST" action="{{ route('admin.pembayaran.validasi', $pembayaran->id) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="disetujui">
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Setujui</button>
                </form>
                <form method="POST" action="{{ route('admin.pembayaran.validasi', $pembayaran->id) }}">
                    @csrf @method('PATCH')
                    <input type="hidden" name="status" value="ditolak">
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak?')">
                        <i class="fa-solid fa-xmark"></i> Tolak
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    @if($pembayaran->bukti_transfer)
    <div class="card">
        <div class="card-header"><h3><i class="fa-solid fa-image"></i> Bukti Transfer</h3></div>
        <div class="card-body" style="text-align:center;">
            <img src="{{ asset('storage/'.$pembayaran->bukti_transfer) }}" alt="Bukti Transfer"
                 style="max-width:100%;border-radius:8px;border:2px solid #dee2e6;">
            <div style="margin-top:12px;">
                <a href="{{ asset('storage/'.$pembayaran->bukti_transfer) }}" target="_blank" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-external-link"></i> Buka di Tab Baru
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
