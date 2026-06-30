<?php

use Illuminate\Support\Facades\Route;

// -------------------------------------------------------
// Landing Page
// -------------------------------------------------------
Route::get('/', fn() => view('welcome'))->name('landing');
Route::get('/informasi-kelas', function() {
    return auth()->check() ? view('informasi_kelas_auth') : view('informasi_kelas');
})->name('informasi.kelas');

// -------------------------------------------------------
// Auth (guest only)
// -------------------------------------------------------
Route::middleware('guest')->group(function () {
    Route::get('/login',    [App\Http\Controllers\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [App\Http\Controllers\AuthController::class, 'login'])->name('login.post');

    // Separate registration for Murid and Pelatih
    Route::get('/register/murid',   [App\Http\Controllers\AuthController::class, 'showRegisterMurid'])->name('register.murid');
    Route::post('/register/murid',  [App\Http\Controllers\AuthController::class, 'registerMurid'])->name('register.murid.post');
    Route::get('/register/pelatih', [App\Http\Controllers\AuthController::class, 'showRegisterPelatih'])->name('register.pelatih');
    Route::post('/register/pelatih', [App\Http\Controllers\AuthController::class, 'registerPelatih'])->name('register.pelatih.post');

    // Keep old register route as redirect to choice or murid default?
    // User said: "Register pelatih dan muridnya ubah", so I'll redirect the generic /register.
    Route::get('/register', function() {
        return redirect()->route('register.murid');
    })->name('register');

    // Forgot / Reset Password (sekarang terintegrasi OTP)
    Route::get('/forgot-password',           [App\Http\Controllers\AuthController::class, 'showForgotPassword'])->name('forgot.password');
    Route::post('/forgot-password',          [App\Http\Controllers\AuthController::class, 'verifyForgotPassword'])->name('forgot.password.post');
    Route::get('/forgot-password/otp',       [App\Http\Controllers\AuthController::class, 'showForgotPasswordOtp'])->name('forgot.password.otp');
    Route::post('/forgot-password/otp',      [App\Http\Controllers\AuthController::class, 'verifyForgotPasswordOtp'])->name('forgot.password.otp.post');
    Route::get('/reset-password',            [App\Http\Controllers\AuthController::class, 'showResetPassword'])->name('reset.password');
    Route::post('/reset-password',           [App\Http\Controllers\AuthController::class, 'resetPassword'])->name('reset.password.post');

    // (OTP routes dipindah keluar dari guest group – lihat di bawah)
});

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// -------------------------------------------------------
// OTP Routes – Tanpa middleware (bisa diakses siapapun)
// Dipindah dari guest group agar user yang sudah login
// juga bisa menggunakan fitur verifikasi OTP.
// -------------------------------------------------------
Route::get('/otp',              [App\Http\Controllers\OtpController::class, 'showSendForm'])->name('otp.send.form');
Route::post('/otp/send',        [App\Http\Controllers\OtpController::class, 'sendOtp'])->name('otp.send');
Route::get('/otp/verify',       [App\Http\Controllers\OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/otp/verify',      [App\Http\Controllers\OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/otp/verified',     [App\Http\Controllers\OtpController::class, 'verified'])->name('otp.verified');

// -------------------------------------------------------
// PROFILE ROUTES (All authenticated users)
// -------------------------------------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// -------------------------------------------------------
// ADMIN routes
// -------------------------------------------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
        ->name('dashboard');

    // Users (Murid & Pelatih)
    Route::get('/users',              [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users');
    Route::get('/users/create',       [App\Http\Controllers\Admin\UserController::class, 'create'])->name('users.create');
    Route::post('/users',             [App\Http\Controllers\Admin\UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit',    [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}',         [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}',      [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{id}/approve',[App\Http\Controllers\Admin\UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{id}/reject', [App\Http\Controllers\Admin\UserController::class, 'reject'])->name('users.reject');

    // Jadwal
    Route::get('/jadwal',             [App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('jadwal');
    Route::get('/jadwal/create',      [App\Http\Controllers\Admin\JadwalController::class, 'create'])->name('jadwal.create');
    Route::post('/jadwal',            [App\Http\Controllers\Admin\JadwalController::class, 'store'])->name('jadwal.store');
    Route::get('/jadwal/{id}/edit',   [App\Http\Controllers\Admin\JadwalController::class, 'edit'])->name('jadwal.edit');
    Route::put('/jadwal/{id}',        [App\Http\Controllers\Admin\JadwalController::class, 'update'])->name('jadwal.update');
    Route::delete('/jadwal/{id}',     [App\Http\Controllers\Admin\JadwalController::class, 'destroy'])->name('jadwal.destroy');

    Route::get('/absensi',            [App\Http\Controllers\Admin\AbsensiController::class, 'index'])->name('absensi');
    Route::post('/absensi/bulk',      [App\Http\Controllers\Admin\AbsensiController::class, 'bulkStore'])->name('absensi.bulkStore');

    // Pembayaran
    Route::get('/pembayaran',          [App\Http\Controllers\Admin\PembayaranController::class, 'index'])->name('pembayaran');
    Route::get('/pembayaran/create',   [App\Http\Controllers\Admin\PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran',         [App\Http\Controllers\Admin\PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/{id}',     [App\Http\Controllers\Admin\PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pembayaran/{id}/edit',[App\Http\Controllers\Admin\PembayaranController::class, 'edit'])->name('pembayaran.edit');
    Route::put('/pembayaran/{id}',     [App\Http\Controllers\Admin\PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::patch('/pembayaran/{id}/validasi', [App\Http\Controllers\Admin\PembayaranController::class, 'validasi'])->name('pembayaran.validasi');
    Route::delete('/pembayaran/{id}',  [App\Http\Controllers\Admin\PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

    // Rekening
    Route::get('/rekening/edit', [App\Http\Controllers\Admin\RekeningController::class, 'edit'])->name('rekening.edit');
    Route::put('/rekening',      [App\Http\Controllers\Admin\RekeningController::class, 'update'])->name('rekening.update');

    // Laporan
    Route::get('/laporan',             [App\Http\Controllers\Admin\LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/monitoring',  [App\Http\Controllers\Admin\LaporanController::class, 'monitoring'])->name('laporan.monitoring');
});

// -------------------------------------------------------
// PELATIH routes
// -------------------------------------------------------
Route::prefix('pelatih')->name('pelatih.')->middleware(['auth', 'role:pelatih'])->group(function () {

    Route::get('/dashboard', [App\Http\Controllers\Pelatih\DashboardController::class, 'index'])
        ->name('dashboard');

    // Jadwal
    Route::get('/jadwal', [App\Http\Controllers\Admin\JadwalController::class, 'index'])->name('jadwal');

    // Absensi
    Route::get('/absensi',            [App\Http\Controllers\Pelatih\AbsensiController::class, 'index'])->name('absensi');
    Route::get('/absensi/eligible-students', [App\Http\Controllers\Pelatih\AbsensiController::class, 'getEligibleStudents'])->name('absensi.eligible-students');
    Route::get('/absensi/create',     [App\Http\Controllers\Pelatih\AbsensiController::class, 'create'])->name('absensi.create');
    Route::post('/absensi',           [App\Http\Controllers\Pelatih\AbsensiController::class, 'store'])->name('absensi.store');
    Route::get('/absensi/{id}/edit',  [App\Http\Controllers\Pelatih\AbsensiController::class, 'edit'])->name('absensi.edit');
    Route::put('/absensi/{id}',       [App\Http\Controllers\Pelatih\AbsensiController::class, 'update'])->name('absensi.update');
    Route::delete('/absensi/{id}',    [App\Http\Controllers\Pelatih\AbsensiController::class, 'destroy'])->name('absensi.destroy');

    // Materi
    Route::get('/materi',             [App\Http\Controllers\Pelatih\MateriController::class, 'index'])->name('materi');
    Route::get('/materi/create',      [App\Http\Controllers\Pelatih\MateriController::class, 'create'])->name('materi.create');
    Route::post('/materi',            [App\Http\Controllers\Pelatih\MateriController::class, 'store'])->name('materi.store');
    Route::get('/materi/{id}/edit',   [App\Http\Controllers\Pelatih\MateriController::class, 'edit'])->name('materi.edit');
    Route::put('/materi/{id}',        [App\Http\Controllers\Pelatih\MateriController::class, 'update'])->name('materi.update');
    Route::delete('/materi/{id}',     [App\Http\Controllers\Pelatih\MateriController::class, 'destroy'])->name('materi.destroy');
});

// -------------------------------------------------------
// MURID routes
// -------------------------------------------------------
Route::prefix('murid')->name('murid.')->middleware(['auth', 'role:murid'])->group(function () {

    Route::get('/dashboard',  [App\Http\Controllers\Murid\DashboardController::class, 'index'])->name('dashboard');

    // Jadwal
    Route::get('/jadwal',     [App\Http\Controllers\Murid\MateriController::class, 'jadwal'])->name('jadwal');

    // Materi
    Route::get('/materi',     [App\Http\Controllers\Murid\MateriController::class, 'index'])->name('materi');

    // Absensi (personal history)
    Route::get('/absensi',    [App\Http\Controllers\Murid\AbsensiController::class, 'index'])->name('absensi');

    // Pembayaran
    Route::get('/pembayaran',         [App\Http\Controllers\Murid\PembayaranController::class, 'index'])->name('pembayaran');
    Route::post('/pembayaran',        [App\Http\Controllers\Murid\PembayaranController::class, 'store'])->name('pembayaran.store');
});

// -------------------------------------------------------
// Fallback route untuk menyajikan file dari storage/app/public jika public/storage tidak ter-link
// -------------------------------------------------------
Route::get('storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (!file_exists($filePath)) {
        abort(404);
    }
    return response()->file($filePath);
})->where('path', '.*');
