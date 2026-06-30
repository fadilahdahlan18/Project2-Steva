<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rekening;

class RekeningController extends Controller
{
    public function edit()
    {
        $rekening = Rekening::first() ?? new Rekening();
        return view('admin.rekening.edit', compact('rekening'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama_bank'      => 'required|string|max:50',
            'nomor_rekening' => 'required|string|max:50',
            'nama_pemilik'   => 'required|string|max:100',
        ], [
            'nama_bank.required'      => 'Nama bank wajib diisi.',
            'nomor_rekening.required' => 'Nomor rekening wajib diisi.',
            'nama_pemilik.required'   => 'Nama pemilik rekening wajib diisi.',
        ]);

        $rekening = Rekening::first();
        if (!$rekening) {
            $rekening = new Rekening();
        }

        $rekening->nama_bank = $request->nama_bank;
        $rekening->nomor_rekening = $request->nomor_rekening;
        $rekening->nama_pemilik = $request->nama_pemilik;
        $rekening->save();

        return redirect()->route('admin.pembayaran')->with('success', 'Informasi rekening berhasil diperbarui.');
    }
}
