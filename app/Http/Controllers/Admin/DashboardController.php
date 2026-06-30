<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Absensi;
use App\Models\Pembayaran;
use App\Models\Materi;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMurid    = User::where('role', 'murid')->count();
        $totalPelatih  = User::where('role', 'pelatih')->count();
        $totalJadwal   = Jadwal::count();
        $totalMateri   = Materi::count();
        $pendingBayar  = Pembayaran::where('status', 'pending')->has('user')->count();
        $totalAbsensi  = Absensi::count();
        
        $totalPendapatan = Pembayaran::where('status', 'disetujui')->has('user')->sum('jumlah');
        
        $perbandinganKelas = User::where('role', 'murid')
            ->selectRaw('kategori_kelas, count(*) as total')
            ->groupBy('kategori_kelas')
            ->get();

        $pendingPelatih = User::where('role', 'pelatih')->where('status', 'pending')->get();
        $pendingMurid = User::where('role', 'murid')->where('status', 'pending')->get();

        $recentPembayaran = Pembayaran::with('user')
            ->has('user')
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        $recentAbsensi = Absensi::with(['user', 'jadwal'])
            ->orderBy('created_at', 'desc')
            ->take(5)->get();

        return view('admin.dashboard', compact(
            'totalMurid', 'totalPelatih', 'totalJadwal', 'totalMateri',
            'pendingBayar', 'totalAbsensi', 'recentPembayaran', 'recentAbsensi',
            'totalPendapatan', 'perbandinganKelas', 'pendingPelatih', 'pendingMurid'
        ));
    }
}
