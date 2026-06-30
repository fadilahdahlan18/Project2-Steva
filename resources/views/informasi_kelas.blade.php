<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Kelas & FAQ – STEVA</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --burgundy:       #800020;
            --burgundy-dark:  #5c0016;
            --burgundy-light: #a0002a;
            --burgundy-pale:  #f9e8ec;
            --gold:           #c9a84c;
            --gold-light:     #f0d080;
            --white:          #ffffff;
            --gray-100:       #f1f3f5;
            --gray-200:       #e9ecef;
            --gray-300:       #dee2e6;
            --gray-500:       #adb5bd;
            --gray-600:       #6c757d;
            --gray-700:       #495057;
            --gray-800:       #343a40;
            --gray-900:       #212529;
            --transition:     0.22s ease;
            --radius:         12px;
            --radius-sm:      8px;
            --shadow-sm:      0 2px 8px rgba(0,0,0,.06);
            --shadow:         0 10px 30px rgba(0,0,0,.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--burgundy-dark) 0%, #3a000d 100%);
            color: var(--gray-800);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 40px 20px;
        }

        /* Floating Back Button */
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
            transition: all var(--transition);
            backdrop-filter: blur(8px);
            z-index: 1000;
        }

        .btn-back-floating:hover {
            background: #ffffff;
            color: var(--burgundy);
            border-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transform: translateY(-1px);
        }

        .btn-back-floating i {
            font-size: 12px;
            transition: transform var(--transition);
        }

        .btn-back-floating:hover i {
            transform: translateX(-3px);
        }

        .guest-wrapper {
            width: 100%;
            max-width: 900px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            overflow: hidden;
            margin-top: 40px;
        }

        @media (max-width: 768px) {
            body {
                padding: 20px 12px;
            }
            .btn-back-floating {
                position: static;
                margin-bottom: 20px;
                align-self: flex-start;
                background: rgba(255, 255, 255, 0.15);
            }
            .guest-wrapper {
                margin-top: 0;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('landing') }}" class="btn-back-floating" title="Kembali ke Beranda">
        <i class="fa-solid fa-arrow-left"></i>
        <span>Kembali ke Beranda</span>
    </a>

    <div class="guest-wrapper">
        <div style="background: linear-gradient(135deg, var(--burgundy) 0%, var(--burgundy-dark) 100%); padding: 30px; text-align: center; color: #fff;">
            <h1 style="font-size: 26px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase;">Perbandingan Kelas & FAQ</h1>
            <p style="font-size: 13px; color: rgba(255,255,255,0.8); margin-top: 6px; letter-spacing: 0.5px;">Informasi lengkap mengenai jenis, tingkat kelas, dan jadwal latihan Studio Tari Eva Tannia</p>
        </div>
        <div style="padding: 30px;">
            @include('informasi_kelas_content')
        </div>
    </div>
</body>
</html>
