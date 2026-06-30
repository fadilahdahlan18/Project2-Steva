<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Berhasil – STEVA</title>
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
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-card {
            background: #fff; border-radius: 20px; width: 100%; max-width: 440px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.35); overflow: hidden;
            animation: cardIn 0.5s ease-out;
            text-align: center;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: scale(0.9); }
            to   { opacity: 1; transform: scale(1); }
        }

        .card-header {
            background: linear-gradient(135deg, #800020, #a0002a);
            padding: 40px 40px 36px;
            position: relative; overflow: hidden;
        }
        .card-header::before {
            content: ''; position: absolute; top: -30px; right: -30px;
            width: 150px; height: 150px; border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .card-header .brand { font-size: 28px; font-weight: 800; color: #fff; letter-spacing: 5px; position: relative; z-index: 1; }
        .card-header .brand-sub { font-size: 10.5px; color: rgba(255,255,255,0.7); letter-spacing: 2px; margin-top: 3px; position: relative; z-index: 1; }

        .success-icon-wrap {
            width: 90px; height: 90px;
            background: rgba(255,255,255,0.15);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            border: 3px solid rgba(255,255,255,0.25);
            position: relative; z-index: 1;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(255,255,255,0.3); }
            50%      { box-shadow: 0 0 0 15px rgba(255,255,255,0); }
        }

        .card-body { padding: 40px; }

        .verified-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: #e9f7ef; border: 1px solid #c3e6cb; color: #155724;
            padding: 8px 20px; border-radius: 20px;
            font-size: 13px; font-weight: 700;
            margin-bottom: 20px;
        }

        .card-title { font-size: 22px; font-weight: 800; color: #1a1a2e; margin-bottom: 10px; }
        .card-desc  { font-size: 14px; color: #6c757d; line-height: 1.7; margin-bottom: 28px; }
        .card-desc .email-hl { color: #800020; font-weight: 700; }

        .info-row {
            display: flex; align-items: center; gap: 10px;
            background: #f9fafb; border-radius: 10px;
            padding: 14px 16px; margin-bottom: 12px;
            text-align: left;
        }
        .info-row i { color: #800020; width: 18px; text-align: center; flex-shrink: 0; }
        .info-row span { font-size: 13px; color: #4b5563; }

        .btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: 10px;
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #800020, #a0002a);
            color: #fff; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all 0.3s; letter-spacing: 0.5px;
            text-decoration: none; margin-top: 8px;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(128,0,32,0.4);
            color: #fff;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="card-header">
        <div class="success-icon-wrap">✅</div>
        <div class="brand">STEVA</div>
        <div class="brand-sub">STUDIO TARI EVA TANNIA</div>
    </div>

    <div class="card-body">
        <div class="verified-badge">
            <i class="fa-solid fa-circle-check"></i>
            Terverifikasi
        </div>

        <h1 class="card-title">Email Berhasil Diverifikasi!</h1>
        <p class="card-desc">
            Identitas Anda telah dikonfirmasi dengan sukses.<br>
            Email <span class="email-hl">{{ $verifiedEmail }}</span>
            telah berhasil diverifikasi.
        </p>

        <div class="info-row">
            <i class="fa-solid fa-shield-halved"></i>
            <span>Akun Anda kini terverifikasi dan lebih aman.</span>
        </div>
        <div class="info-row">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <span>Verifikasi dilakukan pada {{ now()->format('d M Y, H:i') }} WIB.</span>
        </div>

        <a href="{{ route('login') }}" class="btn-primary">
            <i class="fa-solid fa-right-to-bracket"></i>
            Masuk ke Akun
        </a>
    </div>
</div>

</body>
</html>
