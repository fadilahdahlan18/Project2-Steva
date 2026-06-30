<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pegawai | LP3I</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .card { border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .thead-dark { background-color: #343a40; color: white; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">Data Pegawai</h2>
        <a href="/pegawai/tambah" class="btn btn-primary rounded-pill px-4">
            <i class="fas fa-plus-circle me-2"></i> Tambah Pegawai
        </a>
    </div>

    <div class="card p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Umur</th>
                        <th>Alamat</th>
                        <th class="text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawai as $p)
                    <tr>
                        <td class="fw-bold">{{ $p->pegawai_nama }}</td>
                        <td><span class="badge bg-info text-dark">{{ $p->pegawai_jabatan }}</span></td>
                        <td>{{ $p->pegawai_umur }} Thn</td>
                        <td>{{ $p->pegawai_alamat }}</td>
                        <td class="text-center">
                            <a href="/pegawai/edit/{{ $p->pegawai_id }}" class="btn btn-sm btn-warning me-1">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="/pegawai/hapus/{{ $p->pegawai_id }}" class="btn btn-sm btn-danger" onclick="konfirmasiHapus(event, '{{ $p->pegawai_nama }}')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // NOTIFIKASI BERHASIL (Simpan / Edit / Hapus)
    @if(session('status'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('status') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // KONFIRMASI HAPUS
    function konfirmasiHapus(event, nama) {
        event.preventDefault();
        const url = event.currentTarget.getAttribute('href');
        Swal.fire({
            title: 'Hapus ' + nama + '?',
            text: "Data akan hilang selamanya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) { window.location.href = url; }
        });
    }
</script>
</body>
</html>
