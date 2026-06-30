<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->get('bulan', now()->month);
        $tahun = $request->get('tahun', now()->year);
        $kelas = $request->get('kelas');

        $user = auth()->user();
        $jenisKelas = $user->jenis_kelas ? explode(',', $user->jenis_kelas) : [];

        if ($kelas && in_array($kelas, $jenisKelas)) {
            $filteredJenisKelas = [$kelas];
        } else {
            $filteredJenisKelas = $jenisKelas;
        }

        $absensi = Absensi::with('jadwal')
            ->where('user_id', $user->id)
            ->whereHas('jadwal', function($q) use ($user, $filteredJenisKelas) {
                $q->where('kategori_kelas', $user->kategori_kelas)
                  ->whereIn('jenis_kelas', $filteredJenisKelas);
            })
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->orderBy('tanggal', 'desc')
            ->get();

        $hadir = $absensi->where('status', 'hadir')->count();
        $TidakHadir  = $absensi->where('status', '!=', 'hadir')->count();

        return view('murid.absensi.index', compact('absensi', 'hadir', 'TidakHadir', 'bulan', 'tahun', 'kelas', 'jenisKelas'));
    }
}
