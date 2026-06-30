<?php

namespace App\Console\Commands;

use App\Mail\SendOtpMail;
use App\Models\OtpVerification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Test kirim OTP email ke alamat email yang ditentukan';

    public function handle()
    {
        $email   = $this->argument('email');
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->info("Kirim OTP [{$otpCode}] ke: {$email}");

        // Simpan ke DB agar bisa diverifikasi
        OtpVerification::where('email', $email)->where('is_used', false)->update(['is_used' => true]);
        OtpVerification::create([
            'email'      => $email,
            'otp_code'   => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(5),
            'is_used'    => false,
        ]);

        try {
            Mail::to($email)->send(new SendOtpMail($otpCode, $email, 5));
            $this->info("✅ OTP berhasil dikirim ke {$email}! Cek inbox (atau folder Spam).");
        } catch (\Exception $e) {
            $this->error('❌ Gagal: ' . $e->getMessage());
        }
    }
}
