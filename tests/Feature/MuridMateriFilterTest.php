<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use App\Models\User;
use App\Models\Jadwal;
use App\Models\Materi;
use Illuminate\Support\Facades\Hash;

class MuridMateriFilterTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test murid hanya melihat materi dari pelatih kelas yang diikutinya.
     */
    public function test_murid_hanya_melihat_materi_dari_pelatih_kelas_yang_diikuti()
    {
        // 1. Buat pelatih A (mengajar kelas yang cocok) dan pelatih B (mengajar kelas lain)
        $pelatihA = User::create([
            'nama' => 'Pelatih Rampak Pemula',
            'username' => 'pelatiha',
            'email' => 'pelatiha@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);

        $pelatihB = User::create([
            'nama' => 'Pelatih Reguler Madya',
            'username' => 'pelatihb',
            'email' => 'pelatihb@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'pelatih',
            'status' => 'aktif',
        ]);

        // 2. Buat jadwal yang menghubungkan pelatih dengan kelas
        Jadwal::create([
            'nama_kelas' => 'Tari Rampak Pemula',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
            'hari' => 'senin',
            'jam' => '15:00',
            'pelatih_id' => $pelatihA->id,
        ]);

        Jadwal::create([
            'nama_kelas' => 'Tari Reguler Madya',
            'kategori_kelas' => 'madya',
            'jenis_kelas' => 'reguler',
            'hari' => 'selasa',
            'jam' => '16:00',
            'pelatih_id' => $pelatihB->id,
        ]);

        // 3. Buat materi latihan dari masing-masing pelatih
        $materiCocok = Materi::create([
            'judul' => 'Materi Rampak Pemula Rahasia',
            'file_pdf' => 'materi-rampak.pdf',
            'pelatih_id' => $pelatihA->id,
        ]);

        $materiLain = Materi::create([
            'judul' => 'Materi Madya Reguler Rahasia',
            'file_pdf' => 'materi-madya.pdf',
            'pelatih_id' => $pelatihB->id,
        ]);

        // 4. Buat user murid yang terdaftar di kelas: Pemula - Rampak
        $murid = User::create([
            'nama' => 'Murid Pemula Rampak',
            'username' => 'muridrampak',
            'email' => 'muridrampak@steva.com',
            'password' => Hash::make('password123'),
            'role' => 'murid',
            'status' => 'aktif',
            'kategori_kelas' => 'pemula',
            'jenis_kelas' => 'rampak',
        ]);

        // 5. Login sebagai murid tersebut
        $this->actingAs($murid);

        // --- UJI DASHBOARD MURID ---
        $responseDashboard = $this->get(route('murid.dashboard'));
        $responseDashboard->assertStatus(200);
        // Harus melihat materi dari pelatih kelasnya
        $responseDashboard->assertSee($materiCocok->judul);
        // Tidak boleh melihat materi dari pelatih kelas lain
        $responseDashboard->assertDontSee($materiLain->judul);

        // --- UJI HALAMAN INDEKS MATERI MURID ---
        $responseMateri = $this->get(route('murid.materi'));
        $responseMateri->assertStatus(200);
        // Harus melihat materi dari pelatih kelasnya
        $responseMateri->assertSee($materiCocok->judul);
        // Tidak boleh melihat materi dari pelatih kelas lain
        $responseMateri->assertDontSee($materiLain->judul);
    }
}
