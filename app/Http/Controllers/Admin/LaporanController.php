<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Absensi;
use App\Models\Pembayaran;
use App\Models\Jadwal;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $jadwalId = $request->get('jadwal_id');
        $jadwalList = Jadwal::orderBy('kategori_kelas')->get();

        $selectedJadwal = null;
        if ($jadwalId) {
            $selectedJadwal = Jadwal::find($jadwalId);
        }

        $totalMuridQuery = User::where('role', 'murid');
        $totalPelatihQuery = User::where('role', 'pelatih');
        $totalPendapatanQuery = Pembayaran::where('status', 'disetujui')->has('user');
        $pendapatanBulanIniQuery = Pembayaran::where('status', 'disetujui')
            ->has('user')
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year);

        if ($selectedJadwal) {
            $totalMuridQuery->where('kategori_kelas', $selectedJadwal->kategori_kelas)
                ->where('jenis_kelas', 'like', '%' . $selectedJadwal->jenis_kelas . '%');
            $totalPelatihQuery->where('id', $selectedJadwal->pelatih_id);
            $totalPendapatanQuery->where('jadwal_id', $selectedJadwal->id);
            $pendapatanBulanIniQuery->where('jadwal_id', $selectedJadwal->id);
        }

        $totalMurid = $totalMuridQuery->count();
        $totalPelatih = $totalPelatihQuery->count();
        $totalPendapatan = $totalPendapatanQuery->sum('jumlah');
        $pendapatanBulanIni = $pendapatanBulanIniQuery->sum('jumlah');

        // Perbandingan antar kelas
        $perbandinganKelasQuery = User::where('role', 'murid');
        if ($selectedJadwal) {
            $perbandinganKelasQuery->where('kategori_kelas', $selectedJadwal->kategori_kelas)
                ->where('jenis_kelas', 'like', '%' . $selectedJadwal->jenis_kelas . '%');
        }
        
        $perbandinganKelas = $perbandinganKelasQuery
            ->selectRaw('kategori_kelas, count(*) as total')
            ->groupBy('kategori_kelas')
            ->get();

        return view('admin.laporan.index', compact(
            'totalMurid', 
            'totalPelatih', 
            'totalPendapatan', 
            'pendapatanBulanIni', 
            'perbandinganKelas',
            'jadwalList',
            'jadwalId'
        ));
    }

    public function monitoring(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        $jadwalId  = $request->get('jadwal_id');

        $jadwalList = Jadwal::orderBy('kategori_kelas')->get();
        $selectedJadwal = null;
        if ($jadwalId) {
            $selectedJadwal = Jadwal::find($jadwalId);
        }

        // Pendapatan STEVA (Total)
        $totalPendapatanQuery = Pembayaran::where('status', 'disetujui')
            ->has('user')
            ->whereBetween('tanggal', [$startDate, $endDate]);

        // Jumlah murid aktif
        $totalMuridQuery = User::where('role', 'murid')->where('status', 'aktif');

        if ($selectedJadwal) {
            $totalPendapatanQuery->where('jadwal_id', $selectedJadwal->id);
            $totalMuridQuery->where('kategori_kelas', $selectedJadwal->kategori_kelas)
                ->where('jenis_kelas', 'like', '%' . $selectedJadwal->jenis_kelas . '%');
        }

        $totalPendapatan = $totalPendapatanQuery->sum('jumlah');
        $totalMurid = $totalMuridQuery->count();

        // Pendapatan dan Murid per Kelas
        $laporanKelas = [];
        
        foreach ($jadwalList as $jadwal) {
            if ($selectedJadwal && $selectedJadwal->id !== $jadwal->id) {
                continue;
            }

            $namaKelas = $jadwal->nama_kelas ?? 'Kelas ' . ucfirst($jadwal->kategori_kelas) . ' ' . ucfirst($jadwal->jenis_kelas);
            $muridKelas = User::where('role', 'murid')
                ->where('status', 'aktif')
                ->where('kategori_kelas', $jadwal->kategori_kelas)
                ->where('jenis_kelas', 'like', '%' . $jadwal->jenis_kelas . '%')
                ->get();
                
            $muridIds = $muridKelas->pluck('id');
            
            $pendapatan = Pembayaran::whereIn('user_id', $muridIds)
                ->where('status', 'disetujui')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->sum('jumlah');
                
            $laporanKelas[] = [
                'nama_kelas' => $namaKelas,
                'jumlah_murid' => $muridKelas->count(),
                'pendapatan' => $pendapatan
            ];
        }

        // 1. Detail Murid
        $detailMuridQuery = User::where('role', 'murid');
        if ($selectedJadwal) {
            $detailMuridQuery->where('kategori_kelas', $selectedJadwal->kategori_kelas)
                ->where('jenis_kelas', 'like', '%' . $selectedJadwal->jenis_kelas . '%');
        }
        $detailMurid = $detailMuridQuery->orderBy('nama')->get();

        // 2. Detail Pembayaran
        $detailPembayaranQuery = Pembayaran::with('user', 'jadwal')
            ->has('user')
            ->whereBetween('tanggal', [$startDate, $endDate]);
        if ($selectedJadwal) {
            $detailPembayaranQuery->where('jadwal_id', $selectedJadwal->id);
        }
        $detailPembayaran = $detailPembayaranQuery->orderBy('tanggal', 'desc')->get();

        // 3. Detail Absensi
        $detailAbsensiQuery = Absensi::with('user', 'jadwal')
            ->whereBetween('tanggal', [$startDate, $endDate]);
        if ($selectedJadwal) {
            $detailAbsensiQuery->where('jadwal_id', $selectedJadwal->id);
        }
        $detailAbsensi = $detailAbsensiQuery->orderBy('tanggal', 'desc')->get();

        return view('admin.laporan.laporan', compact(
            'startDate', 
            'endDate',
            'totalPendapatan',
            'totalMurid',
            'laporanKelas',
            'jadwalList',
            'jadwalId',
            'selectedJadwal',
            'detailMurid',
            'detailPembayaran',
            'detailAbsensi'
        ));
    }
}
