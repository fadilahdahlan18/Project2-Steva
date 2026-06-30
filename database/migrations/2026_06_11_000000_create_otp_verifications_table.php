<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel otp_verifications untuk menyimpan kode OTP sementara.
     * Pendekatan tabel terpisah dipilih agar tidak "mengotori" tabel users
     * dan memudahkan pembersihan (hapus record setelah verifikasi / expired).
     */
    public function up(): void
    {
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();        // Email tujuan OTP
            $table->string('otp_code', 6);           // Kode OTP 6 digit
            $table->timestamp('expires_at');         // Waktu kedaluwarsa
            $table->boolean('is_used')->default(false); // Tandai jika sudah dipakai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};
