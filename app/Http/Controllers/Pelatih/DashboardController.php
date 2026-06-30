<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Materi;
use App\Models\Absensi;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $pelatihId    = auth()->id();
        $totalMateri  = Materi::where('pelatih_id', $pelatihId)->count();
        
        $myJadwalRaw  = Jadwal::where('pelatih_id', $pelatihId)->get();
        $myJadwal     = Jadwal::transformCollection($myJadwalRaw);
        $totalJadwal  = $myJadwal->count();
        
        $totalMurid = User::where('role', 'murid')
            ->where('status', 'aktif')
            ->where(function($q) use ($myJadwalRaw) {
                if ($myJadwalRaw->isEmpty()) {
                    $q->whereRaw('1 = 0');
                    return;
                }
                foreach ($myJadwalRaw as $j) {
                    $q->orWhere(function($sub) use ($j) {
                        $sub->where('kategori_kelas', $j->kategori_kelas)
                            ->where('jenis_kelas', 'like', '%' . $j->jenis_kelas . '%');
                    });
                }
            })
            ->count();

        $recentMateri = Materi::where('pelatih_id', $pelatihId)
            ->orderBy('created_at', 'desc')->take(5)->get();

        $jadwal = Jadwal::transformCollection($myJadwalRaw);

        return view('pelatih.dashboard', compact(
            'totalMateri', 'totalJadwal', 'totalMurid', 'recentMateri', 'jadwal'
        ));
    }
}
