<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – STEVA</title>
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
            background:#fff; border-radius:16px; width:100%; max-width:450px;
            box-shadow:0 20px 60px rgba(0,0,0,0.3); overflow:hidden;
        }

        .auth-card-top{
            background:linear-gradient(135deg,#800020,#a0002a);
            padding:30px; text-align:center; color: #fff;
        }
        .auth-card-top h1{ font-size: 26px; letter-spacing: 3px; font-weight: 800; margin-bottom: 5px; }
        .auth-card-top p{ font-size: 12px; opacity: 0.8; letter-spacing: 1px; }

        .auth-card-body{padding:40px;}
        .auth-card-body h2{font-size:20px; font-weight:700; color:#1a1a2e; margin-bottom:8px;}
        .auth-card-body .subtitle{font-size:13px; color:#6c757d; margin-bottom:30px;}

        .form-group{margin-bottom:18px;}
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
            width:100%; padding:12px 13px 12px 40px;
            border:1.5px solid #dee2e6; border-radius:8px;
            font-size:14px; font-family:'Inter',sans-serif;
            color:#343a40; transition:all .2s;
        }
        .form-control:focus{outline:none;border-color:#800020;box-shadow:0 0 0 3px rgba(128,0,32,0.1);}
        .form-control.is-invalid{border-color:#dc3545;}
        .invalid-feedback{color:#dc3545;font-size:12px;margin-top:4px;}

        .remember-row{
            display:flex; align-items:center; justify-content:space-between;
            margin-bottom:25px; font-size:13px;
        }
        .remember-row label{display:flex;align-items:center;gap:8px;cursor:pointer;color:#495057;}

        .btn-login{
            width:100%; padding:14px;
            background:linear-gradient(135deg,#800020,#a0002a);
            color:#fff; border:none; border-radius:8px;
            font-size:15px; font-weight:700; cursor:pointer;
            transition:all .3s; letter-spacing:0.5px;
            text-transform: uppercase;
        }
        .btn-login:hover{
            transform: translateY(-2px);
            box-shadow:0 8px 25px rgba(128,0,32,0.4);
        }

        .divider{
            text-align:center; margin:25px 0; position:relative;
            color:#adb5bd; font-size:12px;
        }
        .divider::before,.divider::after{
            content:''; position:absolute; top:50%; width:35%; height:1px;
            background:#dee2e6;
        }
        .divider::before{left:0;} .divider::after{right:0;}

        .register-link{
            text-align:center; font-size:14px; color:#6c757d;
        }
        .register-link a{color:#800020;font-weight:700;text-decoration:none;}
        .register-link a:hover{text-decoration:underline;}

        .alert-danger{
            background:#fdf0f0;border:1px solid #f5c6cb;color:#721c24;
            padding:12px 16px; border-radius:8px; font-size:13.5px; margin-bottom:20px;
            display:flex;align-items:center;gap:10px;
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
        <h2>Selamat Datang</h2>
        <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>

        @if($errors->any())
            <div class="alert-danger">
                <i class="fa-solid fa-circle-xmark"></i>
                {{ $errors->first() }}
            </div>
        @endif

        @if(session('success'))
            <div style="background:#e9f7ef;border:1px solid #c3e6cb;color:#155724;padding:12px 16px;border-radius:8px;font-size:13.5px;margin-bottom:20px;display:flex;align-items:center;gap:10px;">
                <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <input type="hidden" name="to" value="{{ request('to') }}">
            <div class="form-group">
                <label class="form-label">Username</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-user icon"></i>
                    <input id="username" type="text" name="username" class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                        value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
                </div>
                @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-lock icon"></i>
                    <input id="password" type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                        placeholder="Masukkan password" required style="padding-right:40px;">
                    <i class="fa-solid fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                </div>
                @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>

            <div class="remember-row">
                <label>
                    <input type="checkbox" name="remember"> Ingat saya
                </label>
                <a href="{{ route('forgot.password') }}" style="color: #800020; font-weight: 600; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">Lupa Password?</a>
            </div>

            <button type="submit" class="btn-login">
                <i class="fa-solid fa-right-to-bracket"></i> &nbsp;Masuk
            </button>
        </form>

        <div class="divider">atau</div>

        <div class="register-link">
            Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
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
