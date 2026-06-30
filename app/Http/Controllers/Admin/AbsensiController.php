<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Jadwal;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->get('tanggal', date('Y-m-d'));
        $jadwal_id = $request->get('jadwal_id');

        $jadwalList = Jadwal::orderBy('nama_kelas')->get();
        $muridList = collect();
        $absensiMap = [];
        $selectedJadwal = null;

        if ($jadwal_id && $tanggal) {
            $jadwal = Jadwal::with('pelatih')->find($jadwal_id);
            if ($jadwal) {
                $selectedJadwal = $jadwal;
                // Find all murid that belong to this class category and type
                $muridList = User::where('role', 'murid')
                    ->where('status', 'aktif')
                    ->where('kategori_kelas', $jadwal->kategori_kelas)
                    ->where('jenis_kelas', 'like', '%' . $jadwal->jenis_kelas . '%')
                    ->orderBy('nama')
                    ->paginate(5)
                    ->appends(['tanggal' => $tanggal, 'jadwal_id' => $jadwal_id]);

                $absensiRecords = Absensi::where('jadwal_id', $jadwal_id)
                    ->where('tanggal', $tanggal)
                    ->get();
                
                foreach ($absensiRecords as $abs) {
                    $absensiMap[$abs->user_id] = $abs->status;
                }
            }
        }

        return view('admin.absensi.index', compact('jadwalList', 'tanggal', 'jadwal_id', 'muridList', 'absensiMap', 'selectedJadwal'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id',
            'tanggal'   => 'required|date',
            'status'    => 'required|array', // user_id => status
        ]);

        foreach ($request->status as $user_id => $status) {
            Absensi::updateOrCreate(
                ['user_id' => $user_id, 'jadwal_id' => $request->jadwal_id, 'tanggal' => $request->tanggal],
                ['status'  => $status]
            );
        }

        return redirect()->back()->with('success', 'Data absensi berhasil disimpan.');
    }

    public function create()
    {
        $muridList  = User::where('role', 'murid')->orderBy('nama')->get();
        $jadwalList = Jadwal::orderBy('nama_kelas')->get();
        return view('admin.absensi.create', compact('muridList', 'jadwalList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'jadwal_id' => 'required|exists:jadwal,id',
            'tanggal'   => 'required|date',
            'status'    => 'required|in:hadir,izin,alpha',
        ]);

        Absensi::updateOrCreate(
            ['user_id' => $request->user_id, 'jadwal_id' => $request->jadwal_id, 'tanggal' => $request->tanggal],
            ['status'  => $request->status]
        );

        return redirect()->route('admin.absensi')->with('success', 'Absensi berhasil disimpan.');
    }

    public function edit($id)
    {
        $absensi    = Absensi::findOrFail($id);
        $muridList  = User::where('role', 'murid')->orderBy('nama')->get();
        $jadwalList = Jadwal::orderBy('nama_kelas')->get();
        return view('admin.absensi.edit', compact('absensi', 'muridList', 'jadwalList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'jadwal_id' => 'required|exists:jadwal,id',
            'tanggal'   => 'required|date',
            'status'    => 'required|in:hadir,izin,alpha',
        ]);

        Absensi::findOrFail($id)->update($request->only('user_id', 'jadwal_id', 'tanggal', 'status'));
        return redirect()->route('admin.absensi')->with('success', 'Absensi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Absensi::findOrFail($id)->delete();
        return redirect()->route('admin.absensi')->with('success', 'Absensi berhasil dihapus.');
    }
}
