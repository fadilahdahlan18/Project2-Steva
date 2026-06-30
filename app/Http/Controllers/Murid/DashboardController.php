<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Materi;
use App\Models\Absensi;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function index()
    {
        $user          = auth()->user();
        $muridId       = $user->id;
        $jenisKelas    = $user->jenis_kelas ? explode(',', $user->jenis_kelas) : [];
        $rawJadwal     = Jadwal::where('kategori_kelas', $user->kategori_kelas)
            ->whereIn('jenis_kelas', $jenisKelas)
            ->orderBy('hari')
            ->orderBy('jam')
            ->get();
        $jadwal        = Jadwal::transformCollection($rawJadwal);
        $totalHadir    = Absensi::where('user_id', $muridId)
            ->where('status', 'hadir')
            ->whereHas('jadwal', function($q) use ($user, $jenisKelas) {
                $q->where('kategori_kelas', $user->kategori_kelas)
                  ->whereIn('jenis_kelas', $jenisKelas);
            })
            ->count();

        $totalTidakHadir   = Absensi::where('user_id', $muridId)
            ->where('status', '!=', 'hadir')
            ->whereHas('jadwal', function($q) use ($user, $jenisKelas) {
                $q->where('kategori_kelas', $user->kategori_kelas)
                  ->whereIn('jenis_kelas', $jenisKelas);
            })
            ->count();

        $totalBayar    = Pembayaran::where('user_id', $muridId)
            ->where('status', 'disetujui')
            ->where(function($q) use ($user, $jenisKelas) {
                $q->whereNull('jadwal_id')
                  ->orWhereHas('jadwal', function($sub) use ($user, $jenisKelas) {
                      $sub->where('kategori_kelas', $user->kategori_kelas)
                          ->whereIn('jenis_kelas', $jenisKelas);
                  });
            })
            ->sum('jumlah');

        $pendingBayar  = Pembayaran::where('user_id', $muridId)
            ->where('status', 'pending')
            ->where(function($q) use ($user, $jenisKelas) {
                $q->whereNull('jadwal_id')
                  ->orWhereHas('jadwal', function($sub) use ($user, $jenisKelas) {
                      $sub->where('kategori_kelas', $user->kategori_kelas)
                          ->whereIn('jenis_kelas', $jenisKelas);
                  });
            })
            ->count();

        $recentAbsensi = Absensi::with('jadwal')
            ->where('user_id', $muridId)
            ->whereHas('jadwal', function($q) use ($user, $jenisKelas) {
                $q->where('kategori_kelas', $user->kategori_kelas)
                  ->whereIn('jenis_kelas', $jenisKelas);
            })
            ->orderBy('tanggal', 'desc')
            ->take(5)->get();

        $pelatihIds    = Jadwal::where('kategori_kelas', $user->kategori_kelas)
            ->whereIn('jenis_kelas', $jenisKelas)
            ->pluck('pelatih_id')
            ->filter()
            ->unique();

        $recentMateri  = Materi::whereIn('pelatih_id', $pelatihIds)
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        return view('murid.dashboard', compact(
            'jadwal', 'totalHadir', 'totalTidakHadir', 'totalBayar',
            'pendingBayar', 'recentAbsensi', 'recentMateri'
        ));
    }
}
