<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Jadwal;
use App\Models\User;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $jadwal_id = $request->get('jadwal_id'); // Composite key e.g., "pemula|reguler|Sabtu"
        $tanggal = $request->get('tanggal'); // Tanggal Pertemuan
        $status = $request->get('status');

        $rawJadwal = Jadwal::orderBy('hari')->orderBy('jam')->get();
        $jadwalList = Jadwal::transformCollection($rawJadwal);
        
        $muridList = collect();
        $pembayaranMap = [];
        $pendingPayments = collect();

        if ($status === 'pending' && (!$jadwal_id || !$tanggal)) {
            $pendingPayments = Pembayaran::with(['user', 'jadwal'])
                ->where('status', 'pending')
                ->has('user')
                ->orderBy('tanggal', 'desc')
                ->get();
        }

        if ($jadwal_id && $tanggal) {
            $jadwal = null;
            if (strpos($jadwal_id, '|') !== false) {
                list($kategori, $jenis, $hari) = explode('|', $jadwal_id);
                // Find a matching physical schedule in DB
                $realJadwal = Jadwal::where('kategori_kelas', $kategori)
                    ->where('jenis_kelas', $jenis)
                    ->first();
                
                $jadwal = new Jadwal();
                $jadwal->kategori_kelas = $kategori;
                $jadwal->jenis_kelas = $jenis;
                $jadwal->hari = $hari;
                $jadwal->id = $realJadwal ? $realJadwal->id : 0;
            } else {
                $jadwal = Jadwal::find($jadwal_id);
            }

            if ($jadwal) {
                // Semua murid di kelas ini
                $muridList = User::where('role', 'murid')
                    ->where('status', 'aktif')
                    ->where('kategori_kelas', $jadwal->kategori_kelas)
                    ->where('jenis_kelas', 'like', '%' . $jadwal->jenis_kelas . '%')
                    ->orderBy('nama')
                    ->get();
                
                $muridIds = $muridList->pluck('id');
                
                // Cari data pembayaran untuk murid-murid tersebut pada tanggal ini (global match by date)
                $pembayaranList = Pembayaran::with('user')
                    ->whereIn('user_id', $muridIds)
                    ->where('tanggal', $tanggal)
                    ->get();

                foreach ($pembayaranList as $p) {
                    $pembayaranMap[$p->user_id] = $p;
                }
            }
        }

        $rekening = \App\Models\Rekening::first();

        return view('admin.pembayaran.index', compact('jadwalList', 'jadwal_id', 'tanggal', 'muridList', 'pembayaranMap', 'rekening', 'pendingPayments'));
    }

    public function show($id)
    {
        $pembayaran = Pembayaran::with(['user', 'jadwal'])->findOrFail($id);
        return view('admin.pembayaran.show', compact('pembayaran'));
    }

    public function create(Request $request)
    {
        $user_id = $request->get('user_id');
        $jadwal_id = $request->get('jadwal_id');
        $tanggal = $request->get('tanggal', date('Y-m-d'));

        if ($jadwal_id && strpos($jadwal_id, '|') !== false) {
            list($kategori, $jenis, $hari) = explode('|', $jadwal_id);
            $realJadwal = Jadwal::where('kategori_kelas', $kategori)
                ->where('jenis_kelas', $jenis)
                ->first();
            $jadwal_id = $realJadwal ? $realJadwal->id : null;
        }

        $murids = User::where('role', 'murid')->where('status', 'aktif')->orderBy('nama')->get();
        $jadwalList = Jadwal::orderBy('kategori_kelas')->get();
        
        foreach($jadwalList as $jadwal) {
            $jadwal->nama_kelas = $jadwal->nama_kelas ?? 'Kelas ' . ucfirst($jadwal->kategori_kelas) . ' ' . ucfirst($jadwal->jenis_kelas);
        }
        
        return view('admin.pembayaran.create', compact('murids', 'jadwalList', 'user_id', 'jadwal_id', 'tanggal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jadwal_id' => 'required|exists:jadwal,id',
            'tanggal' => 'required|date',
            'jumlah'  => 'required|numeric|min:0',
            'metode'  => 'required|string|in:transfer,qr,cash',
            'status'  => 'required|in:pending,disetujui,ditolak',
            'keterangan' => 'nullable|string',
            'bukti_transfer' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'jumlah.required' => 'Jumlah pembayaran wajib diisi.',
            'jumlah.min'      => 'Jumlah pembayaran tidak boleh bernilai negatif.',
        ]);

        $data = $request->only('user_id', 'jadwal_id', 'tanggal', 'jumlah', 'metode', 'status', 'keterangan');

        // Cek apakah sudah pernah bayar untuk jadwal dan tanggal ini
        $existing = Pembayaran::where('user_id', $data['user_id'])
            ->where('jadwal_id', $data['jadwal_id'])
            ->where('tanggal', $data['tanggal'])
            ->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Pembayaran untuk murid, kelas, dan tanggal pertemuan tersebut sudah ada.');
        }

        if ($request->hasFile('bukti_transfer')) {
            $data['bukti_transfer'] = $request->file('bukti_transfer')->store('bukti-pembayaran', 'public');
        }

        Pembayaran::create($data);

        return redirect()->route('admin.pembayaran', [
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => $request->tanggal
        ])->with('success', 'Data pembayaran berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $murids = User::where('role', 'murid')->orderBy('nama')->get();
        $jadwalList = Jadwal::orderBy('kategori_kelas')->get();
        return view('admin.pembayaran.edit', compact('pembayaran', 'murids', 'jadwalList'));
    }

    public function update(Request $request, $id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'jadwal_id' => 'required|exists:jadwal,id',
            'tanggal' => 'required|date',
            'jumlah'  => 'required|numeric|min:0',
            'metode'  => 'required|string|in:transfer,qr,cash',
            'status'  => 'required|in:pending,disetujui,ditolak',
            'keterangan' => 'nullable|string',
            'bukti_transfer' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'jumlah.required' => 'Jumlah pembayaran wajib diisi.',
            'jumlah.min'      => 'Jumlah pembayaran tidak boleh bernilai negatif.',
        ]);

        $data = $request->only('user_id', 'jadwal_id', 'tanggal', 'jumlah', 'metode', 'status', 'keterangan');

        if ($request->hasFile('bukti_transfer')) {
            if ($pembayaran->bukti_transfer) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($pembayaran->bukti_transfer);
            }
            $data['bukti_transfer'] = $request->file('bukti_transfer')->store('bukti-pembayaran', 'public');
        }

        $pembayaran->update($data);

        return redirect()->route('admin.pembayaran', [
            'jadwal_id' => $request->jadwal_id,
            'tanggal' => $request->tanggal
        ])->with('success', 'Data pembayaran berhasil diperbarui.');
    }

    public function validasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:disetujui,ditolak',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->update(['status' => $request->status]);

        $msg = $request->status === 'disetujui' ? 'Pembayaran disetujui.' : 'Pembayaran ditolak.';
        return redirect()->back()->with('success', $msg);
    }

    public function destroy($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();
        return redirect()->back()->with('success', 'Data pembayaran dihapus.');
    }
}
