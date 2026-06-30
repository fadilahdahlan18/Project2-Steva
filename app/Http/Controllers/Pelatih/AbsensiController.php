<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Jadwal;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tanggal   = $request->get('tanggal', today()->toDateString());
        $jadwal_id = $request->get('jadwal_id');

        $absensi = Absensi::with(['user', 'jadwal'])
            ->whereHas('jadwal', fn($q) => $q->where('pelatih_id', auth()->id()))
            ->when($jadwal_id, fn($q) => $q->where('jadwal_id', $jadwal_id))
            ->when($tanggal, fn($q) => $q->where('tanggal', $tanggal))
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $jadwalList = Jadwal::where('pelatih_id', auth()->id())->orderBy('nama_kelas')->get();

        return view('pelatih.absensi.index', compact('absensi', 'jadwalList', 'tanggal', 'jadwal_id'));
    }

    public function create()
    {
        $jadwalList = Jadwal::where('pelatih_id', auth()->id())->orderBy('nama_kelas')->get();
        return view('pelatih.absensi.create', compact('jadwalList'));
    }

    public function getEligibleStudents(Request $request)
    {
        $request->validate([
            'jadwal_id' => 'required|exists:jadwal,id,pelatih_id,' . auth()->id(),
            'tanggal'   => 'required|date',
        ]);

        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        $muridList = User::where('role', 'murid')
            ->where('status', 'aktif')
            ->where('kategori_kelas', $jadwal->kategori_kelas)
            ->where('jenis_kelas', 'like', '%' . $jadwal->jenis_kelas . '%')
            ->whereDoesntHave('absensi', function($q) use ($request) {
                $q->where('jadwal_id', $request->jadwal_id)
                  ->where('tanggal', $request->tanggal);
            })
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($muridList);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id'                => 'required|exists:jadwal,id,pelatih_id,' . auth()->id(),
            'tanggal'                  => 'required|date',
            'absensi'                  => 'required|array',
            'absensi.*.user_id'        => 'required|distinct|exists:users,id',
            'absensi.*.status'         => 'required|in:hadir,izin,alpha',
        ], [
            'absensi.*.user_id.distinct' => 'Data murid tidak boleh duplikat.',
        ]);

        foreach ($request->absensi as $item) {
            Absensi::updateOrCreate(
                ['user_id' => $item['user_id'], 'jadwal_id' => $request->jadwal_id, 'tanggal' => $request->tanggal],
                ['status'  => $item['status']]
            );
        }

        return redirect()->route('pelatih.absensi')->with('success', 'Absensi berhasil disimpan.');
    }

    public function edit($id)
    {
        $absensi    = Absensi::whereHas('jadwal', fn($q) => $q->where('pelatih_id', auth()->id()))->findOrFail($id);
        
        $myJadwalRaw = Jadwal::where('pelatih_id', auth()->id())->get();
        $muridList = User::where('role', 'murid')
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
            ->orderBy('nama')
            ->get();

        $jadwalList = Jadwal::where('pelatih_id', auth()->id())->orderBy('nama_kelas')->get();

        return view('pelatih.absensi.edit', compact('absensi', 'muridList', 'jadwalList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,alpha',
        ]);
        $absensi = Absensi::whereHas('jadwal', fn($q) => $q->where('pelatih_id', auth()->id()))->findOrFail($id);
        $absensi->update(['status' => $request->status]);
        return redirect()->route('pelatih.absensi')->with('success', 'Absensi diperbarui.');
    }

    public function destroy($id)
    {
        $absensi = Absensi::whereHas('jadwal', fn($q) => $q->where('pelatih_id', auth()->id()))->findOrFail($id);
        $absensi->delete();
        return redirect()->route('pelatih.absensi')->with('success', 'Absensi berhasil dihapus.');
    }
}
