<?php

namespace App\Http\Controllers;

use App\Mail\SendOtpMail;
use App\Models\OtpVerification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

class OtpController extends Controller
{
    /**
     * Durasi berlakunya OTP (dalam menit).
     */
    private const OTP_EXPIRY_MINUTES = 5;

    /**
     * Maksimum percobaan pengiriman OTP per email per menit (rate limiting).
     */
    private const MAX_SEND_ATTEMPTS = 3;

    // ─────────────────────────────────────────────────────────────
    // VIEWS
    // ─────────────────────────────────────────────────────────────

    /** Tampilkan form kirim OTP */
    public function showSendForm()
    {
        return view('otp.send');
    }

    /** Tampilkan form verifikasi OTP */
    public function showVerifyForm(Request $request)
    {
        // Pastikan email sudah ada di session (dari langkah sebelumnya)
        if (!$request->session()->has('otp_email')) {
            return redirect()->route('otp.send.form')
                ->with('error', 'Silakan masukkan email Anda terlebih dahulu.');
        }
        return view('otp.verify');
    }

    // ─────────────────────────────────────────────────────────────
    // LOGIKA BISNIS
    // ─────────────────────────────────────────────────────────────

    /**
     * STEP 1 – Generate OTP 6 digit, simpan ke DB, kirim via email.
     *
     * Pendekatan keamanan:
     *  - Rate limiting: maks 3 kirim/menit per email.
     *  - OTP lama untuk email yang sama akan di-nonaktifkan sebelum membuat yang baru.
     *  - OTP disimpan sebagai plain text (6 digit angka) — cukup aman karena:
     *    a) berlaku hanya 5 menit,  b) single-use,  c) tidak sensitif seperti password.
     */
    public function sendOtp(Request $request)
    {
        // ── Validasi input ──
        $request->validate([
            'email' => 'required|email|max:255',
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $email = strtolower(trim($request->email));

        // ── Rate Limiting ──
        $rateLimitKey = 'otp-send:' . $email;
        if (RateLimiter::tooManyAttempts($rateLimitKey, self::MAX_SEND_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()->withInput()->with(
                'error',
                "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."
            );
        }
        RateLimiter::hit($rateLimitKey, 60); // Reset setiap 60 detik

        // ── Batalkan semua OTP lama untuk email ini ──
        OtpVerification::where('email', $email)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // ── Generate kode OTP 6 digit ──
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // ── Simpan ke database ──
        OtpVerification::create([
            'email'      => $email,
            'otp_code'   => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(self::OTP_EXPIRY_MINUTES),
            'is_used'    => false,
        ]);

        // ── Kirim email ──
        try {
            Mail::to($email)->send(new SendOtpMail($otpCode, $email, self::OTP_EXPIRY_MINUTES));
        } catch (\Exception $e) {
            // Log error tapi jangan expose detail ke user
            \Log::error('OTP Mail Error: ' . $e->getMessage());
            return back()->withInput()->with(
                'error',
                'Gagal mengirim email. Periksa konfigurasi mail Anda dan coba lagi.'
            );
        }

        // ── Simpan email di session untuk langkah verifikasi ──
        $request->session()->put('otp_email', $email);

        return redirect()->route('otp.verify.form')
            ->with('success', "Kode OTP berhasil dikirim ke {$email}. Berlaku selama " . self::OTP_EXPIRY_MINUTES . " menit.");
    }

    /**
     * STEP 2 – Validasi kode OTP yang diinput user.
     *
     * Alur validasi:
     *  1. Ambil email dari session (bukan dari input user, agar tidak bisa dimanipulasi).
     *  2. Cari OTP yang aktif (belum dipakai & belum expired) di DB.
     *  3. Bandingkan kode.
     *  4. Jika cocok → tandai is_used = true, hapus session, redirect sukses.
     *  5. Jika tidak cocok → kembalikan error.
     */
    public function verifyOtp(Request $request)
    {
        // ── Validasi input ──
        $request->validate([
            'otp_code' => 'required|digits:6',
        ], [
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'otp_code.digits'   => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        // ── Ambil email dari session ──
        $email = $request->session()->get('otp_email');
        if (!$email) {
            return redirect()->route('otp.send.form')
                ->with('error', 'Sesi telah berakhir. Silakan minta kode OTP baru.');
        }

        // ── Rate Limiting verifikasi ──
        $rateLimitKey = 'otp-verify:' . $email;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()->with(
                'error',
                "Terlalu banyak percobaan verifikasi. Coba lagi dalam {$seconds} detik."
            );
        }

        // ── Cari OTP aktif di database ──
        $otpRecord = OtpVerification::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP tidak ditemukan atau sudah kedaluwarsa. Silakan minta kode baru.');
        }

        // ── Bandingkan kode ──
        if ($request->otp_code !== $otpRecord->otp_code) {
            RateLimiter::hit($rateLimitKey, 300); // Catat percobaan gagal
            return back()->with('error', 'Kode OTP yang Anda masukkan salah. Periksa kembali email Anda.');
        }

        // ── OTP Cocok! Tandai sebagai sudah dipakai ──
        $otpRecord->update(['is_used' => true]);

        // ── Bersihkan session & rate limiter ──
        $request->session()->forget('otp_email');
        RateLimiter::clear($rateLimitKey);
        RateLimiter::clear('otp-send:' . $email);

        // ── Tandai email sudah terverifikasi di session ──
        $request->session()->put('otp_verified_email', $email);

        return redirect()->route('otp.verified')
            ->with('success', 'Email Anda berhasil diverifikasi! 🎉');
    }

    /**
     * Halaman konfirmasi setelah OTP berhasil diverifikasi.
     */
    public function verified(Request $request)
    {
        if (!$request->session()->has('otp_verified_email')) {
            return redirect()->route('otp.send.form');
        }

        $verifiedEmail = $request->session()->get('otp_verified_email');

        // Optional: hapus session verified setelah ditampilkan sekali
        // $request->session()->forget('otp_verified_email');

        return view('otp.verified', compact('verifiedEmail'));
    }
}
