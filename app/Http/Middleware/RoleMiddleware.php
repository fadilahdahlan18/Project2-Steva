<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('role:admin') or ->middleware('role:admin,pelatih')
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Update last_active_at setiap 5 menit (non-admin)
        if ($user->role !== 'admin') {
            if (!$user->last_active_at || $user->last_active_at->diffInMinutes(now()) >= 5) {
                $user->last_active_at = now();
                $user->save();
            }
        }

        // Cek apakah status di DB sudah tidak aktif / pending / ditolak
        if ($user->status !== 'aktif') {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['nama' => 'Akun Anda tidak aktif atau sedang menunggu persetujuan admin.']);
        }

        // Auto-nonaktifkan jika murid tidak hadir 3 minggu
        if ($user->role === 'murid' && $user->autoDeactivateIfInactive()) {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['nama' => 'Akun Anda dinonaktifkan karena tidak hadir latihan selama 3 minggu. Silakan hubungi admin untuk mengaktifkan kembali.']);
        }

        $userRole = $user->role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
