<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi – STEVA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *{box-sizing:border-box;margin:0;padding:0;}
        body{
            font-family:'Inter',sans-serif; min-height:100vh;
            background:linear-gradient(135deg,#800020 0%,#5c0016 100%);
            display:flex; flex-direction:column; align-items:center; justify-content:center; padding:24px;
        }

        .auth-card{
            background:#fff; border-radius:16px; width:100%; max-width:550px;
            box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden;
            margin: 20px 0;
        }

        .auth-card-top{
            background:linear-gradient(135deg,#800020,#a0002a);
            padding:25px; text-align:center; color: #fff;
        }
        .auth-card-top h1{ font-size: 24px; letter-spacing: 3px; font-weight: 800; margin-bottom: 5px; }
        .auth-card-top p{ font-size: 11px; opacity: 0.8; letter-spacing: 1px; }

        .auth-card-body{padding:30px 40px;}
        .auth-card-body h2{font-size:19px; font-weight:700; color:#1a1a2e; margin-bottom:20px; border-bottom: 2px solid #f9e8ec; padding-bottom: 10px;}

        .form-group{margin-bottom:16px;}
        .form-label{display:block;font-size:13px;font-weight:600;color:#495057;margin-bottom:6px;}

        .input-wrap{position:relative;}
        .input-wrap .icon{
            position:absolute; left:13px; top:50%; transform:translateY(-50%);
            color:#adb5bd; font-size:13px;
        }
        .input-wrap .toggle-password {
            position:absolute; right:13px; top:50%; transform:translateY(-50%);
            color:#adb5bd; font-size:14px; cursor:pointer; z-index:10;
            transition: color 0.2s;
        }
        .input-wrap .toggle-password:hover {
            color:#800020;
        }
        .form-control{
            width:100%; padding:11px 13px 11px 40px;
            border:1.5px solid #dee2e6; border-radius:8px;
            font-size:14px; font-family:'Inter',sans-serif;
            color:#343a40; transition:all .2s;
        }
        .form-control:focus{outline:none;border-color:#800020;box-shadow:0 0 0 3px rgba(128,0,32,0.1);}
        .form-control.is-invalid{border-color:#dc3545;}
        .invalid-feedback{color:#dc3545;font-size:12px;margin-top:4px;}

        .special-box {
            background: #fff9e6; border: 1.2px dashed #f0d080; padding: 18px; border-radius: 10px; margin: 20px 0;
        }

        .btn-register{
            width:100%; padding:14px;
            background:linear-gradient(135deg,#800020,#a0002a);
            color:#fff; border:none; border-radius:8px;
            font-size:15px; font-weight:700; cursor:pointer;
            transition:all .3s; letter-spacing:1px;
            text-transform: uppercase; margin-top: 10px;
        }
        .btn-register:hover{
            transform: translateY(-2px);
            box-shadow:0 8px 25px rgba(128,0,32,0.4);
        }

        .login-link{
            text-align:center; font-size:13.5px; color:#6c757d; margin-top: 20px;
        }
        .login-link a{color:#800020;font-weight:700;text-decoration:none;}
        .login-link a:hover{text-decoration:underline;}

        @media(max-width:576px){
            .auth-card-body{padding:25px;}
        }

        /* Floating Back Button Styles */
        .btn-back-floating {
            position: absolute;
            top: 24px;
            left: 24px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.22s ease;
            backdrop-filter: blur(8px);
            z-index: 1000;
        }

        .btn-back-floating:hover {
            background: #ffffff;
            color: #800020;
            border-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
        }

        .btn-back-floating i {
            font-size: 12px;
            transition: transform 0.22s ease;
        }

        .btn-back-floating:hover i {
            transform: translateX(-3px);
        }

        @media (max-width: 768px) {
            .btn-back-floating {
                position: static;
                margin-bottom: 20px;
                align-self: flex-start;
                background: rgba(255, 255, 255, 0.15);
            }
        }
    </style>
</head>
<body>

<a href="javascript:void(0);" onclick="goBack()" class="btn-back-floating" title="Kembali">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali</span>
</a>

<div class="auth-card">
    <div class="auth-card-top">
        <h1>STEVA</h1>
        <p>STUDIO TARI EVA TANNIA</p>
    </div>
    <div class="auth-card-body">
        <h2>Daftar sebagai {{ ucfirst($role) }}</h2>

        @if($errors->any())
            <div style="background:#fdf0f0; border:1px solid #f5c6cb; color:#721c24; padding:12px 16px; border-radius:8px; font-size:13.5px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                <i class="fa-solid fa-circle-xmark"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('register.' . $role . '.post') }}">
            @csrf

            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user icon"></i>
                    <input type="text" name="nama" class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" 
                           value="{{ old('nama') }}" placeholder="Masukkan Nama Lengkap" required>
                </div>
                @error('nama')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Username *</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user-tag icon"></i>
                    <input type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}" 
                           value="{{ old('username') }}" placeholder="Masukkan Username (tanpa spasi)" required>
                </div>
                @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email *</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope icon"></i>
                    <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                           value="{{ old('email') }}" placeholder="Masukkan Email" required>
                </div>
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nomor HP</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-phone icon"></i>
                    <input type="text" name="no_hp" class="form-control {{ $errors->has('no_hp') ? 'is-invalid' : '' }}" 
                           value="{{ old('no_hp') }}" placeholder="Masukkan Nomor HP (opsional)">
                </div>
                @error('no_hp')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password *</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock icon"></i>
                    <input id="password" type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                           placeholder="Minimal 6 karakter" required style="padding-right:40px;">
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                </div>
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password *</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-check-double icon"></i>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required style="padding-right:40px;">
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
                </div>
            </div>

            @if($role === 'murid' || $role === 'pelatih')
            <div class="form-group" style="margin-top:20px;">
                <label class="form-label">Kategori Kelas *</label>
                <select name="kategori_kelas" class="form-control" required style="cursor:pointer; padding-left:15px;">
                    <option value="" disabled selected>Pilih Kategori Kelas</option>
                    <option value="pemula" {{ old('kategori_kelas') == 'pemula' ? 'selected' : '' }}>Pemula</option>
                    <option value="madya" {{ old('kategori_kelas') == 'madya' ? 'selected' : '' }}>Madya</option>
                    <option value="ahli" {{ old('kategori_kelas') == 'ahli' ? 'selected' : '' }}>Ahli</option>
                </select>
                @error('kategori_kelas')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Jenis Kelas *</label>
                <div style="display:flex; gap:20px; font-size:14px; margin-top:8px; color:#495057; background:#f9e8ec; padding:12px; border-radius:8px;">
                    <label style="cursor:pointer;"><input type="checkbox" name="jenis_kelas[]" value="rampak" {{ is_array(old('jenis_kelas')) && in_array('rampak', old('jenis_kelas')) ? 'checked' : '' }}> Rampak</label>
                    <label style="cursor:pointer;"><input type="checkbox" name="jenis_kelas[]" value="reguler" {{ is_array(old('jenis_kelas')) && in_array('reguler', old('jenis_kelas')) ? 'checked' : '' }}> Reguler</label>
                </div>
                <p style="font-size:11px; color:#6c757d; margin-top:6px;">* Kamu bisa memilih satu atau keduanya</p>
                @error('jenis_kelas')<span class="invalid-feedback" style="display:block;">{{ $message }}</span>@enderror
            </div>
            @endif

            <button type="submit" class="btn-register">
                DAFTAR
            </button>
        </form>

        <div class="login-link">
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </div>
    </div>
</div>

<script>
function goBack() {
    if (document.referrer && document.referrer !== window.location.href && document.referrer.indexOf(window.location.host) !== -1) {
        window.history.back();
    } else {
        window.location.href = "{{ route('landing') }}";
    }
}

function togglePassword(inputId, toggleEl) {
    const input = document.getElementById(inputId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        toggleEl.classList.remove('fa-eye');
        toggleEl.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        toggleEl.classList.remove('fa-eye-slash');
        toggleEl.classList.add('fa-eye');
    }
}
</script>
</body>
</html>