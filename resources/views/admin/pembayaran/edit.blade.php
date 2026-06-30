@extends('template.main')
@section('title', 'Edit Pembayaran')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-pen"></i> Edit Data Pembayaran</h2>
    <a href="{{ route('admin.pembayaran') }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Kelas Pembayaran *</label>
                <select name="jadwal_id" class="form-control" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                    @foreach($jadwalList as $jadwal)
                        <option value="{{ $jadwal->id }}" {{ old('jadwal_id', $pembayaran->jadwal_id) == $jadwal->id ? 'selected' : '' }}>
                            {{ $jadwal->nama_kelas }} ({{ ucfirst($jadwal->hari) }})
                        </option>
                    @endforeach
                </select>
                @error('jadwal_id') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Pilih Murid *</label>
                <select name="user_id" class="form-control" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                    <option value="" disabled>-- Pilih Murid --</option>
                    @foreach($murids as $murid)
                        <option value="{{ $murid->id }}" {{ old('user_id', $pembayaran->user_id) == $murid->id ? 'selected' : '' }}>
                            {{ $murid->nama }} 
                            @if($murid->kategori_kelas) ({{ ucfirst($murid->kategori_kelas) }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('user_id') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Tanggal Pertemuan *</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $pembayaran->tanggal) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                @error('tanggal') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Jumlah Pembayaran (Rp) *</label>
                <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $pembayaran->jumlah) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                <p style="font-size:11px; color:#6c757d; margin-top:5px;">Nominal pembayaran otomatis terisi Rp 20.000, namun tetap dapat diubah jika diperlukan.</p>
                @error('jumlah') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Metode Pembayaran *</label>
                <select name="metode" class="form-control" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                    <option value="transfer" {{ old('metode', $pembayaran->metode) == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                    <option value="qr" {{ old('metode', $pembayaran->metode) == 'qr' ? 'selected' : '' }}>Scan QRIS</option>
                    <option value="cash" {{ old('metode', $pembayaran->metode) == 'cash' ? 'selected' : '' }}>Bayar Tunai (Cash)</option>
                </select>
                @error('metode') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Status Pembayaran *</label>
                <select name="status" class="form-control" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                    <option value="disetujui" {{ old('status', $pembayaran->status) == 'disetujui' ? 'selected' : '' }}>Lunas</option>
                    <option value="pending" {{ old('status', $pembayaran->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="ditolak" {{ old('status', $pembayaran->status) == 'ditolak' ? 'selected' : '' }}>Belum Lunas</option>
                </select>
                @error('status') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Keterangan / Catatan</label>
                <textarea name="keterangan" class="form-control" rows="3" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">{{ old('keterangan', $pembayaran->keterangan) }}</textarea>
                @error('keterangan') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Bukti Transfer</label>
                @if($pembayaran->bukti_transfer)
                    <div style="margin-bottom: 10px;">
                        <img src="{{ asset('storage/'.$pembayaran->bukti_transfer) }}" alt="Bukti Transfer" style="max-height: 150px; border-radius: 8px; border: 1px solid #ddd;">
                    </div>
                @endif
                <input type="file" name="bukti_transfer" class="form-control" accept="image/*" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                <p style="font-size:11px; color:#6c757d; margin-top:5px;">Abaikan jika tidak ingin mengubah gambar. Hanya format gambar (jpg, jpeg, png). Maksimal 2MB.</p>
                @error('bukti_transfer') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-warning" style="width: 100%; padding: 12px; font-weight: bold;">Update Pembayaran</button>
        </form>
    </div>
</div>
@endsection
