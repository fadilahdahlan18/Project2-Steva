<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\User;
use App\Models\OtpVerification;
use App\Mail\SendOtpMail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $to = $request->get('to');
            if ($to === 'materi') {
                if ($user->role === 'murid' || $user->role === 'pelatih') {
                    return redirect()->route($user->role . '.materi');
                }
            } elseif ($to === 'pembayaran') {
                if ($user->role === 'murid' || $user->role === 'admin') {
                    return redirect()->route($user->role . '.pembayaran');
                }
            }
            return $this->redirectByRole($user->role);
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if ($user->status === 'tidak aktif') {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda tidak aktif. Silakan hubungi admin.'])->withInput();
            }

            if ($user->status === 'pending') {
                Auth::logout();
                return back()->withErrors(['username' => 'Akun Anda masih dalam status menunggu persetujuan admin.'])->withInput();
            }

            if ($user->status === 'ditolak') {
                Auth::logout();
                return back()->withErrors(['username' => 'Pendaftaran akun Anda ditolak oleh admin.'])->withInput();
            }

            // Validasi hak akses berdasarkan parameter 'to'
            $to = $request->input('to');
            if ($to === 'materi') {
                if ($user->role === 'admin') {
                    Auth::logout();
                    return back()->withErrors(['username' => 'Akun Admin tidak memiliki akses ke Materi Latihan.'])->withInput();
                }
            } elseif ($to === 'pembayaran') {
                if ($user->role === 'pelatih') {
                    Auth::logout();
                    return back()->withErrors(['username' => 'Akun Pelatih tidak memiliki akses ke Pembayaran.'])->withInput();
                }
            }

            if ($user->role !== 'admin') {
                $user->last_active_at = now();
                $user->save();
            }

            $request->session()->regenerate();

            // Arahkan langsung ke halaman materi/pembayaran sesuai parameter 'to'
            if ($to === 'materi') {
                if ($user->role === 'murid' || $user->role === 'pelatih') {
                    return redirect()->route($user->role . '.materi');
                }
            } elseif ($to === 'pembayaran') {
                if ($user->role === 'murid' || $user->role === 'admin') {
                    return redirect()->route($user->role . '.pembayaran');
                }
            }

            return $this->redirectByRole($user->role);
        }

        return back()->withErrors(['username' => 'Username atau password salah.'])->withInput();
    }

    public function showRegisterMurid()
    {
        return view('auth.register', ['role' => 'murid']);
    }

    public function registerMurid(Request $request)
    {
        return $this->processRegister($request, 'murid');
    }

    public function showRegisterPelatih()
    {
        return view('auth.register', ['role' => 'pelatih']);
    }

    public function registerPelatih(Request $request)
    {
        return $this->processRegister($request, 'pelatih');
    }

    private function processRegister(Request $request, $role)
    {
        $rules = [
            'nama'     => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:users,username|regex:/^\S+$/',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'no_hp'    => 'nullable|string|max:20',
        ];

        $messages = [
            'nama.required'      => 'Nama Lengkap wajib diisi.',
            'username.required'  => 'Username wajib diisi.',
            'username.unique'    => 'Username sudah digunakan.',
            'username.regex'     => 'Username tidak boleh mengandung spasi.',
            'email.required'     => 'Email wajib diisi.',
            'email.unique'       => 'Email sudah terdaftar.',
            'password.required'  => 'Password wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];

        if ($role === 'murid' || $role === 'pelatih') {
            $rules['kategori_kelas'] = 'required|in:pemula,madya,ahli';
            $rules['jenis_kelas'] = 'required|array';
            $messages['kategori_kelas.required'] = 'Kategori kelas wajib dipilih.';
            $messages['jenis_kelas.required'] = 'Jenis kelas wajib dipilih.';
        }

        $request->validate($rules, $messages);

        $userData = [
            'nama'     => $request->nama,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $role,
            'status'   => 'pending',
            'no_hp'    => $request->no_hp,
        ];

        if ($role === 'pelatih') {
            // Generate unique kode_pelatih
            $lastPelatih = User::where('role', 'pelatih')->orderBy('id', 'desc')->first();
            $nextId = $lastPelatih ? $lastPelatih->id + 1 : 1;
            $userData['kode_pelatih'] = 'PLT-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
        }

        if ($role === 'murid' || $role === 'pelatih') {
            $userData['kategori_kelas'] = $request->kategori_kelas;
            $userData['jenis_kelas'] = implode(',', $request->jenis_kelas);
        }

        User::create($userData);

        $message = 'Registrasi sebagai ' . ucfirst($role) . ' berhasil! Menunggu persetujuan Admin.';

        return redirect()->route('login')->with('success', $message);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('landing')->with('success', 'Anda berhasil keluar.');
    }

    // =========================================================
    // LUPA PASSWORD – TERINTEGRASI OTP
    // Alur: (1) Verifikasi identitas → (2) Kirim OTP email
    //       → (3) Input OTP → (4) Reset password
    // =========================================================

    /** Step 1 – Tampilkan form verifikasi identitas */
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    /**
     * Step 1 POST – Validasi identitas user, lalu kirim OTP ke email.
     * Setelah cocok, OTP langsung dikirim — tidak lagi langsung ke reset.
     */
    public function verifyForgotPassword(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'email'    => 'required|email',
        ], [
            'username.required' => 'Username wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.email'       => 'Format email tidak valid.',
        ]);

        $user = User::where('username', $request->username)
            ->where('email', $request->email)
            ->first();

        if (!$user) {
            return back()
                ->withErrors(['username' => 'Data yang Anda masukkan tidak cocok dengan catatan kami.'])
                ->withInput();
        }

        // Pastikan user punya email untuk dikirim OTP
        if (!$user->email) {
            return back()
                ->withErrors(['email' => 'Akun ini tidak memiliki email terdaftar. Hubungi admin.'])
                ->withInput();
        }

        $email = strtolower(trim($user->email));

        // Rate limiting: maks 3 kirim per email per 60 detik
        $rateLimitKey = 'otp-reset:' . $email;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 3)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()->withInput()->withErrors([
                'username' => "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik."
            ]);
        }
        RateLimiter::hit($rateLimitKey, 60);

        // Batalkan OTP reset lama untuk email ini
        OtpVerification::where('email', $email)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        // Generate OTP 6 digit
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpVerification::create([
            'email'      => $email,
            'otp_code'   => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5),
            'is_used'    => false,
        ]);

        // Kirim OTP via email
        try {
            Mail::to($email)->send(new SendOtpMail($otpCode, $user->nama ?? $user->username, 5));
        } catch (\Exception $e) {
            Log::error('Forgot Password OTP Mail Error: ' . $e->getMessage());
            return back()->withInput()->withErrors([
                'username' => 'Gagal mengirim email OTP. Periksa konfigurasi mail server.'
            ]);
        }

        // Simpan user ID & email di session untuk step berikutnya
        session([
            'password_reset_user_id' => $user->id,
            'password_reset_email'   => $email,
        ]);

        $maskedEmail = $this->maskEmail($email);

        return redirect()->route('forgot.password.otp')
            ->with('success', "Kode OTP telah dikirim ke {$maskedEmail}. Berlaku 5 menit.");
    }

    /** Step 2 – Tampilkan form input OTP (khusus reset password) */
    public function showForgotPasswordOtp(Request $request)
    {
        if (!$request->session()->has('password_reset_user_id')) {
            return redirect()->route('forgot.password')
                ->withErrors(['username' => 'Silakan verifikasi identitas Anda terlebih dahulu.']);
        }
        return view('auth.forgot-password-otp');
    }

    /**
     * Step 2 POST – Validasi OTP untuk reset password.
     * Jika OTP valid → tandai session 'password_reset_otp_verified' → redirect ke reset.
     */
    public function verifyForgotPasswordOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|digits:6',
        ], [
            'otp_code.required' => 'Kode OTP wajib diisi.',
            'otp_code.digits'   => 'Kode OTP harus 6 digit angka.',
        ]);

        if (!$request->session()->has('password_reset_user_id')) {
            return redirect()->route('forgot.password')
                ->with('error', 'Sesi berakhir. Silakan ulangi proses.');
        }

        $email = $request->session()->get('password_reset_email');

        // Rate limiting verifikasi: maks 5 percobaan
        $rateLimitKey = 'otp-verify-reset:' . $email;
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5)) {
            $seconds = RateLimiter::availableIn($rateLimitKey);
            return back()->with('error', "Terlalu banyak percobaan. Coba lagi dalam {$seconds} detik.");
        }

        $otpRecord = OtpVerification::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP tidak ditemukan atau sudah kedaluwarsa. Silakan minta kode baru.');
        }

        if ($request->otp_code !== $otpRecord->otp_code) {
            RateLimiter::hit($rateLimitKey, 300);
            return back()->with('error', 'Kode OTP yang Anda masukkan salah.');
        }

        // OTP valid
        $otpRecord->update(['is_used' => true]);
        RateLimiter::clear($rateLimitKey);
        RateLimiter::clear('otp-reset:' . $email);

        // Tandai OTP sudah diverifikasi
        session(['password_reset_otp_verified' => true]);

        return redirect()->route('reset.password')
            ->with('success', 'Identitas berhasil diverifikasi! Silakan buat password baru.');
    }

    /** Step 3 – Tampilkan form reset password */
    public function showResetPassword()
    {
        if (!session()->has('password_reset_user_id') || !session()->has('password_reset_otp_verified')) {
            return redirect()->route('forgot.password')
                ->withErrors(['username' => 'Silakan selesaikan verifikasi OTP terlebih dahulu.']);
        }

        return view('auth.reset-password');
    }

    /** Step 3 POST – Simpan password baru */
    public function resetPassword(Request $request)
    {
        if (!session()->has('password_reset_user_id') || !session()->has('password_reset_otp_verified')) {
            return redirect()->route('forgot.password');
        }

        $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.min'       => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $userId = session('password_reset_user_id');
        $user   = User::findOrFail($userId);
        $user->password = Hash::make($request->password);
        $user->save();

        // Bersihkan semua session terkait reset
        session()->forget(['password_reset_user_id', 'password_reset_email', 'password_reset_otp_verified']);

        return redirect()->route('login')
            ->with('success', 'Password Anda berhasil diperbarui. Silakan login.');
    }

    /** Helper: Samarkan email (contoh: muh***@gmail.com) */
    private function maskEmail(string $email): string
    {
        [$local, $domain] = explode('@', $email);
        $masked = substr($local, 0, min(3, strlen($local))) . str_repeat('*', max(0, strlen($local) - 3));
        return $masked . '@' . $domain;
    }

    private function redirectByRole($role)
    {
        return match ($role) {
            'admin'   => redirect()->route('admin.dashboard'),
            'pelatih' => redirect()->route('pelatih.dashboard'),
            'murid'   => redirect()->route('murid.dashboard'),
            default   => redirect()->route('login'),
        };
    }
}