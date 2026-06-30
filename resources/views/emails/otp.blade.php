<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Kode OTP – STEVA</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f4f4f8;
            color: #333333;
            -webkit-font-smoothing: antialiased;
        }

        .email-wrapper {
            width: 100%;
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.10);
        }

        /* ── HEADER ── */
        .email-header {
            background: linear-gradient(135deg, #800020 0%, #a0002a 50%, #6b0019 100%);
            padding: 40px 40px 36px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .email-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 180px; height: 180px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .email-header::after {
            content: '';
            position: absolute;
            bottom: -30px; left: -30px;
            width: 120px; height: 120px;
            border-radius: 50%;
            background: rgba(255,255,255,0.06);
        }
        .email-header .logo-text {
            font-size: 32px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 6px;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }
        .email-header .logo-sub {
            font-size: 11px;
            font-weight: 400;
            color: rgba(255,255,255,0.75);
            letter-spacing: 2.5px;
            margin-top: 4px;
            position: relative;
            z-index: 1;
        }
        .email-header .shield-icon {
            font-size: 48px;
            margin-bottom: 16px;
            display: block;
            position: relative;
            z-index: 1;
        }

        /* ── BODY ── */
        .email-body {
            padding: 40px 36px 30px;
        }
        .greeting {
            font-size: 15px;
            color: #555;
            margin-bottom: 8px;
        }
        .greeting strong {
            color: #1a1a2e;
        }
        .intro-text {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.7;
            margin-bottom: 36px;
        }

        /* ── OTP BOX ── */
        .otp-section {
            text-align: center;
            margin-bottom: 36px;
        }
        .otp-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #9ca3af;
            margin-bottom: 16px;
        }
        .otp-box {
            display: inline-block;
            background: linear-gradient(135deg, #fff5f7 0%, #fff0f2 100%);
            border: 2px solid #f9c0c8;
            border-radius: 16px;
            padding: 24px 36px;
            position: relative;
        }
        .otp-box::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 14px;
            border: 2px solid transparent;
            background: linear-gradient(135deg, #800020, #e74c3c) border-box;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: destination-out;
            mask-composite: exclude;
        }
        .otp-digits {
            font-size: 44px;
            font-weight: 800;
            letter-spacing: 10px;
            color: #800020;
            font-family: 'Courier New', 'Consolas', monospace;
            text-shadow: 0 2px 8px rgba(128, 0, 32, 0.15);
        }
        .otp-timer {
            margin-top: 10px;
            font-size: 12px;
            color: #e05e7a;
            font-weight: 500;
        }
        .otp-timer span {
            background: #fce4ea;
            padding: 3px 10px;
            border-radius: 20px;
            font-weight: 700;
        }

        /* ── DIVIDER ── */
        .divider {
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 32px 0;
        }

        /* ── INFO CARDS ── */
        .info-cards {
            display: table;
            width: 100%;
            border-spacing: 10px;
            margin-bottom: 8px;
        }
        .info-card {
            display: table-cell;
            background: #f9fafb;
            border-radius: 10px;
            padding: 16px;
            text-align: center;
            vertical-align: top;
            width: 33.3%;
        }
        .info-card .ic-icon { font-size: 22px; margin-bottom: 6px; display: block; }
        .info-card .ic-title { font-size: 11px; font-weight: 700; color: #374151; margin-bottom: 2px; }
        .info-card .ic-desc  { font-size: 11px; color: #9ca3af; }

        /* ── WARNING ── */
        .warning-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            border-radius: 0 8px 8px 0;
            padding: 14px 18px;
            margin-bottom: 28px;
            font-size: 13px;
            color: #92400e;
            line-height: 1.6;
        }

        /* ── FOOTER ── */
        .email-footer {
            background: #f9fafb;
            border-top: 1px solid #f0f0f0;
            padding: 28px 44px;
            text-align: center;
        }
        .footer-brand {
            font-size: 16px;
            font-weight: 800;
            color: #800020;
            letter-spacing: 3px;
            margin-bottom: 6px;
        }
        .footer-sub {
            font-size: 11px;
            color: #9ca3af;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .footer-note {
            font-size: 11.5px;
            color: #b0b0b0;
            line-height: 1.7;
        }
        .footer-note a {
            color: #800020;
            text-decoration: none;
        }

        /* ── RESPONSIVE MEDIA QUERIES ── */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                margin: 20px auto;
                border-radius: 12px;
            }
            .email-header {
                padding: 30px 24px 24px;
            }
            .email-header .logo-text {
                font-size: 26px;
                letter-spacing: 4px;
            }
            .email-body {
                padding: 24px 20px 20px;
            }
            .intro-text {
                margin-bottom: 24px;
                font-size: 13.5px;
            }
            .otp-box {
                padding: 16px 20px;
                max-width: 270px;
                width: 100%;
                box-sizing: border-box;
            }
            .otp-digits {
                font-size: 32px;
                letter-spacing: 6px;
            }
            .info-cards {
                display: block !important;
                width: 100% !important;
                border-spacing: 0 !important;
            }
            .info-cards tbody, .info-cards tr {
                display: block !important;
                width: 100% !important;
            }
            .info-card {
                display: block !important;
                width: 100% !important;
                box-sizing: border-box;
                margin-bottom: 12px;
                padding: 12px;
            }
            .warning-box {
                padding: 12px 14px;
                font-size: 12px;
            }
            .email-footer {
                padding: 24px 20px;
            }
        }
    </style>
</head>
<body>
<div class="email-wrapper">

    {{-- HEADER --}}
    <div class="email-header">
        <div class="logo-text">STEVA</div>
        <div class="logo-sub">STUDIO TARI EVA TANNIA</div>
    </div>

    {{-- BODY --}}
    <div class="email-body">

        <p class="greeting">Halo, <strong>{{ $recipientName }}</strong>!</p>
        <p class="intro-text">
            Kami menerima permintaan verifikasi untuk akun Anda. Gunakan kode OTP di bawah ini
            untuk melanjutkan. Kode ini bersifat <strong>rahasia</strong> dan hanya berlaku selama
            <strong>{{ $expiryMinutes }} menit</strong>.
        </p>

        {{-- OTP BOX --}}
        <div class="otp-section">
            <div class="otp-label">Kode Verifikasi Anda</div>
            <div class="otp-box">
                <div class="otp-digits">{{ $otpCode }}</div>
                <div class="otp-timer">Berlaku selama <span>{{ $expiryMinutes }} Menit</span></div>
            </div>
        </div>

        {{-- INFO CARDS --}}
        <table class="info-cards" cellpadding="10" cellspacing="10">
            <tr>
                <td class="info-card">
                    <div class="ic-title">Waktu Terbatas</div>
                    <div class="ic-desc">Kode kedaluwarsa dalam {{ $expiryMinutes }} menit</div>
                </td>
                <td class="info-card">
                    <div class="ic-title">Sekali Pakai</div>
                    <div class="ic-desc">Kode hanya bisa digunakan satu kali</div>
                </td>
                <td class="info-card">
                    <div class="ic-title">Jaga Privasi</div>
                    <div class="ic-desc">Jangan bagikan kode ini kepada siapapun</div>
                </td>
            </tr>
        </table>

        <hr class="divider">

        {{-- WARNING --}}
        <div class="warning-box">
            <strong>Peringatan Keamanan:</strong> Jika Anda tidak merasa melakukan permintaan ini,
            abaikan email ini. Kode akan kedaluwarsa secara otomatis. Jangan pernah memberikan
            kode OTP kepada siapapun, termasuk pihak yang mengaku dari STEVA.
        </div>

    </div>

    {{-- FOOTER --}}
    <div class="email-footer">
        <div class="footer-brand">STEVA</div>
        <div class="footer-sub">Studio Tari Eva Tannia</div>
        <div class="footer-note">
            Email ini dikirim secara otomatis, mohon tidak membalas email ini.<br>
            &copy; {{ date('Y') }} STEVA – Seluruh hak dilindungi.
        </div>
    </div>

</div>
</body>
</html>
