<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email – STEVA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #800020 0%, #5c0016 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative blobs */
        body::before {
            content: '';
            position: fixed;
            top: -80px; right: -80px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed;
            bottom: -100px; left: -100px;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: rgba(255,255,255,0.03);
            pointer-events: none;
        }

        /* Back Button */
        .btn-back-floating {
            position: fixed;
            top: 24px; left: 24px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255,255,255,0.1);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.22s ease;
            backdrop-filter: blur(8px);
            z-index: 100;
        }
        .btn-back-floating:hover {
            background: #fff;
            color: #800020;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .btn-back-floating:hover i { transform: translateX(-3px); }
        .btn-back-floating i { transition: transform 0.22s ease; }

        /* Card */
        .auth-card {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.35);
            overflow: hidden;
            animation: cardIn 0.4s ease-out;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            background: linear-gradient(135deg, #800020, #a0002a);
            padding: 36px 40px 32px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .card-header::before {
            content: '';
            position: absolute;
            top: -30px; right: -30px;
            width: 150px; height: 150px;
            border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .card-header .brand { font-size: 28px; font-weight: 800; color: #fff; letter-spacing: 5px; position: relative; z-index: 1; }
        .card-header .brand-sub { font-size: 10.5px; color: rgba(255,255,255,0.7); letter-spacing: 2px; margin-top: 3px; position: relative; z-index: 1; }
        .card-header .icon-wrap {
            width: 70px; height: 70px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            font-size: 30px;
            border: 2px solid rgba(255,255,255,0.2);
            position: relative; z-index: 1;
            backdrop-filter: blur(5px);
        }

        .card-body { padding: 40px; }
        .card-title { font-size: 20px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
        .card-subtitle { font-size: 13px; color: #6c757d; margin-bottom: 28px; line-height: 1.6; }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            line-height: 1.5;
        }
        .alert-danger  { background: #fdf0f0; border: 1px solid #f5c6cb; color: #721c24; }
        .alert-success { background: #e9f7ef; border: 1px solid #c3e6cb; color: #155724; }

        /* Form */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 13px; font-weight: 600; color: #495057;
            margin-bottom: 7px;
        }
        .input-wrap { position: relative; }
        .input-wrap .icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #adb5bd; font-size: 14px;
        }
        .form-control {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: 1.5px solid #dee2e6;
            border-radius: 10px;
            font-size: 14px; font-family: 'Inter', sans-serif;
            color: #343a40;
            transition: all 0.2s;
        }
        .form-control:focus {
            outline: none;
            border-color: #800020;
            box-shadow: 0 0 0 3px rgba(128,0,32,0.1);
        }
        .form-control.is-invalid { border-color: #dc3545; }
        .invalid-feedback { color: #dc3545; font-size: 12px; margin-top: 5px; display: block; }
        .form-hint { font-size: 11.5px; color: #9ca3af; margin-top: 5px; }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #800020, #a0002a);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px; font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            letter-spacing: 0.5px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 8px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(128,0,32,0.4);
        }
        .btn-submit:active { transform: translateY(0); }

        /* Security note */
        .security-note {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            padding: 12px 14px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 12px;
            color: #6c757d;
        }
        .security-note i { color: #800020; font-size: 14px; flex-shrink: 0; }

        @media (max-width: 480px) {
            .btn-back-floating { position: static; margin-bottom: 20px; }
            .card-body { padding: 28px 24px; }
        }
    </style>
</head>
<body>

<a href="{{ url()->previous() ?? route('landing') }}" class="btn-back-floating">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali</span>
</a>

<div class="auth-card">
    <div class="card-header">
        <div class="icon-wrap">📧</div>
        <div class="brand">STEVA</div>
        <div class="brand-sub">STUDIO TARI EVA TANNIA</div>
    </div>

    <div class="card-body">
        <h1 class="card-title">Verifikasi Email</h1>
        <p class="card-subtitle">
            Masukkan alamat email Anda. Kami akan mengirimkan kode OTP 6 digit
            untuk verifikasi identitas Anda.
        </p>

        {{-- Error Message --}}
        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-xmark"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-xmark"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('otp.send') }}" id="otpSendForm">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Alamat Email</label>
                <div class="input-wrap">
                    <i class="fa-solid fa-envelope icon"></i>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                        value="{{ old('email') }}"
                        placeholder="contoh@email.com"
                        required
                        autofocus
                    >
                </div>
                @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <div class="form-hint">
                    <i class="fa-solid fa-circle-info"></i>
                    Kode OTP akan dikirim ke email ini dan berlaku 5 menit.
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fa-solid fa-paper-plane"></i>
                Kirim Kode OTP
            </button>
        </form>

        <div class="security-note">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Kode OTP bersifat rahasia. Tim STEVA tidak akan pernah meminta kode OTP Anda.</span>
        </div>
    </div>
</div>

<script>
document.getElementById('otpSendForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';
});
</script>

</body>
</html>
