@extends('template.main')
@section('title', 'Input Absensi Kelas')
@section('content')
<div class="page-header">
    <h2><i class="fa-solid fa-clipboard-list"></i> Input Absensi Kelas</h2>
    <div class="breadcrumb">
        <a href="{{ route('pelatih.absensi') }}">Absensi</a>
        <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i> Input
    </div>
</div>

<div class="card" style="max-width:700px;">
    <div class="card-header"><h3><i class="fa-solid fa-clipboard-list"></i> Form Absensi Per Kelas</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('pelatih.absensi.store') }}" id="absensiForm">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Kelas / Jadwal *</label>
                    <select name="jadwal_id" class="form-control form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($jadwalList as $j)
                            <option value="{{ $j->id }}" data-kategori="{{ $j->kategori_kelas }}" data-jenis="{{ $j->jenis_kelas }}">{{ $j->nama_kelas }} ({{ $j->hari }}, {{ $j->jam }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Tanggal Pertemuan *</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ today()->toDateString() }}" required>
                </div>
            </div>            <div class="card" style="border:1.5px solid #dee2e6;">
                <div class="card-header" style="background:#f9e8ec;">
                    <h3 style="color:#800020;"><i class="fa-solid fa-users"></i> Daftar Murid</h3>
                </div>
                <div class="card-body" style="padding:0;">
                    <div id="no-murid-msg" style="padding:24px;text-align:center;color:#adb5bd;">
                        Silakan pilih Kelas / Jadwal terlebih dahulu.
                    </div>

                    <table class="steva-table" style="display:none; width: 100%;">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Murid</th>
                                <th style="text-align:center;">Hadir</th>
                                <th style="text-align:center;">Tidak Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:16px;">
                <button type="submit" class="btn btn-primary" id="btnSubmit" style="display:none;"><i class="fa-solid fa-save"></i> Simpan Semua</button>
                <a href="{{ route('pelatih.absensi') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jadwalSelect = document.querySelector('select[name="jadwal_id"]');
    const tanggalInput = document.querySelector('input[name="tanggal"]');
    const tableBody = document.querySelector('.steva-table tbody');
    const tableElement = document.querySelector('.steva-table');
    const noMuridMsg = document.getElementById('no-murid-msg');
    const btnSubmit = document.getElementById('btnSubmit');

    async function fetchEligibleStudents() {
        const jadwalId = jadwalSelect.value;
        const tanggal = tanggalInput.value;

        if (!jadwalId || !tanggal) {
            tableBody.innerHTML = '';
            tableElement.style.display = 'none';
            noMuridMsg.style.display = 'block';
            noMuridMsg.textContent = 'Silakan pilih Kelas / Jadwal terlebih dahulu.';
            btnSubmit.style.display = 'none';
            return;
        }

        noMuridMsg.style.display = 'block';
        noMuridMsg.textContent = 'Memuat data murid...';
        tableElement.style.display = 'none';
        btnSubmit.style.display = 'none';

        try {
            const response = await fetch(`/pelatih/absensi/eligible-students?jadwal_id=${jadwalId}&tanggal=${tanggal}`);
            if (!response.ok) throw new Error('Gagal mengambil data murid');
            const students = await response.json();

            tableBody.innerHTML = '';
            if (students.length === 0) {
                noMuridMsg.style.display = 'block';
                noMuridMsg.textContent = 'Tidak ada murid yang perlu diabsen pada jadwal dan tanggal ini (semua sudah terabsen atau tidak ada murid terdaftar).';
                tableElement.style.display = 'none';
                btnSubmit.style.display = 'none';
            } else {
                noMuridMsg.style.display = 'none';
                tableElement.style.display = 'table';
                btnSubmit.style.display = 'inline-block';

                students.forEach((student, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>
                            <input type="hidden" name="absensi[${index}][user_id]" value="${student.id}">
                            <strong>${student.nama}</strong>
                        </td>
                        <td style="text-align:center;">
                            <input type="radio" name="absensi[${index}][status]" value="hadir" required
                                style="width:18px;height:18px;accent-color:#800020;cursor:pointer;">
                        </td>
                        <td style="text-align:center;">
                            <input type="radio" name="absensi[${index}][status]" value="alpha" required
                                style="width:18px;height:18px;accent-color:#800020;cursor:pointer;">
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            }
        } catch (error) {
            console.error(error);
            tableBody.innerHTML = '';
            tableElement.style.display = 'none';
            noMuridMsg.style.display = 'block';
            noMuridMsg.textContent = 'Terjadi kesalahan saat memuat data murid.';
            btnSubmit.style.display = 'none';
        }
    }

    jadwalSelect.addEventListener('change', fetchEligibleStudents);
    tanggalInput.addEventListener('change', fetchEligibleStudents);

    // Initial check
    fetchEligibleStudents();
});
</script>
@endsection
