<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jadwal;

class JadwalController extends Controller
{
    public function index()
    {
        if (auth()->check() && auth()->user()->role === 'pelatih') {
            $rawJadwal = Jadwal::with('pelatih')
                ->where('pelatih_id', auth()->id())
                ->orderBy('hari')
                ->orderBy('jam')
                ->get();

            $transformed = Jadwal::transformCollection($rawJadwal);

            // Paginate the transformed collection
            $perPage = 10;
            $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
            $currentItems = $transformed->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $jadwal = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $transformed->count(),
                $perPage,
                $currentPage,
                ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
            );
        } else {
            $jadwal = Jadwal::with('pelatih')->orderBy('hari')->orderBy('jam')->paginate(10);
        }

        foreach ($jadwal as $j) {
            $j->jumlah_murid = \App\Models\User::where('role', 'murid')
                ->where('status', 'aktif')
                ->where('kategori_kelas', $j->kategori_kelas)
                ->where('jenis_kelas', 'like', '%' . $j->jenis_kelas . '%')
                ->count();
        }
        return view('admin.jadwal.index', compact('jadwal'));
    }

    public function create()
    {
        $pelatihList = \App\Models\User::where('role', 'pelatih')->where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.jadwal.create', compact('pelatihList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelatih_id' => 'required|exists:users,id',
            'kategori_kelas' => 'required|in:pemula,madya,ahli',
            'jenis_kelas' => 'required|in:rampak,reguler',
            'hari'       => 'required|string',
            'jam'        => ['required', 'string', 'regex:/^[0-2][0-9]\.[0-5][0-9]-[0-2][0-9]\.[0-5][0-9]$/'],
        ], [
            'jam.regex' => 'Format jam harus 00.00-00.00 (contoh: 14.00-16.00)',
        ]);

        $data = $request->only('pelatih_id', 'kategori_kelas', 'jenis_kelas', 'hari', 'jam');
        $data['nama_kelas'] = 'Kelas ' . ucfirst($request->kategori_kelas) . ' ' . ucfirst($request->jenis_kelas);

        Jadwal::create($data);

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $pelatihList = \App\Models\User::where('role', 'pelatih')->where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.jadwal.edit', compact('jadwal', 'pelatihList'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pelatih_id' => 'required|exists:users,id',
            'kategori_kelas' => 'required|in:pemula,madya,ahli',
            'jenis_kelas' => 'required|in:rampak,reguler',
            'hari'       => 'required|string',
            'jam'        => ['required', 'string', 'regex:/^[0-2][0-9]\.[0-5][0-9]-[0-2][0-9]\.[0-5][0-9]$/'],
        ], [
            'jam.regex' => 'Format jam harus 00.00-00.00 (contoh: 14.00-16.00)',
        ]);

        $data = $request->only('pelatih_id', 'kategori_kelas', 'jenis_kelas', 'hari', 'jam');
        $data['nama_kelas'] = 'Kelas ' . ucfirst($request->kategori_kelas) . ' ' . ucfirst($request->jenis_kelas);

        Jadwal::findOrFail($id)->update($data);

        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Jadwal::findOrFail($id)->delete();
        return redirect()->route('admin.jadwal')->with('success', 'Jadwal berhasil dihapus.');
    }
}
