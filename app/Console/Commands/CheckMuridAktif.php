<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Absensi;
use Carbon\Carbon;

class CheckMuridAktif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-murid-aktif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mengecek absensi murid dan menonaktifkan jika absen 3 minggu berturut-turut.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $murids = User::where('role', 'murid')->where('status', 'aktif')->get();
        $tigaMingguLalu = Carbon::now()->subDays(21)->startOfDay();
        $count = 0;

        foreach ($murids as $murid) {
            // Ambil absensi dalam 3 minggu terakhir (21 hari)
            $queryAbsensi = Absensi::where('user_id', $murid->id)
                ->where('tanggal', '>=', $tigaMingguLalu);

            if ($murid->reactivated_at) {
                $queryAbsensi->where('tanggal', '>=', $murid->reactivated_at->startOfDay());
            }

            $absensiTerbaru = $queryAbsensi->get();

            // Jika murid baru terdaftar > 3 minggu yang lalu dan sama sekali belum pernah absen (termasuk alpha),
            // dan kita mau non-aktifkan, kita cek created_at.
            // Namun sesuai plan: harus ada indikasi kelas berjalan (alpha minimal 1)
            // KECUALI jika murid tidak punya histori hadir/izin sama sekali selama > 3 minggu sejak akun dibuat.
            // Mari terapkan:
            
            $adaHadirAtauIzin = $absensiTerbaru->whereIn('status', ['hadir', 'izin'])->count() > 0;
            $adaAlpha = $absensiTerbaru->where('status', 'alpha')->count() > 0;

            if (!$adaHadirAtauIzin && $adaAlpha) {
                // Murid punya jadwal (dibuktikan dgn status alpha) tapi tidak pernah hadir/izin
                $murid->status = 'tidak aktif';
                $murid->save();
                $count++;
            } elseif ($absensiTerbaru->isEmpty()) {
                // Jika tidak ada data absensi sama sekali di 3 minggu terakhir,
                // Cek kapan terakhir kali dia hadir/izin di seluruh histori
                $terakhirHadir = Absensi::where('user_id', $murid->id)
                    ->whereIn('status', ['hadir', 'izin'])
                    ->orderBy('tanggal', 'desc')
                    ->first();
                
                $tanggalPatokan = $terakhirHadir ? Carbon::parse($terakhirHadir->tanggal) : $murid->created_at;
                
                if ($murid->reactivated_at && $murid->reactivated_at->gt($tanggalPatokan)) {
                    $tanggalPatokan = $murid->reactivated_at;
                }
                
                // Jika sudah > 3 minggu (21 hari) sejak terakhir hadir atau sejak akun dibuat, nonaktifkan.
                // Logika: kelas mungkin tidak ada / libur? Jika libur, tidak ada alpha. Tapi prompt bilang:
                // "Jika murid tidak hadir latihan selama 3 minggu berturut-turut".
                // Untuk mencegah salah blokir saat libur panjang, kita hanya menonaktifkan jika $tanggalPatokan < 21 hari?
                // Plan: "Jika murid telah terdaftar lebih dari 3 minggu tanpa absensi apa pun sama sekali, statusnya juga akan menjadi tidak aktif."
                if ($tanggalPatokan->startOfDay()->lte($tigaMingguLalu)) {
                    // Karena plan kita sudah dikonfirmasi "ya", kita set ke tidak aktif.
                    $murid->status = 'tidak aktif';
                    $murid->save();
                    $count++;
                }
            }
        }

        $this->info("Berhasil mengecek keaktifan murid. $count akun dinonaktifkan.");
        return Command::SUCCESS;
    }
}
