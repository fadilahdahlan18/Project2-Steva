<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pegawai | LP3I</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">Edit Data Pegawai</h5>
                </div>
                <div class="card-body p-4">
                    @foreach($pegawai as $p)
                    <form action="/pegawai/update" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="{{ $p->pegawai_id }}">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="{{ $p->pegawai_nama }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input type="text" name="jabatan" class="form-control" value="{{ $p->pegawai_jabatan }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Umur</label>
                            <input type="number" name="umur" class="form-control" value="{{ $p->pegawai_umur }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required>{{ $p->pegawai_alamat }}</textarea>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="/pegawai" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">Update Data</button>
                        </div>
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
