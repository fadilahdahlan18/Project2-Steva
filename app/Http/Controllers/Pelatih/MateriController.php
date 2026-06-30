<?php

namespace App\Http\Controllers\Pelatih;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Materi;

class MateriController extends Controller
{
    public function index()
    {
        $materi = Materi::where('pelatih_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('pelatih.materi.index', compact('materi'));
    }

    public function create(Request $request)
    {
        $judul = $request->get('judul');
        return view('pelatih.materi.create', compact('judul'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul'      => 'required|string|max:200',
            'file_pdf'   => 'nullable|mimes:pdf|max:20480',
            'file_video' => 'nullable|mimes:mp4,mov,avi,qt|max:51200',
            'link_video' => 'nullable|url',
        ], [
            'file_pdf.mimes' => 'File harus berformat PDF.',
            'file_pdf.max'   => 'Ukuran PDF maksimal 20MB.',
            'file_video.mimes' => 'Format video harus mp4, mov, atau avi.',
            'file_video.max'   => 'Ukuran video maksimal 50MB.',
            'link_video.url' => 'Format link video tidak valid.',
        ]);

        $pdfPath = null;
        if ($request->hasFile('file_pdf')) {
            $pdfPath = $request->file('file_pdf')->store('materi-pdf', 'public');
        }

        $videoPath = null;
        if ($request->hasFile('file_video')) {
            $videoPath = $request->file('file_video')->store('materi-video', 'public');
        }

        Materi::create([
            'judul'      => $request->judul,
            'file_pdf'   => $pdfPath,
            'file_video' => $videoPath,
            'link_video' => $request->link_video,
            'pelatih_id' => auth()->id(),
        ]);

        return redirect()->route('pelatih.materi')->with('success', 'Materi berhasil diunggah.');
    }

    public function edit($id)
    {
        $materi = Materi::where('pelatih_id', auth()->id())->findOrFail($id);
        return view('pelatih.materi.edit', compact('materi'));
    }

    public function update(Request $request, $id)
    {
        $materi = Materi::where('pelatih_id', auth()->id())->findOrFail($id);

        $request->validate([
            'judul'      => 'required|string|max:200',
            'file_pdf'   => 'nullable|mimes:pdf|max:20480',
            'file_video' => 'nullable|mimes:mp4,mov,avi,qt|max:51200',
            'link_video' => 'nullable|url',
        ], [
            'file_pdf.mimes' => 'File harus berformat PDF.',
            'file_pdf.max'   => 'Ukuran PDF maksimal 20MB.',
            'file_video.mimes' => 'Format video harus mp4, mov, atau avi.',
            'file_video.max'   => 'Ukuran video maksimal 50MB.',
            'link_video.url' => 'Format link video tidak valid.',
        ]);

        if ($request->hasFile('file_pdf')) {
            if ($materi->file_pdf) Storage::disk('public')->delete($materi->file_pdf);
            $materi->file_pdf = $request->file('file_pdf')->store('materi-pdf', 'public');
        }

        if ($request->hasFile('file_video')) {
            if ($materi->file_video) Storage::disk('public')->delete($materi->file_video);
            $materi->file_video = $request->file('file_video')->store('materi-video', 'public');
        }

        $materi->judul      = $request->judul;
        $materi->link_video = $request->link_video;
        $materi->save();

        return redirect()->route('pelatih.materi')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $materi = Materi::where('pelatih_id', auth()->id())->findOrFail($id);
        if ($materi->file_pdf) Storage::disk('public')->delete($materi->file_pdf);
        if ($materi->file_video) Storage::disk('public')->delete($materi->file_video);
        $materi->delete();

        return redirect()->route('pelatih.materi')->with('success', 'Materi berhasil dihapus.');
    }
}
