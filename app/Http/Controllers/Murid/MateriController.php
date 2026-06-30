<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Jadwal;

class MateriController extends Controller
{
    public function index()
    {
        $user          = auth()->user();
        $jenisKelas    = $user->jenis_kelas ? explode(',', $user->jenis_kelas) : [];
        $pelatihIds    = Jadwal::where('kategori_kelas', $user->kategori_kelas)
            ->whereIn('jenis_kelas', $jenisKelas)
            ->pluck('pelatih_id')
            ->filter()
            ->unique();

        $materi = Materi::with('pelatih')
            ->whereIn('pelatih_id', $pelatihIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('murid.materi.index', compact('materi'));
    }

    public function jadwal()
    {
        $user          = auth()->user();
        $jenisKelas    = $user->jenis_kelas ? explode(',', $user->jenis_kelas) : [];
        $rawJadwal     = Jadwal::where('kategori_kelas', $user->kategori_kelas)
            ->whereIn('jenis_kelas', $jenisKelas)
            ->orderBy('hari')
            ->orderBy('jam')
            ->get();
        $jadwal        = Jadwal::transformCollection($rawJadwal);
        return view('murid.jadwal', compact('jadwal'));
    }
}
