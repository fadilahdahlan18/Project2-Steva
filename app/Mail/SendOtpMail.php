<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Kode OTP 6 digit yang akan dikirim ke email pengguna.
     */
    public string $otpCode;

    /**
     * Nama penerima (opsional, untuk personalisasi email).
     */
    public string $recipientName;

    /**
     * Waktu kedaluwarsa (menit).
     */
    public int $expiryMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otpCode, string $recipientName = 'Pengguna STEVA', int $expiryMinutes = 5)
    {
        $this->otpCode       = $otpCode;
        // Jika yang dikirim adalah email address, tampilkan sebagai 'Pengguna STEVA'
        $this->recipientName = filter_var($recipientName, FILTER_VALIDATE_EMAIL)
            ? 'Pengguna STEVA'
            : $recipientName;
        $this->expiryMinutes = $expiryMinutes;
    }

    /**
     * Get the message envelope.
     * Subject tanpa emoji agar tidak di-flag sebagai spam.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Verifikasi Akun STEVA',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
