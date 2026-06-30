@extends('template.main')
@section('title', 'Pembayaran')
@section('styles')
<style>
    /* ===== STATS ===== */
    .pay-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    /* ===== LAYOUT GRID ===== */
    .pay-layout {
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    /* ===== FORM CARD ===== */
    .form-card-inner { padding: 22px; }

    .form-section-title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--gray-500);
        margin: 18px 0 10px;
        padding-bottom: 6px;
        border-bottom: 1px solid var(--gray-200);
    }

    .amount-display {
        font-size: 28px;
        font-weight: 700;
        color: var(--burgundy);
        text-align: center;
        background: var(--burgundy-pale);
        border-radius: var(--radius);
        padding: 14px;
        margin-bottom: 18px;
        letter-spacing: -0.5px;
    }

    .amount-display small {
        display: block;
        font-size: 11px;
        font-weight: 500;
        color: var(--gray-500);
        margin-bottom: 4px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* Upload area */
    .upload-area {
        border: 2px dashed var(--gray-300);
        border-radius: var(--radius);
        padding: 20px;
        text-align: center;
        cursor: pointer;
        transition: all var(--transition);
        position: relative;
        background: var(--gray-50);
    }

    .upload-area:hover {
        border-color: var(--burgundy);
        background: var(--burgundy-pale);
    }

    .upload-area input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        width: 100%;
        height: 100%;
    }

    .upload-area i {
        font-size: 28px;
        color: var(--gray-400);
        display: block;
        margin-bottom: 8px;
    }

    .upload-area p {
        font-size: 13px;
        color: var(--gray-500);
        margin: 0;
    }

    .upload-area .upload-hint {
        font-size: 11px;
        color: var(--gray-400);
        margin-top: 4px;
    }

    #preview-wrapper {
        display: none;
        margin-top: 10px;
        text-align: center;
    }

    #preview-img {
        max-width: 100%;
        max-height: 160px;
        border-radius: var(--radius-sm);
        border: 1px solid var(--gray-200);
        object-fit: contain;
    }

    /* ===== STATUS BADGES ===== */
    .badge-disetujui { background: #d4edda; color: #155724; }
    .badge-pending   { background: #fff3cd; color: #856404; }
    .badge-ditolak   { background: #f8d7da; color: #721c24; }

    /* ===== EMPTY STATE ===== */
    .empty-pay {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray-400);
    }
    .empty-pay i { font-size: 42px; margin-bottom: 12px; display: block; }
    .empty-pay p { font-size: 13px; }

    /* ===== Info Note ===== */
    .pay-note {
        background: #fff8e1;
        border: 1px solid #ffe082;
        border-left: 4px solid var(--gold);
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        font-size: 12.5px;
        color: #795700;
        margin-bottom: 0;
        display: flex;
        gap: 8px;
        align-items: flex-start;
    }
</style>
@endsection

@section('content')

{{-- ===== PAGE HEADER ===== --}}
<div class="page-header">
    <div>
        <h2><i class="fa-solid fa-money-bill-wave"></i> Pembayaran</h2>
        <div class="breadcrumb">
            <a href="{{ route('murid.dashboard') }}">Dashboard</a>
            <i class="fa-solid fa-chevron-right" style="font-size:9px;"></i>
            <span>Pembayaran</span>
        </div>
    </div>
</div>

{{-- ===== STATS ===== --}}
<div class="pay-stats">
    <div class="stat-card">
        <div class="stat-icon burgundy"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info">
            <div class="stat-num">Rp{{ number_format($totalBayar, 0, ',', '.') }}</div>
            <div class="stat-label">Total Terbayar</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon warning"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $pendingBayar }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-info">
            <div class="stat-num">{{ $pembayaran->total() }}</div>
            <div class="stat-label">Total Transaksi</div>
        </div>
    </div>
</div>

{{-- ===== MAIN LAYOUT ===== --}}
<div class="pay-layout">

    {{-- ===== FORM PEMBAYARAN ===== --}}
    <div class="card" style="max-width: 550px; width: 100%; margin: 0 auto 12px auto;">
        <div class="card-header">
            <h3><i class="fa-solid fa-plus-circle"></i> Form Pembayaran</h3>
        </div>
        <div class="form-card-inner">

            <div class="amount-display">
                <small>Bayar Latihan</small>
                Rp20.000
            </div>

            @if ($errors->any())
                <div class="alert alert-danger" style="margin-bottom:16px;">
                    <i class="fa-solid fa-circle-xmark"></i>
                    <ul style="margin:0; padding-left:16px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('murid.pembayaran.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="metode" value="transfer">

                <div class="form-section-title">Detail Pembayaran</div>
                <div class="form-group" style="margin-bottom: 15px;">
                    <label class="form-label">Jumlah Pembayaran *</label>
                    <input type="text" name="jumlah" id="jumlah" class="form-control" value="{{ old('jumlah', 20000) }}" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                    <p style="font-size:11px; color:#6c757d; margin-top:5px;">Nominal otomatis terisi Rp20.000.</p>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Pilih Kelas *</label>
                    <select name="jadwal_id" class="form-control @error('jadwal_id') is-invalid @enderror" required style="cursor:pointer; width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #dee2e6;">
                        <option value="" disabled selected>-- Pilih Kelas --</option>
                        @foreach($jadwalList as $j)
                            <option value="{{ $j->id }}" {{ old('jadwal_id') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_kelas }} ({{ ucfirst($j->hari) }} - {{ $j->jam }} WIB)
                            </option>
                        @endforeach
                    </select>
                    @error('jadwal_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label">Tanggal Pembayaran</label>
                    <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                <!-- Info Transfer Bank (Centered and Beautified) -->
                <div class="form-section-title" style="text-align: center;">Metode Pembayaran</div>
                
                <div id="info-transfer-box" class="pay-info-box" style="background: var(--burgundy-pale); border: 1px solid rgba(128, 0, 32, 0.15); border-radius: var(--radius); padding: 18px; text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 24px; color: var(--burgundy); margin-bottom: 8px;">
                        <i class="fa-solid fa-building-columns"></i>
                    </div>
                    <div style="font-size: 14px; font-weight: 700; color: var(--gray-800); margin-bottom: 4px;">
                        Transfer Bank {{ $rekening->nama_bank ?? 'BCA' }}
                    </div>
                    <div style="font-size: 13px; color: var(--gray-600); margin-bottom: 12px;">
                        Atas Nama: <strong style="color: var(--gray-800);">{{ $rekening->nama_pemilik ?? 'Eva Tania' }}</strong>
                    </div>
                    
                    <div style="background: var(--white); border: 1px solid var(--gray-200); border-radius: var(--radius-sm); padding: 10px 14px; display: inline-flex; align-items: center; justify-content: center; gap: 10px; margin: 0 auto;">
                        <span id="rekening-num" style="font-weight: 700; font-family: monospace; font-size: 16px; color: var(--burgundy); letter-spacing: 0.5px;">{{ $rekening->nomor_rekening ?? '0551637106' }}</span>
                        <button type="button" onclick="copyRekening()" class="btn btn-secondary btn-sm" style="padding: 4px 10px; font-size: 11px; border-radius: 6px; display: flex; align-items: center; gap: 4px; border: none; background: var(--gray-100); color: var(--gray-700);">
                            <i class="fa-regular fa-copy"></i> Salin
                        </button>
                    </div>
                </div>

                <div class="form-section-title">Bukti Pembayaran</div>
                <div class="form-group" style="margin-bottom: 20px;">
                    <div class="upload-area" id="uploadArea">
                        <input type="file" name="bukti_transfer" id="buktiInput" accept="image/jpeg,image/png,image/jpg" onchange="previewBukti(this)" required>
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <p>Klik atau seret foto bukti ke sini</p>
                        <p class="upload-hint">JPG, JPEG, PNG · Maks. 3 MB</p>
                    </div>
                    <div id="preview-wrapper">
                        <img id="preview-img" src="" alt="Preview">
                        <div style="margin-top:6px;">
                            <button type="button" onclick="clearPreview()" class="btn btn-secondary btn-sm">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>
                        </div>
                    </div>
                    @error('bukti_transfer') <span class="invalid-feedback" style="display:block; margin-top:4px;">{{ $message }}</span> @enderror
                </div>

                {{-- Note diupdate agar informatif --}}
                <div class="pay-note" style="margin-bottom: 20px;">
                    <i class="fa-solid fa-circle-check" style="flex-shrink:0; margin-top:1px; color: #2e7d32;"></i>
                    <span>Pembayaran diverifikasi otomatis. Status akan langsung menjadi <b>Disetujui</b> setelah bukti diunggah.</span>
                </div>

                <div style="margin-top:18px;">
                    <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:11px;">
                        <i class="fa-solid fa-paper-plane"></i> Kirim Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== RIWAYAT PEMBAYARAN ===== --}}
    <div class="card" style="margin-top: 12px;">
        <div class="card-header">
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pembayaran</h3>
            <span style="font-size:12px; color:var(--gray-500);">{{ $pembayaran->total() }} transaksi</span>
        </div>

        @if ($pembayaran->isEmpty())
            <div class="empty-pay">
                <i class="fa-regular fa-credit-card"></i>
                <p>Belum ada riwayat pembayaran.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="steva-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kelas</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pembayaran as $i => $p)
                        <tr>
                            <td>{{ $pembayaran->firstItem() + $i }}</td>
                            <td>
                                <div style="font-weight:600; font-size:13px;">{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</div>
                            </td>
                            <td>
                                @if($p->jadwal)
                                    <span class="badge badge-burgundy" style="font-weight:600; text-transform:capitalize;">{{ $p->jadwal->jenis_kelas }}</span>
                                @elseif($p->user && $p->user->jenis_kelas)
                                    @php
                                        $classes = explode(',', $p->user->jenis_kelas);
                                        $formattedClasses = array_map('ucfirst', $classes);
                                    @endphp
                                    <span class="badge badge-burgundy" style="font-weight:600;">{{ implode(', ', $formattedClasses) }}</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                            <td style="font-weight:700; color:var(--burgundy);">Rp{{ number_format($p->jumlah, 0, ',', '.') }}</td>
                            <td>
                                @if($p->metode === 'transfer')
                                    <span class="badge badge-info"><i class="fa-solid fa-building-columns"></i> Transfer</span>
                                @elseif($p->metode === 'cash')
                                    <span class="badge badge-secondary"><i class="fa-solid fa-money-bill"></i> Tunai</span>
                                @else
                                    <span class="badge badge-burgundy"><i class="fa-solid fa-qrcode"></i> QRIS</span>
                                @endif
                            </td>
                            <td>
                                @if($p->status === 'disetujui')
                                    <span class="badge badge-disetujui"><i class="fa-solid fa-circle-check"></i> Disetujui</span>
                                @elseif($p->status === 'pending')
                                    <span class="badge badge-pending"><i class="fa-solid fa-clock"></i> Pending</span>
                                @else
                                    <span class="badge badge-ditolak"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>
                                @endif
                            </td>
                            <td>
                                @if($p->bukti_transfer)
                                    <a href="{{ asset('storage/'.$p->bukti_transfer) }}" target="_blank" class="btn btn-secondary btn-sm">
                                        <i class="fa-solid fa-image"></i> Lihat
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($pembayaran->hasPages())
                <div style="padding:16px 20px; display:flex; justify-content:center; border-top:1px solid var(--gray-200);">
                    {{ $pembayaran->links('vendor.pagination.simple') }}
                </div>
            @endif
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
            return rupiah ? 'Rp' + rupiah : '';
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

    function copyRekening() {
        const rekText = document.getElementById('rekening-num').innerText;
        navigator.clipboard.writeText(rekText).then(() => {
            alert('Nomor rekening berhasil disalin!');
        }).catch(err => {
            console.error('Gagal menyalin rekening: ', err);
        });
    }

    function previewBukti(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('preview-wrapper').style.display = 'block';
                document.getElementById('uploadArea').style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function clearPreview() {
        document.getElementById('buktiInput').value = '';
        document.getElementById('preview-wrapper').style.display = 'none';
        document.getElementById('uploadArea').style.display = 'block';
    }
</script>
@endsection