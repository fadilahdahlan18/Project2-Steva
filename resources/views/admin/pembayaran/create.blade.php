@extends('template.main')
@section('title', 'Tambah Pembayaran')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-plus"></i> Tambah Data Pembayaran</h2>
    <a href="{{ route('admin.pembayaran', ['jadwal_id' => $jadwal_id, 'tanggal' => $tanggal]) }}" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Kembali</a>
</div>

<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form action="{{ route('admin.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Pilih Kelas Dahulu *</label>
                <select name="jadwal_id" id="jadwal_id" class="form-control" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                    <option value="" disabled {{ !$jadwal_id ? 'selected' : '' }}>-- Pilih Kelas --</option>
                    @foreach($jadwalList as $jadwal)
                        <option value="{{ $jadwal->id }}" data-kategori="{{ $jadwal->kategori_kelas }}" data-jenis="{{ $jadwal->jenis_kelas }}" {{ $jadwal_id == $jadwal->id ? 'selected' : '' }}>
                            {{ $jadwal->nama_kelas }} ({{ ucfirst($jadwal->hari) }})
                        </option>
                    @endforeach
                </select>
                @error('jadwal_id') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Pilih Murid *</label>
                <select name="user_id" id="user_id" class="form-control" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                    <option value="" disabled {{ !$user_id ? 'selected' : '' }}>-- Pilih Murid --</option>
                    @foreach($murids as $murid)
                        <option value="{{ $murid->id }}" data-kategori="{{ $murid->kategori_kelas }}" data-jenis="{{ $murid->jenis_kelas }}" {{ ($user_id == $murid->id || old('user_id') == $murid->id) ? 'selected' : '' }}>
                            {{ $murid->nama }}
                            @if($murid->kategori_kelas) ({{ ucfirst($murid->kategori_kelas) }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('user_id') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Tanggal Pertemuan *</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', $tanggal) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                @error('tanggal') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

           <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Jumlah Pembayaran *</label>
                <input type="text" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', 20000) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                <p style="font-size:11px; color:#6c757d; margin-top:5px;">Nominal otomatis terisi Rp 20.000.</p>
                @error('jumlah') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Metode Pembayaran *</label>
                {{-- Menghapus opsi lain dan mengunci menjadi Cash --}}
                <input type="text" class="form-control" value="Bayar Tunai (Cash)" readonly style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6; background-color: #e9ecef;">
                <input type="hidden" name="metode" value="cash">
                @error('metode') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Status Pembayaran *</label>
                <select name="status" class="form-control" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                    <option value="disetujui">Lunas</option>
                    <option value="ditolak">Belum Lunas</option>
                </select>
                @error('status') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label class="form-label" style="display:block; margin-bottom:5px; font-weight:600;">Keterangan / Catatan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Opsional" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">{{ old('keterangan') }}</textarea>
                @error('keterangan') <span style="color:red; font-size:12px;">{{ $message }}</span> @enderror
            </div>


            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; font-weight: bold;">Simpan Pembayaran</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jadwalSelect = document.getElementById('jadwal_id');
    const muridSelect = document.getElementById('user_id');
    const muridOptions = muridSelect.querySelectorAll('option:not([disabled])');

    function filterMurid() {
        const selectedOption = jadwalSelect.options[jadwalSelect.selectedIndex];

        if (!selectedOption || !selectedOption.value) {
            muridOptions.forEach(opt => {
                opt.style.display = 'block';
                opt.hidden = false;
                opt.disabled = false;
            });
            return;
        }

        const kategori = selectedOption.getAttribute('data-kategori');
        const jenis = selectedOption.getAttribute('data-jenis');

        let isCurrentSelectedValid = false;

        muridOptions.forEach(opt => {
            const optKategori = opt.getAttribute('data-kategori');
            const optJenis = opt.getAttribute('data-jenis');

            if (optKategori === kategori && optJenis && optJenis.includes(jenis)) {
                opt.style.display = 'block';
                opt.hidden = false;
                opt.disabled = false;
                if (muridSelect.value === opt.value) isCurrentSelectedValid = true;
            } else {
                opt.style.display = 'none';
                opt.hidden = true;
                opt.disabled = true;
            }
        });

        if (!isCurrentSelectedValid) {
            muridSelect.value = "";
        }
    }

    jadwalSelect.addEventListener('change', filterMurid);

    // Run on load
    if (jadwalSelect.value) {
        filterMurid();
    }

    // Rupiah Formatter
    function formatRupiah(value) {
        if (!value) return '';
        let numberString = value.toString().replace(/[^0-9]/g, '');
        let split = numberString.split('');
        let sisa = split.length % 3;
        let rupiah = split.slice(0, sisa).join('');
        let ribuan = split.slice(sisa).join('').match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah ? 'Rp ' + rupiah : '';
    }

    const jumlahInput = document.getElementById('jumlah');
    if (jumlahInput) {
        // Format initial value
        jumlahInput.value = formatRupiah(jumlahInput.value);

        // Format as user types
        jumlahInput.addEventListener('input', function(e) {
            e.target.value = formatRupiah(e.target.value);
        });

        // Unmask before submit
        const form = jumlahInput.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                let rawValue = jumlahInput.value.replace(/[^0-9]/g, '');
                jumlahInput.value = rawValue;
            });
        }
    }
});
</script>
@endsection
