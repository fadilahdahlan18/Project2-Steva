<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpVerification extends Model
{
    use HasFactory;

    protected $table = 'otp_verifications';

    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used'    => 'boolean',
    ];

    /**
     * Cek apakah OTP masih berlaku (belum expired dan belum dipakai).
     */
    public function isValid(): bool
    {
        return !$this->is_used && Carbon::now()->lessThanOrEqualTo($this->expires_at);
    }

    /**
     * Scope untuk mendapatkan OTP aktif berdasarkan email.
     */
    public function scopeActiveForEmail($query, string $email)
    {
        return $query->where('email', $email)
                     ->where('is_used', false)
                     ->where('expires_at', '>', Carbon::now());
    }
}
