<?php

namespace App\Http\Controllers\Murid;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pembayaran;
use App\Models\Absensi;

class PembayaranController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $jenisKelas = $user->jenis_kelas ? explode(',', $user->jenis_kelas) : [];

        $pembayaran   = Pembayaran::with(['jadwal', 'user'])->where('user_id', $user->id)
            ->where(function($q) use ($user, $jenisKelas) {
                $q->whereNull('jadwal_id')
                  ->orWhereHas('jadwal', function($sub) use ($user, $jenisKelas) {
                      $sub->where('kategori_kelas', $user->kategori_kelas)
                          ->whereIn('jenis_kelas', $jenisKelas);
                  });
            })
            ->orderBy('tanggal', 'desc')
            ->paginate(3);

        $totalBayar   = Pembayaran::where('user_id', $user->id)
            ->where('status', 'disetujui')
            ->where(function($q) use ($user, $jenisKelas) {
                $q->whereNull('jadwal_id')
                  ->orWhereHas('jadwal', function($sub) use ($user, $jenisKelas) {
                      $sub->where('kategori_kelas', $user->kategori_kelas)
                          ->whereIn('jenis_kelas', $jenisKelas);
                  });
            })
            ->sum('jumlah');

        $pendingBayar = Pembayaran::where('user_id', $user->id)
            ->where('status', 'pending')
            ->where(function($q) use ($user, $jenisKelas) {
                $q->whereNull('jadwal_id')
                  ->orWhereHas('jadwal', function($sub) use ($user, $jenisKelas) {
                      $sub->where('kategori_kelas', $user->kategori_kelas)
                          ->whereIn('jenis_kelas', $jenisKelas);
                  });
            })
            ->count();

        $rekening = \App\Models\Rekening::first();

        $jadwalList = \App\Models\Jadwal::where('kategori_kelas', $user->kategori_kelas)
            ->whereIn('jenis_kelas', $jenisKelas)
            ->get();

        return view('murid.pembayaran.index', compact(
            'pembayaran', 'totalBayar', 'pendingBayar', 'rekening', 'jadwalList'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jadwal_id'      => 'required|exists:jadwal,id',
            'jumlah'         => 'required|numeric|min:0',
            'tanggal'        => 'required|date',
            'metode'         => 'required|string|in:transfer,qr,cash',
            'bukti_transfer' => 'required|image|mimes:jpg,jpeg,png|max:3072',
        ], [
            'jadwal_id.required'      => 'Kelas wajib dipilih.',
            'jadwal_id.exists'        => 'Kelas tidak valid.',
            'jumlah.required'         => 'Jumlah pembayaran wajib diisi.',
            'jumlah.min'              => 'Jumlah pembayaran tidak boleh bernilai negatif.',
            'metode.required'         => 'Metode pembayaran wajib dipilih.',
            'bukti_transfer.required' => 'Bukti transfer wajib diunggah.',
            'bukti_transfer.image'    => 'Bukti harus berupa gambar.',
            'bukti_transfer.max'      => 'Ukuran gambar maksimal 3MB.',
        ]);

        $buktiPath = $request->file('bukti_transfer')->store('bukti-transfer', 'public');

        $status = ($request->metode === 'qr' || $request->metode === 'transfer') ? 'disetujui' : 'pending';

        Pembayaran::create([
            'user_id'        => auth()->id(),
            'jadwal_id'      => $request->jadwal_id,
            'jumlah'         => $request->jumlah,
            'tanggal'        => $request->tanggal,
            'metode'         => $request->metode,
            'bukti_transfer' => $buktiPath,
            'status'         => $status,
        ]);

        $pesan = ($request->metode === 'qr' || $request->metode === 'transfer')
            ? 'Pembayaran berhasil dan telah disetujui secara otomatis (' . strtoupper($request->metode) . ').' 
            : 'Pembayaran berhasil dikirim. Menunggu verifikasi admin.';

        return redirect()->route('murid.pembayaran')->with('success', $pesan);
    }
}
