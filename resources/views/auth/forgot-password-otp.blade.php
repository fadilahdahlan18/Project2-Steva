<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP – STEVA</title>
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
        }
        body::before {
            content: ''; position: fixed; top: -80px; right: -80px;
            width: 400px; height: 400px; border-radius: 50%;
            background: rgba(255,255,255,0.04); pointer-events: none;
        }
        body::after {
            content: ''; position: fixed; bottom: -100px; left: -100px;
            width: 500px; height: 500px; border-radius: 50%;
            background: rgba(255,255,255,0.03); pointer-events: none;
        }

        /* Steps Indicator */
        .steps-bar {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 24px;
            width: 100%;
            max-width: 460px;
        }
        .step {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            color: rgba(255,255,255,0.5);
        }
        .step.active { color: #fff; }
        .step.done   { color: rgba(255,255,255,0.7); }
        .step-circle {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            border: 2px solid rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: rgba(255,255,255,0.5);
            flex-shrink: 0;
        }
        .step.active .step-circle {
            background: #fff;
            border-color: #fff;
            color: #800020;
        }
        .step.done .step-circle {
            background: rgba(255,255,255,0.3);
            border-color: rgba(255,255,255,0.5);
            color: #fff;
        }
        .step-line {
            flex: 1;
            height: 2px;
            background: rgba(255,255,255,0.15);
            margin: 0 6px;
        }
        .step-line.done { background: rgba(255,255,255,0.4); }

        /* Back Button */
        .btn-back-floating {
            position: fixed; top: 24px; left: 24px;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 8px 16px;
            background: rgba(255,255,255,0.1); color: #fff;
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 8px; font-size: 13.5px; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: all 0.22s ease; backdrop-filter: blur(8px); z-index: 100;
        }
        .btn-back-floating:hover { background: #fff; color: #800020; transform: translateY(-1px); box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .btn-back-floating:hover i { transform: translateX(-3px); }
        .btn-back-floating i { transition: transform 0.22s ease; }

        /* Card */
        .auth-card {
            background: #fff; border-radius: 20px; width: 100%; max-width: 460px;
            box-shadow: 0 25px 70px rgba(0,0,0,0.35); overflow: hidden;
            animation: cardIn 0.4s ease-out;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-header {
            background: linear-gradient(135deg, #800020, #a0002a);
            padding: 32px 40px 28px; text-align: center;
            position: relative; overflow: hidden;
        }
        .card-header::before {
            content: ''; position: absolute; top: -30px; right: -30px;
            width: 150px; height: 150px; border-radius: 50%;
            background: rgba(255,255,255,0.07);
        }
        .card-header .icon-wrap {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.15); border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px; font-size: 28px;
            border: 2px solid rgba(255,255,255,0.2);
            position: relative; z-index: 1;
        }
        .card-header .brand { font-size: 24px; font-weight: 800; color: #fff; letter-spacing: 5px; position: relative; z-index: 1; }
        .card-header .brand-sub { font-size: 10px; color: rgba(255,255,255,0.7); letter-spacing: 2px; margin-top: 3px; position: relative; z-index: 1; }

        .card-body { padding: 36px 40px; }
        .card-title { font-size: 19px; font-weight: 700; color: #1a1a2e; margin-bottom: 6px; }
        .card-subtitle { font-size: 13px; color: #6c757d; margin-bottom: 24px; line-height: 1.6; }
        .card-subtitle .highlight { color: #800020; font-weight: 700; }

        /* Alerts */
        .alert {
            padding: 12px 16px; border-radius: 10px; font-size: 13.5px;
            margin-bottom: 20px; display: flex; align-items: flex-start; gap: 10px; line-height: 1.5;
        }
        .alert-danger  { background: #fdf0f0; border: 1px solid #f5c6cb; color: #721c24; }
        .alert-success { background: #e9f7ef; border: 1px solid #c3e6cb; color: #155724; }

        /* OTP Input */
        .otp-input-group {
            display: flex; gap: 10px; justify-content: center; margin-bottom: 8px;
        }
        .otp-digit {
            width: 54px; height: 62px;
            border: 2px solid #dee2e6; border-radius: 12px;
            font-size: 26px; font-weight: 700;
            text-align: center; color: #800020;
            font-family: 'Courier New', monospace;
            transition: all 0.2s; background: #fff; caret-color: #800020;
        }
        .otp-digit:focus {
            outline: none; border-color: #800020;
            box-shadow: 0 0 0 3px rgba(128,0,32,0.12); background: #fff5f7;
        }
        .otp-digit.is-invalid { border-color: #dc3545; }
        .otp-digit.filled { border-color: #800020; background: #fff5f7; }
        #otp_code { display: none; }

        .invalid-feedback { color: #dc3545; font-size: 12px; margin-top: 5px; display: block; text-align: center; }

        /* Timer */
        .timer-section { text-align: center; margin: 16px 0 22px; }
        .timer-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 7px 16px; border-radius: 20px; font-size: 13px; font-weight: 600;
        }
        .timer-badge.active { background: #fff5f7; color: #800020; border: 1px solid #f9c0c8; }
        .timer-badge.expired { background: #fdf0f0; color: #dc3545; border: 1px solid #f5c6cb; }
        #countdown { font-weight: 800; font-size: 15px; }

        /* Button */
        .btn-submit {
            width: 100%; padding: 14px;
            background: linear-gradient(135deg, #800020, #a0002a);
            color: #fff; border: none; border-radius: 10px;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all 0.3s; letter-spacing: 0.5px;
            display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(128,0,32,0.4); }
        .btn-submit:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

        /* Resend */
        .resend-section { text-align: center; margin-top: 18px; font-size: 13.5px; color: #6c757d; }
        .resend-section a { color: #800020; font-weight: 700; text-decoration: none; }
        .resend-section a:hover { text-decoration: underline; }

        @media (max-width: 480px) {
            .btn-back-floating { position: static; margin-bottom: 20px; }
            .card-body { padding: 28px 20px; }
            .otp-digit { width: 44px; height: 52px; font-size: 22px; }
            .otp-input-group { gap: 6px; }
        }
    </style>
</head>
<body>

<a href="{{ route('forgot.password') }}" class="btn-back-floating">
    <i class="fa-solid fa-arrow-left"></i>
    <span>Kembali</span>
</a>

{{-- Step Indicator --}}
<div class="steps-bar">
    <div class="step done">
        <div class="step-circle"><i class="fa-solid fa-check" style="font-size:10px;"></i></div>
        <span>Verifikasi</span>
    </div>
    <div class="step-line done"></div>
    <div class="step active">
        <div class="step-circle">2</div>
        <span>Kode OTP</span>
    </div>
    <div class="step-line"></div>
    <div class="step">
        <div class="step-circle">3</div>
        <span>Password Baru</span>
    </div>
</div>

<div class="auth-card">
    <div class="card-header">
        <div class="icon-wrap">🔐</div>
        <div class="brand">STEVA</div>
        <div class="brand-sub">STUDIO TARI EVA TANNIA</div>
    </div>

    <div class="card-body">
        <h1 class="card-title">Masukkan Kode OTP</h1>
        <p class="card-subtitle">
            Kode 6 digit telah dikirim ke email terdaftar Anda.
            @if(session('password_reset_email'))
                <br>Dikirim ke: <span class="highlight">{{ session('password_reset_email') }}</span>
            @endif
        </p>

        @if(session('error'))
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-xmark"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-xmark"></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('forgot.password.otp.post') }}" id="otpVerifyForm">
            @csrf

            <div class="otp-input-group" id="otpDigitsContainer">
                @for($i = 0; $i < 6; $i++)
                <input class="otp-digit" type="text" inputmode="numeric" maxlength="1"
                    data-index="{{ $i }}" id="otp_d{{ $i }}" autocomplete="off">
                @endfor
            </div>

            <input type="hidden" name="otp_code" id="otp_code">

            @error('otp_code')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror

            {{-- Countdown Timer --}}
            <div class="timer-section">
                <div class="timer-badge active" id="timerBadge">
                    <i class="fa-regular fa-clock"></i>
                    Kode berlaku: <span id="countdown">05:00</span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fa-solid fa-shield-halved"></i>
                Verifikasi OTP
            </button>
        </form>

        <div class="resend-section">
            Tidak menerima kode?
            <a href="{{ route('forgot.password') }}">Minta kode baru</a>
        </div>
    </div>
</div>

<script>
// ── OTP Digit Input Behavior ──
const digits = document.querySelectorAll('.otp-digit');
const hiddenInput = document.getElementById('otp_code');

digits.forEach((input, idx) => {
    input.addEventListener('input', (e) => {
        const val = e.target.value.replace(/\D/g, '');
        e.target.value = val ? val[0] : '';
        if (val && idx < digits.length - 1) digits[idx + 1].focus();
        input.classList.toggle('filled', !!e.target.value);
        syncHiddenInput();
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Backspace' && !e.target.value && idx > 0) {
            digits[idx - 1].focus();
            digits[idx - 1].value = '';
            digits[idx - 1].classList.remove('filled');
            syncHiddenInput();
        }
    });

    input.addEventListener('paste', (e) => {
        e.preventDefault();
        const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
        pasted.split('').forEach((ch, i) => {
            if (digits[i]) { digits[i].value = ch; digits[i].classList.add('filled'); }
        });
        if (digits[Math.min(pasted.length, 5)]) digits[Math.min(pasted.length, 5)].focus();
        syncHiddenInput();
    });

    input.addEventListener('focus', () => input.select());
});

function syncHiddenInput() {
    hiddenInput.value = Array.from(digits).map(d => d.value).join('');
}

digits[0].focus();

// ── Form Submit ──
document.getElementById('otpVerifyForm').addEventListener('submit', function(e) {
    syncHiddenInput();
    if (hiddenInput.value.length !== 6) {
        e.preventDefault();
        digits.forEach(d => d.classList.add('is-invalid'));
        return;
    }
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memverifikasi...';
});

// ── Countdown Timer 5 menit ──
let remaining = 300;
const countdownEl = document.getElementById('countdown');
const timerBadge  = document.getElementById('timerBadge');
const submitBtn   = document.getElementById('submitBtn');

function pad(n) { return n.toString().padStart(2, '0'); }

const timer = setInterval(() => {
    remaining--;
    countdownEl.textContent = `${pad(Math.floor(remaining/60))}:${pad(remaining%60)}`;
    if (remaining <= 60) timerBadge.style.color = '#dc3545';
    if (remaining <= 0) {
        clearInterval(timer);
        countdownEl.textContent = 'KEDALUWARSA';
        timerBadge.className = 'timer-badge expired';
        timerBadge.innerHTML = '<i class="fa-solid fa-xmark"></i> Kode sudah kedaluwarsa';
        submitBtn.disabled = true;
    }
}, 1000);
</script>

</body>
</html>
